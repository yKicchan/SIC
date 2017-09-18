<script src="/js/edit_member.js" charset="utf-8"></script>
<p class="top-list"><a href="/">Top</a> > <?= $data['group']['group_name'] ?></p>
<h2><?= $data['group']['group_name'] ?>のメンバー一覧<button class="btn btn-default right" data-toggle="modal" data-target="#modal">メンバー追加</button></h2>
<?php if ($data['isUpdate']) { ?>
    <div class="alert alert-success" role="alert">
        更新されました
    </div>
<?php } ?>
<form action="/groups/detail/<?= $data['group']['group_id'] ?>" method="post">
    <!-- メンバーデータ一覧   -->
    <table class="table table-striped groups">
        <thead>
            <tr>
                <th>ID</th>
                <th>メンバー名</th>
                <th>経路</th>
                <!-- <th>アドレス</th> -->
                <th></th>
            </tr>
        </thead>
        <tbody id='members'>
            <?php foreach ($data['records'] as $row) { ?>
              <tr>
                <td><?=$row['member_id']?><input type="hidden" value="<?=$row['member_id']?>" name="data[id][]"></td>
                <td><?=$row['name']?><input type="hidden" value="<?=$row['name']?>" name="data[name][]"></td>
                <td><?=$row['line']?><input type="hidden" value="<?=$row['line']?>" name="data[route][]"></td>
                <!-- <td><?=$row['mail']?></td> -->
                <td><input type="button" class="btn del" value="×"></td>
              </tr>
            <?php } ?>
        </tbody>
    </table>
    <button type="submit" name="sub" class="btn btn-success right">確定</button>
    <a href="/" class="btn btn-default right">戻る</a>
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
                    <select class="routes" style="width: 100%" id="roc">
                        <option selected disabled></option>
                        <?php foreach ($data['routes'] as $routes) { ?>
                            <option value="<?= $routes['name'] ?>"><?= $routes['name'] ?></option>
                        <?php } ?>
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
