<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Class VerificationCode.
 */
class VerificationCode
{
    const KEY_TEMPLATE = 'verify_code_of_%s';

    /**
     * 创建并存储验证码
     *
     * @param string $phone
     * @param string $type 类型 user用户,member联盟会员
     * @return int
     */
    public static function create($phone, $type = 'user')
    {
        $code = mt_rand(1000, 9999);
        $log = Log::channel('smscode');
        $log->info("生成验证码:{$phone}:{$code}");
        $key = $type . '_' . sprintf(self::KEY_TEMPLATE, $phone);
        Cache::put($key, $code, 300);
        try
        {
            $result = app('easysms')->send($phone, [
                'content'  => '验证码' . $code . '，您正在登录，若非本人操作，请勿泄露。',
                'template' => '',
                'data' => [
                    'code' => $code
                ],
            ]);
        }
        catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $e)
        {
            $log->info('发送失败' . $e->getLastException()->getMessage());

            return false;
        }

        $log->info('验证码:' . $code . '发送成功' . "\r\n");

        return $code;
    }

    /**
     * 检查手机号与验证码是否匹配.
     *
     * @param string $phone
     * @param int    $code
     * @param string $type 类型 user用户,member联盟会员
     *
     * @return bool
     */
    public static function validate($phone, $code, $type = 'user')
    {
        if (config('app.debug') && config('easysms.no_send_smscode') === $code)
        {
            return true;
        }

        if (empty($phone) || empty($code))
        {
            return false;
        }

        $key = $type . '_' . sprintf(self::KEY_TEMPLATE, $phone);
        $cachedCode = Cache::get($key);
        $log = Log::channel('smscode');
        $log->info('cached verify code', ['key' => $key, 'cached' => $cachedCode, 'input' => $code]);
        $log->info("\r\n");

        return strval($cachedCode) === strval($code);
    }
}
