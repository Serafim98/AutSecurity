<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Área do Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if(session('erro'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p>{{session('erro')}}</p>
                        </div>
                    @endif

                    <div class="p-6 bg-white border-b border-gray-200">
                        <table class="min-w-full">
                            <tr>
                                <th class="px-6 py-3 text-center text-lg leading-4 text-black-500 tracking-wider" colspan="4">Carrinho de Compras</th>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-lg leading-4 text-black-500
                                                tracking-wider">Seguro</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-lg leading-4 text-black-500
                                                tracking-wider">Seguradora</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-lg leading-4 text-black-500
                                                tracking-wider">Valor Unitário</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-lg leading-4 text-black-500
                                                tracking-wider">Quantidade</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-lg leading-4 text-black-500
                                                tracking-wider"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($carrinho == null)
                                <tr>
                                    <td class="px-6 py-3 border-b-2 border-gray-300 text-center text-lg leading-4 text-black-500
                                                tracking-wider" colspan="4">Carrinho está vazio!</td>
                                </tr>
                            @else
                                @foreach($carrinho->produtos as $p)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b text-gray-900 border-gray-500 text-sm
                                            leading-5">{{$p->nome}}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b text-gray-900 border-gray-500 text-sm
                                            leading-5">{{$p->categoria->descricao}}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b text-gray-900 border-gray-500 text-sm
                                            leading-5">{{$p->valor}}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b text-gray-900 border-gray-500 text-sm
                                            leading-5">{{$p->pivot->quantidade}}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b text-gray-900 border-gray-500 text-sm
                                            leading-5">
                                            <a class="px-5 py-2 border-red-500 border text-red-500 rounded transition duration-300
                                    hover:bg-red-700 hover:text-white focus:outline-none" href="{{route('remover_produto', $p->id)}}">Remover produto do carrinho</a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            @if($carrinho != null)
                                <tfoot>
                                <tr>
                                    <td class="px-6 py-3 text-center text-lg leading-4 text-black-500
                                                tracking-wider" colspan="4">Total: R${{number_format($carrinho->total,2,",",".")}}</td>
                                    <td class="px-6 py-3 text-lg text-right" colspan="6">
                                        <a class="px-5 py-2 border-gray-500 border text-gray-500 rounded transition duration-300
                                                    hover:bg-gray-700 hover:text-white focus:outline-none"
                                           href="{{ route('encerrar_venda')}}">Checkout</a>
                                    </td>
                                </tr>
                                </tfoot>
                            @endif
                        </table>


                    </div>
                </div>
            </div>
        </div>
</x-app-layout>

