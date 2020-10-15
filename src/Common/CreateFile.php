<?php
namespace App\Common;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class CreateFile
{
    protected $fileSystem;

    protected $rootDir;

    protected $module;

    protected $saveDir;

    protected $tplDir;

    public function __construct($params)
    {
        $module = $params['module'];
        $this->rootDir = dirname(dirname(dirname(__FILE__))) . '/';
        $this->saveDir = $this->rootDir . 'var/cache/magento2/';
        $this->module = ucfirst($module['vendor']) . '/' . ucfirst($module['module']) . '/';
        $this->tplDir = $this->rootDir . 'templates/m2tpl/';
        $this->fileSystem = new Filesystem();
    }

    public function create($filePath, $content)
    {
        // remove vendor and model
        $filePath = \explode($this->module, $filePath);
        $filePath = array_pop($filePath);

        // get file path
        $file = $this->saveDir . $this->module . $filePath;

        // get file dir path
        $fileDir = dirname($file);

        try {
            // create save dir
            $this->checkAndCreateDir($this->saveDir);

            // create file dir
            $this->checkAndCreateDir($fileDir);

            // create file
            $this->checkoutAndCreateFile($file, $content);

        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
            die();
        }
    }

    public function checkAndCreateDir($dirPath)
    {
        if (!$this->fileSystem->exists($dirPath)) {
            $this->fileSystem->mkdir($dirPath);
        }
        return $this;
    }

    public function checkoutAndCreateFile($file, $content)
    {
        if (!$this->fileSystem->exists($file)) {
            $this->fileSystem->dumpFile($file, $content);
        }
        return $this;
    }

    public function renderFile($path, $params=[])
    {
        $loader = new FilesystemLoader($this->tplDir);
        $twig = new Environment($loader);
        return $twig->render($path, $params);
    }
}