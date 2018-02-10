<?php
/**
 * メンバー詳細ページを扱うクラス
 *
 * @package     SIC
 * @subpackage  Controllers
 * @author      yKicchan
 */
class MembersController extends AppController
{
    /**
     * メンバー編集画面
     * @return void
     */
    public function editAction()
    {
        // メンバー情報を取得
        $member = $this->getMember();

        // グループ情報取得
        $model = new Groups();
        $group = $model->get($member['group_id']);

        // パンくずリスト生成
        $breadcrumb = array(
            array(
                'url' => "/groups/detail/{$group['group_id']}",
                'str' => $group['group_name']
            ),
            array(
                'str' => "メンバーを編集"
            )
        );

        // 経路情報を全取得
        $model = new Routes();
        $routes = $model->get();

        // Viewと共有するデータをセット
        $this->set('routes', $routes);
        $this->set('breadcrumb', $breadcrumb);
        $this->set('member', $member);
        $this->set('group', $group);

        // 表示
        $this->disp('/Members/edit.php');
    }

    /**
     * URLから取得したIDのメンバーの情報を返す
     * @return array メンバー情報
     */
    private function getMember()
    {
        $id = $this->getID();
        $sql = "SELECT * FROM `members` WHERE `member_id` = {$id}";
        $model = new AppModel();
        $row = $model->find($sql);
        $sql = "SELECT routes.name AS name FROM `member_route`, `routes` WHERE `member_id` = {$id} AND routes.route_id = member_route.route_id";
        $row[0]['routes'] = $model->find($sql);
        return $row[0];
    }
}
