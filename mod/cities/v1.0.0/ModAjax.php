<?php

require_once(DOC_ROOT . "core2/inc/ajax.func.php");

/**
 * Class ModAjax
 */
class ModAjax extends ajaxFunc
{
    /**
     * Сохранение города
     * @param array $data
     * @return xajaxResponse
     * @throws Zend_Exception
     */
    public function axSaveCity($data)
    {
        $refid = $this->getSessFormField($data['class_id'], 'refid');
        $fields = [
            'name' => 'req',
            'region_id' => 'req',
            'lat' => 'float',
            'lng' => 'float',
        ];

        $dataForSave = [
            'name' => $data['control']['name'],
            'region_id' => $data['control']['region_id'],
            'lat' => $data['control']['lat'],
            'lng' => $data['control']['lng'],
        ];

        if ($this->ajaxValidate($data, $fields)) {
            return $this->response;
        }

        $this->db->beginTransaction();
        try {
            if ($refid == 0) {
                $this->db->insert('cities', $dataForSave);
                //$refid = $this->db->lastInsertId('cities');
            } else {
                $where = $this->db->quoteInto('id = ?', $refid);
                $this->db->update('cities', $dataForSave, $where);
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
            $this->error[] = $e->getMessage();
        }

        $this->done($data);
        return $this->response;
    }
}