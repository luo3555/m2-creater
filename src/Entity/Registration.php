<?php
namespace App\Entity;

use App\Entity\EntityBase;
use App\Common\CreateFile;

class Registration extends EntityBase
{
    protected function _construct()
    {
        // create init file
        $this->createComposerJson();

        $this->createRegistration();

        $this->createModelXml();
    }

    public function createComposerJson()
    {
        $composer = [
            'name' => sprintf('%s/%s', strtolower($this->params['module']['vendor']), strtolower($this->params['module']['module'])),
            'description' => 'N/A',
            'type' => 'magento2-module',
            "license" => [
                "BSD-3-Clause"
            ],
            "require" => [
                // @todo add require config
                "php" => "~7"
            ],
            "autoload" => [
                "files" => [
                    "registration.php"
                ],
                "psr-4" => [
                    sprintf("%s\\%s\\", ucfirst($this->params['module']['vendor']), \sprintf($this->params['module']['module'])) => ""
                ]
            ]
        ];
        $content = \json_encode($composer, 192);
        $file = 'composer.json';
        $this->creater->create($file, $content);
    }

    public function createModelXml()
    {
        $content = $this->creater->renderFile('xml/module.twig', [
            'license' => $this->params['license'],
            'vendor' => $this->params['module']['vendor'],
            'module' => $this->params['module']['module'],
            'depends' => $this->params['module']['depends']
        ]);
        $file = 'etc/module.xml';
        $this->creater->create($file, $content);
    }

    public function createRegistration()
    {
        $content = $this->creater->renderFile('php/registration.twig', [
            'license' => $this->params['license'],
            'vendor' => $this->params['module']['vendor'],
            'module' => $this->params['module']['module']
        ]);
        $file = 'registration.php';
        $this->creater->create($file, $content);
    }
}