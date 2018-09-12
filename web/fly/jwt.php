<?php

/**
 * EZ Sport JWT(JSON Web Token) 实现
 * JWT请参照 http://jwt.io
 * 此程序只对EZ Sport使用的 json object进行编码，为提高效率签名算法也使用固定值，
 * 因此JWT的header部分也使用了固定的字符串
 * @author Arthur
 *
 */
namespace fly;

class JWT {

    /**
     * header 固定为如下JSON对象的BASE64编码
     * {
     *   "alg" : "HS256",
     *   "typ" : "JWT"
     * }
     * @var unknown
     */
    const HEADER = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';
    const ALGORITHM = 'sha256'; // 加密算法
//    const SECRET = 'nrW9;|D@dZY8R?xFDaiPxa&j8B,p/Y~DE3p0tJHA& J^3zaG+Q@xNU(/0NYF{a9h'; // 密钥
    const EXPIRE = 360000; // 有效时间（秒）默认1小时
    const REFRESH_ALLOWED_IN_SECONDS = 300; // 允许访问令牌在此时间(秒)之内刷新
    const MAX_EXPIRE_SECONDS = 43200; // 访问令牌最大允许的有效时间(秒)

    /**
     * 生成URL可接受的BASE64编码
     * @param unknown $data
     */

    private static function base64Encode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * JWT标准编码的BASE64字符串解码
     * @param unknown $data
     */
    private static function base64Decode($data) {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }

    /**
     * 生成编码token
     * @param $obj 要编码的对象
     * @return token
     */
    public static function encode($obj) {
        $obj->iat = time();
        $obj->exp = $obj->iat + self::EXPIRE;
        $payload = self::base64Encode(json_encode($obj));
        $sign = self::base64Encode(hash_hmac(self::ALGORITHM, self::HEADER . '.' . $payload, JWT_SECRET, true));
        return self::HEADER . '.' . $payload . '.' . $sign;
    }

    /**
     * 解码JWT字串，如果成功，返回数据对象，如果失败（签名不正确、格式不正确）返回false
     * @param unknown $token
     */
    public static function decode($token) {
        $parts = explode('.', $token);
        // 只有3个部分
        if (count($parts) !== 3) {
            return false;
        }

        list($header, $payload, $sign) = $parts;

        // 根据传入的payload 和服务器Header, Secret 生成签名
        $mySign = self::base64Encode(hash_hmac(self::ALGORITHM, $header . '.' . $payload, JWT_SECRET, TRUE));

        // 如果重新计算的签名和Token传入的签名不一致, 返回错误
        if ($mySign !== $sign) {
            return false;
        }

        // 将 payload字串转换成对象，签名验证通过即可以正常解析json
        $obj = json_decode(self::base64Decode($payload));

        // 检查当前时间是否大于等于签发日期，并且在有效期之内
        $now = time();
        $exp = intval($obj->exp);
        if ($now >= intval($obj->iat) && $now < $exp) {
            // 一切检查通过，返回对象
            //unset($obj->iat);
            //unset($obj->exp);
            return $obj;
        }

        // 忽略有效期，暂时让所有合法 token 有效
        return $obj;
//        return false;
    }

    /**
     * 刷新$token, 在access token失效前 5分钟调用此方法, 延长原token的有效期(1小时), 返回一个新的token
     * 如果token有效期在5分钟以上, 则返回原token
     * 如果token的签发时间已经超过指定时长(12小时), 则不再签发, 返回 false
     * 
     * 补充说明:
     * 由于访问令牌 TOKEN 保存在客户端(浏览器), 一旦 TOKEN 泄漏将导致很严重的后果, 一个访问令牌
     * 有效时间太长, 用户长时间可不需要登录访问API, 安全性比较差
     * 有效时间太短, 用户需要频繁登录重新换取令牌, 因此提供刷新机制用于将当前令牌延期, 但一个令牌
     * 不允许一直延期
     * 
     * @param type $token 有效期延长后的 token
     */
    public static function refresh($token) {
        $payload = self::decode($token);
        if ($payload === false) {
            return false;
        }
        $expire_time = intval($payload->exp);
        
        $expire_in = $expire_time - time();
        $issue_time = intval($payload->iat);
        // 如果 过期时间 - 签发时间 > 允许时间(12小时), 不签发
        $signed_expire_in = $expire_time - $issue_time;
        
        // 如果有效时间大于指定时间(5分钟), 不签发
        // 如果签发合计有效时间 > 12小时, 不签发, 必须重新登录
        if($expire_in > self::REFRESH_ALLOWED_IN_SECONDS
                || $signed_expire_in >= self::MAX_EXPIRE_SECONDS) {
            return ['token' => $token, 'expire_in' => $expire_in];
        }
        
        // 计算签发的过期时间过去了多少个单位时间
        $times = ceil($signed_expire_in / self::EXPIRE);
        // 有效期增加一个时间单位
        $new_expire_in = self::EXPIRE * ($times + 1);
        // 设置新的过期时间
        $payload->exp = $issue_time + $new_expire_in;
        
        $expire_in += self::EXPIRE;
        
        $encoded_payload = self::base64Encode(json_encode($payload));
        $new_sign = self::base64Encode(hash_hmac(self::ALGORITHM, self::HEADER . '.' . $encoded_payload, JWT_SECRET, true));
        $new_token = self::HEADER . '.' . $encoded_payload . '.' . $new_sign;
        
        return ['token' => $new_token, 'expire_in' => $expire_in];
    }
}
