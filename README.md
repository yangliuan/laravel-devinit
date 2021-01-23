<h1 align="center"> laravel-devinit </h1>

<p align="center"> laravel develop init script.</p>
基于laravel框架的日常搬砖开发初始化脚本,用于简化开发中的初始化工作，生成基于laravel-passport前后台API认证功能,和RBAC权限管理。覆盖原有框架部分原有代码生成控制器和模型完成开发初始化工作
#### 脚本将会自动安装如下扩展包

| **扩展包** | **一句话描述** | **本项目应用场景** |
| ---- | ---- | ---- | 
| [laravel/passport](https://github.com/laravel/passport) | Laravel Passport is an OAuth2 server and API authentication package that is simple and enjoyable to use. | 用户认证 |
| [barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper) | This package generates helper files that enable your IDE to provide accurate autocompletion. Generation is done based on the files in your project, so they are always up-to-date.| IDE开发自动补全工具 |
| [laravel-telescope](https://github.com/laravel/telescope) | Laravel Telescope is an elegant debug assistant for the Laravel framework | laravel框架调试工具 |
| [laravel-telescope-toolbar](https://github.com/fruitcake/laravel-telescope-toolbar) |Extends Laravel Telescope to show a powerful Toolbar |laravel框架调试工具条|
| [tucker-eric/eloquentfilter](https://github.com/tucker-eric/eloquentfilter) | An Eloquent way to filter Eloquent Models and their relationships| 字段查询筛选过滤 |
| [overtrue/laravel-lang](https://github.com/overtrue/laravel-lang) |https://github.com/overtrue/laravel-lang| laravel语言包 |




## Installing

```shell
$ composer require yangliuan/laravel-devinit --dev

$ php artisan dev:init //执行初始化

$ php artisan dev:reset //刷新数据和key

$ php artisan dev:make  Banner --schema="name:string(50):default(''):comment('名称'),asset:string(255):default(''):comment('资源图片'),sort:integer:default(0):comment('排序值'),show_position:tinyInteger:default(0):comment('展示位0Banner')"

crud生成工具，迁移文件属性之间用“:”分割，字段组之间用“,”分割， 交互操作
----------- dev: Banner -----------

 Do you want to make all? (yes/no) [no]:
 > n

 Do you want to make migration? (yes/no) [no]:
 > n

 Do you want to make seed? (yes/no) [no]:
 > n

 Do you want to make model? (yes/no) [no]:
 > n

 Do you want to make controller? (yes/no) [no]:
 > n

 Do you want to make form request? (yes/no) [no]:
 > n

 Do you want to make model observer? (yes/no) [no]:
 > n

 Do you want to make policy? (yes/no) [no]:
 > n

 Do you want to run migrate? (yes/no) [no]:
 > n


----------- ----------- -----------
----------- >DUMP AUTOLOAD< -----------
```


## Usage



## Contributing



## License

MIT