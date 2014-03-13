<?php

class PlaylistsController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Response::json(Playlist::all(), 200);
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
        $name = Input::get('name');
        $private = Input::get('private');

        if ($name) {
            if (strlen($name) > 2 && strlen($name < 30)) {
                $playlistId = Playlist::insert(array(
                    'token' => Input::get('token'),
                    'name' => $name,
                    'private' => $private
                ));
                $responseObj = new stdClass();

                $responseObj->id = $playlistId;
                $responseObj->name = $name;

                return Response::json($responseObj, 200);
            }
        } else {
            return Response::json(array(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $response = array();

        $response['playlist'] = Playlist::getById($id);
        $response['tracks'] = Playlist::getTracks($id);
        return Response::json($response);
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

        $responseObj = array();
        $responseObj['test'] = $id;
        return Response::json($responseObj);
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

}