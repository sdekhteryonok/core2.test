<?php

require_once DOC_ROOT . 'core2/inc/classes/Alert.php';
require_once DOC_ROOT . 'core2/inc/classes/Common.php';
require_once DOC_ROOT . 'core2/inc/classes/Panel.php';

require_once 'classes/City.php';
require_once 'classes/View.php';
require_once 'Model/Cities.php';

use Cities\City as City;
use Cities\View as View;

/**
 * Class ModCitiesController
 */
class ModCitiesController extends Common
{
    /**
     * @return string
     * @throws Exception
     */
    public function action_index()
    {
        if (!$this->auth->ADMIN) {
            throw new Exception(911);
        }

        $action = $this->actionURL;
        $view = new View();
        $panel = new Panel();

        try {
            if (isset($_GET['edit'])) {
                if (empty($_GET['edit'])) {
                    $panel->setTitle($this->_("Создание нового города"), '', $action);
                    $content = $view->getEdit($action);
                } else {
                    $city = new City($_GET['edit']);
                    $panel->setTitle($city->__get('city')['name'], $this->_('Редактирование города'), $action);
                    $content = $view->getEdit($action, $city);
                }
            } else {
                $panel->setTitle($this->_("Справочник городов Беларуси"));
                $content = $view->getList($action);
            }
        } catch (\Exception $e) {
            $content = Alert::danger($e->getMessage(), 'Ошибка');
        }

        $panel->setContent($content);
        return $panel->render();
    }
}