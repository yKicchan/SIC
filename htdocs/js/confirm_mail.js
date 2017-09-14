$(function(){
  $("#test").click(function(){

    console.log("とおりました");
    var mail = $('#owner-mail').val();
    console.log(mail);

        $.ajax({
            type: "POST",
            url: "/groups/ajax_confirm_mail",
            data: {
                "mail": mail
            },
            success: function(j_data){

                // 処理を記述
                console.log(j_data);
            }
        });

    });
});
