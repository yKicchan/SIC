<script src="/js/add_group.js" charset="utf-8"></script>
<h2>グループを編集</h2>
<form action="/" method="post">
    <input type="hidden" name="data[group_id]" value="<?= $data['group']['group_id'] ?>">
    <div class="form-group">
        <label for="group-name">グループ名</label>
        <input type="text" class="form-control" id="group-name" name="data[group][name]" value="<?= $data['group']['group_name'] ?>" >
    </div>
    <div class="form-group">
        <label for="owner-name">通知先名</label>
        <input type="text" class="form-control"　id="owner-name"  name="data[owner][name]" value="<?= $data['group']['owner_name'] ?>" autocomplete="on" list="owner-names" >
        <datalist id="owner-names">
            <?php foreach ($data['owners'] as $owner) { ?>
                <option value="<?= $owner['name'] ?>">
            <?php } ?>
        </datalist>
    </div>
    <div class="form-group">
        <label for="owner-mail">通知先メールアドレス</label>
        <input type="text" class="form-control"　id="owner-mail"  name="data[owner][mail]" value="<?= $data['group']['mail'] ?>" autocomplete="on" list="owner-mails" >
        <p class="help-block">このメールアドレス宛に、遅延情報が通知されます。</p>
        <datalist id="owner-mails">
            <?php foreach ($data['owners'] as $owner) { ?>
                <option value="<?= $owner['mail'] ?>">
            <?php } ?>
        </datalist>
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" name="data[check]" id="schedule">通知スケジュールを設定する
        </label>
    </div>
    <div class="form-group schedule" hidden="hidden">
        <p class="help-block">※設定した時間以降は、遅延情報が通知されなくなります。</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="sun">日</th>
                    <th>月</th>
                    <th>火</th>
                    <th>水</th>
                    <th>木</th>
                    <th>金</th>
                    <th class="sat">土</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="time" name="data[schedule][sun]" value="<?= $data['schedule']['sun'] ?>" ></td>
                    <td><input type="time" name="data[schedule][mon]" value="<?= $data['schedule']['mon'] ?>" ></td>
                    <td><input type="time" name="data[schedule][tue]" value="<?= $data['schedule']['tue'] ?>" ></td>
                    <td><input type="time" name="data[schedule][wed]" value="<?= $data['schedule']['wed'] ?>" ></td>
                    <td><input type="time" name="data[schedule][thu]" value="<?= $data['schedule']['thu'] ?>" ></td>
                    <td><input type="time" name="data[schedule][fri]" value="<?= $data['schedule']['fri'] ?>" ></td>
                    <td><input type="time" name="data[schedule][sat]" value="<?= $data['schedule']['sat'] ?>" ></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal">削除</button>
        <button type="submit" name="edit" class="btn btn-success right">確定</button>
        <a href="/" class="btn btn-default right">戻る</a>
    </div>
</form>
<!-- モーダルウィンドウ表示項目  -->
<div class="modal fade" id="modal">
    <div class="modal-dialog" role="dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">グループを削除</h4>
            </div>
            <div class="modal-body">
                <p>
                    グループを削除しますか？<br>
                    一度削除すると、元に戻すことはできません。
                </p>
            </div>
            <div class="modal-footer">
                <form action="/" method="post">
                    <input type="hidden" name="data[group_id]" value="<?= $data['group']['group_id'] ?>">
                    <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                    <button type="submit" name="remove" class="btn btn-danger">削除</button>
                </form>
            </div>
        </div>
    </div>
</div>
