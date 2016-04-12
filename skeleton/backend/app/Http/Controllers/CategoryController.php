<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::getCategories();

        if($categories) {

            return $this->toJsonResponse(200, $categories, false);              
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
        $newid = Category::createCategory($request);

        if($newid) {
             $data = Category::getCategory($newid);

            return $this->toJsonResponse(201, $data, false);     
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
        $category = Category::getCategory($id);

        if($category) {

            return $this->toJsonResponse(200, $category, false); 
        } else {
            $error = 'Category with id = ' . $id . ' wasn\'t found';

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
        $affected = Category::updateCategory($request, $id);

        if($affected) {
            $data = Category::getCategory($id);

            return $this->toJsonResponse(201, $data, false); 
        } else {
            $error = 'Category with id = ' . $id . ' wasn\'t found';

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
        $deleted = Category::deleteCategory($id);

        if($deleted) {
            $data = $id;

            return $this->toJsonResponse(204, $data, false);
        } else {
            $error = 'Category with id = ' . $id . ' wasn\'t found';

            return $this->toJsonResponse(404, false, $error);
        }
    }

    /**
     * Get all technologies by the given category
     *
     * @param  integer $id technology's id
     * @return \Illuminate\Http\Response
     */
    public function getTechnologies($id)
    {
        $technologiesByCategory = Category::getTechnologiesByCategory($id);

        if($technologiesByCategory) {

            return $this->toJsonResponse(200, $technologiesByCategory, false);
        } else {
            $error = "No users with this technology.";

            return $this->toJsonResponse(404, false, $error);
        }
    }

    /**
     * Get all users by the given category
     *
     * @param  integer $id of given category
     * @return \Illuminate\Http\Response
     */
    public function getUsers($id)
    {
        $usersByCategory = Category::getUsersByCategory($id);

        if($usersByCategory) {

            return $this->toJsonResponse(200, $usersByCategory, false);
        } else {
            $error = "No users with this category.";

            return $this->toJsonResponse(404, false, $error);
        }
    }
}
