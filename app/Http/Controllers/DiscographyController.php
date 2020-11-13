<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\dto\DTOAlbum;
use App\Http\ApiClients\SpotifyApiClient;
//use App\User;

class DiscographyController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  Request $request
     * @return Response
     */
    protected $spotifyApiClient;

    public function __construct()
    {
        $this->spotifyApiClient = new SpotifyApiClient();
    }

    public function getAlbums(Request $request)
    {
        $artist = $request->query()['q'];

        $bands = $this->spotifyApiClient->getArtistsByName($artist, $request->header('Authorization'));

        if (!isset($bands)){
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        if($bands == []){
            return response()->json(['Not found' => 'Artist not found.'], 404); 
        }
        $bandId = $bands[0]->id;
        
        $albums = $this->spotifyApiClient->getAlbumsByArtistId($bandId, $request->header('Authorization'));
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