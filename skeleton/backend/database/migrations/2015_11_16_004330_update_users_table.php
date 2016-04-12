<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'username');
            $table->string('first_name')->after('name');
            $table->string('second_name')->after('first_name');
            $table->string('avatar_link')->after('second_name')->default('img/users-avatars/no-user-image.gif');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('username', 'name');
            $table->dropColumn(array('first_name', 'second_name', 'avatar_link'));
        });
    }
}