<?php

namespace Cities;

require_once DOC_ROOT . "core2/inc/classes/Common.php";

require_once __DIR__ . '/../Model/Cities.php';

use Cities\Model\Cities as CitiesModel;

/**
 * Class City
 */
class City extends \Common
{
    /**
     * @var $city
     */
    private $city;

    /**
     * @param int $city_id
     * @throws \Exception
     */
    public function __construct(int $city_id)
    {
        parent::__construct();
        $this->setData($city_id);
    }

    /**
     * @param $v
     * @return \Common|\CommonApi|\CoreController|mixed|\stdObject|\Zend_Db_Adapter_Abstract|null
     * @throws \Exception
     */
    public function __get($v) {
        return $this->city ?? parent::__get($v);
    }

    /**
     * Удаление города
     */
    public function delete()
    {
        $where = $this->db->quoteInto('id = ?', $this->city->id);
        $this->db->delete('cities', $where);
    }

    /**
     * Получение данных города по айди
     * @param int $city_id
     * @return void
     * @throws \Exception
     */
    private function setData(int $city_id)
    {
        $model = new CitiesModel();
        $city = $city_id ? $model->getCityById($city_id): null;

        if ($city) {
            $this->city = $city;
        } else {
            throw new \Exception($this->_('Указанный город не найден'));
        }
    }
}