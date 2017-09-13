<?php
/**
 * Groups表を扱うモデルクラス
 *
 * @package     SIC
 * @subpackage  Models
 * @author      yKicchan
 */
class Groups extends AppModel
{
    /**
     * グループの情報を取り出す
     * $idがなければ全てを抽出
     *
     * @param  integer $id ID
     * @return array       結果
     */
    public function get($id = 0)
    {
        $sql = "SELECT groups.group_id, groups.name AS group_name, owners.name AS owner_name, owners.mail FROM `groups`, `owners` WHERE groups.owner_id = owners.owner_id";
        if ($id > 0) {
            $sql .= " AND `group_id` = $id";
            $rows = $this->find($sql);
            return $rows[0];
        }
        return $this->find($sql);
    }

    /**
     * グループを１行削除する
     *
     * @param  integer $id ID
     * @return boolean     結果
     */
    public function delete($id)
    {
        $sql = "DELETE FROM `groups` WHERE `group_id` = $id";
        return $this->query($sql);
    }
}
