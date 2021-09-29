<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Seguradora') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{route('seguradora.update', $seguradora->id)}}">
                        @csrf
                        @method('PATCH')
                        <div>
                            <x-label for="Nome" :value="__('Nome')" />
                            <x-input id="Nome" class="block mt-1 w-full" type="text"
                                     name="Nome" required autofocus
                                     value="{{$seguradora->Nome}}"/>

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