<?php
/**
 * Routes表を扱うモデルクラス
 *
 * @package     SIC
 * @subpackage  Models
 * @author      yKicchan
 */
class Routes extends AppModel
{
    public function get()
    {
        $sql = "SELECT * FROM `routes`";
        return $this->find($sql);
    }
}
