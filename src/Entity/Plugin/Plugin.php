<?php
namespace App\Entity\Plugin;

use App\Entity\EntityBase;
// https://doc.nette.org/en/3.0/php-generator
use Nette\PhpGenerator\PhpFile;
// use Nette\PhpGenerator\PhpNamespace;

class Plugin extends EntityBase
{
    protected function _construct()
    {
        $this->createPlugin();
    }

    public function getDocUrl()
    {
        return 'https://devdocs.magento.com/guides/v2.4/extension-dev-guide/plugins.html';
    }

    public function createPlugin()
    {
        if (isset($this->params['etc']['di'])) {
            if (is_array($this->params['etc']['di'])) {
                foreach ($this->params['etc']['di'] as $area => $config) {
                    if (isset($config['plugin'])) {
                        foreach ($config['plugin'] as $plugin) {
                            $plugin['class'] = sprintf('%s\%s\%s', $this->getVendor(), $this->getModule(), $plugin['class']);
                            $className = $plugin['class'];
                            $classNameArr = explode('\\', $className);
                            $fileName = array_pop($classNameArr);
                            $namespace = implode('\\', $classNameArr);

                            $content = $content = $this->fetch('php/Plugin/plugin.twig', [
                                'license' => $this->license,
                                'namespace' => $namespace,
                                'action_name' => $fileName
                            ]);
    
                            $file = str_replace('\\', '/', $plugin['class']) . '.php';
                            $this->creater->create($file, $content);
                        }
                    }
                }
            }
        }
    }
}