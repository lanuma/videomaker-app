<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleServiceReal;
use App\Models\User;
use Auth;

class YoutubeController extends Controller
{
    public function index(Request $request, User $user)
    {

        $google = new GoogleServiceReal('http://localhost:8000/youtube', null);
        $auth_url =  $google->auth_url();

        if ($request->get('code')) {
            $token = $google->fetchAccessToken($request->get('code'));
            $request->session()->put('youtube-token', $token);

            $user->updateOrCreate([
                'id' => '1'
            ])->channel_detail()->updateOrCreate([
                'channel_id' => $google->getMyChannel()->getItems()[0]->id
            ],[
                'channel_name' => $google->getMyChannel()->getItems()[0]->getSnippet()->title,
                'access_token' => $token['access_token'],
                'expires_in' => $token['expires_in'],
                'refresh_token' => $token['refresh_token'],
                'scope' => $token['scope'],
                'token_type' => $token['token_type'],
            ]);

            // Auth::loginUsingId(1);


            return redirect()->route('youtube');

        }
        // dd($google->getMyChannel()->getItems()[0]->getSnippet());

        dump(auth()->user());

        return view('pages.google', compact('auth_url'));
    }

    public function login()
    {
        Auth::loginUsingId(1);

        return redirect()->route('youtube');
    }
}
