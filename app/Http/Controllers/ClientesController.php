<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Seguro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClientesController extends Controller
{

    private $venda;
    private $seguro;

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize("acesso-cliente");
        $clientes = Clientes::orderBy('NomeCompleto')->paginate(5);
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $cliente = new Clientes();
            $dados = $request->only($cliente->getFillable());
            Clientes::create($dados);
            return redirect()->
                   action([ClientesController::class, 'index'])->
                    with('sucesso', 'Registro salvo com sucesso!');
        } catch (\Exception $e){
            return redirect()->
                    action([ClientesController::class, 'index'])->
                    with('erro', 'Erro ao salvar o registro!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = Clientes::findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cliente = Clientes::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $cliente = new Clientes();
            $dados = $request->only($cliente->getFillable());
            Clientes::whereId($id)->update($dados);
            return redirect()->
                    action([ClientesController::class, 'index'])->
                    with('sucesso', 'Registro Alterado!');
        } catch (\Exception $e){
            return redirect()->
                    action([ClientesController::class, 'index'])->
                    with('erro', 'N??o foi poss??vel alterar o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Clientes::destroy($id);
            return redirect()->action([ClientesController::class, 'index'])->with('sucesso', 'Registro Excluido');
        }
        catch(\Exception $e){
            return redirect()->action([ClientesController::class, 'index'])->with('erro', 'N??o foi poss??vel excluir o registro');
        }
    }

    public function delete($id){
        $cliente = Clientes::findOrFail($id);
        return view('clientes.delete', compact('cliente'));
    }

    public function search(Request $request){
        $filtro = $request->query('filtro');
        $pesquisa = $request->query('pesquisa');
        $clientes = Clientes::where($filtro, 'like', '%'.$pesquisa.'%')->orderBy($filtro)->paginate(5);
        return view('clientes.index', compact('clientes', 'filtro', 'pesquisa'));
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
                ->with(['erro' => 'N??o foi poss??vel excluir o produto!']);
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
                ->with(['erro' => 'N??o foi poss??vel encerrar a compra!']);
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
                ->with(['erro' => 'N??o foi poss??vel alterar a quantidade do produto!']);
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
                ->with(['erro' => 'N??o foi poss??vel inserir o produto!']);
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
                ->with(['erro' => 'N??o foi poss??vel iniciar a venda!']);
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
                ->with(['erro' => 'N??o foi poss??vel iniciar a venda!']);
        }
    }

}
