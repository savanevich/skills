<?php

namespace App\Models;

use DB;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use Pagination;
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'technologies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['name', 'priority', 'category_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

	/**
     * Get the users, which have the given technology
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\Users', 'user_technology')->withPivot('id' ,'level');
    }

	/**
     * Get category by technology
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

	/**
	 * @param $request
     *
     * @return mixed
     */
    public static function getTechnologies($request)
    {
        $query = "SELECT * FROM technologies";
        $query = self::addPaginationToQuery($query);
        $paginationParams = self::toPaginationParameters($request->page, $request->limit);
        $result = DB::select($query, $paginationParams);

        return $result;
    }

    /**
     * Gets one technology by id from db
     *
     * @param  int  $id
     * @return array
     */
    public static function getTechnology($id)
    {
    	$query = "SELECT * FROM technologies WHERE id = :id";
    	$result = DB::select($query, ['id' => $id]);

    	return $result;
    }

    /**
     * Creates new technology in db
     *
     * @param $name
     * @param $categoryId
     * @param $priority
     *
     * @return int
     */
    public static function createTechnology($name, $categoryId, $priority = 1)
    {

       DB::beginTransaction();

        try {
            $query = "INSERT INTO technologies(name, priority, category_id) VALUES(:name, :priority, :category_id)";
            DB::insert($query, ['name' => $name, 'priority' => $priority, 'category_id' => $categoryId]);

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
    public static function updateTechnology($data, $id)
    {
    	$query = "UPDATE technologies SET name = :name, priority = :priority, category_id = :category_id WHERE id = :id";
    	$result = DB::update($query, ['name' => $data->name, 'priority' => $data->priority, 'id' => $id, 'category_id' => $data->category_id]);

    	return $result;
    }

    /**
     * Delete one record from db by id
     *
     * @param  int $id
     * @return int [<number of rows deleted>]
     */
    public static function deleteTechnology($id)
    {
    	$query = "DELETE FROM technologies WHERE id = :id";
    	$result = DB::delete($query, ['id' => $id]);

    	return $result;
    }

    /**
     * Get the users with the given technology
     *
     * @param  int $id technology's id
     * @return array
     */
    public static function getUsersByTechnology($id)
    {
        $query = "SELECT u.id, u.username, u.email, ut.level 
                  FROM users AS u 
                  INNER JOIN user_technology AS ut ON u.id = ut.user_id 
                  INNER JOIN technologies AS t ON t.id = ut.technology_id
                  WHERE ut.technology_id = :id";
        $result = DB::select($query, ['id' => $id]);

        return $result;
    }

    /**
     * Check if this name of technology exists
     *
     * @param  string $name
     * @return boolean/int 
     */
    public static function isTechnologyExistByName($name)
    {
        $query = "SELECT id
                  FROM technologies
                  WHERE name = :technology_name";
        //return array which contains objects with property id of existed technology
        $idOfExistedTechnology = DB::select($query, ['technology_name' => $name]);

        if(!count($idOfExistedTechnology)) {

            return false;
        } else {

            return $idOfExistedTechnology[0]->id;
        }
    }

	/**
	 * Count number of technologies in the table
	 *
	 * @return mixed
	 */
	public static function countTechnologies()
	{
		$query = "SELECT COUNT(*) AS size FROM technologies";
		$result = DB::select($query);

		return $result[0]->size;
	}

	/**
     * Change category of technology
     *
     * @param $technologyId
     * @param $categoryId
     */
    public static function changeCategory($technologyId, $categoryId)
    {
        $technology = self::find($technologyId);
        $technology->category_id = $categoryId;
        $technology->save();
    }

	/**
     * @param $technologyName
     * @param $categoryId
     *
     * @return int
     */
    public static function createOrUpdateIfExists($technologyName, $categoryId)
    {
        $technology = self::where('name', $technologyName)->first();
        if ($technology) {
            $technologyId = $technology->id;

            self::changeCategory($technologyId, $categoryId);
        } else {
            $technologyId = self::createTechnology($technologyName, $categoryId);
        }

        return $technologyId;
    }

	/**
     * Get ordering technologies
     *
     * @param $sortingColumn
     * @param $sortingOrder
     *
     * @return mixed
     */
    public static function getSortedTechnologies($sortingColumn, $sortingOrder)
    {
        switch($sortingColumn) {
            case 'name':
                $sortingColumn = 'technologies.name';
                break;
            case 'category.name':
                $sortingColumn = 'categories.name';
                break;
            default:
                $sortingColumn = 'technologies.name';
        }
        $sortingOrder = $sortingOrder === 'desc' ? 'desc' : 'asc';

        $technologies = self::with('category')
            ->join('categories', 'technologies.category_id', '=', 'categories.id')
            ->orderBy($sortingColumn, $sortingOrder)
            ->select('technologies.name', 'technologies.category_id');

        return $technologies;
    }
}
