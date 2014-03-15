<?php

use Gosu\Repositories\MySQLTracksRepository;

class TracksController extends BaseController {

    protected $tracks;

    // Inject the MySQLTracksRepository. We use a repository instead of just using the
    // model because the Controller shouldn't depend on the Eloquent model. We might
    // want to support more than just MySQL later on, so we use a TracksRepository interface.
    public function __construct(MySQLTracksRepository $tracks) {
        $this->tracks = $tracks;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
        $sortType = Input::get('sort', 'uploaded');
        $order = Input::get('order', 'desc');
        $sortSettings = array(
            'type' => $sortType,
            'order' => $order
        );

        if (Input::has('page')) {
            $response = array();
            $response['tracks'] = $this->tracks->forPage(Input::get('page'), $sortSettings);
            $response['pageCount'] = $this->tracks->pageCount();

            return Response::json($response);
        } else {
            return Response::json($this->tracks->allSorted($sortType, $order));
        }
	}

    /**
     * Shows a filtered list of tracks
     */
    public function filter() {
        return Response::json($this->tracks->filter(Input::all()), 200);
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {
        $track = $this->tracks->find($id);
        $track->stats = $this->tracks->getStats($id);

        // Make sure a track with that id actually exists
        if (!is_null($track)) {
            // If a token is given, check if user is authenticated and if they have voted for this track
            if (Input::has('token')) {
                $user = AuthToken::auth(Input::get('token'));
                $vote = Vote::get($user->id, $track->trackId);

                // If vote doesn't exist, default to 0 (no vote yet)
                if (is_null($vote)) {
                    $track->liked = 0;
                } else {
                    $track->liked = $vote->liked;
                }
            }

            return Response::json($track, 200);
        } else {
            return Response::json(NULL, 400);
        }
	}

    public function search($searchTerms) {

        $sortType = Input::get('sort', 'uploaded');
        $order = Input::get('order', 'desc');

        $tracks = $this->tracks->allSorted($sortType, $order);

        $searchResults = array();
        $searchArray = explode("+", preg_replace('/\s/', "+", $searchTerms));

        foreach ($searchArray as $searchTerm) {
            $cleanedSearchTerm = strtolower(preg_replace('/[^a-zA-Z0-9-_]/', "", $searchTerm));
            foreach($tracks as $track) {
                if (strpos(strtolower($track->title), $cleanedSearchTerm) !== FALSE || strpos(strtolower($track->artistName), $cleanedSearchTerm) !== FALSE) {
                    array_push($searchResults, $track);
                }
            }
        }
        $response = array();
        $response["tracks"] = $searchResults;
        $response["count" ] = count($searchResults);

        return Response::json($response);
    }

}