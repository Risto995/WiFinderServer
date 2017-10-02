<?php

namespace App\Http\Controllers;

use App\WiFi;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class WiFiController extends Controller
{
    public static function postWiFi(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();

        $wifi = new WiFi();
        $wifi->name = $request->get('name');

        if ($request->has('password')){
            $wifi->password = $request->get('password');
        }

        $wifi->latitude = $request->get('latitude');
        $wifi->longitude = $request->get('longitude');
        $wifi->created_by = $user->id;
        $wifi->save();
        
        $user->points += 10;
        $user->save();

        return $wifi;
    }

    public static function getWiFis(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();

        $wifis = $user->wifis()->get();
        foreach ($wifis as $wifi){
            $wifi->user = User::find($wifi->created_by)->first()->name;
        }

        return $wifis;
    }

    public static function locationWifis(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();

        $user->latitude = $request->get('latitude');
        $user->longitude = $request->get('longitude');

        $user->save();

        $wifis = $user->wifis()->get();
        foreach ($wifis as $wifi){
            $wifi->user = User::find($wifi->created_by)->first()->name;
        }

        return $wifis;
    }

    public static function getAllWifis(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();

        $wifis = WiFi::all();

        foreach ($wifis as $wifi){
            $wifi->user = User::find($wifi->created_by)->first()->name;
        }

        return $wifis;
    }

    public static function locationAllWifis(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();

        $user->latitude = $request->get('latitude');
        $user->longitude = $request->get('longitude');

        $user->save();

        $wifis = WiFi::all();

        foreach ($wifis as $wifi){
            $wifi->user = User::find($wifi->created_by)->first()->name;
        }

        return $wifis;
    }
}
