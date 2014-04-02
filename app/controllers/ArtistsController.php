<?php

class ArtistsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        if (Input::has('page')) {

            $sortType = Input::get('sort', 'name');
            $order = Input::get('order', 'ascd');
            $gender = Input::get('gender', 'all');
            $type = Input::get('type', 'all');

            $filters = array(
                'gender' => $gender,
                'type' => $type
            );

            $sorts = array(
                'type' => $sortType,
                'order' => $order
            );

            $response = array();
            $response['artists'] = Artist::getArtistsForPage(Input::get('page'), $filters, $sorts);
            $response['pageCount'] = Artist::getArtistsTotalPageCount();

            return Response::json($response);
        } else {
		  return Artist::with('tracks')->get();
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

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return Response::json(Artist::with('tracks')->find($id));
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
        $sortType = Input::get('sort', 'name');
        $order = Input::get('order', 'desc');
        $artists = Artist::getAll();
        $searchResults = array();

        $searchArray = explode("+", preg_replace('/\s/', '+', $searchTerms));
        foreach ($searchArray as $searchTerm) {
            $cleanedSearchTerm = strtolower(preg_replace('/[^a-zA-Z0-9-_]/', "", $searchTerm));
            foreach($artists as $artist) {
                if (strpos(strtolower($artist->name), $cleanedSearchTerm) !== FALSE) {
                    array_push($searchResults, $artist);
                }
            }
        }

        $response = new stdClass();
        $response->artists = $searchResults;
        $response->pageCount = count($searchResults);

        return Response::json($response);
    }

}