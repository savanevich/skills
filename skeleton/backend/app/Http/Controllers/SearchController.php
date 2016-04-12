<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    /**
     * Display a result of searching by skills.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function searchSkills(Request $request)
    {
        $users = User::searchUsersBySkills($request);
        $totalUsers = User::countUsersBySearch($request);
        
        if(!$users || !$totalUsers) {
            $error = 'Nothing was found';

            return $this->toJsonResponse(404, false, $error);
        } else {
            $skills = [];

            foreach ($users as $key => $value) {
                $skills[$value->user_id] = User::getSkillsByUser($value->user_id);
            }

            $result = ['users' => $users, 'skills' => $skills, 'total' => $totalUsers];

            return $this->toJsonResponse(200, $result, false);
        }
    }
}
