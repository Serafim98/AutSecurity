<?php

namespace App\Http\Controllers;

use App\Models\Seguro;
use App\Models\Venda;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClienteController extends Controller
{

    private $venda;
    private $produto;

    public function __construct(){
        $this->middleware('auth');
        Gate::authorize("acesso-cliente");
    }

    public function areacliente(){
        Gate::authorize("acesso-cliente");
        $carrinho = Venda::with('produtos')
            ->where([
                'user_id' => Auth::id(),
                'status' => 'aberta'
            ])->first();
        return view('areacliente', compact('carrinho'));
    }

    public function adicionar_produto($id){
        Gate::authorize("acesso-cliente");
        $this->produto = Produto::find($id);
        if ($this->existe_venda_aberta()){
            if ($this->existe_produto_venda()){
                $this->incrementar_quantidade_produto_venda();
            } else{
                $this->adicionar_produto_venda();
                $this->atualizar_venda();
            }
        } else{
            $this->abrir_venda();
        }
        return redirect('/areacliente');
    }

    public function remover_produto($id){
        Gate::authorize("acesso-cliente");
        $this->existe_venda_aberta();
        $this->produto = $this->venda->produtos->find($id);
        try{
            Venda::whereId($this->venda->id)->update([
                'total' => $this->venda->total - $this->produto->pivot->total_unitario
            ]);
            if ($this->produto->pivot->quantidade == 1)
                $this->venda->produtos()->detach($id);
            else
                $this->venda->produtos()
                    ->updateExistingPivot($id, ['quantidade' => $this->produto->pivot->quantidade - 1]);
            return redirect('/areacliente');
        } catch (\Exception $e){
            return redirect('/areacliente')
                ->with(['erro' => 'Não foi possível excluir o produto!']);
        }
    }

    public function encerrar_venda(){
        Gate::authorize('acesso-cliente');
        try{
            $this->existe_venda_aberta();
            Venda::whereId($this->venda->id)->update(['status' => 'fechada']);
            return redirect('/areacliente');
        } catch (\Exception $e){
            return redirect('/areacliente')
                ->with(['erro' => 'Não foi possível encerrar a compra!']);
        }
    }

    private function existe_venda_aberta()
    {
        $this->venda = Venda::with('produtos')
            ->where([
                'user_id' => Auth::id(),
                'status' => 'aberta'
            ])->first();
        return $this->venda != null;
    }

    private function existe_produto_venda()
    {
        return $this->venda->produtos->contains($this->produto);
    }

    private function incrementar_quantidade_produto_venda()
    {
        $quantidade = ($this->venda->produtos->find($this->produto->id)->pivot->quantidade) + 1;
        try{
            $this->venda->produtos()
                ->updateExistingPivot($this->produto->id, ['quantidade' => $quantidade]);
            $this->atualizar_venda();
        }catch (\Exception $e){
            return redirect('/areacliente')
                ->with(['erro' => 'Não foi possível alterar a quantidade do produto!']);
        }
    }

    private function adicionar_produto_venda()
    {
        try{
            $this->venda->produtos()->attach($this->produto->id, [
                'quantidade' => 1,
                'total_unitario' => $this->produto->valor
            ]);
        }catch (\Exception $e){
            return redirect('/areacliente')
                ->with(['erro' => 'Não foi possível inserir o produto!']);
        }
    }

    private function abrir_venda()
    {
        try{
            $this->venda = Venda::create([
                'user_id' => Auth::id(),
                'total' => $this->produto->valor,
                'status' => 'aberta'
            ]);
            $this->adicionar_produto_venda();
        }catch (\Exception $e){
            return redirect('/areacliente')
                ->with(['erro' => 'Não foi possível iniciar a venda!']);
        }
    }

    private function atualizar_venda()
    {
        try {
            $total = $this->venda->total + $this->produto->valor;
            Venda::whereId($this->venda->id)->update([
                'total' => $total
            ]);
        } catch (\Exception $e){
            return redirect('/areacliente')
                ->with(['erro' => 'Não foi possível iniciar a venda!']);
        }
    }

}
