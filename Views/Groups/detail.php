<h2><?= $data['group']['group_name'] ?><a href="" class="btn right btn-success">メンバー追加</a></h2>
<table class="table table-striped groups">
  <thead>
      <tr>
          <th>ID</th>
          <th>名前</th>
          <th>経路</th>
          <th>アドレス</th>
          <th></th>
      </tr>
  </thead>
  <tbody>

<?php if (count($data['records']) > 0) { ?>
  <?php foreach ($data['records'] as $row) { ?>
    <tr>
      <td><?=$row['member_id']?></td>
      <td><?=$row['name']?></td>
      <td><?=$row['line']?></td>
      <td><?=$row['mail']?></td>
      <td></td>
    </tr>
  <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="5">グループがありません</td>
    </tr>
<?php } ?>
  </tbody>

</table>
