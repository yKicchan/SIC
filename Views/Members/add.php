<script src="/js/edit_member.js" charset="utf-8"></script>
<h2><?= $data['group']['group_name'] ?>のメンバー一覧<button class="btn btn-default right" data-toggle="modal" data-target="#modal">メンバー追加</button></h2>
<form action="" method="post">
    <!-- メンバーデータ一覧   -->
    <table class="table groups">
        <thead>
            <tr>
                <th>ID</th>
                <th>メンバー名</th>
                <th>経路</th>
                <th></th>
            </tr>
        </thead>
        <tbody id='members'>
        </tbody>
    </table>
    <button type="button" class="btn btn-default">戻る</button>
    <button type="button" class="btn btn-success">確定</button>
</form>
<!-- モーダルウィンドウ表示項目  -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-sm" role="dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">メンバーを追加</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nam">ID</label>
                    <input type="number" class="form-control" size="20" id="num">
                </div>
                <div class="form-group">
                    <label for="nam">メンバー名</label>
                    <input type="text" class="form-control" size="20" id="nam">
                </div>
                <div class="form-group">
                    <label for="nam">経路</label>
                    <select class="routes" style="width: 100%">
                        <option value="琵琶湖線">琵琶湖線</option>
                        <option value="JR京都線">JR京都線</option>
                        <option value="大阪環状線">大阪環状線</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="assign-next">続けて登録</button>
                <button type="button" class="btn btn-success" id="assign">登録</button>
            </div>
        </div>
    </div>
</div>
