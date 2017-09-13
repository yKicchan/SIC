<?php
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
