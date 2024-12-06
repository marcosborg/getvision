<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ContactComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    private $forms;

    public function __construct()
    {

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.contact-component');
    }
}
