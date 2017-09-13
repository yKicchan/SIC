<?php
/**
 * Owners表を扱うモデルクラス
 *
 * @package     SIC
 * @subpackage  Models
 * @author      yKicchan
 */
class Owners extends AppModel
{
    public function get($id = 0)
    {
        $sql = "SELECT * FROM `owners`";
        if ($id > 0) {
            $sql .= " WHERE `owner_id` = $id";
            $rows = $this->find($sql);
            return $rows[0];
        }
        return $this->find($sql);
    }
}
