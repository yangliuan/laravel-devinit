<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
        {
            $table->id();
            $table->string('mobile', 15)->default('')->comment('手机号');
            $table->string('name', 20)->default('')->comment('昵称');
            $table->string('avatar', 255)->default('')->comment('头像');
            $table->string('email')->default('')->comment('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default('')->comment('密码');
            $table->rememberToken();
            $table->timestamps();
            $table->index('mobile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
