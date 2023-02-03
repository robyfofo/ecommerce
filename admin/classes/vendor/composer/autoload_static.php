<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit938fcc39ae9527c6d61cfc21ca5c59bf
{
    public static $files = array (
        'e69f7f6ee287b969198c3c9d6777bd38' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/bootstrap.php',
        '25072dd6e2470089de65ae7bf11d3109' => __DIR__ . '/..' . '/symfony/polyfill-php72/bootstrap.php',
        'f598d06aa772fa33d905e87be6398fb1' => __DIR__ . '/..' . '/symfony/polyfill-intl-idn/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        'def43f6c87e4f8dfd0c9e1b1bab14fe8' => __DIR__ . '/..' . '/symfony/polyfill-iconv/bootstrap.php',
        'dfc9e5dd545737efbb98020db79bfa08' => __DIR__ . '/..' . '/mos/cimage/defines.php',
        '507fe79d3e285fab95fad400b8d42245' => __DIR__ . '/..' . '/mos/cimage/functions.php',
        '2c102faa651ef8ea5874edb585946bce' => __DIR__ . '/..' . '/swiftmailer/swiftmailer/lib/swift_required.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twig\\' => 5,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Php72\\' => 23,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Intl\\Normalizer\\' => 33,
            'Symfony\\Polyfill\\Intl\\Idn\\' => 26,
            'Symfony\\Polyfill\\Iconv\\' => 23,
            'Symfony\\Polyfill\\Ctype\\' => 23,
        ),
        'R' => 
        array (
            'ReCaptcha\\' => 10,
        ),
        'H' => 
        array (
            'Html2Text\\' => 10,
        ),
        'E' => 
        array (
            'Egulias\\EmailValidator\\' => 23,
        ),
        'D' => 
        array (
            'Doctrine\\Common\\Lexer\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
        'Symfony\\Polyfill\\Php72\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php72',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Intl\\Normalizer\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer',
        ),
        'Symfony\\Polyfill\\Intl\\Idn\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-intl-idn',
        ),
        'Symfony\\Polyfill\\Iconv\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-iconv',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'ReCaptcha\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/recaptcha/src/ReCaptcha',
        ),
        'Html2Text\\' => 
        array (
            0 => __DIR__ . '/..' . '/soundasleep/html2text/src',
        ),
        'Egulias\\EmailValidator\\' => 
        array (
            0 => __DIR__ . '/..' . '/egulias/email-validator/src',
        ),
        'Doctrine\\Common\\Lexer\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/lexer/lib/Doctrine/Common/Lexer',
        ),
    );

    public static $classMap = array (
        'Applications' => __DIR__ . '/../..' . '/class.Applications.php',
        'CAsciiArt' => __DIR__ . '/..' . '/mos/cimage/CAsciiArt.php',
        'CCache' => __DIR__ . '/..' . '/mos/cimage/CCache.php',
        'CFastTrackCache' => __DIR__ . '/..' . '/mos/cimage/CFastTrackCache.php',
        'CHttpGet' => __DIR__ . '/..' . '/mos/cimage/CHttpGet.php',
        'CImage' => __DIR__ . '/..' . '/mos/cimage/CImage.php',
        'CRemoteImage' => __DIR__ . '/..' . '/mos/cimage/CRemoteImage.php',
        'CWhitelist' => __DIR__ . '/..' . '/mos/cimage/CWhitelist.php',
        'Carts' => __DIR__ . '/../..' . '/class.Carts.php',
        'Config' => __DIR__ . '/../..' . '/class.Config.php',
        'Core' => __DIR__ . '/../..' . '/class.Core.php',
        'DateFormat' => __DIR__ . '/../..' . '/class.DateFormat.php',
        'Form' => __DIR__ . '/../..' . '/class.Form.php',
        'Mails' => __DIR__ . '/../..' . '/class.Mails.php',
        'Menu' => __DIR__ . '/../..' . '/class.Menu.php',
        'Multilanguage' => __DIR__ . '/../..' . '/class.Multilanguage.php',
        'Normalizer' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/Resources/stubs/Normalizer.php',
        'Orders' => __DIR__ . '/../..' . '/class.Orders.php',
        'Pages' => __DIR__ . '/../..' . '/class.Pages.php',
        'Permissions' => __DIR__ . '/../..' . '/class.Permissions.php',
        'Products' => __DIR__ . '/../..' . '/class.Products.php',
        'SanitizeStrings' => __DIR__ . '/../..' . '/class.SanitizeStrings.php',
        'Sql' => __DIR__ . '/../..' . '/class.Sql.php',
        'Subcategories' => __DIR__ . '/../..' . '/class.Subcategories.php',
        'ToolsStrings' => __DIR__ . '/../..' . '/class.ToolsStrings.php',
        'ToolsUpload' => __DIR__ . '/../..' . '/class.ToolsUpload.php',
        'Users' => __DIR__ . '/../..' . '/class.Users.php',
        'Utilities' => __DIR__ . '/../..' . '/class.Utilities.php',
        'Wishes' => __DIR__ . '/../..' . '/class.Wishes.php',
        'htmLawed' => __DIR__ . '/../..' . '/class.htmLawed.php',
        'my_session' => __DIR__ . '/../..' . '/class.Sessions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit938fcc39ae9527c6d61cfc21ca5c59bf::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit938fcc39ae9527c6d61cfc21ca5c59bf::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit938fcc39ae9527c6d61cfc21ca5c59bf::$classMap;

        }, null, ClassLoader::class);
    }
}