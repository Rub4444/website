<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('auth.profile.index', ['user' => Auth::user()]);
    }

    public function edit()
    {
        return view('auth.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
       // Валидация
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'delivery_address' => 'required|string|max:255', // Адрес должен быть строкой
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Обновление данных пользователя
        $user = auth()->user();
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'delivery_address' => $validatedData['delivery_address'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
        ]);


        return redirect()->route('profile.index')->with('success', 'Profile updated');
    }
}
