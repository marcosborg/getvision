<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Activity;

class AboutComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    private $activities;

    public function __construct()
    {
        $this->activities = Activity::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.about-component')->with('activities', $this->activities);
    }
}
