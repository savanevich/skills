<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
   /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

	/**
     * Get the technologies by category
     *
     * @return mixed
     */
    public function technologies()
    {
        return $this->hasMany('App\Models\Technology');
    }

    /**
     *	Gets all categories from db
     *
     * @return array
     */
    public static function getCategories()
    {
    	$query = "SELECT * FROM categories";
    	$result = DB::select($query);

    	return $result;
    }

    /**
     * Gets one category by id from db
     *
     * @param  int  $id
     * @return array
     */
    public static function getCategory($id)
    {
    	$query = "SELECT * FROM categories WHERE id = :id";
    	$result = DB::select($query, ['id' => $id]);

    	return $result;
    }

     /**
     * Creates new category in db
     *
     * @param object $data
     * @return  int [<id of new entity>]
     */
    public static function createCategory($data)
    {
        DB::beginTransaction();
        try{
            $query = "INSERT INTO categories(name) VALUES (:name)";
            DB::insert($query, ['name' => $data->name]);
            $result = DB::select('SELECT LAST_INSERT_ID() AS newid');
            $id = $result[0]->newid;
            DB::commit();
            return $id;

        }catch(\Exception $e) {
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
    public static function updateCategory($data, $id)
    {
    	$query = "UPDATE categories SET name = :name WHERE id = :id";
    	$result = DB::update($query, ['name' => $data->name, 'id' => $id]);

    	return $result;
    }

    /**
     * Delete one record from db by id
     *
     * @param  int $id
     * @return int [<number of rows deleted>]
     */
    public static function deleteCategory($id)
    {
    	$query = "DELETE FROM categories WHERE id = :id";
    	$result = DB::delete($query, ['id' => $id]);

    	return $result;
    }

    /**
     * Get all technologies with the given category
     *
     * @param  int $id
     * @return array
     */
    public static function getTechnologiesByCategory($id)
    {
        $query =   "SELECT t.name FROM technologies AS t 
                    INNER JOIN categories AS c ON c.id = t.category_id
                    WHERE c.id = :id";
        $result = DB::select($query, ['id' => $id]);

        return $result;
    }

    /**
     * Get the users with the given category
     *
     * @param  int $id of given category
     * @return array
     */
    public static function getUsersByCategory($id)
    {
        $query =   "SELECT u.id, u.username, u.email 
                    FROM users AS u 
                    INNER JOIN user_category AS uc ON u.id = uc.user_id 
                    INNER JOIN categories AS c ON c.id = uc.category_id
                    WHERE uc.category_id = :id";
        $result = DB::select($query, ['id' => $id]);

        return $result;
    }
}
