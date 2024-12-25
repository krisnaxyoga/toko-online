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
            'province_id' => $request->origin_province == 'Choose Province' ? $store_setting->province_id : $request->origin_province,
            'city_id' => $request->origin_city == 'Choose City' ? $store_setting->province_id : $request->origin_city,
            'postal_code' => $request->postal_code,
        ]);

        if($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $filename);
            $store_setting->update(['logo_url' => "/images/" . $filename]);
        }

        return redirect()->route('store-setting')->with('success','Data has been updated successfully');
    }
}