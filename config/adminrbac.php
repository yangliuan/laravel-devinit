<?php

return [
    //AdminRBAC中间件跳过验证的路由
    //路由格式和$request->is()方法一致，支持*通配符
    'except_routes' => [
        'admin/login'
    ],

    //开发模式,开启时，显示权限规则管理
    'develop_model' => env('ADMIN_DEVELOP_MODEL', false),
];
