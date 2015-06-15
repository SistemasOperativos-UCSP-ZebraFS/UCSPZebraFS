<?php

namespace UCSPZebraFS\Controller;

use Silex;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UCSPZebraFS\Tool\FileManager;

class FileController implements ControllerProviderInterface
{
    public function connect(Silex\Application $app)
    {
        /** @var Silex\ControllerCollection $cont */
        $cont = $app['controllers_factory'];

        $cont->match("/", array($this, 'createFile'))
            ->bind('create_file')
            ->method('POST');
        $cont->match("/{id}", array($this, 'viewFile'))
            ->bind('view_file')
            ->method('GET');

        return $cont;
    }

    public function viewFile(Silex\Application $app)
    {
        $file = array();

        return new Response(json_encode($file), 200, array(
            'Content-Type' => 'application/json'
        ));
    }

    public function createFile(Silex\Application $app)
    {
        /** @var Request $raw */
        $raw = $app['request']->files->get('file');

        $fm = new FileManager($app);
        $fm->create($raw);

        $app->redirect('/file/', 201);
    }
}