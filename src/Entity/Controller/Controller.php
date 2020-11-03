<?php
namespace App\Entity\Controller;

use App\Entity\EntityBase;

class Controller extends EntityBase
{
    const FRONTEND_TPL = 'frontend.twig';

    const ADMINTHMl_TPL = 'adminhtml.twig';

    protected $controller = [];

    protected function _construct()
    {
        $this->createController();
    }

    public function createController()
    {
        $controller = $this->params['etc']['router'];

        // [
        //     'id' => 'route_id',
        //     'front_name' => 'front_name',
        //     'module' => 'Cloyi_Creater',
        //     'action' => 'Post/Index'
        // ],

        foreach ($controller as $area => $routes) {

            if (!empty($routes)) {
                foreach ($routes as $route) {
                    switch ($area) {
                        case \App\Entity\Etc\Router::AREA_FRONTEND:
                            $this->frontend($route);
                        break;
                        case \App\Entity\Etc\Router::AREA_ADMINHTML:
                            $this->adminhtml($route);
                        break;
                        default:
                            $this->frontend($route);
                        break;
                    }
                }
            }
        }
    }

    protected function frontend($route)
    {
        if (!isset($route['action'])) {
            $action = 'Index/Index';
        } else {
            $action = str_replace('\\', '/', $route['action']);
        }

        // create class name
        $className = sprintf('%s/%s/Controller/%s', $this->params['module']['vendor'], $this->params['module']['module'], $action);

        // get right name space
        $nameArr = explode('/', $className);
        $action = array_pop($nameArr);
        $nameSpace = implode('\\', $nameArr);

        // create class content
        $tpl = self::FRONTEND_TPL;
        $content = $this->fetch(sprintf('php/Controller/%s', $tpl), ['license' => $this->license, 'name_space' => $nameSpace, 'action_name' => $action]);

        // create class file
        $file = $nameSpace . '/' . $action . '.php';
        $this->createFile($file, $content);
    }

    protected function adminhtml($route)
    {
        if (!isset($route['action'])) {
            $action = 'Index/Index';
        } else {
            $action = str_replace('\\', '/', $route['action']);
        }

        if (isset($route['resource'])) {
            $resource = $route['resource'];
        } else {
            $resource = 'Magento_Backend::admin';
        }

        // create class name
        $className = sprintf('%s/%s/Controller/Adminhtml/%s', $this->params['module']['vendor'], $this->params['module']['module'], $action);

        // get right name space
        $nameArr = explode('/', $className);
        $action = array_pop($nameArr);
        $nameSpace = implode('\\', $nameArr);

        $tpl = self::ADMINTHMl_TPL;
        $content = $this->fetch(sprintf('php/Controller/%s', $tpl), [
            'license' => $this->license, 
            'name_space' => $nameSpace, 
            'action_name' => $action,
            'resource' => $resource
        ]);

        // create class file
        $file = $nameSpace . '/' . $action . '.php';
        $this->createFile($file, $content);
    }

    protected function createFile($file, $content)
    {
        $file = str_replace('\\', '/', $file);
        $this->creater->create($file, $content);
    }
}