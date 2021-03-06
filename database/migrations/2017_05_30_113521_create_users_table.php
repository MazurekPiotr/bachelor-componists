<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->text('avatar')->nullable();
            $table->enum('role', ['user', 'moderator', 'admin']);
            $table->string('country')->nullable();
            $table->string('imageURL')->nullable();
            $table->string('password')->nullable();
            $table->datetime('last_activity')->default(\Carbon\Carbon::now());
            $table->boolean('verified')->default(false);
            $table->string('token')->null();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
