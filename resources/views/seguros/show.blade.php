<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visualizar Seguros') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                        <div>
                            <x-label for="nome" :value="__('Nome')" />
                            <x-input id="nome" class="block mt-1 w-full" type="text"
                                     name="nome" disabled
                                     value="{{$seguro->nome}}"/>

                            <x-label for="preco" :value="__('Preço')" />
                            <x-input id="preco" class="block mt-1 w-full" type="text"
                                     name="preco"
                                     value="{{$seguro->preco}}"/>

                            <x-label for="seguradora" :value="__('Seguradora')" />
                            <x-input id="seguradora" class="block mt-1 w-full" type="text"
                                    name="Seguradora"
                                    value="{{$seguro->seguradora_id}}"/>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
