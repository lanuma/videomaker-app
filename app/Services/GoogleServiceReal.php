<?php

namespace App\Services;

use Google;
use Google\Service\TrafficDirectorService\GoogleRE2;
use App\Models\ChannelDetails;
use Auth;

Class GoogleServiceReal
{
    public $google;

    public $client;

    public $oauth;

    protected $scope;

    protected $redirect;

    public function __construct($redirect=null, $scope=null)
    {
        if (null !== $scope) $this->scope = $scope;
        if (null !== $redirect) $this->redirect = $redirect;

        $this->google = new Google\Client;
        $this->google->setClientId(config('google.oauth_client_id'));
        $this->google->setClientSecret(config('google.oauth_client_secret'));

        $this->google->setScopes($this->scope ?? [
            Google\Service\YouTube::YOUTUBE_READONLY,
            Google\Service\YouTube::YOUTUBE_UPLOAD
        ]);
        $this->google->setRedirectUri($this->redirect);
        $this->google->setAccessType('offline');
        $this->google->setPrompt('consent');
        // $this->google->setIncludeGrantedScopes(true);
        $this->oauth = new Google\Service\Oauth2($this->google);


        if(session('youtube-token')) {
            $this->google->setAccessToken(session('youtube-token')['access_token'] );
            $this->google->refreshToken(session('youtube-token')['refresh_token']);

            if($this->google->isAccessTokenExpired()) {
                session()->forget('youtube-token');
                redirect()->route('youtube');
            }
        }

    }

    public function auth_url()
    {
        return $this->google->createAuthUrl();
    }

    public function fetchAccessToken($code)
    {
        return $this->google->fetchAccessTokenWithAuthCode($code);
    }

    public function getVideoCategories()
    {
        $youtube = new Google\Service\YouTube($this->google);

        $queryParams = [
            'regionCode' => 'ID'
        ];

        return $youtube->videoCategories->listVideoCategories('snippet', $queryParams);
    }

    public function youtube()
    {
        return new Google\Service\YouTube($this->google);
    }

    public function getMyChannel()
    {
        $queryParams = [
            'mine' => true
        ];

        return $this->youtube()->channels->listChannels('snippet,contentDetails,statistics', $queryParams);
    }
}
