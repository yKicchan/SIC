<?php
// ライブラリの読み込み
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
    public function execAction()
    {
        $path = "/var/www/html/cron.txt";
        $data = "Hello world!!\n";
        file_put_contents($path, $data, FILE_APPEND);
    }
}
