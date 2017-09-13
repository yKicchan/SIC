<h2>グループ新規作成</h2>
<form action="/groups/add_member" method="post">
    <div class="form-group">
        <label for="group-name">グループ名</label>
        <input type="text" class="form-control" id="group-name" name="data[group]" placeholder="IE4A" >
    </div>
    <div class="form-group">
        <label for="owner-name">担当者名</label>
        <input type="text" class="form-control"　id="owner-name"  name="data[owner][name]" placeholder="ECC 太郎" autocomplete="on" list="owner-names" >
        <datalist id="owner-names">
            <?php foreach ($data['owners'] as $owner) { ?>
                <option value="<?= $owner['name'] ?>">
            <?php } ?>
        </datalist>
    </div>
    <div class="form-group">
        <label for="owner-mail">担当者メールアドレス</label>
        <input type="text" class="form-control"　id="owner-mail"  name="data[owner][mail]" placeholder="sic@ecc.ac.jp" autocomplete="on" list="owner-mails" >
        <p class="help-block">このメールアドレス宛に、遅延情報が通知されます。</p>
        <datalist id="owner-mails">
            <?php foreach ($data['owners'] as $owner) { ?>
                <option value="<?= $owner['mail'] ?>">
            <?php } ?>
        </datalist>
    </div>
    <div class="form-group">
        <a href="/" class="btn btn-default">戻る</a>
        <button type="submit" name="sub" class="btn btn-success">完了</button>
    </div>
</form>
