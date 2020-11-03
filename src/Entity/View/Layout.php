<?php
namespace App\Entity\View;

use App\Entity\EntityBase;

class Layout extends EntityBase
{
    const CONTENT_BLOCK = 'block';

    const CONTENT_GRID = 'grid';

    const CONTENT_FROM = 'from';

    protected function _construct()
    {
        $this->createLayoutHandel();
    }

    public function createLayoutHandel()
    {
        $view = $this->params['view'];

        if (is_array($view)) {
            // loop 
            foreach ($view as $area => $config) {
                if (isset($config['layout'])) {
                    foreach ($config['layout'] as $layout) {
                        switch ($area) {
                            case self::AREA_GLOBAL:
                                $this->frontend($layout);
                            break;
                            case self::AREA_FRONTEND:
                                $this->frontend($layout);
                            break;
                            case self::AREA_ADMINHTML:
                                $this->adminhtml($layout);
                            break;
                        }
                    }
                }
            }
        }
    }

    protected function frontend($layout)
    {
        $handelName = $this->createHandelName($layout);
        $content = $this->fetch('xml/layout/page.twig', [
            'license' => $this->license, 
            'module_name' => $this->getModuleName(),
            'ui_component' => false
        ]);
        $file = 'view/frontend/' . $this->createHandelName($layout) . '.xml';
        $this->creater->create($file, $content);
    }

    protected function adminhtml($layout)
    {
        $handelName = $this->createHandelName($layout);
        $content = $this->fetch('xml/layout/page.twig', [
            'license' => $this->license, 
            'module_name' => $this->getModuleName(),
            'ui_component' => $this->useUiComponent($layout),
            'ui_component_handle' => $this->getUiComponentLayout($layout) ,
        ]);
        $file = 'view/adminhtml/' . $this->createHandelName($layout) . '.xml';
        $this->creater->create($file, $content);
    }

    protected function createHandelName($layout)
    {
        $actionArr = explode('\\', $layout['action']);

        // format to right handel name style
        array_walk($actionArr, function($value, $key) use (&$actionArr) {
            $actionArr[$key] = lcfirst($value);
        });

        $action = implode('_', $actionArr);
        return sprintf('%s_%s', $layout['id'], $action);
    }

    protected function useUiComponent($layout)
    {
        return in_array(strtolower($layout['content']), [self::CONTENT_BLOCK, self::CONTENT_GRID, self::CONTENT_FROM]);
    }

    protected function getUiComponentLayout($layout)
    {
        if ($this->useUiComponent($layout)) {
            return $this->createHandelName($layout) . '_' . strtolower($layout['content']);
        }

        return '';
    }
}