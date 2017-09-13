<?php
/**
 * グループを扱うクラス
 *
 * @package     SIC
 * @subpackage  Controllers
 * @author      yKicchan
 */
class GroupsController extends AppController
{
    /**
     * グループ編集画面
     * @return void
     */
    public function editAction()
    {
        // URLからID取得
        $id = $this->getId();
        $model = new Groups();
        $group = $model->get($id);
        $this->set('group', $group);
        $this->disp('/Groups/edit.php');
    }

    /**
     * グループ追加画面
     * @return void
     */
    public function addAction()
    {
        $model = new Owners();
        $owners = $model->get();
        $this->set('owners', $owners);
        $this->disp('/Groups/add.php');
    }
}
