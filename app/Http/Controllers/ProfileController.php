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
            'delivery_city' => 'nullable|string|max:255',
            'delivery_street' => 'nullable|string|max:255',
            'delivery_home' => 'nullable|string|max:255',
        ]);

        // Обновление данных пользователя
        $user = auth()->user();

        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'delivery_city' => $validatedData['delivery_city'],
            'delivery_street' => $validatedData['delivery_street'],
            'delivery_home' => $validatedData['delivery_home'],
        ]);


        return redirect()->route('profile.index')->with('success', 'Profile updated');
    }
}
