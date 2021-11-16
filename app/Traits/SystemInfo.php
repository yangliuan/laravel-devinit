<?php
/**
 * 系统信息
 */
namespace App\Traits;

trait SystemInfo
{
    public function getSysinfo()
    {
        if (auth('admin')->user()->id === 1) {
            return [
                ['name' => 'Timezone', 'value' => get_cfg_var("date.timezone")],
                ['name' => 'Time', 'value' => date("Y-m-d H:i:s")],
                ['name' => 'IP', 'value' => $_SERVER['SERVER_ADDR']],
                ['name' => 'Host', 'value' => $_SERVER['SERVER_NAME']],
                ['name' => 'Port', 'value' => $_SERVER['SERVER_PORT']],
                ['name' => 'Uname', 'value' => php_uname()],
                ['name' => 'Web server', 'value' => $_SERVER['SERVER_SOFTWARE']],
                ['name' => 'Server protocol', 'value' => $_SERVER['SERVER_PROTOCOL']],
                ['name' => 'PHP version', 'value' => PHP_VERSION],
                ['name' => 'PHP SAPI', 'value' => php_sapi_name()],
                ['name' => 'Upload max filesize', 'value' => get_cfg_var("upload_max_filesize")],
                ['name' => 'Max execution time', 'value' => get_cfg_var("max_execution_time") . "秒 "],
                ['name' => 'Memory limit', 'value' => get_cfg_var("memory_limit")],
                ['name' => 'Laravel version', 'value' => $laravel = app()::VERSION],
                ['name' => 'App Env', 'value' => config('app.env')],
                ['name' => 'App debug', 'value' => config('app.debug')],
                ['name' => 'Broadcasting driver', 'value' => config('broadcasting.default')],
                ['name' => 'Cache driver', 'value' => config('cache.default')],
                ['name' => 'Filesystems driver', 'value' => config('filesystems.default')],
                ['name' => 'Queue driver', 'value' => config('queue.default')],
                ['name' => 'Database driver', 'value' => config('database.default')],
                ['name' => 'Database version', 'value' => $this->databaseVersion()],
            ];
        } else {
            return [];
        }
    }

    public function databaseVersion()
    {
        if (config('database.default') === 'mysql') {
            list($res) = \DB::select('select version() as version');

            return $res->version;
        } else {
            return '';
        }
    }
}
