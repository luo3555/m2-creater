<?php
namespace App\Entity\Etc;

use App\Entity\EntityBase;

class Router extends EntityBase
{
    protected function _construct()
    {
        $this->createRouter();
    }

    public function createRouter()
    {
        $routers = $this->params['etc']['router'];

        foreach ($routers as $area => $_routes) {
            if (!empty($_routes)) {
                switch ($area) {
                    case self::AREA_FRONTEND:
                        $this->addDataToRouters($_routes,self::AREA_FRONTEND);
                    break;
                    case self::AREA_ADMINHTML:
                        $this->addDataToRouters($_routes,self::AREA_ADMINHTML);
                    break;
                    case self::AREA_GLOBAL:
                        $this->addDataToRouters($_routes, self::AREA_GLOBAL);
                    break;
                }
            }
        }

        foreach ($this->xml as $area => $nodes) {
            $content = $this->fetch('xml/routers.twig', ['license' => $this->license, 'area' => $this->getRouterIdByArea($area),'content' => implode(PHP_EOL, $nodes)]);
            $this->creater->create($this->getFilePathByArea($area), $content);
        }
    }

    protected function addDataToRouters($routers, $area='')
    {
        if (!isset($this->xml[$area])) {
            $this->xml[$area] = [];
        }

        foreach ($routers as $route) {
            $this->xml[$area][] = $this->fetch('xml/router/route.twig', $route);
        }
    }

    protected function getRouterIdByArea($area)
    {
        switch ($area) {
            case self::AREA_FRONTEND:
                return 'standard';
            break;
            case self::AREA_ADMINHTML:
                return 'admin';
            break;
            case self::AREA_GLOBAL:
                return 'standard';
            break;
        }
    }

    protected function getFilePathByArea($area)
    {
        switch ($area) {
            case self::AREA_FRONTEND:
                return 'etc/frontend/routers.xml';
            break;
            case self::AREA_ADMINHTML:
                return 'etc/adminhtml/routers.xml';
            break;
            case self::AREA_GLOBAL:
                return 'etc/routers.xml';
            break;
        }
    }
}