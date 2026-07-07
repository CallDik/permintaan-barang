<?php namespace Config;

use CodeIgniter\Config\BaseConfig;
use App\Filters\AuthAdmin;
use App\Filters\AuthKaryawan;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'          => \CodeIgniter\Filters\CSRF::class,
        'toolbar'       => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot'      => \CodeIgniter\Filters\Honeypot::class,
        'invalidchars'  => \CodeIgniter\Filters\InvalidChars::class,
        'secureheaders' => \CodeIgniter\Filters\SecureHeaders::class,
        'authAdmin'     => AuthAdmin::class,
        'authKaryawan'  => AuthKaryawan::class,
    ];

    public array $required = [
        'before' => [],
        'after'  => ['toolbar'],
    ];

    public array $globals = [
        'before' => [],
        'after'  => [],
    ];

    public array $methods = [];
    public array $filters = [];
}
