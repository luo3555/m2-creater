<?php
namespace App\Entity\Api;

use App\Entity\EntityBase;
// https://doc.nette.org/en/3.0/php-generator
use Nette\PhpGenerator\PhpNamespace;

class ResetInterface extends EntityBase
{
    protected function _construct()
    {
        $this->createInterface();
    }

    public function createInterface()
    {
        $webapi = $this->params['webapi_reset'];

        foreach ($webapi as $api) {
            // add namespace ext by interface name
            $_namespace = explode('\\', $api['interface']);
            $interfaceName = array_pop($_namespace);

            $namespace = new PhpNamespace(implode('\\', $_namespace));
            $interface = $namespace->addInterface($interfaceName);

            // create interface relate mode
            // @todo
            $model = new PhpNamespace(\str_replace('\\Api', '\\Model', implode('\\', $_namespace)));
            $class = $model->addClass(\str_replace('Interface', '', $interfaceName));
            $class->addExtend('\Magento\Framework\DataObject')
                    ->addImplement($api['interface']);

            if (isset($api['property'])) {
                if (!empty($api['property'])) {
                    foreach ($api['property'] as $param) {
                        $interface->addConstant(\strtoupper($param['name']), \strtolower($param['name']));
    
                        $method = $interface->addMethod('get' . \ucfirst($param['name']));
                        $method->addComment('Get ' . $param['name'])->addComment('@return ' . $param['return'])->setPublic();
    
                        $method = $interface->addMethod('set' . \ucfirst($param['name']));
                        $method->addComment('Set ' . $param['name'])->addComment('@return $this')->setPublic()
                                ->addParameter($param['name'])->setType($param['type']);

                        // model
                        $modelMethod = $class->addMethod('get' . \ucfirst($param['name']));
                        $modelMethod->addComment('Get ' . $param['name'])->addComment('@return ' . $param['return'])->setPublic();
                        $modelMethod->setBody(\sprintf('return $this->getData(self::%s);', \strtoupper($param['name'])));

                        $modelMethod = $class->addMethod('set' . \ucfirst($param['name']));
                        $modelMethod->addComment('Set ' . $param['name'])->addComment('@return $this')->setPublic()
                                    ->addParameter($param['name'])->setType($param['type']);
                        $modelMethod->setBody(\sprintf('return $this->setData(self::%s, %s);', \strtoupper($param['name']), $param['name']));
    
                    }
                }
            }

            if (isset($api['functions'])) {
                if (!empty($api['functions'])) {
                    foreach ($api['functions'] as $func) {
                        $method = $interface->addMethod($func['name'])->setPublic();
                        $method->addComment($func['name']);
                        foreach ($func['arguments'] as $argument) {
                            $method->addComment('@param ' . $argument['type'] . ' $' . $argument['name']);
                            $method->addParameter($argument['name'])->setType($argument['type']);
                        }
                        $method->addComment('@return ' . $func['return']);

                        if (isset($func['throws'])) {
                            if (!empty($func['throws'])) {
                                foreach ($func['throws'] as $e) {
                                    $method->addComment('@throws ' . $e);
                                }
                            }
                        }

                        // model
                        $method = $class->addMethod($func['name'])->setPublic();
                        $method->addComment($func['name']);
                        foreach ($func['arguments'] as $argument) {
                            $method->addComment('@param ' . $argument['type'] . ' $' . $argument['name']);
                            $method->addParameter($argument['name'])->setType($argument['type']);
                        }
                        $method->addComment('@return ' . $func['return']);
                        if (isset($func['throws'])) {
                            if (!empty($func['throws'])) {
                                foreach ($func['throws'] as $e) {
                                    $method->addComment('@throws ' . $e);
                                }
                            }
                        }
                        $method->setBody('// @todo write you logic.');
                    }
                }
            }

            $interfaceFile = str_replace('\\', '/', $api['interface']) . '.php';
            $modelFile = \str_replace('Interface', '', \str_replace('/Api/', '/Model/', $interfaceFile));
            // $printer = new Nette\PhpGenerator\Printer;
            // $printer->printClass($namespace);
            $content = '<?php' . PHP_EOL;

            if (!empty($this->license)) {
                $content .= $this->license . PHP_EOL;
            }

            $this->creater->create($interfaceFile, $content . $namespace);
            $this->creater->create($modelFile, $content . $model);
        }
    }
}