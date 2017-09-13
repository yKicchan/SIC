<h2><?= $data['group']['group_name'] ?></h2>
<form action="/groups/detail/<?= $data['group']['group_id'] ?>" method="post">
    <div class="form-group">
        <label for="group-name">グループ名</label>
        <input type="text" class="form-control" id="group-name" name="data[group][name]" value="<?= $data['group']['group_name'] ?>" >
    </div>
    <div class="form-group">
        <label for="owner-name">担当者名</label>
        <input type="text" class="form-control"　id="owner-name"  name="data[owner][name]" value="<?= $data['group']['owner_name'] ?>" autocomplete="on" list="owner-names" >
        <datalist id="owner-names">
            <?php foreach ($data['owners'] as $owner) { ?>
                <option value="<?= $owner['name'] ?>">
            <?php } ?>
        </datalist>
    </div>
    <div class="form-group">
        <label for="owner-mail">担当者メールアドレス</label>
        <input type="text" class="form-control"　id="owner-mail"  name="data[owner][mail]" value="<?= $data['group']['mail'] ?>" autocomplete="on" list="owner-mails" >
        <p class="help-block">このメールアドレス宛に、遅延情報が通知されます。</p>
        <datalist id="owner-mails">
            <?php foreach ($data['owners'] as $owner) { ?>
                <option value="<?= $owner['mail'] ?>">
            <?php } ?>
        </datalist>
    </div>
    <div class="form-group">
        <button type="button" name="del" class="btn btn-danger">削除</button>
        <button type="submit" name="sub" class="btn btn-success right">確定</button>
    </div>
</form>
