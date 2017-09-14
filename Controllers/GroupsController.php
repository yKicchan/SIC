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
        $model = new Owners();
        $owners = $model->get();
        $sql = "SELECT `sun`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`
        FROM `schedule`, `groups`
        WHERE schedule.schedule_id = groups.schedule_id
        AND `group_id` = {$group['group_id']}";
        $schedule = $model->find($sql);

        $this->set('group', $group);
        $this->set('owners', $owners);
        $this->set('schedule', $schedule[0]);
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
      //$_GET[]
      $post = $this->getPost();
      var_dump($post);
      $this->mailSetting($post['mail'], "テスト", "テスト");
    }

    /**
     * グループ追加画面
     * @return void
     */
    public function addAction()
    {
        $post = $this->getPost();
        $group_id = 0;
        if (isset($post['sub'])) {
            $group_id = $this->groupAdd($post['data']);
            header("Location:/groups/detail/$group_id");
            return;
        }
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
        // メンバー設定されてきたか
        $post = $this->getPost();
        if (isset($post['sub'])) {
            $this->memberAdd($post['data']);
            $this->set('isUpdate', true);
        }

        $group = $this->getGroup();
        $model =new AppModel();
        $key = $group["group_id"];
        $sql  = 'SELECT m.member_id AS \'member_id\', m.name AS \'name\', r.name AS \'line\', m.mail AS \'mail\'
        FROM members m, member_route mr, routes r
        WHERE m.member_id = mr.member_id AND mr.route_id = r.route_id AND m.group_id = ' . $key .
        ' ORDER BY member_id';

        $records = $model->find($sql);
        $new_arr = array(); //乗り換えする人の路線をまとめる配列

        for($i=0; $i<count($records);$i++){
          $tmp_id = $records[$i]['member_id'];
          $tmp_cnt = $i; //調べようとする時点の$iを保管
          $lines = array();//
          $lines[] = $records[$i]['line'];
          //同じ学籍番号であれば路線名を配列にまとめる
          while(isset($records[$i+1]) && $tmp_id == $records[$i+1]['member_id']){
            $lines[] = $records[$i+1]['line'];
            $i++;
          }
          if(count($lines) >= 2){
            //元の配列の一行を流用して新しい配列に格納する
            $records[$tmp_cnt]['line'] =implode(",", $lines);
            $new_arr[] = $records[$tmp_cnt];
          }else{
            $new_arr[] = $records[$i];
          }
        }

        $sql = "SELECT `name` FROM `routes`";
        $routes = $model->find($sql);

        $this->set('records', $new_arr);
        $this->set('group', $group);
        $this->set('routes', $routes);
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
     * @return void
     */
    private function memberAdd($data)
    {
        // グループのメンバー情報を一旦初期化
        $group_id = $this->getId();
        $model = new AppModel();
        $sql = "DELETE FROM `members` WHERE `group_id` = $group_id";
        $model->query($sql);
        if (count($data) == 0) {
            return;
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
    }
}
