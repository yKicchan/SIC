<?php
// メールライブラリの読み込み
require_once '../Other/PHPMailer/PHPMailerAutoload.php';
/**
 * コントローラクラス
 *
 * @package     SIC
 * @subpackage  Controllers
 * @author      yKicchan
 */
class AppController extends Controller
{
    /**
     * アプリの初期設定
     */
    public function __construct()
    {
        parent::__construct();
        $this->setViewDir('/Views');
        $this->set('title', "I'll be late.");
    }

    /**
     * Viewファイルを読み込みページを表示する
     *
     * @param string $fileName 読み込むファイル名
     */
    public function disp($fileName)
    {
        $data = $this->get();
        require_once '../Views/header.php';
        require_once '../Views' . $fileName;
        require_once '../Views/footer.php';
    }

    /**
     * メール送信
     * @param  string $to 送信先 string
     * @param  string $body 本文
     * @return void
     */
    protected function mailSetting($to, $title, $body)
    {
        $subject = $title;//"遅延情報のお知らせ";
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
        }

        //echo $message;
    }

    /**
     * URLからIDを正規表現抽出するメソッド
     *
     * @return integer ID
     */
    public function getId()
    {
        $id = array();
        preg_match("/[0-9]+/", $_SERVER['REQUEST_URI'], $id);
        return $id[0];
    }
}
