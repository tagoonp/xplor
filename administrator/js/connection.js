$(function(){
  $('#datasetForm').submit(function(){
    $check=0;
    if($('#txtHostname').val()==''){
      $check++;
      $('#reqHostname').addClass('has-error');
    }

    if($('#txtDbname').val()==''){
      $check++;
      $('#reqDbname').addClass('has-error');
    }

    if($('#txtDbusername').val()==''){
      $check++;
      $('#reqDbusername').addClass('has-error');
    }

    if($('#txtTablename').val()==''){
      $check++;
      $('#reqTablename').addClass('has-error');
    }

    if($check!=0){
      $('#alertDiv').show();
    }else{
      $('#alertDiv').hide();
      if($('#txtID').val()==""){
        $.post("controller/insertdatabase.php", {
            hostname: $("#txtHostname").val(),
            dbname: $("#txtDbname").val(),
            dbuser: $("#txtDbusername").val(),
            dbpassword: $("#txtDbpassword").val(),
            dbtable: $("#txtTablename").val()
          },function(result){
            if(result=='Y'){
              window.location = 'connection.php';
              return false;
            }else{
              alert('Can not add database');
              return false;
            }
          });
        return false;
      }else{
        $.post("controller/updatedatabase.php", {
            ids: $("#txtID").val(),
            hostname: $("#txtHostname").val(),
            dbname: $("#txtDbname").val(),
            dbuser: $("#txtDbusername").val(),
            dbpassword: $("#txtDbpassword").val(),
            dbtable: $("#txtTablename").val()
          },function(result){
            if(result=='Y'){
              window.location = 'connection_list.php';
              return false;
            }else{
              alert(result);
              return false;
            }
          });
        return false;
      }
    }

    return false;
  });
});
