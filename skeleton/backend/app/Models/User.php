<?php

namespace App\Models;

use DB;

use App\Traits\Pagination;
use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Pagination;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'first_name', 'second_name', 'email'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

	/**
     * Get the skills, which belongs to the given user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function technologies()
    {
        return $this->belongsToMany('App\Models\Technology', 'user_technology')->withPivot('id', 'level');;
    }

    /**
     *  Gets all users from db
     *
     * @return array
     */
    public static function getUsers()
    {
        $query = "SELECT * FROM users";
        $result = DB::select($query);

        return $result;
    }

    /**
     * Gets one user by id from db
     *
     * @param  int  $id
     * @return array
     */
    public static function getUserById($id)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $result = DB::select($query, ['id' => $id]);

        return $result;
    }

    /**
     * Creates new user in db
     *
     * @param object $data
     * @return  int 
     */
    public static function createUser($data)
    {

       DB::beginTransaction();

        try {
            $query = "INSERT INTO users(username, first_name, second_name, email, password) 
                VALUES(:username, :first_name, :second_name, :email, :password)";
            DB::insert($query, [
                'username' => $data->username,
                'first_name' => $data->first_name,
                'second_name' => $data->second_name,
                'email' => $data->email, 
                'password' => Hash::make($data->password)
            ]);

            //get array which contain object with field new which contain id of new entity
            $result = DB::select('SELECT LAST_INSERT_ID() AS newid');
            $id = $result[0]->newid;

            DB::commit();

            return $id;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Update existing record in db
     *
     * @param  object $data
     * @param  int  $id
     * @return int  [<number of rows affected>]
     */
    public static function updateUser($data, $id)
    {
        $query = "UPDATE users SET username = :username, first_name = :first_name, second_name = :second_name, email = :email  WHERE id = :id";
        $result = DB::update($query, [
            'username' => $data->username,
            'first_name' => $data->first_name,
            'second_name' => $data->second_name,
            'email' => $data->email,
            'id' =>$id
        ]);

        return $result;
    }

    /**
     * Delete one record from db by id
     *
     * @param  int $id
     * @return int [<number of rows deleted>]
     */
    public static function deleteUser($id)
    {
        $query = "DELETE FROM users WHERE id = :id";
        $result = DB::delete($query, ['id' => $id]);

        return $result;
    }

    /**
     * Create one skill for user in db
     *
     * @param $userId
     * @param $technologyId
     * @param $level
     *
     * @return int id of created entity
     */
    public static function createSkill($userId, $technologyId, $level)
    {
        DB::beginTransaction();

        try {
            $query = "INSERT INTO user_technology(user_id, technology_id, level)
                      VALUES(:user_id, :technology_id, :level)";
            $result = DB::insert($query, ['user_id' => $userId, 
                                          'technology_id' => $technologyId, 
                                          'level' => $level]);

            $answ = DB::select('SELECT LAST_INSERT_ID() AS newid');
            $user_techn_id = $answ[0]->newid;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return false;
        }

        return $user_techn_id;
    }

    /**
     * Get the skills, which belong to the user
     *
     * @param  int  $userId
     * @return array
     */
    public static function getSkillsByUser($userId)
    {
        $result = self::find($userId)
            ->technologies()
            ->with('category')
            ->get();

        return $result;
    }

    /**
     * Get information about one skill
     *
     * @param $userTechnologyId
     *
     * @return array
     */
    public static function getSkill($userTechnologyId)
    {
        $query = "SELECT ut.id AS user_technology_id, ut.user_id, ut.technology_id, t.name AS technology_name, ut.level 
                  FROM user_technology AS ut
                  INNER JOIN technologies AS t 
                  ON ut.technology_id = t.id
                  WHERE ut.id = :id";
        $result = DB::select($query, ['id' => $userTechnologyId]);

        return $result;
    }

    /**
     * Checking, if the user has this skill 
     *
     * @param  int $userId
     * @param  int $technologyId
     * @return boolean
     */
    public static function isUserHaveThisSkill($userId, $technologyId)
    {
        $query = "SELECT COUNT(ut.id) AS count
                  FROM user_technology AS ut
                  WHERE ut.technology_id = :technology_id AND ut.user_id = :user_id";
        $result = DB::select($query, ['technology_id' => $technologyId,
                                      'user_id' => $userId]);
        $result = $result[0]->count;

        if($result) {

            return true;
        } else {
            
            return false;
        }
    }

    /**
     * Get users, which have skill with entered parameters
     *
     * @param $request
     *
     * @return array
     */
    public static function searchUsersBySkills($request)
    {
        $query = "SELECT DISTINCT u.id AS user_id, u.username AS username, u.first_name, u.second_name, u.email, u.image, u.created_at
                  FROM users AS u 
                  INNER JOIN user_technology AS ut ON u.id = ut.user_id 
                  INNER JOIN technologies AS t ON t.id = ut.technology_id
                  WHERE u.username LIKE :username AND t.name LIKE :technology_name AND ut.level >= :level";
        $username = '%' . $request->username . '%';
        $technologyName = '%' . $request->technology . '%';
        $level = intval($request->level);
        $params = ['username' => $username,
                   'technology_name' => $technologyName,
                   'level' => $level];
        if($request->category) {
            $query .= " AND t.category_id = :category";
            $params['category'] = intval($request->category);
        }
        $query = self::addPaginationToQuery($query);
        $paginationParams = self::toPaginationParameters($request->page, $request->limit);
        $params = array_merge($paginationParams, $params);

        $result = DB::select($query, $params);
        return $result;
    }

    /**
     * Return url of user image
     *
     * @param  int $id of user
     * @return string
     */
    public static function getImagePath($id)
    {
        $query = "SELECT image FROM users WHERE id = :id";
        $result = DB::select($query, ['id' => $id]);
        $result = $result[0]->image;
        return $result;
    }

    /**
     * Store url of user image 
     *
     * @param  int $id of user
     * @return string
     */
    public static function setImagePath($data, $id)
    {
        $query = "UPDATE users SET image = :image WHERE id = :id";
        $result = DB::update($query, ['image' => $data, 'id' =>$id]);
        return $result;
    }

	/**
     * @param $request
     *
     * @return mixed
     */
    public static function countUsersBySearch($request)
    {
        $query = "SELECT COUNT(DISTINCT u.id) AS total
                  FROM users AS u
                  INNER JOIN user_technology AS ut ON u.id = ut.user_id
                  INNER JOIN technologies AS t ON t.id = ut.technology_id
                  WHERE u.username LIKE :username AND t.name LIKE :technology_name AND ut.level >= :level";
        $username = '%' . $request->username . '%';
        $technologyName = '%' . $request->technology . '%';
        $level = intval($request->level);
        $params = ['username' => $username,
                   'technology_name' => $technologyName,
                   'level' => $level];

        if($request->category) {
            $query .= " AND t.category_id = :category";
            $params['category'] = intval($request->category);
        }
        $result = DB::select($query, $params);

        return $result[0]->total;
    }

    /**
     * Check if user exists
     *
     * @param $userId
     *
     * @return mixed
     * @throws Exception
     */
    public static function checkIfUserExists($userId)
    {
        $user = self::find($userId);
        if (!$user) {
            throw new Exception('This user doesn\'t exist.');
        }
        return $user;
    }

	/**
     * Return user's skill
     *
     * @param $user
     * @param $technologyName
     *
     * @return mixed
     */
    public static function getSkillByTechnology($user, $technologyName)
    {
        $skill = $user->technologies()
                        ->with('category')
                        ->where('name', $technologyName)
                        ->first();

        return $skill;
    }

    /**
     * Get ordering users
     *
     * @param $sortingColumn
     * @param $sortingOrder
     * @param $perPage
     *
     * @return mixed
     */
    public static function getSortedUsers($sortingColumn, $sortingOrder, $perPage)
    {
        $model = new User();
        if (!in_array($sortingColumn, $model->fillable)) {
            //default sorting
            $sortingColumn = 'username';
        }
        $sortingOrder = $sortingOrder === 'desc' ? 'desc' : 'asc';

        $users = self::orderBy($sortingColumn, $sortingOrder)->paginate($perPage);

        return $users;
    }
}
