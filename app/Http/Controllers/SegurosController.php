<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\Seguradora;
use App\Models\Seguro;
use Illuminate\Http\Request;



class SegurosController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            Funcionario::whereId($id)->update($dados);
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
            Funcionario::destroy($id);
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
}
