<?php

use Silex\Application;
use Knp\Provider\ConsoleServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use UCSPZebraFS\Tool\FileManager;
use UCSPZebraFS\Controller\FileController;

require_once __DIR__.'/vendor/autoload.php';

$app = new Application();

$app->register(new ConsoleServiceProvider(), array(
    'console.name'              => 'UCSPZebraFS',
    'console.version'           => '1.0.0',
    'console.project_directory' => __DIR__.'/..'
));

$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/config/config.yml'));

$app->register(new DoctrineServiceProvider, array(
    "db.options" => $app['config']['database']
));

$app->register(new DoctrineOrmServiceProvider, array(
    "orm.proxies_dir" => "cache/proxies",
    "orm.em.options" => array(
        "mappings" => array(
            array(
                "type" => 'annotation',
                "namespace" => 'UCSPZebraFS\Entity',
                "use_simple_annotation_reader" => false,
                "path" => __DIR__.'/src'
            ),
        ),
    ),
));

$app->mount("/file", new FileController());

return $app;