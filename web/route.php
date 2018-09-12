<?php

/**
 * HHause 路由器，对传入的参数做基本校验，并调用指定api
 * 
 * -日期--------修改者----------- 内容 ----------------------
 * 2015-09-14  xie            first edition 
 */

namespace fly;

require 'config.php';
require 'fly/action.php';
require 'fly/log.php';
require 'fly/model.php';
require 'fly/jwt.php';

// 调试日志
namespace\Log::debug($_REQUEST);
namespace\route::go();

class route {

    public static function go() {
        // 设置错误处理函数，PHP执行错误记录日志、发送邮件，并返回浏览器错误信息
        set_error_handler('\\' . __NAMESPACE__ . '\\route::error_handler');

        // 配置autoload
        set_include_path(get_include_path()
                . PATH_SEPARATOR . __DIR__ . DIRECTORY_SEPARATOR . 'model');
        
        spl_autoload_register(function ($class) {
            $parts = explode('\\', $class);
            $classname = end($parts) . '.php';
            if (stream_resolve_include_path($classname)) {
                require_once $classname;
            }
        });

        // 系统维护
        if (MANTANCING) {
            self::error(1001);
        }

        self::main();
    }

    private static function main() {
        $module = filter_input(INPUT_GET, 'module', FILTER_SANITIZE_STRING);
        $method = filter_input(INPUT_GET, 'method', FILTER_SANITIZE_STRING);

        // 由于nginx已经做过正则表达式判断，这里不再对api名称是否合法作检验（如检查名称中包含 /. 这类字符）
        $api_file = __DIR__ . DIRECTORY_SEPARATOR . 'action' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $method . '.php';
        // 如果API文件不存在，返回 1002	Invalid API
        if (is_file($api_file) === false) {
            self::error(1002);
        }

        include_once $api_file;
        // 构建对象，传入客户端POST参数，以及uid（用户编号）
//        $class = '\\fly\\' . $method;
        $action = new $method();
        
        // 检查用户权限
        $required_privilege = $action->getPrivilege();
            
        if (isset($required_privilege)) {
            $user = self::getUser();
            $action->user = $user;
            
            if (isset($user->privileges) && in_array($required_privilege, $user->privileges) === FALSE) {
                include_once 'fly/privilege.php';
                $privilege = new namespace\Privilege();
                self::error(1006, $privilege->getName($required_privilege));
            }
        }

        // 检查客户端POST传入的paramter
        $params = self::filterParams($action);

        // 执行实际处理业务
        $result = $action->exec($params);
        if (is_numeric($result)) {
            self::error($result);
        }

        // 输出JSON数据
        header('Content-type: application/json;charset=UTF-8');
        exit(json_encode($result));
    }

    /**
     * 根据传入的 access token，验证，并返回用户对象
     * @return type
     */
    private static function getUser() {
        // 获取access token，优先从 http header 中获取
        $access_token = filter_input(INPUT_SERVER, 'HTTP_X_ACCESS_TOKEN') ?? filter_input(INPUT_GET, 'access_token');

        if (empty($access_token)) {
            self::error(1008);
        }

        $user = \fly\JWT::decode($access_token);
        if (empty($user)) { // 如果不能正确解码、验证access_token，返回错误
            self::error(1005);
        }
               
        return $user;
    }

    private static function error($code, $msg = null) {
        // 输出JSON数据
        require_once 'fly/error.php';
        header('Content-type: application/json;charset=UTF-8');
        exit(json_encode(new namespace\Error($code, $msg)));
    }

    /**
     * 根据 action 的 getFilter() 取得需要过滤的传入参数，如果必要参数没有传入，输出错误
     * @param Action $action
     */
    private static function filterParams(namespace\Action &$action) {
        list($type, $required, $optional) = $action->getFilter();

        $params = NULL;
        // 过滤各必须传入的参数，如果存在空值，输出错误信息
        if (empty($required) === FALSE) {
            $required_params = filter_input_array($type, $required);

            if (empty($required_params)) {
                $keys = array_keys($required);
                self::error(1003, join(',', $keys));
            }
            // 各 action 可能存在必须传入的参数过滤器，如果存在，则判断过滤结果是否为空或NULL
            foreach ($required_params as $key => $p) {
                if (empty($p)) {
                    self::error(1003, $key);
                }
            }

            $params = $required_params;
        }

        // 过滤可选参数
        $optional_params = filter_input_array($type, $optional, FALSE);
        if (is_array($optional_params)) {
            if (empty($params)) {
                $params = $optional_params;
            } else {
                $params = array_merge($params, $optional_params);
            }
        }

        return $params;
    }

    static function error_handler($errno, $errstr, $errfile, $errline) {
        // 错误信息写入日志
        $err = new \stdClass();
        $err->number = $errno;
        $err->file = &$errfile;
        $err->line = $errline;
        $err->str = &$errstr;
        $err->get = &$_GET;
        $err->post = &$_POST;

        namespace\Log::error($err);

        // 输出 "1001	Service currently unavailable" 错误
        include_once 'fly/error.php';
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode(new namespace\Error(1001));
        exit();
    }

}
