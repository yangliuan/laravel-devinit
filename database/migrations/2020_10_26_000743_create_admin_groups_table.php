<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_groups', function (Blueprint $table)
        {
            $table->id();
            $table->string('title', 100)->default('')->comment('管理组名称');
            $table->string('desc', 255)->default('')->comment('描述');
            $table->tinyInteger('status', false, true)->default('1')->comment('状态:1为正常,0为冻结,其他状态自定义');
            $table->longText('cache')->nullable()->comment('缓存字段');
            $table->timestamps();
            $table->index('status');
        });

        DB::statement("ALTER TABLE admin_groups comment '管理组表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_groups');
    }
}
