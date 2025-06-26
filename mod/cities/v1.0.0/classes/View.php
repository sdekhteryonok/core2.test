<?php
namespace Cities;

require_once DOC_ROOT . 'core2/inc/classes/Common.php';
require_once DOC_ROOT . 'core2/inc/classes/class.list.php';
require_once DOC_ROOT . 'core2/inc/classes/class.edit.php';
require_once DOC_ROOT . 'core2/inc/classes/class.tab.php';

require_once __DIR__ . '/../Model/Cities.php';

use Cities\City as City;

/**
 * Class View
 */
class View extends \Common
{
    /**
     * Получение таблицы с городами
     * @param $action
     * @return false|string
     * @throws \Exception
     */
    public function getList($action)
    {
        $list = new \listTable('cities');

        $country_id = $this->db->fetchOne("SELECT `id` FROM `countries` WHERE `name` = 'Беларусь'");
        $search_regions = $this->db->fetchPairs("SELECT `id`, `name` FROM `regions` WHERE `country_id` = {$country_id}");

        $list->addSearch($this->_("Город"), "c.name", "TEXT");
        $list->addSearch($this->_("Регион"), "r.id", "LIST");
        $list->sqlSearch[] = $search_regions;

        $list->SQL = "
            SELECT `c`.`id`, 
                   `c`.`name`,
                   `r`.`name` AS `region_name`,
                   `c`.`lat`, 
                   `c`.`lng`
            FROM `cities` AS `c`
            LEFT JOIN `regions` AS `r`
                ON `r`.`id` = `c`.`region_id`
            LEFT JOIN `countries` AS `ctry`
                ON `ctry`.`id` = `r`.`country_id`
            WHERE `c`.`id` > 0 
                AND `ctry`.`id` = {$country_id}
                /*ADD_SEARCH*/
			ORDER BY `c`.`name`, `r`.`id`
        ";

        $list->addColumn($this->_("Город"), "300", "TEXT");
        $list->addColumn($this->_("Регион"), "300", "TEXT");
        $list->addColumn($this->_("Широта"), "1", "TEXT");
        $list->addColumn($this->_("Долгота"), "1", "TEXT");

        $list->addURL = $action . "&edit=0";
        $list->editURL = $action . "&edit=TCOL_00";
        $list->deleteKey = "cities.id";

        $list->getData();

        ob_start();
        $this->printCssModule('cities', '/assets/css/cities.css');
        $this->printJsModule('cities', '/assets/js/cities.js');
        $list->showTable();

        return ob_get_clean();
    }

    /**
     * @param string $action
     * @param City|null $city
     * @return false|string
     * @throws \Zend_Db_Adapter_Exception
     * @throws \Zend_Exception
     */
    public function getEdit(string $action, City $city = null)
    {
        $edit = new \editTable('city');

        $fields = [
            'id',
            'name',
            'region_id',
            'lat',
            'lng',
        ];

        $implode_fields = implode(",\n", $fields);

        $edit->SQL = $this->db->quoteInto("
            SELECT {$implode_fields}
            FROM `cities`
            WHERE `id` = ?
        ", $city ? $city->__get('city') ? $city->__get('city')['id'] : 0 : 0);

        $regions_list = $this->db->fetchPairs("
            SELECT `id`, `name` 
            FROM `regions` 
            ORDER BY `id` ASC
        ");

        $edit->addControl("Город", "TEXT", "maxlength=\"255\" style=\"width:385px\"", "", "", true);
        $edit->addControl($this->_("Регион"), "LIST", "style=\"width:385px\"", "", "", true);
        $edit->selectSQL[] = ['' => '--'] + $regions_list;
        $edit->addControl($this->_("Широта"), "TEXT", "maxlength=\"20\" style=\"width:385px\"", "", "");
        $edit->addControl($this->_("Долгота"), "TEXT", "maxlength=\"20\" style=\"width:385px\"", "", "");

        $edit->back = $action;
        $edit->firstColWidth = '200px';
        $edit->addButton($this->_("Вернуться к списку городов"), "load('$action')");
        $edit->save("xajax_saveCity(xajax.getFormValues(this.id))");

        return $edit->render();
    }
}