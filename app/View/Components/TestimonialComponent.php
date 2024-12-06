<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Testimonial;

class TestimonialComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    private $testimonials;

    public function __construct()
    {
        $this->testimonials = Testimonial::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.testimonial-component')->with('testimonials', $this->testimonials);
    }
}
