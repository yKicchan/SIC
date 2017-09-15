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
        // グループ編集してきたかの判定
        $post = $this->getPost();
        if (isset($post['edit'])) {
            $this->groupEdit($post['data']);
        }

        // グループ削除してきたか
        if (isset($post['remove'])) {
            $this->groupRemove($post['data']['group_id']);
        }

        // グループの情報をセット
        $model = new Groups();
        $groups = $model->get();
        $this->set('groups', $groups);

        // ダッシュボード表示
        $this->disp('/index.php');
    }

    /**
     * 編集内容を確定する
     * @param  array $data POSTデータ
     * @return void
     */
    private function groupEdit($data)
    {
        // グループ更新
        $group = $data['group'];
        $group['id'] = $data['group_id'];
        $model = new AppModel();
        $group['name'] = $model->escape($group['name']);

        $sql = "UPDATE `groups` SET `name` = '{$group['name']}' WHERE `group_id` = {$group['id']}";
        if (!$model->query($sql)) {
            echo "グループ失敗";
        }

        // 通知先更新
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

        // スケジュール更新
        if (isset($data['check'])) {
            $sql = "SELECT `schedule_id` AS id FROM `groups` WHERE `group_id` = {$group['id']}";
            $schedule = $model->find($sql);
            foreach ($data['schedule'] as &$time) {
                if ($time == '') {
                    $time = '23:59';
                }
            }
            unset($time);
            $sql = "UPDATE `schedule`
            SET `sun` = '{$data['schedule']['sun']}',
            `mon` = '{$data['schedule']['mon']}',
            `tue` = '{$data['schedule']['tue']}',
            `wed` = '{$data['schedule']['wed']}',
            `thu` = '{$data['schedule']['thu']}',
            `fri` = '{$data['schedule']['fri']}',
            `sat` = '{$data['schedule']['sat']}'
            WHERE `schedule_id` = {$schedule[0]['id']}";
            $model->query($sql);
        }
        $this->set('isEdit', true);
    }

    /**
     * グループ削除
     * @param integer $id グループID
     * @return void
     */
    private function groupRemove($id)
    {
        $model = new AppModel();
        $sql = "DELETE FROM `members` WHERE `group_id` = $id";
        $model->query($sql);
        $sql = "DELETE FROM `groups` WHERE `group_id` = $id";
        if (!$model->query($sql)) {
            echo "削除失敗";
        } else {
            $this->set('isRemove', true);
        }
    }
}
