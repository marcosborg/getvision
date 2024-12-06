<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;
use App\Models\FaqQuestion;
use App\Models\Page;

class FaqsComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    private $faq_questions;
    private $page;

    public function __construct()
    {
        $this->faq_questions = FaqQuestion::all();
        $this->page = Page::find(2);
        $slug = Str::slug($this->page->title);
        $this->page->slug = $slug;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.faqs-component')->with([
            'faq_questions' => $this->faq_questions,
            'page' => $this->page
        ]);
    }
}
