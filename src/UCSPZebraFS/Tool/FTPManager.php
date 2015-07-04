<?php

namespace UCSPZebraFS\Tool;
use UCSPZebraFS\Entity\Server;

class FTPManager
{
    private $host;
    private $user;
    private $password;
    private $path;

    public function __construct(Server $server)
    {
        $this->host = $server->getHostname();
        $this->user = $server->getUser();
        $this->password = $server->getPassword();
        $this->path = "/";
    }

    public function upload($file, &$url)
    {
        $conn_id = ftp_connect($this->host);

        $login_result = ftp_login($conn_id, $this->user, $this->password);

        $result = (ftp_put($conn_id, $url . '.bin', $file, FTP_BINARY)) ? true: false;

        ftp_close($conn_id);

        return $result;
    }

    public function exist($file)
    {
        $conn_id = ftp_connect($this->host);

        $login_result = ftp_login($conn_id, $this->user, $this->password);

        $result = (ftp_size($conn_id, $file . '.bin') != -1) ? true : false;

        ftp_close($conn_id);

        return $result;
    }

    public function getURL($file)
    {
        return ($this->exist($file)) ? $this->path . $file . '.bin' : false;
    }

    public function easyRandom()
    {
        $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $string = "";
        for($i=0;$i<32;$i++)
            $string .= $alphabet[rand(0,strlen($alphabet)-1)];

        return $string;
    }
}