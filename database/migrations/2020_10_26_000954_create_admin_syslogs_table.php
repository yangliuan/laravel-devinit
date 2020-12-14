<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminSyslogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_syslogs', function (Blueprint $table)
        {
            $table->id();
            $table->integer('admin_id')->default('0')->comment('管理员id')->index('admin_id');
            $table->string('log', 255)->default('')->comment('操作内容');
            $table->ipAddress('ip')->default('')->comment('ip地址');
            $table->string('method', 100)->default('')->comment('请求方法');
            $table->text('params')->nullable()->comment('请求参数');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE admin_syslogs comment '管理员操作日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_syslogs');
    }
}
