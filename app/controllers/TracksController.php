<?php

class TracksController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $sortType = Input::get('sort', 'uploaded');
        $order = Input::get('order', 'desc');
        $sortSettings = array(
            'type' => $sortType,
            'order' => $order
        );

        if (Input::has('page')) {
            $response = array();
            $response['tracks'] = Track::getTracksForPage(Input::get('page'), $sortSettings);
            $response['pageCount'] = Track::getTracksTotalPageCount();
            $response['sortSettings'] = $sortSettings;

            return Response::json($response);
        } else {
            return Response::json(Track::getAllSorted($sortType, $order));
        }
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return Response::json(Track::getTrack($id));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

    public function search($searchTerms) {

        $sortType = Input::get('sort', 'uploaded');
        $order = Input::get('order', 'desc');

        $tracks = Track::getAllSorted($sortType, $order);

        $searchResults = array();
        $searchArray = explode("+", preg_replace('/\s/', "+", $searchTerms));

        foreach ($searchArray as $searchTerm) {
            $cleanedSearchTerm = strtolower(preg_replace('/[^a-zA-Z0-9-_]/', "", $searchTerm));
            foreach($tracks as $track) {
                if (strpos(strtolower($track->title), $cleanedSearchTerm) !== FALSE || strpos(strtolower($track->name), $cleanedSearchTerm) !== FALSE) {
                    array_push($searchResults, $track);
                }
            }
        }

        return Response::json($searchResults);
    }

}