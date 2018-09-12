<?php

/**
 * X Frame Action抽象类，定义API类的基本方法
 *
 * -日期--------修改者----------- 内容 ----------------------
 * 2015-07-28  xie            first edition
 */
namespace fly;
abstract class Action {
    /**
     * 当前操作的 user 对象, 包括用户ID, 权限, token 过期时间
     * @var Object 
     */
    public $user;

    /**
     * 获取参数过滤器数组
     * 此函数返回的数组必须包括
     * $type 过滤类型，PHP定义的过滤类型 INPUT_GET, INPUT_POST, INPUT_SERVER ...之一
     *   此类型用作 filter_input_array()函数的第一个参数
     * 
     * $required_filter_array 必传参数过滤器，此过滤器用于 filter_input_array 的第二个参数
     *   并且，如果完成正常的过滤，框架还会检查各参数是否为NULL / FALSE，如果是就输出 1003错误
     *   如果没有必传参数，可以为空
     *   <b>注意：</b> 任何布尔型的必传参数，如果是 FALSE 都会输出1003错误，如果需要传此类型
     *   的参数，请用0/1代替
     * 
     * $optional_filter_array 可选参数过滤器，此过滤器用于 filter_input_array 的第二个参数
     *   如果没有必传参数，可以为空
     */
    abstract public function getFilter();
    
    /**
     * 返回此Action需要的权限，具体参见 Privilege 类
     * 
     * 如果Action执行不需要验证 AccessToken，实现此函数时需要留空，例如：
     * public function getPrivilege() {}
     * 这样的Action通常是一些可以公开访问的API，比如 login, register 
     * 
     * 如果网站没有设置权限，需要返回一个默认权限：
     * public function getPrivilege() {
     *      return Privilege::DEFAULT_PRIVILEGE;
     * }
     * 
     */
    abstract public function getPrivilege();

    /**
     * 执行API业务
     */
    abstract public function exec(array $params = null);
}
