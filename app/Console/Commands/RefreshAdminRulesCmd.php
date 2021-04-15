<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RefreshAdminRulesCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:adminrules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh admin rules';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('clear rules...');
        DB::table('admin_rules')->truncate();
        DB::table('admin_group_rules')->truncate();
        DB::table('admin_groups')->where('id', '>', 0)->update(['cache' => null]);
        DB::table('admin_rules')->insert($this->getRulesData());
        $this->info('refresh rules');
    }

    protected function getRulesData()
    {
        $at = Carbon::now();
        $rules = [
            //权限管理
            [
                'id' => 1, 'pid' => 0, 'name' => '权限管理',
                'api_http_method' => 'GET', 'api_behavior' => '', 'params' => '',
                'gui_type' => 1, 'gui_behavior' => '', 'status' => 1, 'is_log' => 0, 'sort' => 999
            ],
            [
                'id' => 2, 'pid' => 1, 'name' => '管理组',
                'api_http_method' => 'GET', 'api_behavior' => 'admin/group', 'params' => '',
                'gui_type' => 2, 'gui_behavior' => '', 'status' => 1, 'is_log' => 0, 'sort' => 1
            ],
            [
                'id' => 3, 'pid' => 2, 'name' => '添加管理组',
                'api_http_method' => 'POST', 'api_behavior' => 'admin/group', 'params' => '',
                'gui_type' => 3, 'gui_behavior' => '', 'status' => 0, 'is_log' => 1, 'sort' => 1
            ],
            [
                'id' => 4, 'pid' => 2, 'name' => '管理组详情',
                'api_http_method' => 'GET', 'api_behavior' => 'admin/group/', 'params' => '',
                'gui_type' => 3, 'gui_behavior' => '', 'status' => 0, 'is_log' => 0, 'sort' => 1
            ],
            [
                'id' => 5, 'pid' => 2, 'name' => '更新管理组',
                'api_http_method' => 'PUT,PATCH', 'api_behavior' => 'admin/group/', 'params' => '',
                'gui_type' => 3, 'gui_behavior' => '', 'status' => 0, 'is_log' => 1, 'sort' => 1
            ],
            [
                'id' => 6, 'pid' => 2, 'name' => '删除管理组',
                'api_http_method' => 'DELETE', 'api_behavior' => 'admin/group/', 'params' => '',
                'gui_type' => 3, 'gui_behavior' => '', 'status' => 0, 'is_log' => 1, 'sort' => 1
            ],
            [
                'id' => 8, 'pid' => 1, 'name' => '管理员',
                'api_http_method' => 'GET', 'api_behavior' => 'admin/admin', 'params' => '',
                'gui_type' => 2, 'gui_behavior' => '', 'status' => 1, 'is_log' => 0, 'sort' => 2
            ],
            [
                'id' => 9, 'pid' => 8, 'name' => '添加管理员',
                'api_http_method' => 'POST', 'api_behavior' => 'admin/admin', 'params' => '',
                'gui_type' => 3, 'gui_behavior' => '', 'status' => 0, 'is_log' => 1, 'sort' => 1
            ],
            [
                'id' => 10, 'pid' => 8, 'name' => '管理员详情',
                'api_http_method' => 'GET', 'api_behavior' => 'admin/admin/', 'params' => '',
                'gui_type' => 3, 'gui_behavior' => '', 'status' => 0, 'is_log' => 0, 'sort' => 1
            ],
            [
                'id' => 11, 'pid' => 8, 'name' => '更新管理员',
                'api_http_method' => 'PUT,PATCH', 'api_behavior' => 'admin/admin/', 'params' => '',
                'gui_type' => 3, 'gui_behavior' => '', 'status' => 0, 'is_log' => 1, 'sort' => 1
            ],
            [
                'id' => 12, 'pid' => 8, 'name' => '删除管理员',
                'api_http_method' => 'DELETE', 'api_behavior' => 'admin/admin/', 'params' => '',
                'gui_type' => 3, 'gui_behavior' => '', 'status' => 0, 'is_log' => 1, 'sort' => 1
            ],
            [
                'id' => 13, 'pid' => 8, 'name' => '启用禁用',
                'api_http_method' => 'PUT,PATCH', 'api_behavior' => 'admin/admin/status/', 'params' => '',
                'gui_type' => 3, 'gui_behavior' => '', 'status' => 0, 'is_log' => 1, 'sort' => 1
            ],
            [
                'id' => 14, 'pid' => 1, 'name' => '系统日志',
                'api_http_method' => 'GET', 'api_behavior' => 'admin/syslogs', 'params' => '',
                'gui_type' => 2, 'gui_behavior' => '', 'status' => 1, 'is_log' => 0, 'sort' => 2
            ],

        ];
        data_set($rules, '*.created_at', $at);
        data_set($rules, '*.updated_at', $at);

        return $rules;
    }
}
