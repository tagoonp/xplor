$(document).ready(function(){
  $('#loginAlert').css("display","none");

  $('#loginForm').submit(function(){
    $check = 0;
    $(".exp-form").removeClass("has-error");

    if (!$("#txtUsername").val()) {
      $("#txtUsername").addClass("exp-has-error");
      $check++;
    }

    if (!$("#txtPassword").val()) {
      $("#txtPassword").addClass("exp-has-error");
      $check++;
    }

    if($check!=0){
      $('#loginAlert').css("display","block");
    }else{
      $.post("core/authentication.php", {
              username: $("#txtUsername").val(),
              password: $("#txtPassword").val()
              },
              function(result){
                if(result=='Y'){
                  window.location = 'core/redirect-user.php';
                }else{
                  alert('Invalid username or password!');
                }
              }
      );
      // End post
    }

    return false;
  });
});
