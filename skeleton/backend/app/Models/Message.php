<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['body'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo('User', 'recipient_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo('User', 'sender_id');
    }

    /**
     * Get message by userID
     *
     * @param $messageID
     * @return mixed
     */
    public static function getMessage($messageID)
    {
        $query = "SELECT `m`.id AS id, `m`.body, `m`.sender_id, `m`.recipient_id, `m`.created_at, `u`.username, `u`.image
                  FROM messages AS `m`
                  INNER JOIN users AS `u` ON `m`.sender_id = `u`.id
                  WHERE `m`.id = :id";

        $result = DB::select($query, ['id' => $messageID]);

        return $result[0];
    }

    /**
     * Get messages by userID
     *
     * @param $senderID
     * @param $recipientID
     * @return mixed
     */
    public static function getMessages($senderID, $recipientID)
    {
        $query = "SELECT `m`.id AS id, `m`.body, `m`.sender_id, `m`.recipient_id, `m`.created_at, `u`.username, `u`.image
                  FROM messages AS `m`
                  INNER JOIN users AS `u` ON `m`.sender_id = `u`.id
                  WHERE `m`.sender_id = :senderID AND `m`.recipient_id = :recipientID";

        $result = DB::select($query, ['senderID' => $senderID, 'recipientID' => $recipientID]);

        return $result;
    }
}
