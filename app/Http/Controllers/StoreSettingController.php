<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Models\Store_setting;

class StoreSettingController extends Controller
{
    public function index(){
        $store_setting = Store_setting::first();
        $sliders = Slider::all();
        return view('admin.setting.index', compact('store_setting','sliders'));
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

    public function addslidder(Request $request){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'button_text' => 'required',
            'button_link' => 'required|url',
        ]);

        $slider = new Slider();
        $slider->title = $request->title;
        $slider->description = $request->description;

        if($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/slider'), $filename);
            $slider->image = "/images/slider/" . $filename;
        }

        $slider->button_text = $request->button_text;
        $slider->button_link = $request->button_link;
        $slider->save();

        return redirect()->route('store-setting')->with('success','Slider has been added successfully');
    }

    public function editslider(Request $request, $id){
        $request->validate([
            'title' => 'nullable',
            'description' => 'nullable',
            'button_text' => 'nullable',
            'button_link' => 'nullable|url',
        ]);

        $slider = Slider::find($id);
        if($slider == null){
            return redirect()->route('store-setting')->with('error','Slider not found');
        }

        $slider->title = $request->title ?? $slider->title;
        $slider->description = $request->description ?? $slider->description;
        $slider->button_text = $request->button_text ?? $slider->button_text;
        $slider->button_link = $request->button_link ?? $slider->button_link;
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/slider'), $filename);
            $slider->image = "/images/slider/" . $filename;
        }
        $slider->save();

        return redirect()->route('store-setting')->with('success','Slider has been updated successfully');
    }

    public function deleteslider( $id){
        $slider = Slider::find($id);
        $slider->delete();

        return redirect()->route('store-setting')->with('success','Slider has been deleted successfully');
    }
}