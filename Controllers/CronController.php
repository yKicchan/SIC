<?php
// メールライブラリの読み込み
require_once '../Other/PHPMailer/PHPMailerAutoload.php';
/**
 * 自動実行されるページを扱うクラス
 *
 * @package     SIC
 * @subpackage  Controllers
 * @author      yKicchan
 */
class CronController extends AppController
{
    /**
     * 遅延情報のapi
     * @var string
     */
    private $url;
    private $not_start_group;
    /**
     * APIのURLを初期化、ライブラリの読み込み
     */
    public function __construct()
    {
        parent::__construct();
        $this->url = "https://rti-giken.jp/fhc/api/train_tetsudo/delay.json";
        // $this->url = "../Config/delay.json";
        $this->not_start_group = array();
        $now = date('H:i');

        $weekdays = array(
          '0' => 'sun',
          '1' => 'mon',
          '2' => 'tue',
          '3' => 'wed',
          '4' => 'thu',
          '5' => 'fri',
          '6' => 'sat',
        );
        $day = $weekdays[date('w')];

        $sql  = 'SELECT g.group_id AS \'group_id\' ,s.' . $day . ' AS \'time\' FROM `groups` g, schedule s WHERE g.schedule_id = s.schedule_id';

        $model = new  AppModel();
        $records = $model->find($sql);

        foreach ($records as $row) {
            if($this->compare_time($row['time'], $now)){
            $this->not_start_group[] = $row['group_id'];
          }
        }
    }

    //時間を比べる $time1が大きかったらtrue,そうでなかったらfalse
    private static function compare_time($time1, $time2){
      //00:00　の形式(24hour)
      $arr1 = explode(':', $time1);
      $arr2 = explode(':', $time2);

      //時間を比べる
      if(intval($arr1[0]) > intval($arr2[0])){
        return true;
      }else if(intval($arr1[0]) < intval($arr2[0])){
        return false;
      }

      //同じ時間であれば分を比べる
      return intval($arr1[1]) > intval($arr2[1]);

    }

    /**
     * Cronジョブ実行URL
     * @return void
     */
    public function execAction()
    {
        $json = file_get_contents($this->url);
        //ASCII,JIS,UTF-8,EUC-JP,SJIS-WINの順番で自動検出 → UTF8に変換
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json, true);
        if ($arr === NULL) {
            print("JSONのデコードに失敗");
            return;
        }

        //$this->not_start_group[]
        //学生の名前と、路線名を取得する
        $model = new AppModel();
        $sql  = 'SELECT m.name student, m.member_id id, r.name route, m.group_id class FROM member_route mr, routes r, members m WHERE mr.route_id = r.route_id AND m.member_id = mr.member_id AND r.is_late = 0';
        //$sql  = 'SELECT m.name student, m.member_id id, r.name route, m.group_id class FROM member_route mr, routes r, members m WHERE mr.route_id = r.route_id AND m.member_id = mr.member_id AND r.is_late = 0';

        $row = $model->find($sql);

        $body = "";
        $class_list = array();

        foreach($row as $line){

            foreach ($arr as $key => $value){
                if ($line['route'] == $value['name']){

                    $message = $line['student'] . "さんが[" . $line['route'] . "]を利用しているため遅刻する恐れがあります。";
                    $g_no = $line['class'];

                    if(in_array($g_no, $class_list)){ //警告が出るので初めて使う配列のスペースでは代入をする
                        $class_list[$g_no] = array();
                    }
                    $class_list[$g_no][] = $message; //グループ(クラス)ごとにメッセージを分ける
                }
            }
        }

        if(count($class_list) === 0){
          print("新しい遅延情報はありません。");
          return;
        }

        // 遅延情報メッセージの作成
        foreach ($class_list as $key => $messages) {

          $pass = false;

          foreach ($this->not_start_group as $group) {
            if($key == $group){
              $pass = true;
            }
          }

          if(!$pass){
            continue;
          }
          // echo "<br>passしたのは{$key}<br>";
            // $body = "教師番号：{$key}\n";
            $body = "";
            foreach ($messages as $message) {
                $body .= $message . "\n";
            }
            $sql  = 'SELECT o.name \'name\', o.mail \'mail\', g.name \'class\' FROM owners o, groups g WHERE o.owner_id = g.owner_id AND o.owner_id = ' . $key;

            $row = $model->find($sql);

            $owner = $row[0]['name'];
            $mail = $row[0]['mail'];
            $g_name = $row[0]['class'];
            $more_info = $this->gethostname() ."/groups/edit/{$key}";

            $body = <<<EOT
{$g_name} / {$owner}様

{$body/*遅延情報*/}

グループメンバーの詳細・設定は下記リンクをご覧ください
{$more_info}
EOT;
            // メール送信
            $result = $this->mailSetting($mail,"[{$g_name}]遅延情報のお知らせ", $body);
            if ($result) {
                $this->setFlag();
            }
        }
    }

    // 遅れている路線にフラグを立てるメソッド
    private function setFlag()
    {
      $json = file_get_contents($this->url);
      //ASCII,JIS,UTF-8,EUC-JP,SJIS-WINの順番で自動検出 → UTF8に変換
      $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
      $arr = json_decode($json, true);
      if ($arr === NULL) {
        print("JSONのデコードに失敗");
        return;
      }

      $sql = "UPDATE `routes` SET `is_late` = 1 WHERE " ;
      //$lating_lines = array();
      $is_first = true;
      foreach($arr as $one){
        if(!$is_first){
          $sql .= " OR ";
        }
        $sql .= "`name` = '{$one['name']}' ";
        $is_first = false;
      }

      $model = new AppModel();
      echo $model->query($sql);

    }
}
