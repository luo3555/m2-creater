<?php
namespace App\Entity;

use App\Common\CreateFile;

abstract class EntityBase
{
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
}