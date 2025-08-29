<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    public function index()
    {

        // $totalVisits = Visit::count();
        // $uniqueIPs = Visit::distinct('ip')->count('ip');
        // $todayVisits = Visit::whereDate('created_at', today())->count();
        // Счётчики
        $totalVisits = DB::table('visits')->count();
        $uniqueVisitors = DB::table('visits')->distinct('ip')->count('ip');
        $todayVisits = DB::table('visits')
            ->whereDate('created_at', today())
            ->count();

        // График за 7 дней
        $chartData = DB::table('visits')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->pluck('count', 'date');

        // Последние 20 визитов
        $lastVisits = DB::table('visits')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Топ браузеры
        $browsers = DB::table('visits')
            ->selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')
            ->pluck('count', 'browser');

        // Топ устройства
        $devices = DB::table('visits')
            ->selectRaw('device, COUNT(*) as count')
            ->groupBy('device')
            ->pluck('count', 'device');

        $banners = Banner::all();
        return view('auth.banners.index', compact('banners',
            'totalVisits',
            'uniqueVisitors',
            'todayVisits',
            'chartData',
            'lastVisits',
            'browsers',
            'devices'));
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


}
