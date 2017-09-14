$(function(){
  $("#mail-test").click(function(){
      alert("メールが送信されました");
    var mail = $('#owner-mail').val();
        $.ajax({
            type: "POST",
            url: "/groups/ajax_confirm_mail",
            data: {
                "mail": mail
            },
            success: function(response){
                // 成功時
            }
        });
    });
});
