<?php

namespace UCSPZebraFS\Tool;

use Silex\Application;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UCSPZebraFS\Entity\File;
use UCSPZebraFS\Tool\FTPManager;
use UCSPZebraFS\Entity\Server;
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
        $servers = $this->app['orm.em']->getRepository("UCSPZebraFS\Entity\Server")->findAll();

        $manager = new FTPManager($servers[0]);
        if(count($similarFiles) >= 2)
        {
            echo "creando paridad\n";
            $parity = $this->createParity($raw->getRealPath(),
                $similarFiles[0]->getServer()->getUrl() . $similarFiles[0]->getNameStored() . ".bin",
                $similarFiles[1]->getServer()->getUrl() . $similarFiles[1]->getNameStored() . ".bin");

            $stored = $this->easyRandom();
            $manager->upload($parity, $stored);

            $parity = new File();
            $parity->setMimetype("parity");
            $parity->setSize(0);
            $parity->setNameStored($this->easyRandom());
            $parity->setStatus(0);
            $parity->setServer($servers[0]);
            $this->app['orm.em']->persist($parity);
        }

        $file->setMimetype($raw->getMimeType());
        $file->setSize($raw->getSize());
        $file->setNameStored($this->easyRandom());
        $file->setStatus(0);
        $file->setServer($servers[0]);

        $manager->upload($raw->getRealPath(), $file->getNameStored());

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

        $this->normalize($b1, $b2, $b3);

        $path = tempnam("/tmp", $this->easyRandom());;
        $gestor = fopen($path, "w+b");
        $parity = "";

        for($i=0; $i < count($b1); $i++)
        {
            $t = $b1[$i] + $b2[$i] + $b3[$i];
            $parity.= $t . "\n";
        }
        fwrite($gestor, $parity);
        fclose($gestor);

        return $path;
    }

    public function rebuild($file1, $file2, $file3)
    {
        $gestor = fopen($file1, "rb");
        $f1 = stream_get_contents($gestor);
        $gestor = fopen($file2, "rb");
        $f2 = stream_get_contents($gestor);

        $b1 = unpack('n*', $f1);
        $b2 = unpack('n*', $f2);
        $b3 = file($file3);

        $this->normalize($b1, $b2, $b3);

        $path = tmpfile();
        $gestor = fopen($path, "w+b");
        $parity = array();

        for($i=0; $i < count($b1); $i++)
        {
            $tmp = $b1[$i] + $b2[$i] + $b3[$i];
            $parity[] = intval($b3) - (intval($b1) + intval($b3));
        }

        $build = pack('n*', $parity);

        fwrite($gestor, $build);
        fclose($gestor);

        return $path;
    }

    public function normalize(&$b1, &$b2, &$b3)
    {
        $arr = array(); $arr[] = count($b1); $arr[] = count($b2); $arr[] = count($b3);
        $m = max($arr);

        $this->addZeros($b1, $m);
        $this->addZeros($b2, $m);
        $this->addZeros($b3, $m);
    }

    public function addZeros(&$arr, $n)
    {
        for($i=count($arr); $i < $n; $i++)
            $arr[] = 0;
    }
}