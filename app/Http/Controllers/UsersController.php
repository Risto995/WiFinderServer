<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Psy\Exception\ErrorException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class UsersController extends Controller
{
    public static function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            // Authentication passed...
            $user = Auth::user();
            return $user['api_token'];
        } else {
            throw new AccessDeniedException('Email or password is incorrect');
        }
    }

    public static function register(Request $request)
    {
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->api_token = str_random(60);
        $user->password = bcrypt($request->get('password'));
        $user->avatar = 'default.jpg';
        $user->save();

        return $user;
    }

    public static function update(Request $request)
    {
        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();
        if ($request->has('phone_number'))
            $user->phone_number = $request->get('phone_number');
        if ($request->file('avatar')) {
            $file = $request->file('avatar');
            $file->move(public_path() . '/images/', $user->id . '.jpg');
            $user->avatar = '/images/' . $user->id . '.jpg';
        }
        if ($request->has('first_name'))
            $user->first_name = $request->get('first_name');
        if ($request->has('last_name'))
            $user->last_name = $request->get('last_name');
        if ($request->has('new_password')) {
            if (Auth::attempt(['email' => $user->email, 'password' => $request->get('old_password')])) {
                $user->password = bcrypt($request->get('new_password'));
            } else {
                throw new ErrorException("The password provided does not match an existing password for this user");
            }
        }
        $user->save();
        return $user;
    }

    public static function getUser(Request $request)
    {
        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $api_token = $request->header('api');
        return User::where('api_token', $api_token)->first();
    }

    public static function addPoints(Request $request)
    {
        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();
        if ($request->has('points')) {
            $user->points += $request->get('points');
        } else {
            throw new MissingMandatoryParametersException('You need to provide points');
        }
        $user->save();
        return $user;
    }

    public static function subtractPoints(Request $request)
    {
        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();
        if ($request->has('points')) {
            $user->points -= $request->get('points');
        } else {
            throw new MissingMandatoryParametersException('You need to provide points');
        }
        $user->save();
        return $user;
    }

    public static function postCurrentLocation(Request $request)
    {
        if (!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null) {
            throw new AccessDeniedException('You need to provide a valid API token');
        }

        if (!$request->has('latitude') || !$request->has('longitude')) {
            throw new MissingMandatoryParametersException('You need to provide latitude and longitude for a new location');
        }

        $user = User::where('api_token', $request->header('api'))->first();

        $user->latitude = $request->get('latitude');
        $user->longitude = $request->get('longitude');

        $user->save();

        return $user;
    }
}
