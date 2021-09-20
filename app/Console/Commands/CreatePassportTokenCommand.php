<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Console\Command;

class CreatePassportTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport-token:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $passportType = $this->choice('请输入用户类型', ['api', 'admin'], 'api');
        $userId = $this->ask('请输入用户ID');

        if (!$userId)
        {
            $this->error('缺少用户ID');
            return;
        }

        switch ($passportType)
        {
            case 'api':
                $passportUser = User::find($userId);
                break;
            case 'admin':
                $passportUser = Admin::find($userId);
                break;
        }

        if (!$passportUser)
        {
            $this->error('用户ID不存在');
            return;
        }

        $this->info($passportUser->getToken());
    }
}
