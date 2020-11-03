<?php
namespace App\Entity\Etc;

use App\Entity\EntityBase;

class Webapi extends EntityBase
{
    protected $xml = [];

    protected function _construct()
    {
        $this->createWebapi();
    }

    public function addRouter($route)
    {
        $route['service'] = sprintf('%s\%s\Api\%s', $this->getVendor(), $this->getModule(), $route['service']);
        $this->xml[] = $this->fetch('xml/webapi/route.twig', $route);
        return $this;
    }

    public function createWebapi()
    {
        $webapi = $this->params['etc']['webapi'];

        foreach ($webapi as $route) {
            $this->addRouter($route);
        }

        $file = 'etc/webapi.xml';
        $content = $this->fetch('xml/webapi.twig', ['content' => implode(PHP_EOL, $this->xml)]);
        $this->creater->create($file, $content);
    }
}