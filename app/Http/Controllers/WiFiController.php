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

        if ($request->has('password'))
            $wifi->password = $request->get('password');

        $wifi->latitude = $request->get('latitude');
        $wifi->longitude = $request->get('longitude');
        $wifi->created_by = $user->id;
        $wifi->save();

        return $wifi;
    }

    public static function getWiFis(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();

        return $user->wifis()->get();
    }

    public static function getAllWifis(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();

        return WiFi::all();
    }
}
