<?php

namespace UCSPZebraFS\Tool;

use Silex\Application;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UCSPZebraFS\Entity\File;
use Doctrine\Common\Util\Debug;
class FileManager
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function create(UploadedFile $raw)
    {
        $file = new File();

        $similarFiles = $this->app['orm.em']->getRepository("UCSPZebraFS\Entity\File")->getSimilarSizFiles($raw->getSize());

        if($similarFiles > 2)
        {
            die("Existen archivos que pueden ser usados");
        }

        $file->setMimetype($raw->getMimeType());
        $file->setSize($raw->getSize());
        $file->setNameStored($this->easyRandom());
        $file->setStatus(0);

        $this->app['orm.em']->persist($file);
        $this->app['orm.em']->flush();
        die("subido");
    }

    public function easyRandom()
    {
        $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $string = "";
        for($i=0;$i<32;$i++)
            $string .= $alphabet[rand(0,strlen($alphabet)-1)];

        return $string;
    }

    public function createParity($file1, $file2, $file3)
    {
        $gestor = fopen($file1, "rb");
        $f1 = stream_get_contents($gestor);
        $gestor = fopen($file2, "rb");
        $f2 = stream_get_contents($gestor);
        $gestor = fopen($file3, "rb");
        $f3 = stream_get_contents($gestor);

        $b1 = unpack('n*', $f1);
        $b2 = unpack('n*', $f2);
        $b3 = unpack('n*', $f3);

        fclose($gestor);
    }
}