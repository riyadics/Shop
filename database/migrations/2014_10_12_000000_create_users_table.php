<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            $table->string('nickname')->unique();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('pic_url')->nullable();
            $table->string('language')->default('en');
            $table->string('mobile_phone')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('description')->nullable();
            $table->string('time_zone')->nullable();
            $table->integer('rate_val')->nullable();
            $table->integer('rate_count')->nullable();
            $table->enum('role', array_keys(trans('antvel::globals.roles')))->default('person');
            $table->enum('type', array_keys(trans('antvel::globals.type_user')))->default('normal');
            $table->enum('verified', array_keys(trans('antvel::globals.verification')))->default('no');
            $table->json('preferences')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('disabled_at')->nullable();
            $table->softDeletes();
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
