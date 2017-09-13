<div class="container">
    <h2 class="title">グループ一覧</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>グループ名</th>
                <th>通知先</th>
                <th>アドレス</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['groups'] as $group) { ?>
                <tr>
                    <td><?= $group['group_id'] ?></td>
                    <td><?= $group['group_name'] ?></td>
                    <td><?= $group['owner_name'] ?></td>
                    <td><?= $group['mail'] ?></td>
                    <td><a class="btn btn-default btn-sm" href="/groups/edit/<?= $group['group_id'] ?>">編集</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="/groups/add" class="btn btn-default">追加</a>
</div>
