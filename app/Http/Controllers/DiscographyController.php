<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\dto\DTOAlbum;

//use App\User;

class DiscographyController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  Request $request
     * @return Response
     */
    public function getAlbums(Request $request)
    {
        $artist = $request->query()['q'];

        $response = Http::withToken($request->header('Authorization'))->get('https://api.spotify.com/v1/search?q='.$artist.'&type=artist');
        
        if($response->status() == 401){
            return response()->json(['error' => 'Unauthenticated.'], 401);
        };
        
        $band = json_decode($response->body());
        if($band->artists->items == []){
            return response()->json(['Not found' => 'Artist not found.'], 404); 
        }
        $bandId = $band->artists->items[0]->id;
        $response = Http::withToken($request->header('Authorization'))->get('https://api.spotify.com/v1/artists/'.$bandId.'/albums?limit=50&market=AR');
        $albums = json_decode($response->body());
        $album = new DTOAlbum();
        $album->name = $albums->items[0]->name;
        $album->released = $albums->items[0]->release_date;
        $album->tracks = $albums->items[0]->total_tracks;
        $album->cover = ['height' => $albums->items[0]->images[0]->height];
        $album->cover = ['width' => $albums->items[0]->images[0]->width];
        $album->cover = ['url' => $albums->items[0]->images[0]->url];
        
        $albumsArray = [];
        for($i = 0; $i < count($albums->items); $i++){
            $album = new DTOAlbum();
            $album->name = $albums->items[$i]->name;
            $album->released = $albums->items[$i]->release_date;
            $album->tracks = $albums->items[$i]->total_tracks;
            $album->cover = Array('height' => $albums->items[$i]->images[0]->height,
                                    'width' => $albums->items[$i]->images[0]->width,
                                    'url' => $albums->items[$i]->images[0]->url);
           
            $albumsArray[] = ($album);
        }

        return response()->json($albumsArray);
        return($response);
        
    }
}