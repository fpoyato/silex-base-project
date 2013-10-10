<?php

use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Provider\AsseticServiceProvider;

// include the prod configuration
require __DIR__.'/prod.php';

// enable the debug mode
$app['debug'] = true;

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/silex_dev.log',
));

$app->register($p = new WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => __DIR__.'/../cache/profiler',
));
$app->mount('/_profiler', $p);

$app->register(
    new AsseticServiceProvider(),
    array(
        'file_assets' => array(
            'html5shiv' => array(
                'asset_path' => __DIR__ . '/../vendor/twitter/bootstrap/assets/js/html5shiv.js',
                'type' => 'js',
                'target' => 'html5shiv.js'
            ),
            'respond' => array(
                'asset_path' => __DIR__ . '/../vendor/twitter/bootstrap/assets/js/respond.min.js',
                'type' => 'js',
                'target' => 'respond.js'
            ),
            'jquery' => array(
                'asset_path' => __DIR__ . '/../vendor/twitter/bootstrap/assets/js/jquery.js',
                'type' => 'js',
                'target' => 'jquery.js'
            ),
            'bootstrap_js' => array(
                'asset_path' => __DIR__ . '/../vendor/twitter/bootstrap/dist/js/bootstrap.js',
                'type' => 'js',
                'target' => 'bootstrap.js'
            ),
            'bootstrap_css' => array(
                'asset_path' => __DIR__ . '/../assets/less/bootstrap.less',
                'filter' => 'lessphp',
                'type' => 'css',
                'target' => 'bootstrap.css'
            )
        )
    )
);

$app['assetic.write_assets'];
