<h2>グループ一覧<a href="/groups/add" class="btn btn-link right">+新規作成</a></h2>
<table class="table table-striped groups">
    <thead>
        <tr>
            <!-- <th>ID</th> -->
            <th>グループ名</th>
            <th>通知先</th>
            <th>アドレス</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($data['groups']) > 0) { ?>
            <?php foreach ($data['groups'] as $group) { ?>
            <tr>
                <!-- <td><div class="cell"><?= $group['group_id'] ?></div></td> -->
                <td><a href="/groups/detail/<?= $group['group_id'] ?>" class="btn btn-link"><?= $group['group_name'] ?></a></td>
                <td><div class="cell"><?= $group['owner_name'] ?></td>
                <td><div class="cell"><?= $group['mail'] ?></td>
                <td><a class="btn btn-default btn-sm" href="/groups/edit/<?= $group['group_id'] ?>">編集</a></td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">グループがありません</td>
            </tr>
        <?php } ?>
    </tbody>
</table>
