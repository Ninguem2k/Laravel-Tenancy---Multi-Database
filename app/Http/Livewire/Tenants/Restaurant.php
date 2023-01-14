<?php

namespace App\Http\Livewire\Tenants;

use Livewire\Component;
use App\Models\Tenant\Restaurant as RestaurantModel;

class Restaurant extends Component
{
    public ?RestaurantModel $restaurant;

    protected $rules = [
        'restaurant.restaurant' => 'required|max:255',
        'restaurant.description' => 'required|max:255',
        'restaurant.phone' => 'required|max:20',
        'restaurant.whatsapp' => 'required|max:20',
    ];

    public function mount(RestaurantModel $restaurant)
    {
        $this->restaurant = $restaurant->first() ?: $restaurant;
    }

    public function saveRestaurant()
    {
        $this->validate();

        $this->restaurant->save();

        session()->flash('success', 'Restaurante Salvo com Sucesso!');
    }

    public function render()
    {
        return view('livewire.tenants.restaurant');
    }
}
