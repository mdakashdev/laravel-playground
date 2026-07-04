<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return "hello world";
    }

    public function about()
    {
        return [
            'name' => 'John Doe'
        ];
    }

    public function contact()
    {
        return response()->json([
            'name' => 'John Doe'
        ]);
    }
}
