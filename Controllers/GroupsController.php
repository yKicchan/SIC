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
        // グループ情報を取得
        $group = $this->getGroup();
        $model = new Owners();
        $owners = $model->get();
        $sql = "SELECT `sun`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`
        FROM `schedule`, `groups`
        WHERE schedule.schedule_id = groups.schedule_id
        AND `group_id` = {$group['group_id']}";
        $schedule = $model->find($sql);

        // パンくずリストを生成
        $breadcrumb = array(
            array('str' => 'グループを編集')
        );

        // Viewと共有するデータをセット
        $this->set('group', $group);
        $this->set('owners', $owners);
        $this->set('schedule', $schedule[0]);
        $this->set('breadcrumb', $breadcrumb);

        // 表示
        $this->disp('/Groups/edit.php');
    }

    /**
     * Ajaxによる通信かどうかを判定
     *
     * @return boolean True or False
     */
    public function isAjax() { return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'; }

    public function ajax_confirm_mailAction()
    {
      if(!$this->isAjax()){
        return;
      }
      $post = $this->getPost();

      $title = "テスト送信";
      $body = <<<EOT
メールをテスト送信しています。
このメールアドレス宛に遅延情報を送信します。
EOT;
      $this->mailSetting($post['mail'], $title, $body);
    }

    /**
     * グループ追加画面
     * @return void
     */
    public function addAction()
    {
        // グループを追加確定してきたか
        $post = $this->getPost();
        if (isset($post['sub'])) {
            $group_id = $this->groupAdd($post['data']);
            $_SESSION['group_created'] = true;
            header("Location:/groups/detail/$group_id");
            return;
        }

        // 新規追加のときすでにある通知先の情報を取得
        $owners = (new Owners())->get();

        // パンくずリスト生成
        $breadcrumb = array(
            array('str' => 'グループ新規作成')
        );

        // Viewと共有するデータをセット
        $this->set('owners', $owners);
        $this->set('breadcrumb', $breadcrumb);

        // 表示
        $this->disp('/Groups/add.php');
    }

    /**
     * グループ詳細画面
     * @return void
     */
    public function detailAction()
    {
        // グループ作成されてきたか
        if (isset($_SESSION['group_created']) && $_SESSION['group_created'] === true) {
            $this->set('isCreated', true);
            unset($_SESSION['group_created']);
        }

        // メンバー設定されてきたか
        $post = $this->getPost();
        if (isset($post['sub'])) {
            if ($this->memberAdd($post['data'])) {
                $this->set('isUpdate', true);
            } else {
                $this->set('error', true);
            }

        }

        // メンバー編集されてきたか
        if (isset($post['edit'])) {
            if ($this->memberEdit($post['data'])){
                $this->set('isUpdate', true);
            } else {
                $this->set('error', true);
            }
        }

        // メンバー削除されてきたか
        if (isset($post['remove'])) {
            if ($this->memberRemove($post['data'])){
                $this->set('isRemove', true);
            } else {
                $this->set('error', true);
            }
        }

        // グループの詳細情報を取得
        $group = $this->getGroup();
        $model =new AppModel();
        $key = $group["group_id"];
        $sql  = 'SELECT m.member_id AS \'member_id\', m.name AS \'name\', r.name AS \'line\', m.mail AS \'mail\'
        FROM members m, member_route mr, routes r
        WHERE m.member_id = mr.member_id AND mr.route_id = r.route_id AND m.group_id = ' . $key .
        ' ORDER BY member_id';

        $records = $model->find($sql);
        $new_arr = array(); //乗り換えする人の路線をまとめる配列

        for ($i=0; $i<count($records);$i++) {
          $tmp_id = $records[$i]['member_id'];
          $tmp_cnt = $i; //調べようとする時点の$iを保管
          $lines = array();//
          $lines[] = $records[$i]['line'];
          //同じ学籍番号であれば路線名を配列にまとめる
          while (isset($records[$i+1]) && $tmp_id == $records[$i+1]['member_id']) {
            $lines[] = $records[$i+1]['line'];
            $i++;
          }
          if (count($lines) >= 2) {
            //元の配列の一行を流用して新しい配列に格納する
            $records[$tmp_cnt]['line'] =implode(",", $lines);
            $new_arr[] = $records[$tmp_cnt];
          } else {
            $new_arr[] = $records[$i];
          }
        }

        // 路線情報を取得
        $sql = "SELECT `name` FROM `routes`";
        $routes = $model->find($sql);

        // パンくずリスト生成
        $breadcrumb = array(
            array('str' => 'メンバー一覧')
        );

        // Viewと共有するデータをセット
        $this->set('records', $new_arr);
        $this->set('group', $group);
        $this->set('routes', $routes);
        $this->set('breadcrumb', $breadcrumb);

        // 表示
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
     * グループの新規登録
     * @param  array $data POSTデータ
     * @return integer 登録されたグループID(失敗した時は0)
     */
    private function groupAdd($data)
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
        // スケジュール登録
        if (isset($data['check'])) {
            foreach ($data['schedule'] as &$time) {
                if ($time == '') {
                    $time = '23:59';
                }
            }
            unset($time);
            $sql = "INSERT INTO `schedule` (`sun`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`) VALUES (
                '{$data['schedule']['sun']}',
                '{$data['schedule']['mon']}',
                '{$data['schedule']['tue']}',
                '{$data['schedule']['wed']}',
                '{$data['schedule']['thu']}',
                '{$data['schedule']['fri']}',
                '{$data['schedule']['sat']}')";
        } else {
            $sql = "INSERT INTO `schedule` (`sun`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`)
            VALUES('23:59', '23:59', '23:59', '23:59', '23:59', '23:59', '23:59')";
        }
        if ($model->query($sql)) {
            $schedule_id = $model->getInsertId();
        } else {
            return 0;
        }

        // グループ登録
        $sql = "INSERT INTO `groups`(`name`, `owner_id`, `schedule_id`) values('{$data['group']['name']}', {$owner['id']}, {$schedule_id})";
        if (!$model->query($sql)) {
            return 0;
        }
        return $model->getInsertId();
    }

    /**
     * メンバー設定を保存する
     * @param  array $data POSTデータ
     * @return boolean 処理結果
     */
    private function memberAdd($data)
    {
        // グループのメンバー情報を一旦初期化
        $group_id = $this->getId();
        $model = new AppModel();
        $sql = "DELETE FROM `members` WHERE `group_id` = $group_id";
        if (!$model->query($sql)){
            return false;
        }

        // 追加するメンバーがいなければ(メンバーが全て削除されてきたとき)処理終了
        if (count($data) == 0) {
            return true;
        }

        // ルートの名前からIDを取得
        $sql = "SELECT `route_id`, `name` FROM `routes`";
        $routes = $model->find($sql);
        foreach ($data['route'] as &$value) {
            $value = explode(',', $value);
            foreach ($value as &$val) {
                foreach ($routes as $route) {
                    if ($route['name'] == $val) {
                        $val = $route['route_id'];
                        break;
                    }
                }
            }
            unset($val);
        }
        unset($value);

        // メンバー登録
        for ($i = 0; $i < count($data['id']); $i++) {
            // 空の値はスキップ
            if ($data['id'][$i] == "" || $data['name'][$i] == "") {
                continue;
            }
            $sql = "INSERT INTO `members` values({$data['id'][$i]}, '{$data['name'][$i]}', {$group_id}, null)";
            $result = $model->query($sql);
            foreach ($data['route'][$i] as $route) {
                $sql = "INSERT INTO `member_route` values({$data['id'][$i]}, '{$route}')";
                $model->query($sql);
            }
        }
        return true;
    }

    /**
     * メンバー削除確定処理
     * @param  array $post POSTデータ
     * @return boolean 処理実行結果
     */
    private function memberRemove($data)
    {
        $sql = "DELETE FROM `members` WHERE `member_id` = {$data['member_id']}";
        $model = new AppModel();
        if (!$model->query($sql)) {
            return false;
        }
        return true;
    }

    private function memberEdit($data)
    {
        // メンバー情報を更新
        $sql = "UPDATE `members`
                SET `member_id` = '{$data['member']['id']}',
                    `name` = '{$data['member']['name']}'
                WHERE `member_id` = {$data['member_id']}";
        $model = new AppModel();
        if (!$model->query($sql)) {
            var_dump('update');
            return false;
        }

        // 一旦メンバーの経路情報を削除
        $sql = "DELETE FROM `member_route` WHERE `member_id` = {$data['member_id']}";
        $model = new AppModel();
        if (!$model->query($sql)) {
            var_dump('delete');
            return false;
        }
        // 登録し直す
        // ルートの名前からIDを取得
        $sql = "SELECT `route_id`, `name` FROM `routes`";
        $routes = $model->find($sql);
        foreach ($data['member']['routes'] as &$target) {
            foreach ($routes as $route) {
                if ($route['name'] == $target) {
                    $target = $route['route_id'];
                }
            }
        }
        unset($target);
        foreach ($data['member']['routes'] as $route) {
            $sql = "INSERT INTO `member_route` values({$data['member']['id']}, {$route})";
            if (!$model->query($sql)) {
                var_dump($sql);
                return false;
            }
        }
        return true;
    }
}
