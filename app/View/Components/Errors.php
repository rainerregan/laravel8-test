<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Errors extends Component
{


    /**
     * Errors
     *
     * @var [type]
     */
    public $errors;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($errors = null)
    {
        //
        $this->errors = $errors;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.errors');
    }
}
