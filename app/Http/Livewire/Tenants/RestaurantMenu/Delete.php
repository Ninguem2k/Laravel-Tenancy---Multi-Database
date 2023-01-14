<?php

namespace App\Http\Livewire\Tenants\RestaurantMenu;

use App\Models\Tenant\Menu;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class Delete extends Component
{
    public $menu;

    public function mount(int $menu)
    {
        $this->menu = Menu::find($menu);
    }

    public function deleteItem()
    {

        if ($this->menu->photo) {
            $storage = Storage::disk('public');
            if($storage->exists($this->menu->photo)) $storage->delete($this->menu->photo);
        }

        $this->menu->delete();
        $this->emit('menuItemDeleted');
    }

    public function render()
    {
        return view('livewire.tenants.restaurant-menu.delete');
    }
}