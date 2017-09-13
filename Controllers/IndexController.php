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
        // グループの情報をセット
        $model = new Groups();
        $groups = $model->get();
        $this->set('groups', $groups);

        // ダッシュボード表示
        $this->disp('/index.php');
    }
}
