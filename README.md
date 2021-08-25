<h1 align="center"> laravel-devinit </h1>

<p align="center"> laravel develop init script.</p>
基于laravel框架的日常项目开发初始化脚本,用于简化开发的初始化工作，交互安装，发布常用代码、配置、迁移文件。devinit只发布代码并没有实际功能。

- RBAC相关代码
- 用户手机号登录注册
- 小程序登录注册
- 短信验证相关功能



#### 可选扩展包

| **扩展包** | **一句话描述** | **本项目应用场景** |
| ---- | :--- | ---- |
| [laravel/passport](https://github.com/laravel/passport) | Laravel Passport is an OAuth2 server and API authentication package that is simple and enjoyable to use. | 用户认证 |
| [barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper) | This package generates helper files that enable your IDE to provide accurate autocompletion. Generation is done based on the files in your project, so they are always up-to-date.| IDE开发自动补全工具 |
| [laravel-telescope](https://github.com/laravel/telescope) | Laravel Telescope is an elegant debug assistant for the Laravel framework | laravel框架调试工具 |
| [laravel-horizon](https://github.com/laravel/horizon) |Horizon provides a beautiful dashboard and code-driven configuration for your Laravel powered Redis queues. Horizon allows you to easily monitor key metrics of your queue system such as job throughput, runtime, and job failures. |队列仪表盘|
| [tucker-eric/eloquentfilter](https://github.com/tucker-eric/eloquentfilter) | An Eloquent way to filter Eloquent Models and their relationships| 字段查询筛选过滤 |
| [overtrue/laravel-lang](https://github.com/overtrue/laravel-lang) |laravel语言包 |语言配置 |
| [yangliuan/generator](https://github.com/yangliuan/generator) |代码生成器|提升开发效率 |
| [propaganistas/laravel-phone](https://github.com/propaganistas/laravel-phone) |支持全球地区的手机号验证|手机号验证|
| [overtrue/easy-sms](https://github.com/overtrue/easy-sms) |支持多平台的短信发送|短信发送|
| [overtrue/wechat](https://github.com/w7corp/easywechat) |微信sdk|微信开发|




## Installing

```shell
## Command list

$ php artisan dev:init //执行初始化

$ php artisan dev:reset //刷新数据和passport的证书以及配置

$ php artisan dev:refresh-rules //刷新权限路由，该命令中通过数组配置权限

## Usage

#创建laravel项目
composer create-project --prefer-dist laravel/laravel project

#安装devinit
composer require yangliuan/laravel-devinit --dev 

#执行初始化命令开始交互安装
php artisan dev:init

#选择api用户认证方式 passport
 please choice authorization method ? [passport]:
  [0] passport
 > 0
 
#是否启用 passport --uuid选项 
 would you use --uuid options? (yes/no) [yes]:
 yes

#the next input must be yes!
#使用 UUID 作为  Passport Client 的主键，而不是使用自动递增的整数作为主键 ，启用--uuid选项后这一步必须要选择yes，否则会有错误提示（不影响最终结果）
 In order to finish configuring client UUIDs, we need to rebuild the Passport database tables. Would you like to rollback and re- run your last migration? (yes/no) [no]:
yes

#用户登录方式 手机验证码登录 微信小程序登录 自定义
 please choice users login method [mobile-smscode]:
  [0] mobile-smscode
  [1] wechat-miniprogram
  [2] custom
 > 0

#选择验证码发送方式 easysms扩展包 自定义
 please choice smscode type [easysms]:
  [0] easysms
  [1] custom
 > 0

#自动安装 overtrue/laravel-lang 语言包
start install overtrue/laravel-lang
install overtrue/laravel-lang successed!

#是否安装 tucker-eric/eloquentfilter sql查询过滤
 Do you want to install barryvdh/laravel-ide-helper? [yes]:
  [0] yes
  [1] no
 > 0

#是否安装接口crud工具
 Do you want to install yangliuan/generator? [yes]:
  [0] yes
  [1] no
 > 1

#是否安装horizon队列仪表盘
 Do you want to install laravel/horizon? [yes]:
  [0] yes
  [1] no
 > 1

#是否安装telescope调试工具
 Do you want to install laravel/telescope? [yes]:
  [0] yes
  [1] no
 > 1

#是否安装laravel ide提示工具
 Do you want to install barryvdh/laravel-ide-helper? [yes]:
  [0] yes
  [1] no
 > 0

#生成passport 个人客户端配置 回车确认
 What should we name the personal access client? [LaravelDevTest Personal Access Client]:
 > 
Personal access client created successfully.
Client ID: 93da6bff-ef83-4637-99c1-8e82f37e8ac4
Client secret: AQgPQFOSNAcEDK2rcMZHuGKsL1YACNSrCGzQblNH
clear rules...
refresh rules

## Contributing

#config/easysms.php  验证码配置 测试验证码
'no_send_smscode' => env('NO_SEND_SMSCODE', '')

#config/adminrbac.php rbac权限配置 AdminRBAC中间件跳过验证的路由 路由格式和$request->is()方法一致，支持*通配符
'except_routes' => [
   'admin/login'
]

#权限配置 app/Console/Commands/RefreshAdminRulesCmd.php 根据参数值区分不同路由时 'params' => 'name1=value1&name2=value2'
#pid上级权限id status是否开启验证 is_log是否记录日志 sort排序值
[
   'id' => 0, 'pid' => 0, 'name' => '权限名称',
   'api_http_method' => '接口请求方法', 'api_behavior' => '接口路由', 'params' => '接口参数',
   'gui_type' => 2, 'gui_behavior' => '前端路由或页面', 'status' => 0, 'is_log' => 0, 'sort' => 0
],


## License

MIT