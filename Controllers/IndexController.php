<?php
/**
 * トップページを扱うクラス
 *
 * @package     SIC
 * @subpackage  Controllers
 * @author      yKicchan
 */
class IndexController extends AppController
{
    /**
     * トップページ
     *
     * @return void
     */
    public function indexAction()
    {
        // トップページ表示
        $this->disp('/index.php');
    }
}
