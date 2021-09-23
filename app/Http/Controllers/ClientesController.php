<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;

class ClientesController extends Controller
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
            Clientes::destroy($id);
            return redirect()->action([ClientesController::class, 'index'])->with('sucesso', 'Registro Excluido');
        }
        catch(\Exception $e){
            return redirect()->action([ClientesController::class, 'index'])->with('erro', 'Não foi possível excluir o registro');
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
}
