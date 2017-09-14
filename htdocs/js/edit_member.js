$(function(){

    var isChanged = false;

    var assign = function() {
        var id = $('#num').val();
        var name = $('#nam').val();
        var route = $('#roc').val();
        var row = '<tr>';
        row += '<td>' + id;
        row += '<input type="hidden" value="' + id + '" name="data[id][]">';
        row += '</td>';
        row += '<td>' + name;
        row += '<input type="hidden" value="' + name + '" name="data[name][]">';
        row += '</td>';
        row += '<td>' + route;
        row += '<input type="hidden" value="' + route + '" name="data[route][]">';
        row += '</td>';
        row += '<td><input type="button" class="btn del" value="×"></td>';
        row += '</tr>';
        $('#members').append(row);
        $('#num').val('');
        $('#nam').val('');
        isChanged = true;
    };

    // メンバーの追加処理
    $('#assign-next').on('click', function(){
        assign();
    });
    $('#assign').on('click', function(){
        assign();
        $('#modal').modal('hide');
    });

    // プラグインの初期化
    $('.routes').select2({
        width: 'resolve'
    });

    // メンバー一行削除
    $(document).on('click', '.del', function(){
        $(this).parent().parent().remove();
        isChanged = true;
    });

    // 離脱確認
    var isSubmit = false;
    $(window).on('beforeunload', function () {
        if (!isSubmit && isChanged) {
            return "変更が確定されていません。";
        }
    });

    $("button[type=submit]").click(function(){
        isSubmit = true;
    });
});
