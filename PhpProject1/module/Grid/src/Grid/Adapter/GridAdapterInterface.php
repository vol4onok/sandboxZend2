<?php
namespace Grid\Adapter;
/**
 *
 * @author alexander
 */
use Zend\Config\Config;

interface GridAdapterInterface {
    
    public function setTableName($entity);
    public function getTableName();
    public function getDataListForSetting(Config $setting);
    
}
