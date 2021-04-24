<h1 align="center"> laravel-devinit </h1>

<p align="center"> laravel develop init script.</p>
基于laravel框架的日常搬砖开发初始化脚本,用于简化开发中的初始化工作，生成基于laravel-passport前后台API认证功能,和RBAC权限管理。覆盖原有框架部分原有代码生成控制器和模型完成开发初始化工作
#### 脚本将会自动安装如下扩展包

| **扩展包** | **一句话描述** | **本项目应用场景** |
| ---- | ---- | ---- | 
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
$ composer require yangliuan/laravel-devinit --dev

$ php artisan dev:init //执行初始化

$ php artisan dev:reset //刷新数据和key

## Usage



## Contributing



## License

MIT