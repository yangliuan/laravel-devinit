<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminGrouprulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_group_rules', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('group_id', false, true)->default('0')->comment('管理组id');
            $table->integer('rule_id', false, true)->default('0')->comment('规则id');
            $table->index(['group_id', 'rule_id']);
            $table->timestamps();
        });
        DB::statement("ALTER TABLE admin_group_rules comment '管理组权限规则表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_group_rules');
    }
}
