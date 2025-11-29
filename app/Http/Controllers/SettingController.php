<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{

    public function index()
    {
        $settings = Setting::orderByDesc('created_at')->get(); 
        return view('settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'banner_image' => 'required|image|max:2048', // max 2MB
        ]);

        $path = $request->file('banner_image')->store('banners', 'public');

        Setting::create([
            'banner_image' => $path,
        ]);

        return redirect()->route('settings.index')->with('success', 'Imagen subida correctamente');
    }


}
