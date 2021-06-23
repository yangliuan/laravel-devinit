<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->default('')->comment('管理员名称');
            $table->string('account', 20)->default('')->comment('登录账号');
            $table->string('mobile', 20)->default('')->comment('手机号');
            $table->string('password')->default('')->comment('密码');
            $table->string('email')->default('')->comment('email');
            $table->timestamp('email_verified_at')->nullable()->comment('email验证时间');
            $table->integer('group_id', false, true)->default('0')->comment('管理组id');
            $table->tinyInteger('status', false, true)->default('1')->comment('状态1正常0冻结');
            $table->rememberToken();
            $table->timestamps();
            $table->index('name');
            $table->index('mobile');
            $table->index('email');
            $table->index('group_id');
        });
        DB::statement("ALTER TABLE admins comment '管理员用户表'");

        DB::table('admins')->insert([
            'name' => '系统管理员',
            'account' => 'admin',
            'mobile' => 'admin',
            'email' => '',
            'group_id' => 0,
            'password' => bcrypt('admin'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
