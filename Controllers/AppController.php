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
    }

    /**
     * Viewファイルを読み込みページを表示する
     *
     * @param string $fileName 読み込むファイル名
     */
    public function disp($fileName)
    {
        $data = $this->get();
        require_once '../Views' . $fileName;
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
