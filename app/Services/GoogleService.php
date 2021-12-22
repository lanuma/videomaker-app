<?php

namespace App\Services;

use Google;
use Google\Service\TrafficDirectorService\GoogleRE2;

Class GoogleService
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
        if(config('google.oauth_client_id') && config('google.oauth_secret_id')) {
            $this->google->setClientId(config('google.oauth_client_id'));
            $this->google->setClientSecret(config('google.oauth_client_secret'));

            $this->google->setScopes($this->scope ?? [
                Google\Service\YouTube::YOUTUBE_READONLY,
                Google\Service\YouTube::YOUTUBE_UPLOAD
            ]);
            $this->google->setRedirectUri($this->redirect);
            $this->google->setAccessType('offline');
            $this->oauth = new Google\Service\Oauth2($this->google);
        }else{
            $this->google->setDeveloperKey(config('google.developer_api_key'));
        }

        if(session('youtube-token')) {
            $this->google->setAccessToken(session('youtube-token')['access_token'] );
            $this->google->refreshToken(session('youtube-token')['refresh_token']);

            if($this->google->isAccessTokenExpired()) {
                session()->forget('youtube-token');
                redirect()->route('youtube');
            }
        }

        // dump($this->google->isAccessTokenExpired());


    }

    public function auth_url()
    {
        if (!config('google.oauth_client_id') && !config('google.oauth_client_secret')) {
            throw new \Exception('You must provide google oauth_client_id and oauth_client_secret to use this method');
        }
        // dd($this->redirect);

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
}
