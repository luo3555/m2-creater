<?php
namespace App\Entity\Etc;

use App\Entity\EntityBase;

class Di extends EntityBase
{
    protected $xml = [];

    protected function _construct()
    {
        $this->createDi();
    }

    /**
     * @param array $preference ['for' => 'for sth', 'type' => 'class']
     */
    public function addPreference($preference, $area='')
    {
        $preference['type'] = sprintf('%s\%s\Model\%s', $this->getVendor(), $this->getModule(), $preference['type']);
        $content = $this->fetch('xml/di/preference.twig', $preference);
        return $this->addDataToDi($content, $area);
    }

    /**
     * @param array $plugin [
     *                  'observed' => 'Magento\Framework\Mview\View\StateInterface', // observed what interface or class
     *                  'class' => 'Cloyi\Creater\Plugin\Test',
     *                  'name' => 'Plugin_Name',
     *                  'sort' => 100, // plugin run sort order
     *                  'disabled' => false // plugin disabled
     *               ]
     */
    public function addPlugin($plugin, $area='')
    {
        $plugin['class'] = sprintf('%s\%s\Plugin\%s', $this->getVendor(), $this->getModule(), $plugin['class']);
        $content = $this->fetch('xml/di/plugin.twig', $plugin);
        return $this->addDataToDi($content, $area);
    }

    public function createDi()
    {
        $di = $this->params['etc']['di'];

        // create data struct 
        foreach ($di as $area => $items) {
            foreach ($items as $type => $nodes) {
                foreach ($nodes as $node) {
                    switch ($type) {
                        case 'plugin':
                            $this->addPlugin($node, $area);
                        break;
                        case 'preference':
                            $this->addPreference($node, $area);
                        break;
                        default:
                        break;
                    }
                }
            }
        }
        
        foreach ($this->xml as $area => $nodes) {
            $area = $area=='global' ? '' : $area ;
            $area = !empty($area) ? $area . '/' : $area ;
            $file = \sprintf('etc/%sdi.xml', $area);
            $content = $this->fetch('xml/di.twig', ['license' => $this->license,'content' => implode(PHP_EOL, $nodes)]);
            $this->creater->create($file, $content);
        }
    }

    public function addDataToDi($content, $area='')
    {
        if ($area) {
            if (!isset($this->xml[$area])) {
                $this->xml[$area] = [];
            }
            $this->xml[$area][] = $content;
        }
        return $this;
    }
}