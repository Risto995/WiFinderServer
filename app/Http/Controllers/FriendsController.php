<?php

namespace App\Http\Controllers;

use App\User;
use App\Friends;

use Illuminate\Http\Request;
use Psy\Exception\ErrorException;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class FriendsController extends Controller
{
    public static function getUsersFriends(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();
        $friendsRelationship = Friends::where('first_user', $user->id)->orWhere('second_user', $user->id)->get();
        $friends = [];
        foreach ($friendsRelationship as $frR){
            $id = null;
            if($frR->first_user == $user->id)
                $id = $frR->second_user;
            else
                $id = $frR->first_user;
            $f = User::where('id', $id)->first();
            array_push($friends, $f);
        }
        if($friends == null)
            throw new ErrorException('This user has no friends :(');
        return $friends;
    }

    public static function addFriend(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();

        if(Friends::where('first_user', $user->id)->where('second_user', $request->get('friend_id'))->first() != null
            || Friends::where('first_user', $request->get('friend_id'))->where('second_user', $user->id)->first() != null ) {
            throw new ErrorException('This friendship already exists!');
        }
        
        $friends = new Friends();
        $friends->first_user = $user->id;
        $friends->second_user = $request->get('friend_id');
        $friends->save();
        return $friends;
    }

    public static function locationFriends(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();

        $user->latitude = $request->get('latitude');
        $user->longitude = $request->get('longitude');

        $user->save();

        $friendsRelationship = Friends::where('first_user', $user->id)->orWhere('second_user', $user->id)->get();
        $friends = [];
        foreach ($friendsRelationship as $frR){
            $id = null;
            if($frR->first_user == $user->id)
                $id = $frR->second_user;
            else
                $id = $frR->first_user;
            $f = User::where('id', $id)->first();
            array_push($friends, $f);
        }
        if($friends == null)
            throw new ErrorException('This user has no friends :(');
        return $friends;
    }

    public static function getUserWithFriends(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();
        $friendsRelationship = Friends::where('first_user', $user->id)->orWhere('second_user', $user->id)->get();
        $friends = [];
        foreach ($friendsRelationship as $frR){
            $id = null;
            if($frR->first_user == $user->id)
                $id = $frR->second_user;
            else
                $id = $frR->first_user;
            $f = User::where('id', $id)->first();
            array_push($friends, $f);
        }
        if($friends == null)
            throw new ErrorException('This user has no friends :(');

        array_push($friends, $user);
        return $friends;
    }

    public static function removeFriend(Request $request)
    {
        if(!$request->hasHeader('api') || User::where('api_token', $request->header('api'))->first() == null)
            throw new AccessDeniedException('You need to provide a valid API token');
        $user = User::where('api_token', $request->header('api'))->first();
        $friends = Friends::where('first_user', $user->id)->where('second_user', $request->get('friend_id'))->first();
        $friends->delete();
        return [];
    }
}
