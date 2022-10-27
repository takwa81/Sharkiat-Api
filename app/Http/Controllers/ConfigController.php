<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConfigResource;
use Illuminate\Http\Request;
use App\Models\Config;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    function index(){
		
            $config = Config::first();
			if(!$config){
				return response()->json(['message'=>'not found']);
			}
            return response()->json($config);
        
    }


	function store(Request $request)
	{
		$usertype = Auth::user()->role;
        if($usertype == "1"){
		$countData = Config::count();
		if ($countData == 0) {
			$data = new Config;

			$data->address = $request->address;
			$data->ads = $request->ads;
			$data->telephone = $request->telephone;
			$data->phone = $request->phone;
			$data->whatsapp = $request->whatsapp;
			$data->instagram = $request->instagram;
			$data->facebook = $request->facebook;
			$data->twitter = $request->twitter;
			$data->open = $request->open;
			$data->close = $request->close;
			$data->save();
		} else {
			$firstData = Config::first();
			$data = Config::find($firstData->id);
			$data->address = $request->address ?? $data->address;
			$data->ads = $request->ads ?? $data->ads;
			$data->telephone = $request->telephone ?? $data->telephone;
			$data->phone = $request->phone ?? $data->phone;
			$data->whatsapp = $request->whatsapp ?? $data->whatsapp;
			$data->instagram = $request->instagram ?? $data->instagram;
			$data->facebook = $request->facebook ?? $data->facebook;
			$data->twitter = $request->twitter ?? $data->twitter;
			$data->open = $request->open ?? $data->open;
			$data->close = $request->close ?? $data->open;
			$data->save();
		}

		return response()->json(['message'=>'Config Updated Successfully ....', 'config'=>$data]);
	}else{
		return response()->json(['message'=> 'you are not admin']);
	}
	}
}
