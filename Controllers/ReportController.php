<?php
// ライブラリの読み込み
require_once '../Other/PHPMailer/PHPMailerAutoload.php';
class ReportController extends AppController
{
    /**
     * 遅延情報のapi
     * @var string
     */
    private $url;

    /**
     * APIのURLを初期化、ライブラリの読み込み
     */
    public function __construct()
    {
        parent::__construct();
        $this->url = "https://rti-giken.jp/fhc/api/train_tetsudo/delay.json";
    }

    /**
     * メール送信
     * @param  string $to 送信先 string
     * @param  string $body 本文
     * @return void
     */
    private function mailSetting($to, $body)
    {
        $subject = "遅延情報のお知らせ";
        $from = "from@from.com";
        $smtp_user = "ecccomp.sic@gmail.com";
        $smtp_password = "123qwEcc";

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true;
        $mail->CharSet = 'utf-8';
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->IsHTML(false);
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_password;
        $mail->SetFrom($smtp_user);
        $mail->From     = $from;
        $mail->Subject = $subject;
        $mail->Body = $body;

        // 宛先
        $mail->AddAddress($to);

        if(!$mail->Send()){
            $message  = "Message was not sent<br/ >";
            $message .= "Mailer Error: " . $mailer->ErrorInfo;
        } else {
            $message  = "Message has been sent";
            $this->setFlag();
        }

        echo $message;
    }

    public function sendMailAction()
    {
        $json = file_get_contents($this->url);
        //ASCII,JIS,UTF-8,EUC-JP,SJIS-WINの順番で自動検出 → UTF8に変換
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json, true);
        if ($arr === NULL) {
            die("JSONのデコードに失敗");
        }

        //学生の名前と、路線名を取得する
        $model = new AppModel();
        $sql  = 'SELECT m.name student, m.member_id id, r.name route, m.group_id class FROM member_route mr, route r, members m WHERE mr.route_id = r.route_id AND m.member_id = mr.member_id AND r.is_late = 0';
        $row = $model->find($sql);

        $body = "";
        $class_list = array();
        $mess = "";

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

        // 遅延情報メッセージの作成
        foreach ($class_list as $key => $messages) {
            $body = "";
            foreach ($messages as $message) {
                $body .= $message . "\n";
            }
            $sql  = 'SELECT `name`, `mail` FROM `owners` WHERE owner_id = ' . $key;
            $row = $model->find($sql);

            $this->mailSetting($row[0]['mail'], $body);
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
        die("JSONのデコードに失敗");
      }

      $sql = "UPDATE `route` SET `is_late` = 1 WHERE " ;
      //$lating_lines = array();
      $is_first = true;
      foreach($arr as $one){
        if(!$is_first){
          $sql .= " OR ";
        }
        $sql .= "`name` = '{$one['name']}' ";
        $is_first = false;
      }
      echo $sql;
      $model = new AppModel();
      echo $model->query($sql);

    }
}
