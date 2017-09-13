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
        $group = $this->getGroup();
        $owners = (new Owners())->get();
        $this->set('group', $group);
        $this->set('owners', $owners);
        $this->disp('/Groups/edit.php');
    }

    /**
     * グループ追加画面
     * @return void
     */
    public function addAction()
    {
        $owners = (new Owners())->get();
        $this->set('owners', $owners);
        $this->disp('/Groups/add.php');
    }

    /**
     * グループ詳細画面
     * @return void
     */
    public function detailAction()
    {
        // 編集してきたかの判定
        $post = $this->getPost();
        if (isset($post['sub'])) {
            $this->editCommit($post['data']);
        }

        $group = $this->getGroup();
        $model =new AppModel();
        $key = $group["group_id"];
        $sql  = 'SELECT m.member_id AS \'member_id\', m.name AS \'name\', r.name AS \'line\', m.mail AS \'mail\' FROM members m, member_route mr, routes r WHERE m.member_id = mr.member_id AND mr.route_id = r.route_id AND m.group_id = ' . $key;
        $row = $model->find($sql);

        $this->set('records', $row);
        $this->set('group', $group);
        $this->disp('/Groups/detail.php');
    }

    /**
     * グループ情報を取得する
     * @return array グループ情報
     */
    private function getGroup()
    {
        $id = $this->getId();
        $model = new Groups();
        return $model->get($id);
    }

    /**
     * 編集内容を確定する
     * @param  array $data POSTデータ
     * @return void
     */
    public function editCommit($data)
    {
        $group = $data['group'];
        $group['id'] = $this->getId();
        $model = new AppModel();
        $group['name'] = $model->escape($group['name']);

        $sql = "UPDATE `groups` SET `name` = '{$group['name']}' WHERE `group_id` = {$group['id']}";
        if (!$model->query($sql)) {
            echo "グループ失敗";
        }

        $owner = $data['owner'];
        $sql = "SELECT `owner_id` FROM `groups` WHERE `group_id` = {$group['id']}";
        $row = $model->find($sql);
        $owner['id'] = $row[0]['owner_id'];

        $owner['name'] = $model->escape($owner['name']);
        $owner['mail'] = $model->escape($owner['mail']);

        $sql = "UPDATE `owners` SET `name` = '{$owner['name']}', `mail` = '{$owner['mail']}' WHERE `owner_id` = {$owner['id']}";
        if (!$model->query($sql)) {
            echo "オーナー失敗";
        }
    }
}
