<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Visit;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return view('auth.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('auth.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'      => 'required|image|max:2048',
            'img_mobile' => 'nullable|image|max:2048',
            'title'      => 'nullable|string|max:255',
            'link'       => 'nullable|url'
        ]);

        $path = $request->file('image')->store('banners', 'public');

        $mobilePath = null;
        if ($request->hasFile('img_mobile')) {
            $mobilePath = $request->file('img_mobile')->store('banners/mobile', 'public');
        }

        Banner::create([
            'title'      => $request->title,
            'image'      => $path,
            'img_mobile' => $mobilePath,
            'link'       => $request->link,
            'is_active'  => true,
        ]);

        return redirect()->route('banners.index')->with('success', 'Баннер добавлен!');
    }


    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete($banner->image);
        $banner->delete();

        return back()->with('success', 'Баннер удалён');
    }

    public function edit(Banner $banner)
    {
        return view('auth.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image'      => 'nullable|image|max:2048',
            'img_mobile' => 'nullable|image|max:2048',
            'title'      => 'nullable|string|max:255',
            'link'       => 'nullable|url'
        ]);

        // Если загружено новое desktop изображение
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image);
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        // Если загружено новое mobile изображение
        if ($request->hasFile('img_mobile')) {
            Storage::disk('public')->delete($banner->img_mobile);
            $banner->img_mobile = $request->file('img_mobile')->store('banners/mobile', 'public');
        }

        $banner->title = $request->title;
        $banner->link  = $request->link;
        $banner->save();

        return redirect()->route('banners.index')->with('success', 'Баннер обновлён!');
    }

    public function stats()
    {
        $totalVisits = Visit::count();
        $uniqueIPs = Visit::distinct('ip')->count('ip');
        $todayVisits = Visit::whereDate('created_at', today())->count();

        return view('admin.stats', compact('totalVisits', 'uniqueIPs', 'todayVisits'));
    }

}
