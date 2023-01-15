<div class="mt-10">
    <x-slot name="header">Cardápio</x-slot>
    @if(session()->has('success'))
        <div class="w-full px-2 py-4 border border-green-900 bg-green-400 text-green-900 rounded mb-10">
            {{session('success')}}
        </div>
    @endif

    <form>
        <div class="w-full mb-8">
            <label>Nome Item</label>
            <input type="text" class="w-full rounded mt-2 @error('menu.item') border-red-700 @enderror"  wire:model="menu.item">

            @error('menu.item')
            <strong class="block mt-4 text-red-700 font-bold">{{$message}}</strong>
            @enderror
        </div>

        <div class="w-full mb-8">
            <label>Descrição</label>
            <input type="text" class="w-full rounded mt-2 @error('menu.description') border-red-700 @enderror" wire:model="menu.description">

            @error('menu.description')
            <strong class="block mt-4 text-red-700 font-bold">{{$message}}</strong>
            @enderror
        </div>

        <div class="w-full mb-8">
            <label>Preço</label>
            <input type="text" class="w-full rounded mt-2 @error('menu.price') border-red-700 @enderror" wire:model.defer="menu.price">

            @error('menu.price')
             <strong class="block mt-4 text-red-700 font-bold">{{$message}}</strong>
            @enderror
        </div>

        <div class="w-full mb-8">
            <div class="w-1/2">
                <label>Foto Item</label>
                <input type="file" class="w-full rounded mt-2 @error('photo') border-red-700 @enderror" wire:model="photo">

                @error('photo')
                <strong class="block mt-4 text-red-700 font-bold">{{$message}}</strong>
                @enderror
            </div>
            <div class="w-1/2">
                @if($photo)
                <img src="{{$photo->temporaryUrl()}}" alt="Previda Image Item">
                @elseif($menu?->photo)
                <img src="{{route('server.image', str_replace('/', '|', $menu->photo))}}" alt="Image Item: {{$menu->item}}">
                @endif
            </div>
        </div>

        <button
            wire:click.prevent="saveItem"
            class="px-4 py-2 text-white font-bold text-xl rounded bg-blue-700 border border-blue-900 hover:bg-blue-500
                   transition duration-300 ease-in-out">
            Salvar Item
        </button>
    </form>
</div>