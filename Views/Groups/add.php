<div class="container">
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
            <input type="text" class="form-control"　id="owner-mail"  name="data[owner][mail]" placeholder="sic@ecc.ac.jp" >
            <p class="help-block">このメールアドレス宛に、遅延情報が通知されます。</p>
        </div>
        <button type="submit" name="sub" class="btn btn-success">完了</button>
    </form>
    <button type="button" id="btn" class="btn btn-default">btn</button>
</div>
<script type="text/javascript">
    $(function(){
        var owners = <?= json_encode($data['owners']) ?>;
        $('#btn').on('click', function(){
            console.log('click');
        });
        $('#owner-name').on('click', function(){
            console.log("click");
            var length = owners.length
            for (var i = 0; i < length; i++) {
                console.log(owners[i]);
                if ($(this).val == owners[i]['mail']) {
                    $('#owner-mail').val(owners[i]['mail']);
                }
            }
        });
    });
</script>
