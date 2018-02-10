<div class="row">
    <h2 class="form-title">メンバーを編集</h2>
</div>
<form action="/groups/detail/<?=$data['member']['group_id']?>" method="post">
    <input type="hidden" name="data[member_id]" value="<?= $data['member']['member_id'] ?>">
    <div class="form-group">
        <label for="member-id">メンバーID</label>
        <input type="text" class="form-control" id="member-id" name="data[member][id]" value="<?= $data['member']['member_id'] ?>">
    </div>
    <div class="form-group">
        <label for="member-name">メンバー名</label>
        <input type="text" class="form-control" id="member-name" name="data[member][name]" value="<?= $data['member']['name'] ?>">
    </div>
    <div class="form-group">
        <label for="roc">経路</label>
        <select name="data[member][routes][]" class="routes" style="width: 100%" id="roc" multiple>
            <?php foreach ($data['routes'] as $routes) {
                $isSelected = false;
                foreach ($data['member']['routes'] as $route) {
                    if ($routes['name'] == $route['name']) {
                        $isSelected = true;
                        break;
                    }
                } ?>
                <option value="<?= $routes['name'] ?>" <?= $isSelected ? 'selected' : '' ?>><?= $routes['name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal">削除</button>
        <button type="submit" name="edit" class="btn btn-success right">確定</button>
        <a href="/groups/detail/<?=$data['member']['group_id']?>" class="btn btn-default right">戻る</a>
    </div>
</form>
<!-- モーダルウィンドウ表示項目  -->
<div class="modal fade" id="modal">
    <div class="modal-dialog" role="dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">メンバーを削除</h4>
            </div>
            <div class="modal-body">
                <p>
                    メンバーを削除しますか？<br>
                    一度削除すると、元に戻すことはできません。
                </p>
            </div>
            <div class="modal-footer">
                <form action="/groups/detail/<?= $data['group']['group_id'] ?>" method="post">
                    <input type="hidden" name="data[member_id]" value="<?= $data['member']['member_id'] ?>">
                    <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                    <button type="submit" name="remove" class="btn btn-danger">削除</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // プラグインの初期化
    $('.routes').select2({
        width: 'resolve',
        placeholder: '経路を選択'
    });
</script>
