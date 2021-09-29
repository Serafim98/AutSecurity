<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Funcionário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{route('funcionario.store')}}">
                        @csrf
                        <div>
                        <x-label for="cargo" :value="__('Cargo')" />
                            <x-input id="cargo" class="block mt-1 w-full" type="text" name="Cargo" />

                            <x-label for="nomecompleto" :value="__('Nome Completo')" />
                            <x-input id="nomecompleto" class="block mt-1 w-full" type="text" name="NomeCompleto" />
                            
                            <x-label for="datadenascimento" :value="__('Data de Nascimento')" />
                            <x-input id="datadenascimento" class="block mt-1 w-full" type="text" name="DatadeNascimento"/>
                            
                            <x-label for="cpf" :value="__('CPF')" />
                            <x-input id="cpf" class="block mt-1 w-full" type="text" name="CPF"/>

                            <x-label for="rg" :value="__('RG')" />
                            <x-input id="rg" class="block mt-1 w-full" type="text" name="RG"/>

                            <x-label for="endereco" :value="__('Endereço')" />
                            <x-input id="endereco" class="block mt-1 w-full" type="text" name="Endereco"/>

                            <x-label for="telefone" :value="__('Telefone')" />
                            <x-input id="telefone" class="block mt-1 w-full" type="text" name="Telefone"/>
                            
                            <x-label for="email" :value="__('E-Mail')" />
                            <x-input id="email" class="block mt-1 w-full" type="text" name="Email"/>

                            <x-label for="banco" :value="__('Banco')" />
                            <x-input id="banco" class="block mt-1 w-full" type="text" name="Banco"/>

                            <x-label for="agencia" :value="__('Agência')" />
                            <x-input id="agencia" class="block mt-1 w-full" type="text" name="Agencia"/>

                            <x-label for="conta" :value="__('Conta')" />
                            <x-input id="conta" class="block mt-1 w-full" type="text" name="Conta"/>
                            
                            <x-label for="seguradora_id" :value="__('Seguradora')" />
                            <select input id="seguradora_id"name="seguradora_id" class="block mt-1 w-full" required>
                                @foreach($seguradoras as $s)
                                <option value="{{$s->id}}">{{$s->Nome}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-4">
                                {{ __('Salvar') }}
                            </x-button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


