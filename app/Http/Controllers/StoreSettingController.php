<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store_setting;

class StoreSettingController extends Controller
{
    public function index(){
        $store_setting = Store_setting::first();
        return view('admin.setting.index', compact('store_setting'));
    }

    public function update(Request $request){
        $request->validate([
            'store_name' => 'required',
        ]);

        $store_setting = Store_setting::first();
        $store_setting->update([
            'store_name' => $request->store_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'postal_code' => $request->postal_code,
            'logo_url' => $request->logo_url,
        ]);

        return redirect()->route('store-setting')->with('success','Data has been updated successfully');
    }
}