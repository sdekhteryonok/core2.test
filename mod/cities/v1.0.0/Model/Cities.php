<?php
namespace Cities\Model;

/**
 * Class Cities
 */
class Cities extends \Zend_Db_Table_Abstract
{
    protected $_name = 'cities';

    /**
     * @param string $expr
     * @param array $var
     * @return null|\Zend_Db_Table_Row_Abstract
     */
    public function exists($expr, $var = array())
    {
        $sel = $this->select()->where($expr, $var);

        return $this->fetchRow($sel->limit(1));
    }

    /**
     * Получение значения одного поля
     * @param $field
     * @param $expr
     * @param array $var
     * @return string
     */
    public function fetchOne($field, $expr, $var = array())
    {
        $sel = $this->select();
        if ($var) {
            $sel->where($expr, $var);
        } else {
            $sel->where($expr);
        }
        $res = $this->fetchRow($sel);
        return $res ? $res->$field : null;
    }

    /**
     * Получение города по $id
     * @param string $id
     * @return mixed
     */
    public function getCityById($id)
    {
        $res = $this->_db->fetchRow("
            SELECT `c`.`id`, 
                   `c`.`name`, 
                   `c`.`lat`, 
                   `c`.`lng`, 
                   `r`.`id` AS `region_id`, 
                   `r`.`name` AS `region_name`, 
                   `ctry`.`id` AS `country_id`, 
                   `ctry`.`name` AS `country_name`
            FROM `cities` AS `c`
            LEFT JOIN `regions` AS `r`
                ON `r`.`id` = `c`.`region_id`
            LEFT JOIN `countries` AS `ctry`
                ON `ctry`.`id` = `r`.`country_id`
            WHERE `c`.`id` = ? 
            LIMIT 1
        ", $id);

        return $res;
    }

    /**
     * Получение всего списка городов по $country_id
     * @return array
     */
    public function getCitiesListByCountryId($country_id = 1)
    {
        $cities = $this->_db->fetchAll("
            SELECT `c`.`id`, 
                   `c`.`name`, 
                   `c`.`lat`, 
                   `c`.`lng`, 
                   `r`.`id` AS `region_id`, 
                   `r`.`name` AS `region_name`, 
                   `ctry`.`id` AS `country_id`, 
                   `ctry`.`name` AS `country_name`
            FROM `cities` AS `c`
            LEFT JOIN `regions` AS `r`
                ON `r`.`id` = `c`.`region_id`
            LEFT JOIN `countries` AS `ctry`
                ON `ctry`.`id` = `r`.`country_id`
            WHERE `ctry`.`id` = ? 
			ORDER BY `c`.`name`, `r`.`id`
		");

        return $cities;
    }
}