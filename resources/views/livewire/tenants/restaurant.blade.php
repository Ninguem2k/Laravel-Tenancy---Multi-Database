<div>
    <x-slot name="header">Meu Restaurante</x-slot>
    @if(session()->has('success'))
        <div class="w-full px-2 py-4 border border-green-900 bg-green-400 text-green-900 rounded mb-10">
            {{session('success')}}
        </div>
    @endif

    <form>
        <div class="w-full mb-8">
            <label>Nome Restaurante</label>
            <input type="text" class="w-full rounded mt-2 @error('restaurant.restaurant') border-red-700 @enderror"  wire:model="restaurant.restaurant">

            @error('restaurant.restaurant')
                <strong class="block mt-4 text-red-700 font-bold">{{$message}}</strong>
            @enderror
        </div>

        <div class="w-full mb-8">
            <label>Descrição</label>
            <input type="text" class="w-full rounded mt-2 @error('restaurant.description') border-red-700 @enderror" wire:model="restaurant.description">

            @error('restaurant.description')
            <strong class="block mt-4 text-red-700 font-bold">{{$message}}</strong>
            @enderror
        </div>

        <div class="w-full flex justify-between gap-2 mb-8">

            <div class="w-1/2">
                <label>Telefone Fixo</label>
                <input type="text" class="w-full rounded mt-2 @error('restaurant.phone') border-red-700 @enderror" wire:model="restaurant.phone">

                @error('restaurant.phone')
                <strong class="block mt-4 text-red-700 font-bold">{{$message}}</strong>
                @enderror
            </div>

           <div class="w-1/2">
               <label>Whatsapp</label>
               <input type="text" class="w-full rounded mt-2 @error('restaurant.whatsapp') border-red-700 @enderror" wire:model="restaurant.whatsapp">

               @error('restaurant.whatsapp')
               <strong class="block mt-4 text-red-700 font-bold">{{$message}}</strong>
               @enderror
           </div>

        </div>

        <button
            wire:click.prevent="saveRestaurant"
            class="px-4 py-2 text-white font-bold text-xl rounded bg-green-700 border border-green-900 hover:bg-green-500
                   transition duration-300 ease-in-out">
            Salvar
        </button>
    </form>
</div>
