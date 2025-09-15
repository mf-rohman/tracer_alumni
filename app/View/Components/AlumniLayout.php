<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AlumniLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // Memberitahu Laravel untuk menggunakan file layout yang benar
        return view('layouts.alumni');
    }
}
