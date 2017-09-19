<script src="/js/add_group.js" charset="utf-8"></script>
<script src="/js/confirm_mail.js" charset="utf-8"></script>
<h2 class="form-title">グループ新規作成</h2>
<form action="/groups/add" method="post">
    <div class="form-group">
        <label for="group-name" class="form-required">グループ名</label>
        <input type="text" class="form-control" id="group-name" name="data[group][name]" placeholder="IE4A" required>
    </div>
    <div class="form-group">
        <label for="owner-name" class="form-required">通知先名</label>
        <input type="text" class="form-control"　id="owner-name"  name="data[owner][name]" placeholder="ECC 太郎" autocomplete="on" list="owner-names" required>
        <datalist id="owner-names">
            <?php foreach ($data['owners'] as $owner) { ?>
                <option value="<?= $owner['name'] ?>">
            <?php } ?>
        </datalist>
    </div>
    <div class="form-group">
        <label for="owner-mail" class="form-required">通知先メールアドレス</label>
        <input type="text" class="form-control" id="owner-mail" name="data[owner][mail]" placeholder="sic@ecc.ac.jp" autocomplete="on" list="owner-mails" required>
        <p class="help-block">
            このメールアドレス宛に、遅延情報が通知されます。
            <span class="btn btn-default btn-xs" id="mail-test">テスト送信</span>
        </p>
        <datalist id="owner-mails">
            <?php foreach ($data['owners'] as $owner) { ?>
                <option value="<?= $owner['mail'] ?>">
            <?php } ?>
        </datalist>
    </div>
    <div class="checkbox">
        <label class="form-optional">
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
                    <td><input type="time" name="data[schedule][sun]" ></td>
                    <td><input type="time" name="data[schedule][mon]" ></td>
                    <td><input type="time" name="data[schedule][tue]" ></td>
                    <td><input type="time" name="data[schedule][wed]" ></td>
                    <td><input type="time" name="data[schedule][thu]" ></td>
                    <td><input type="time" name="data[schedule][fri]" ></td>
                    <td><input type="time" name="data[schedule][sat]" ></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group right">
        <a href="/" class="btn btn-default">戻る</a>
        <button type="submit" name="sub" class="btn btn-success">完了</button>
    </div>
</form>
