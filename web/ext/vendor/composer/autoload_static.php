<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit234b0ed90b5a174e28ce4b531021498d
{
    public static $files = array (
        '841780ea2e1d6545ea3a253239d59c05' => __DIR__ . '/..' . '/qiniu/php-sdk/src/Qiniu/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Q' => 
        array (
            'Qiniu\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Qiniu\\' => 
        array (
            0 => __DIR__ . '/..' . '/qiniu/php-sdk/src/Qiniu',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PHPExcel' => 
            array (
                0 => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit234b0ed90b5a174e28ce4b531021498d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit234b0ed90b5a174e28ce4b531021498d::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit234b0ed90b5a174e28ce4b531021498d::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
