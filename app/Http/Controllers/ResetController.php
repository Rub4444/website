<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ResetController extends Controller
{
    public function reset()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route('index')->with('error', 'Access denied.');
        }

        Artisan::call('migrate:fresh --seed');

        foreach (['categories', 'products'] as $folder) {
            // Удаляем старые папки
            Storage::disk('public')->deleteDirectory($folder);
            Storage::disk('public')->makeDirectory($folder);

            // Получаем файлы из диска 'reset'
            $files = Storage::disk('reset')->files($folder);

            foreach ($files as $file) {
                // Копируем файлы в диск 'public'
                Storage::disk('public')->put($file, Storage::disk('reset')->get($file));
            }
        }
        session()->flash('success', 'The project has been reset.');
        return redirect()->route('index');
    }
}
