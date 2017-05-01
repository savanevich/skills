<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\UserFollower;
use App\Models\Technology;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserSkillRequest;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortingColumn = $request->sort;
        $sortingOrder = $request->order;
        $perPage = $request->limit ? intval($request->limit) : 10;

        if($sortingColumn) {
            $users = User::getSortedUsers($sortingColumn, $sortingOrder, $perPage);
        } else {
            $users = User::paginate($perPage);
        }

        if($users) {
            return response()->json($users, 200);
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
        $userId = User::createUser($request);

        if ($userId) {
            $user = User::getUserById($userId);
            
            return $this->toJsonResponse(201, $user, false);
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
        $user = User::getUserById($id);

        if ($user) {
            return $this->toJsonResponse(200, $user, false);
        } else {
            $error = 'User with id = ' . $id . ' wasn\'t found';

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
        $user = Auth::User();
        $userId = $user->id;

        $messages = [
            'required' => 'The :attribute field is required.',
            'email.email' => 'The field email must have format as an e-mail address',
            'unique' => 'User with this :attribute already exists'
        ];

        $rules = [
            'username' => 'required|max:255|unique:users,username,' . $userId,
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'first_name' => 'max:255',
            'second_name' => 'max:255'
        ];

        $this->validate($request, $rules, $messages);

        $affected = User::updateUser($request, $userId);

        if($affected) {
            $data = User::find($id);

            return $this->toJsonResponse(201, $data, false); 
        } else {
            $error = 'User with id = ' . $id . ' wasn\'t found';

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
        $deleted = User::deleteUser($id);

        if($deleted) {

            return $this->toJsonResponse(204, $id, false);
        } else {
            $error = 'User with id = ' . $id . ' wasn\'t found';

            return $this->toJsonResponse(404, false, $error);
        }
    }

    /**
     * Display a listing of the skills of user.
     *
     * @param $userId
     *
     * @return \Illuminate\Http\Response
     */
    public function getSkills($userId)
    {
        $skills = User::getSkillsByUser($userId);

        if($skills) {

            return $this->toJsonResponse(200, $skills, false);
        } else {
            $error = 'Nothing was found';

            return $this->toJsonResponse(404, false, $error);
        }
    }

    /**
     * Add the skill to the user.
     *
     * @param Request $request
     * @param  int $userId
     *
     * @return \Illuminate\Http\Response
     * @internal param $StoreUserSkillRequest
     */
    public function addSkill(Request $request, $userId)
    {
        $user = Auth::User();
        $userId = $user->id;

        $messages = [
            'required' => 'The :attribute field is required.',
            'email.email' => 'The field email must have format as an e-mail address',
            'unique' => 'User with this :attribute already exists'
        ];

        $rules = [
            'name' => 'required',
            'level' => 'required|between:1,10',
            'category_id' => 'required|exists:categories,id'
        ];

        $this->validate($request, $rules, $messages);

        //checking, maybe this technology has already existed
        $idOfExistedTechnology = Technology::isTechnologyExistByName($request->name);

        if($idOfExistedTechnology) {
            $technologyId = $idOfExistedTechnology;

            //checking, maybe this user has already had this skill
            $result = User::isUserHaveThisSkill($userId, $technologyId);
            if($result) {
                $error = "This user has already had this skill.";

                return $this->toJsonResponse(409, false, $error);
            }
        } else {
            $technologyId = Technology::createTechnology($request->name, $request->category_id);
        }

        $result = User::createSkill($userId, $technologyId, $request->level);

        if($result) {
            $skill = $user->technologies()->with('category')
                ->where('name', $request->name)
                ->first();

            return $this->toJsonResponse(200, $skill, false);
        } else {
            $error = 'Nothing was added';

            return $this->toJsonResponse(404, false, $error);
        }
    }

    /**
     * Update user's skill
     *
     * @param StoreUserSkillRequest $request
     * @param $userId
     *
     * @return json
     * @throws Exception
     */
    public function updateSkill(StoreUserSkillRequest $request, $userId)
    {
        $user = Auth::User();
        $level = $request->pivot['level'];
        $userTechnologyId = $request->pivot['id'];
        $technologyName = $request->name;
        $technologyId = $request->pivot['technology_id'];
        $categoryId = $request->category['id'];

        //checking, maybe this user will update skill without changes in name
        $skill = User::getSkillByTechnology($user, $technologyName);
        if ($skill) {
            Technology::changeCategory($technologyId, $categoryId);
            $user->technologies()->updateExistingPivot($technologyId, ['level' => $level]);
        } else {
            $technologyId = Technology::createOrUpdateIfExists($technologyName, $categoryId);
            DB::table('user_technology')
                            ->where('id', $userTechnologyId)
                            ->update(['technology_id' => $technologyId,
                                      'level' => $level]);
        }
        $affectedSkill = User::getSkillByTechnology($user, $technologyName);

        return $this->toJsonResponse(200, $affectedSkill, false);
    }

    /**
     * Remove user's skill from db
     *
     * @param $userId
     * @param $technologyId
     *
     * @return json
     * @throws Exception
     */
    public function deleteSkill($userId, $technologyId)
    {
        $user = Auth::User();
        $user->technologies()->detach(intval($technologyId));
        return $this->toJsonResponse(200, $technologyId, false);
    }

    /**
     * Get path of user image.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function getAvatarLink($userId)
    {
        $pathOfUserImage = User::getImagePath($userId);

        if($pathOfUserImage) {
            if (file_exists($pathOfUserImage)) {
                $url = $pathOfUserImage;
            } else {
                $url = "users_avatars/no-user-image.gif";
            }

            return $this->toJsonResponse(200, $url, false);
        }else{
            $error = 'Error loading image';

            return $this->toJsonResponse(404, false, $error);
        }
    }

    /**
     * Save the new user image and delete the rpevious .
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function saveAvatar(Request $request, $userId)
    {
        $user = Auth::User();
        $userId = $user->id;
        $defaultPicture = "users_avatars/no-user-image.gif";

        $imageToDelete = User::getImagePath($userId);
        $file = Input::file('userfile');
        $newImageName = "user" . $userId . "_" . str_random(4) . "." . $file->getClientOriginalExtension();
        $result = $file->move('users_avatars', $newImageName);

        if ($result) {
            User::setImagePath('users_avatars/' . $newImageName, $userId);
            if ($imageToDelete != $defaultPicture && file_exists($imageToDelete)) {
                unlink(public_path($imageToDelete));
            }
        }

        return $this->toJsonResponse(200, 'users_avatars/' . $newImageName, false);
    }

    /**
     * Add follower to the user
     *
     * @param Request $request
     * @return json
     */
    public function followUser(Request $request)
    {
        $user = Auth::User();
        $userId = $user->id;

        $rules = [
            'followingId' => 'required|exists:users,id'
        ];

        $this->validate($request, $rules);

        $userFollower = new UserFollower;

        $userFollower->user_id = $request->followingId;
        $userFollower->follower_id = $userId;

        $userFollower->save();

        $result = array(
            'user_id' => $userFollower->user_id,
            'follower_id' => $userFollower->follower_id,
        );

        return $this->toJsonResponse(200, $result, false);
    }

    /**
     * Unfollow user
     *
     * @param Request $request
     * @return json
     */
    public function unFollowUser(Request $request)
    {
        $user = Auth::User();
        $userId = $user->id;

        $rules = [
            'followingId' => 'required|exists:users,id'
        ];

        $this->validate($request, $rules);

        $deletedRows = UserFollower::where(
            array(
                'follower_id' => $userId,
                'user_id' => $request->followingId
            ))->delete();

        return $this->toJsonResponse(200, $deletedRows, false);
    }
}
