<?php

namespace App\Http\Controllers;

use App\Models\Seguradora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SeguradorasController extends Controller
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
        $seguradoras = Seguradora::orderBy('Nome')->paginate(5);
        return view('seguradoras.index', compact('seguradoras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seguradoras.create');
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
            $seguradora = new Seguradora();
            $dados = $request->only($seguradora->getFillable());
            Seguradora::create($dados);
           return redirect()->
                    action([SeguradorasController::class, 'index'])->
                    with('sucesso', 'Registro salvo com sucesso!');
        } catch (\Exception $e){
            return redirect()->
                    action([SeguradorasController::class, 'index'])->
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
        $seguradora = Seguradora::findOrFail($id);
        return view('seguradoras.show', compact('seguradora'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seguradora = Seguradora::findOrFail($id);
        return view('seguradoras.edit', compact('seguradora'));
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
            $seguradora = new Seguradora();
            $dados = $request->only($seguradora->getFillable());
            Seguradora::whereId($id)->update($dados);
            return redirect()->
                    action([SeguradorasController::class, 'index'])->
                    with('sucesso', 'Registro Alterado!');
        } catch (\Exception $e){
            return redirect()->
                    action([SeguradorasController::class, 'index'])->
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
            Seguradora::destroy($id);
            return redirect()->action([SeguradorasController::class, 'index'])->with('sucesso', 'Registro Excluido');
        }
        catch(\Exception $e){
            return redirect()->action([SeguradorasController::class, 'index'])->with('erro', 'Não foi possível excluir o registro');
        }
    }

    public function delete($id){
        $seguradoras = Seguradora::findOrFail($id);
        return view('seguradoras.delete', compact('seguradoras'));
    }

    public function search(Request $request){
        $filtro = $request->query('filtro');
        $pesquisa = $request->query('pesquisa');
        $seguradoras = Seguradora::where($filtro, 'like', '%'.$pesquisa.'%')->orderBy($filtro)->paginate(5);
        return view('seguradoras.index', compact('seguradoras', 'filtro', 'pesquisa'));
    }

}
