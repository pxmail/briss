<?php

/**
 * fly 框加 错误代码、错误消息、错误处理类
 *
 * -日期--------修改者----------- 内容 ----------------------
 * 2015-07-28  xie            first edition
 */
namespace fly;
class Error {

    public $error;

    function __construct(int $code, ...$submsg) {
        $errors = file_get_contents(FILE_ERROR_CODES);
        $msg = '';
        if($errors !== FALSE) {
            $matches = null;
            $pattern = '/' . $code . '[\t\s]+(.+)/';
            preg_match($pattern, $errors, $matches);
            unset($errors);
            if(count($matches) == 2) {
                $msg = $matches[1];
                if($submsg) {
                    $msg = sprintf($msg, ...$submsg);
                }
            }
        }
        
        $this->error = [
            'code' => $code,
            'msg' => $msg
        ];
    }

}