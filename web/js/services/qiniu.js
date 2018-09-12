define(['../modules'], function (services) {
    'use strict';

    var serviceObject = function ($resource, $state, $ionicHistory) {
        var upload = function (el, token, callback, progress) {

            if (!token || token === '') {
                console.log('七牛上传令牌无效');
                return;
            }

            if (typeof (el) === 'string') {
                el = window.angular.element(document.getElementById(el))[0];
            }

            if (el && el.files && el.files.length) {

                var fd = new FormData();
                fd.append('file', el.files[0]);
                fd.append('token', token);

                var xhr = new XMLHttpRequest();

                // 初始化进度条
                if (progress) {
                    progress.val(0);

                    var progressCallback = function (event) {
                        var loaded = event.loaded || event.loaded;
                        var total = event.total || event.total;
                        var per = Math.floor(loaded / total * 1000) / 10;
                        progress.val(per);
                    };

                    xhr.addEventListener('progress', progressCallback, false);
                    if (xhr.upload) {
                        xhr.upload.onprogress = progressCallback;
                    }
                }

                var readycallback = function () {
                    if (xhr.readyState === 4) {
                        window.angular.element(el).removeAttr('disabled');
                        if (xhr.status === 200) {
                            var json = JSON.parse(xhr.responseText);
                            if (json) {
                                if (json.hash) {
                                    callback(json.key, json);
                                    console.log('文件已成功上传');
                                } else {
                                	console.log('七牛服务上传失败,错误信息:' + json.error);
                                }
                            } else {
                            	console.log('七牛服务上传失败,错误信息已写入浏览器控制台');
                                console.log(xhr.responseText);
                            }
                        }
                    }
                };

                xhr.onreadystatechange = readycallback;

                xhr.open('post', '//up.qbox.me?', true);
                window.angular.element(el).attr('disabled', 'disabled');

                xhr.send(fd);

            } else {
                return;
            }
        };

        return {
            upload: upload
        };
    };

    services.factory('qiniuService', serviceObject);
});
