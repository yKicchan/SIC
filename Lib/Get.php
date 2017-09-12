<?php
/**
 * GET変数を扱うクラス
 *
 * @package     SIC
 * @subpackage  Lib
 * @author  yKicchan
 */
class Get extends Request
{
    /**
     * パラメータ値を設定するメソッド
     */
    protected function setValues()
    {
        foreach ($_GET as $key => $value) {
            $this->values[$key] = $value;
        }
    }
}
