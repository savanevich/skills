<?php

namespace App\Models;

use DB;

use Illuminate\Database\Eloquent\Model;

class UserFollower extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_follower';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
