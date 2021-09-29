<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visualizar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                        <div>
                            <x-label for="nomecompleto" :value="__('Nome Completo')" />
                            <x-input id="nomecompleto" class="block mt-1 w-full" type="text"
                                     name="NomeCompleto" disabled
                                     value="{{$funcionario->NomeCompleto}}"/>

                            <x-label for="datadenascimento" :value="__('Data de Nascimento')" />
                            <x-input id="datadenascimento" class="block mt-1 w-full" type="text" 
                                    name="DatadeNascimento"
                                    value="{{$funcionario->DatadeNascimento}}"/>

                            <x-label for="cpf" :value="__('CPF')" />
                            <x-input id="cpf" class="block mt-1 w-full" type="text"
                                    name="CPF"
                                    value="{{$funcionario->CPF}}"/>

                            <x-label for="rg" :value="__('RG')" />
                            <x-input id="rg" class="block mt-1 w-full" type="text" 
                                    name="RG"
                                    value="{{$funcionario->RG}}"/>

                            <x-label for="endereco" :value="__('Endereço')" />
                            <x-input id="endereco" class="block mt-1 w-full" type="text" 
                                    name="Endereco"
                                    value="{{$funcionario->Endereco}}"/>

                            <x-label for="telefone" :value="__('Telefone')" />
                            <x-input id="telefone" class="block mt-1 w-full" type="text" 
                                    name="Telefone" 
                                    value="{{$funcionario->Telefone}}"/>
                            
                            <x-label for="email" :value="__('E-Mail')" />
                            <x-input id="email" class="block mt-1 w-full" type="text" 
                                    name="Email" 
                                    value="{{$funcionario->Email}}"/> 
                            
                            <x-label for="seguradora" :value="__('Seguradora')" />
                            <x-input id="seguradora" class="block mt-1 w-full" type="text" 
                                    name="Seguradora" 
                                    value="{{$funcionario->seguradora_id}}"/> 
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
