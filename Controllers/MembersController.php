<?php
/**
 *  メンバーを扱うクラス
 *
 * @package     SIC
 * @subpackage  Controllers
 * @author      yKicchan
 */
class MembersController extends AppController
{
    public function addAction()
    {
        $post = $this->getPost();
        $group_id = 0;
        if (isset($post['sub'])) {
            $group_id = $this->groupAddCommit($post['data']);
        }
        $this->disp('/Members/add.php');
    }

    /**
     * グループの新規登録
     * @param  array $data POSTデータ
     * @return integer 登録されたグループID(失敗した時は0)
     */
    private function groupAddCommit($data)
    {
        // 通知先がすでに登録されていないかチェック
        $owner = $data['owner'];
        $sql = "SELECT * FROM `owners` WHERE `mail` = '{$owner['mail']}'";
        $model = new AppModel();
        $row = $model->find($sql);
        if (count($row) == 0) {
            // 未登録の場合は新規登録する
            $sql = "INSERT INTO `owners`(`name`, `mail`) values('{$owner['name']}', '{$owner['mail']}')";
            if ($model->query($sql)) {
                $owner['id'] = $model->getInsertId();
            } else {
                return 0;
            }
        } else {
            // 登録済みの場合はidを取得
            $owner['id'] = $row[0]['owner_id'];
        }
        // グループ登録
        $sql = "INSERT INTO `groups`(`name`, `owner_id`) values('{$data['group']['name']}', {$owner['id']})";
        if (!$model->query($sql)) {
            return 0;
        }
        return $model->getInsertId();
    }
}
