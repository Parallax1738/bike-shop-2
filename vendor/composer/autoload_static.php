<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2ce6bd13222234cc5da3c5d173adbab7
{
    public static $prefixLengthsPsr4 = array (
        'j' => 
        array (
            'joshtronic\\' => 11,
        ),
        'c' => 
        array (
            'conedor\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'joshtronic\\' => 
        array (
            0 => __DIR__ . '/..' . '/joshtronic/php-loremipsum/src',
        ),
        'conedor\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/view',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2ce6bd13222234cc5da3c5d173adbab7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2ce6bd13222234cc5da3c5d173adbab7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2ce6bd13222234cc5da3c5d173adbab7::$classMap;

        }, null, ClassLoader::class);
    }
}
