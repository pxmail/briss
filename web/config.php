<?php

/**
 * HiHause service 配置文件
 *
 * -日期--------修改者----------- 内容 ----------------------
 * 2015-07-28  xie            first edition
 */
// 系统维护状态
define('MANTANCING', false);

// 数据库相关定义
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'biss');
define('DB_USER', 'root'); //'u_tea' );
define('DB_PASS', 'ezs2016'); //'Ba53%$a(Px7' );
// 调试日志文件
define('DEBUG_LOG', '../log/debug.log');
// 业务日志文件
define('BIZ_LOG', '../log/biz.log');
// 错误日志文件
define('ERROR_LOG', '../log/php_error.log');
// 触屏端登录用的验证码存放文件
define('TOUCH_CODE_FILE', '../code.txt');

// 错误码文件
define('FILE_ERROR_CODES', 'error_codes');
// 权限码定义文件
define('FILE_PRIVILEGES', 'privileges');
// JWT密钥
define('JWT_SECRET', 'nrW9;|D@dZY8R?xFDaiPxa&j8B,p/Y~DE3p0tJHA& J^3zaG+Q@xNU(/0NYF{a9h');

// 七牛空间设置
define ( 'QINIU_CALLBACK', 'https://briss.ezsport.com.cn/qiniu_callback.php' );
define ( 'QINIU_ACCESS_KEY', 'acr2JZZJ4PrCSLZFeWqSUql2UQSNw0CQnWAI7Df9' );
define ( 'QINIU_SECRET_KEY', '_CxszUGc_iD5zCNN7TPFQjXaUXhKJdEKcws8HBLd' );
define ( 'QINIU_PRIVATE_BUCKET', 'cert-test' );
define ( 'QINIU_PUBLIC_BUCKET', 'ezsport-test' );
define ( 'QINIU_URL_HTTPS', 'https://dn-ezsport-test.qbox.me/');
