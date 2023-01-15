<x-guest-layout>

    <header class="w-full py-4 bg-red-600 mb-20">
        <div class="max-w-7xl mx-auto flex justify-between">
            <h2 class="text-white text-xl font-bold">{{$restaurant?->restaurant}}</h2>
            <p>
                <span class="text-white font-bold">{{$restaurant?->phone}} / {{$restaurant?->phone}} </span>
            </p>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-4 md:px-0">
        <div class="max-w-full">
            <h2 class="text-4xl font-extrabold text-black mb-10 text-red-900">Cardápio</h2>
        </div>
        @forelse($menuItems  as $item)
            <div class="w-full rounded-xl shadow-xl bg-white min-h-48 mb-10 flex">
                <div
                    class="w-48 h-48 flex items-center justify-center mr-8 rounded-l-xl
                           @if(!$item->photo) bg-gray-200 text-black font-bold @endif">

                    @if(!$item->photo)
                        Sem Foto
                    @else
                        <img src="{{route('server.image', str_replace('/', '|', $item->photo))}}" alt="Thumb do vídeo {{$item->item}}" class="max-w-full w-48 h-48 rounded-l-xl">
                    @endif

                </div>

                <div class="flex items-center">
                   <div>
                       <h2 class="mb-2 text-xl font-bold">{{$item->item}}</h2>
                       <h4 class="mb-2 text-2xl text-red-600">R$ {{$item->price}}</h4>
                       <p class="mb-2 ">{{$item->description}}</p>
                   </div>
                </div>
            </div>
        @empty
            <h3 class="text-4xl text-red-600 block">Nenhum Item Cadastrado...</h3>
        @endforelse

        <div class="w-full mt-10">
            {{$menuItems->links()}}
        </div>
    </section>
</x-guest-layout>