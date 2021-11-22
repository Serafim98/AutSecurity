<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\Seguradora;
use Illuminate\Support\Facades\Gate;
class FuncionariosController extends Controller
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
        $funcionarios = Funcionario::orderBy('NomeCompleto')->paginate(5);
        return view('funcionarios.index', compact('funcionarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $seguradoras = Seguradora::all();
        return view('funcionarios.create', compact('seguradoras'));
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
            $funcionario = new Funcionario();
            $dados = $request->only($funcionario->getFillable());
            Funcionario::create($dados);
           return redirect()->
                    action([FuncionariosController::class, 'index'])->
                    with('sucesso', 'Registro salvo com sucesso!');
        } catch (\Exception $e){
            return redirect()->
                    action([FuncionariosController::class, 'index'])->
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
        $funcionario = Funcionario::findOrFail($id);
        return view('funcionarios.show', compact('funcionario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $funcionario = Funcionario::findOrFail($id);
        return view('funcionarios.edit', compact('funcionario'));
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
            $funcionario = new Funcionario();
            $dados = $request->only($funcionario->getFillable());
            Funcionario::whereId($id)->update($dados);
            return redirect()->
                    action([FuncionariosController::class, 'index'])->
                    with('sucesso', 'Registro Alterado!');
        } catch (\Exception $e){
            return redirect()->
                    action([FuncionariosController::class, 'index'])->
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
            return redirect()->action([FuncionariosController::class, 'index'])->with('sucesso', 'Registro Excluido');
        }
        catch(\Exception $e){
            return redirect()->action([FuncionariosController::class, 'index'])->with('erro', 'Não foi possível excluir o registro');
        }
    }

    public function delete($id){
        $funcionario = Funcionario::findOrFail($id);
        return view('funcionarios.delete', compact('funcionario'));
    }

    public function search(Request $request){
        $filtro = $request->query('filtro');
        $pesquisa = $request->query('pesquisa');
        $funcionarios = Funcionario::where($filtro, 'like', '%'.$pesquisa.'%')->orderBy($filtro)->paginate(5);
        return view('funcionarios.index', compact('funcionarios', 'filtro', 'pesquisa'));
    }
}
