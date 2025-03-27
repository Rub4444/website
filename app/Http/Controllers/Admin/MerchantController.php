<?php

namespace App\Http\Controllers\Admin;

use App\Models\Merchant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $merchants = Merchant::paginate(10);
        return view('auth.merchants.index', compact('merchants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.merchants.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Merchant::create($request->all());
        return redirect()->route('merchants.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Merchant $merchant)
    {
        return view('auth.merchants.show', compact('merchant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Merchant $merchant)
    {
        return view('auth.merchants.form', compact('merchant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Merchant $merchant)
    {
        $merchant->update($request->all());
        return redirect()->route('merchants.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Merchant $merchant)
    {
        $merchant->delete();
        return redirect()->route('merchants.index');
    }

    public function updateToken(Merchant $merchant)
    {
        session()->flash('success', $merchant->createToken());
        return redirect()->route('merchants.index');
    }
}
