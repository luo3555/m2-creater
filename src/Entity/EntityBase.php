<?php
namespace App\Entity;

use App\Common\CreateFile;

abstract class EntityBase
{
    const AREA_GLOBAL = 'global';

    const AREA_FRONTEND = 'frontend';

    const AREA_ADMINHTML = 'adminhtml';

    protected $xml = [];

    protected $creater;

    protected $params;

    protected $license;

    public function __construct($params=[])
    {
        $this->creater = new CreateFile($params);
        $this->params = $params;
        $this->license = $params['license'];
        $this->_construct();
    }

    abstract protected function _construct();

    public function fetch($tpl, $params)
    {
        return $this->creater->renderFile($tpl, $params);
    }

    public function getModuleName()
    {
        return $this->params['module']['vendor'] .'_' . $this->params['module']['module'];
    }

    public function getVendor()
    {
        return $this->params['module']['vendor'];
    }

    public function getModule()
    {
        return $this->params['module']['module'];
    }
}