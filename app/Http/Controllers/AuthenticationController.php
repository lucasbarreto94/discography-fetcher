<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthenticationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $urlLogin;
    private $urlToken;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    public function __construct()
    {
        $config = include('../config.php');
        $this->urlLogin = $config['urlLoginSpotify'];
        $this->urlToken = $config['urlTokenSpotify'];
        $this->clientId = $config['clientId'];
        $this->clientSecret = $config['clientSecret'];
        $this->redirectUri = $config['redirectUriSpotify'];

    }

    public function authenticate(Request $request)
    {
        return redirect($this->urlLogin.'?client_id='.$this->clientId.'&redirect_uri='.$this->redirectUri.'&response_type=code');

    }

    public function callback(Request $request)
    {
        if(isset($request->query()['error'])) {
            return view('login');
        }

        $response = Http::asForm()->post($this->urlToken, [
            'client_id' => $this->clientId,
            'grant_type' => 'authorization_code',
            'code' => $request->query()['code'],
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri
        ]);
        
        return ("access token: ".json_decode($response->body())->access_token);
    }
}
