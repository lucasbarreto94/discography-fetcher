<?php

namespace App\Http\ApiClients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpotifyApiClient
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //
    public function getArtistsByName($name, $securityToken)
    {
        $response = Http::withToken($securityToken)->get('https://api.spotify.com/v1/search?q='.$name.'&type=artist');
        if($response->status() == 401){
            return null;
        };
        $result = json_decode($response->body());
        $artist= $result->artists->items;
        return $artist;
    }

    public function getAlbumsByArtistId($bandId, $securityToken)
    {
        $response = Http::withToken($securityToken)->get('https://api.spotify.com/v1/artists/'.$bandId.'/albums?limit=50&market=AR');
        if($response->status() == 401){
            return null;
        };
        $albums = json_decode($response->body());
        return $albums;
    }
}
