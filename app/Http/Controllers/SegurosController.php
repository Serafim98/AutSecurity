<?php

namespace App\Http\Controllers;

use App\Models\Seguradora;
use App\Models\Seguro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;



class SegurosController extends Controller
{
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
        Gate::authorize("acesso-funcionario");
        $seguros = Seguro::orderBy('Nome')->paginate(5);
        return view('seguros.index', compact('seguros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $seguradoras = Seguradora::all();
        return view('seguros.create', compact('seguradoras'));
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
            $seguro = new Seguro();
            $dados = $request->only($seguro->getFillable());
            Seguro::create($dados);
            return redirect()->
            action([SegurosController::class, 'index'])->
            with('sucesso', 'Registro salvo com sucesso!');
        } catch (\Exception $e){
            return redirect()->
            action([SegurosController::class, 'index'])->
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
        $seguro = Seguro::findOrFail($id);
        return view('seguros.show', compact('seguro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seguro = Seguro::findOrFail($id);
        return view('seguros.edit', compact('seguro'));
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
            $seguro = new Seguro();
            $dados = $request->only($seguro->getFillable());
            Seguro::whereId($id)->update($dados);
            return redirect()->
            action([SegurosController::class, 'index'])->
            with('sucesso', 'Registro Alterado!');
        } catch (\Exception $e){
            return redirect()->
            action([SegurosController::class, 'index'])->
            with('erro', 'Não foi possível alterar o registro!');
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
            Seguro::destroy($id);
            return redirect()->action([SegurosController::class, 'index'])->with('sucesso', 'Registro Excluido');
        }
        catch(\Exception $e){
            return redirect()->action([SegurosController::class, 'index'])->with('erro', 'Não foi possível excluir o registro');
        }
    }

    public function delete($id){
        $seguro = Seguro::findOrFail($id);
        return view('seguros.delete', compact('seguro'));
    }

    public function search(Request $request){
        $filtro = $request->query('filtro');
        $pesquisa = $request->query('pesquisa');
        $seguros = Seguro::where($filtro, 'like', '%'.$pesquisa.'%')->orderBy($filtro)->paginate(5);
        return view('seguros.index', compact('seguros', 'filtro', 'pesquisa'));
    }

    public function adicionar_produto($id){
        Gate::authorize("acesso-cliente");
        $this->produto = Seguro::find($id);
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
        $this->venda = Seguro::with('produtos')
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
            $this->venda = Seguro::create([
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


