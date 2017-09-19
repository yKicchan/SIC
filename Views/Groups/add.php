<script src="/js/add_group.js" charset="utf-8"></script>
<script src="/js/confirm_mail.js" charset="utf-8"></script>
<h2 class="form-title">グループ新規作成</h2>
<form action="/groups/add" method="post">
    <div class="form-group">
        <label for="group-name">グループ名</label><span class="form-required">必須</span>
        <input type="text" class="form-control" id="group-name" name="data[group][name]" placeholder="IE4A" required>
    </div>
    <div class="form-group">
        <label for="owner-name">通知先名</label><span class="form-required">必須</span>
        <input type="text" class="form-control"　id="owner-name"  name="data[owner][name]" placeholder="ECC 太郎" autocomplete="on" list="owner-names" required>
        <datalist id="owner-names">
            <?php foreach ($data['owners'] as $owner) { ?>
                <option value="<?= $owner['name'] ?>">
            <?php } ?>
        </datalist>
    </div>
    <div class="form-group">
        <label for="owner-mail">通知先メールアドレス</label><span class="form-required">必須</span>
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
        <label>
            <input type="checkbox" name="data[check]" id="schedule">通知スケジュールを設定する
        </label>
        <span class="form-optional">任意</span>
        <div class="schedule">
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
        <p class="help-block">※設定した時間以降は、遅延情報が通知されなくなります。</p>
    </div>
    <div class="form-group">
        <button type="submit" name="sub" class="btn btn-success right">完了</button>
        <a href="/" class="btn btn-default right">戻る</a>
    </div>
</form>
