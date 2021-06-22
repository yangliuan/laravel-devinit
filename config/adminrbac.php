<?php

return [
    //AdminRBAC中间件跳过验证的路由
    //路由格式和$request->is()方法一致，支持*通配符
    'except_routes' => [
        'admin/login'
    ]
];
