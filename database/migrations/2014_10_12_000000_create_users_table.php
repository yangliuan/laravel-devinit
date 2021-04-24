<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->string('wechat_openid', 100)->default('')->comment('微信标识');
            $table->string('mobile', 15)->default('')->comment('手机号');
            $table->string('name', 20)->default('')->comment('昵称');
            $table->string('avatar', 255)->default('')->comment('头像');
            $table->tinyInteger('sex', false, true)->default(0)->comment('性别0未知1男2女');
            $table->string('province', 20)->default('')->comment('省');
            $table->string('city', 20)->default('')->comment('市');
            $table->string('area', 20)->default('')->comment('区');
            $table->string('email')->default('')->comment('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default('')->comment('密码');
            $table->rememberToken();
            $table->timestamps();
            $table->index('mobile');
        });
        DB::statement("ALTER TABLE users comment '用户表'");
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
