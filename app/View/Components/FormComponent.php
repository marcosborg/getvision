<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\FormName;

class FormComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $form_name_id;

    public function __construct($form_name_id)
    {
        $this->form_name_id = $form_name_id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        return view('components.form-component');
    }
}
