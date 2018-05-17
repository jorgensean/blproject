$(document).ready(function(){
  $('textarea, input').bind('change', function() {

    var value = $(this).val();
    var field = $(this).attr("name");
    var session = $("#session_id").val();

    $.ajax({
      type: "POST",
      contentType: "application/json",
      url: '/leads/ajaxRegister/',
      data: JSON.stringify( { "field": field, "value": value, "session": session } ),
      success: function(response){
        res = $.parseJSON(response);
        $("#session_id").val(res.session)
      }
    });
  });
});