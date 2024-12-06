<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Service;

class ServicesComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    private $services;

    public function __construct()
    {
        $this->services = Service::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.services-component')->with('services', $this->services);
    }
}
