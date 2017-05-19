<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class PagesController extends Controller
{
    public function index() {
        if (View::exists('pages.index'))
        {
            // return view('pages.index', ['text' => '<b>Hello world</b>']);
            return view('pages.index')
            ->with('text', '<b>Hellooooo</b>')
            ->with(['name' => 'Adekemi', 'location' => 'Abeokuta']);
        } else {
            return "No view available";
        }
    }

    public function profile() {
        return view('pages.profile');
    }

    public function settings() {
        return view('pages.settings');
    }

    public function blade() {
        $gender = 'female';
        $text = 'Hello there';
        return view('blade.bladetest', compact('gender','text'));
    }
}
