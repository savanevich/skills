<?php

namespace App\Http\Controllers;

use App\Models\Technology;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TechnologyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $njkn = 0;
        $sortingColumn = $request->sort;
        $sortingOrder = $request->order;
        $perPage = $request->limit ? intval($request->limit) : 10;

        if($sortingColumn) {
            $technologies = Technology::getSortedTechnologies($sortingColumn, $sortingOrder)
                ->paginate($perPage);
        } else {
            $technologies = Technology::with('category')
                ->paginate($perPage);
        }

        if($technologies) {
            return response()->json($technologies, 200);
        } else {
            $error = 'Nothing was found';

            return $this->toJsonResponse(404, false, $error);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = Technology::createTechnology($request->name, $request->category_id, $request->priority);

        if($result) {
            $technology = Technology::getTechnology($result);
            
            return $this->toJsonResponse(201, $technology, false);     
        } else {
            $error = 'Nothing was added';

            return $this->toJsonResponse(404, false, $error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $technology = Technology::getTechnology($id);

        if($technology) {

            return $this->toJsonResponse(200, $technology, false); 
        } else {
            $error = 'Technology with id = ' . $id . ' wasn\'t found';

            return $this->toJsonResponse(404, false, $error);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $affected = Technology::updateTechnology($request, $id);

        if($affected) {
            $data = Technology::getTechnology($id);

            return $this->toJsonResponse(201, $data, false); 
        } else {
            $error = 'Technology with id = ' . $id . ' wasn\'t found';

            return $this->toJsonResponse(404, false, $error);  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = Technology::deleteTechnology($id);

        if($deleted) {
            $data = $id;

            return $this->toJsonResponse(204, $data, false);
        } else {
            $error = 'Technology with id = ' . $id . ' wasn\'t found';

            return $this->toJsonResponse(404, false, $error);
        }
    }
    
    /**
     * Get all users by the given technology
     *
     * @param  integer $id technology's id
     * @return \Illuminate\Http\Response
     */
    public function getUsers($id)
    {
        $usersByTechnology = Technology::getUsersByTechnology($id);

        if($usersByTechnology) {

            return $this->toJsonResponse(200, $usersByTechnology, false);
        } else {
            $error = "No users with this technology.";

            return $this->toJsonResponse(404, false, $error);
        }
    }
}
