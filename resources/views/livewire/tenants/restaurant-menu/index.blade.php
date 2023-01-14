<div>
    <x-slot name="header">Menu Itens</x-slot>

    <div class="py-12" x-data="{open: false}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-start mb-8">
                <a @click="open = true; Livewire.emit('modalOpened');"
                   class="px-4 py-2 text-white font-bold rounded bg-blue-700 border border-blue-900 hover:bg-blue-500
                   transition duration-300 ease-in-out cursor-pointer">
                    Criar Itens Cardápio
                </a>
            </div>
            @forelse($menuItems as $item)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-5">
                    <div class="p-6 flex justify-between bg-white border-b border-gray-200">

                        <div class="flex">
                            <div class="w-28 h-28 flex items-center justify-center mr-8
                            @if(!$item->photo) bg-gray-200 text-black font-bold @endif">

                                @if(!$item->photo)

                                    Sem Foto

                                @else
                                <img src="{{route('server.image', str_replace('/', '|', $item->photo))}}"  alt="Thumb do vídeo {{$item->item}}" class="max-w-full">
                                @endif
                            </div>

                            <h2>{{$item->item}}</h2>
                        </div>

                        <div class="flex items-center gap-2">
                            <button @click="Livewire.emit('modalOpened'); Livewire.emit('editMenuItem', {{$item->id}}); open = true;"
                                class="px-2 py-1 text-white font-bold rounded bg-blue-700 border border-blue-900 hover:bg-blue-500
                                       transition duration-300 ease-in-out text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>

                            </button>

                            <livewire:tenants.restaurant-menu.delete menu="{{$item->id}}" key="{{$item->id}}"/>
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        Sem Itens no Cardápio
                    </div>
                </div>
            @endforelse

            {{$menuItems->links()}}
        </div>


        @include('livewire.tenants.restaurant-menu.modal.item-modal')
    </div>


</div>