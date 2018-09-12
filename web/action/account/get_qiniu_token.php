<?php
require_once 'ext/vendor/autoload.php';
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
use fly\Action;

class get_qiniu_token extends Action {
	public function getFilter() {
		$optional = array(
				'private' => FILTER_SANITIZE_STRING
		);
		return [INPUT_GET, null, $optional];
	}
	public function exec(array $params = null) {
		// 构建鉴权对象
		$auth = new Auth ( QINIU_ACCESS_KEY, QINIU_SECRET_KEY );
		// 如果传递了“private”标志,则上传到七牛私有空间
		if (isset ( $params['private'] )) {
			// 上传私有空间
			$bucket = QINIU_PRIVATE_BUCKET;
			// 上传策略
			$putPolicy = null;
		} else {
			// 上传公有空间
			$bucket = QINIU_PUBLIC_BUCKET;
			// 上传策略
			$putPolicy = array (
					"callbackBody" => "hash=$(etag)&mime=$(mimeType)&size=$(fsize)&type=$(mimeType)&key=$(key)"
			);
		}
		// 生成上传 Token
		$token = $auth->uploadToken ( $bucket, null, 300, $putPolicy, true );
		return [ 
				'token' => $token,
				'base_url' => QINIU_URL_HTTPS
		];
	}
	public function getPrivilege() {
		return 10;
	}
}