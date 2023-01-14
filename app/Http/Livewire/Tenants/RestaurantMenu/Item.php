<?php

namespace App\Http\Livewire\Tenants\RestaurantMenu;

use App\Models\Tenant\Restaurant;
use App\Models\Tenant\Menu;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Item extends Component
{
    use WithFileUploads;

    public $menu;
    public $photo;

    protected $listeners = ['editMenuItem', 'modalClosed'];

    protected $rules = [
        'menu.item' => 'required|max:255',
        'menu.description' => 'nullable|string|max:255',
        'menu.price' => 'required',
        'photo' => 'nullable|image',
    ];

    public function mount()
    {
        $this->menu = new Menu();
    }

    public function saveItem()
    {
        $this->validate();
        // $this->menu->restaurant_id = Restaurant::first()->id;

        if($this->photo && $this->menu->photo){
            $storage = Storage::disk('public');

            if($storage->exists($this->menu->photo)) $storage->delete($this->menu->photo);
        }

        $this->menu->photo = $this->photo ? $this->photo->store('menu-items-photos','public') : $this->menu->photo;

        $this->menu->save();

        $this->emit('menuItemUpdated');
        $this->dispatchBrowserEvent('modal-close');

        $this->menu = new Menu();

        $this->reset('photo');

        session()->flash('success', 'Item salvo/atualizado com sucesso!');
    }

    public function editMenuItem($item)
    {
        $this->resetValidation();
        $this->menu = Menu::find($item);
    }

    public function modalClosed()
    {
        $this->resetValidation();

        $this->menu = new Menu();

        $this->reset('photo');
    }

    public function render()
    {
        return view('livewire.tenants.restaurant-menu.item');
    }
}
