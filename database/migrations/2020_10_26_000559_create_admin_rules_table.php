<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_rules', function (Blueprint $table)
        {
            $table->id();
            $table->integer('pid', false, true)->default('0')->comment('上级id');
            $table->string('name', 100)->default('')->comment('标题');
            $table->string('api_http_method', 100)->default('')->comment('接口http请求方式');
            $table->string('api_behavior', 255)->default('')->comment('接口行为');
            $table->string('params', 255)->default('')->comment('参数');
            $table->tinyInteger('gui_type', false, true)->default('1')->comment('图形界面类型:1主菜单2子菜单3事件');
            $table->string('gui_behavior', 255)->default('')->comment('图形界面行为');
            $table->tinyInteger('status', false, true)->default('0')->comment('状态:0禁用1启用');
            $table->tinyInteger('is_log', false, true)->default('0')->comment('是否记录日志:0禁用1启用');
            $table->integer('sort', false, true)->default('0')->comment('排序值');
            $table->timestamps();
            $table->index('pid');
            $table->index('status');
        });

        DB::statement("ALTER TABLE admin_rules comment '权限规则表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_rules');
    }
}
