$(function(){
  $('#checkbox_all').click(function(){
    if($("#checkbox_all").is(':checked')){
      $(".checkbox_param").prop('checked', true);
    }else{
      $(".checkbox_param").prop('checked', false);
    }
  });

  $('.checkbox_param').click(function(){
    if($("#checkbox_all").is(':checked')){
      $("#checkbox_all").prop('checked', false);
    }
  });

  $('#btnBack2Selectlist').click(function(){
    window.location = 'selected_param.php';
  });

  $('#changetofactor').change(function(){
    if($('#changetofactor').val()==1){
      $('#discritize').show();
      $('#labeltovalue1').show();
    }else{
      $('#discritize').hide();
      $('#labeltovalue1').hide();
    }
  });

  $('#disby').change(function(){
    if($('#disby').val()=='Range'){
      $('#byrangediv').show();
      $('#byvaludiv').hide();
      $('#txtValue').val('');
    }else{
      $('#byrangediv').hide();
      $('#byvaludiv').show();
      $('#startRange').val('');
      $('#endRange').val('');
    }
  });

  $('#option2').click(function(){
    $('#labeltovalue1').hide();
    $('#discritizeby').show();
  });

  $('#option1').click(function(){
    $('#labeltovalue1').show();
    $('#discritizeby').hide();
  });

  // When click save management
  $('#btnSaveRecode').click(function(){
    $check = 0;
    $('.form-control').removeClass('has-error');

    if($('#txtShortDesc').val()==''){
      $('#txtShortDesc').addClass('has-error');
      $check++;
    }

    if($('#txtFullDesc').val()==''){
      $('#txtFullDesc').addClass('has-error');
      $check++;
    }

    if($('#changetofactor').val()=="1"){
      // If discritize is Yes
      if($("input[name='optionsRadios1']:checked").val()=="Yes"){
        // If range
        if($('#disby').val()=="Range"){
          if($('#startRange').val()==''){
            $('#startRange').addClass('has-error');
            $check++;
          }

          if($('#endRange').val()==''){
            $('#endRange').addClass('has-error');
            $check++;
          }

          if($('#labelGroupname1').val()==''){
            $('#labelGroupname1').addClass('has-error');
            $check++;
          }
        // If value
        }else{
          if($('#txtValue').val()==''){
            $('#txtValue').addClass('has-error');
            $check++;
          }

          if($('#labelGroupname2').val()==''){
            $('#labelGroupname2').addClass('has-error');
            $check++;
          }
        }
      // If discritize is No
      }else{
        $('input[name="labelValue[]"]').each(function () {
          if($(this).val()==""){
            $(this).addClass('has-error');
            $check++;
          }
        });
      }
    }

    // If no error
    if($check==0){
      // Do not convert to factor
      if($('#changetofactor').val()=="0"){
        console.log('Part1');
        $.post("controller/insertparameterconfig1.php", {
            txtVarid: $('#txtVarid').val(),
            txtShortDesc: $('#txtShortDesc').val(),
            txtFullDesc: $('#txtFullDesc').val()
          },function(result){
            if(result=='Y'){
              // window.location = 'connection.php';
              window.location = 'var_basicinfo.php?var_id=' + $('#txtVarid').val();
            }else{
              alert(result);
              alert('Can not add database');
            }
        });
        // End post
      }else{  // Conver to factor
        // If discritize
        console.log('Part2');
        if($("input[name='optionsRadios1']:checked").val()=="Yes"){
          console.log('Part2-1');
          // if discritize by range
          if($('#disby').val()=='Range'){
            $check2 = 0;
            if($('#cutRangelabel').val()==""){
              $('#cutRangelabel').addClass('has-error');
              $check2++;
            }

            if($('#cutRangevalue').val()==""){
              $('#cutRangevalue').addClass('has-error');
              $check2++;
            }

            $str = $('#cutRangevalue').val();
            $val = $str.split(",");

            $str = $('#cutRangelabel').val();
            $label = $str.split(",");

            if($label.length >= $val.length){
              $('#cutRangevalue').addClass('has-error');
              $('#cutRangelabel').addClass('has-error');
              $check2++;
            }

            if($label.length != ($val.length)-1){
              $('#cutRangevalue').addClass('has-error');
              $('#cutRangelabel').addClass('has-error');
              $check2++;
            }

            if($check2==0){
              $.post("controller/insertparameterconfig3.php", {
                  txtVarid: $('#txtVarid').val(),
                  txtShortDesc: $('#txtShortDesc').val(),
                  txtFullDesc: $('#txtFullDesc').val(),
                  cutRangelabel: $('#cutRangelabel').val(),
                  cutRangevalue: $('#cutRangevalue').val()
                },function(result){
                  if(result=='Y'){
                    window.location = 'var_basicinfo.php?var_id=' + $('#txtVarid').val();
                  }else{
                    alert(result);
                  }
              }); //End post

              return false;
            }else{

            }


          }
          //By value
          else{

          }

        }
        // If not discritize
        else
        {
          console.log('Part2-2');
          $label = [];
          $origin = [];
          $desc = [];
          $('input[name="labelValue[]"]').each(function () {
            if($(this).val()!=""){
              $label.push($(this).val());
            }
          });

          $('input[name="labelOrigine[]"]').each(function () {
            if($(this).val()!=""){
              $origin.push($(this).val());
            }
          });

          $('input[name="labelDesc[]"]').each(function () {
            if($(this).val()!=""){
              $desc.push($(this).val());
            }
          });


          $.post("controller/insertparameterconfig2.php", {
              txtVarid: $('#txtVarid').val(),
              txtShortDesc: $('#txtShortDesc').val(),
              txtFullDesc: $('#txtFullDesc').val(),
              origin: $origin,
              label: $label,
              desc: $desc
            },function(result){
              // alert($origin.length);
              if(result=='Y'){
                window.location = 'conf_param3.php?var_id=' + $('#txtVarid').val();
              }else{
                alert(result);
                // alert('Can not add database');
              }
          });

          return false;
        }

      }

    }
    // If there is some error
    else
    {

    }
  });
});

function toggleStatus(param, id, to, prev){
  // alert(param);
  $.post("controller/togglereference.php", {
      spar: param,
      id: id,
      to: to
    },function(result){
      if(result=='Y'){
        window.location = 'conf_param3.php?id=' + prev.toString();
      }else{
        alert('Can not chage status');
        alert(result);
        return false;
      }
    });
}

function toggleReference2(record_id, val, spar_id, prev){
  // alert(param);
  $.post("controller/togglereference2.php", {
      spar: spar_id,
      id: record_id,
      to: val
    },function(result){
      if(result=='Y'){
        window.location = 'conf_param3.php?id=' + prev.toString();
      }else{
        alert('Can not chage status');
        alert(result);
        return false;
      }
    });
}

function toggleSelection(par_name, to){
  var myCheckboxes = new Array();
  myCheckboxes.push(par_name);

  $.post("controller/insertparameter.php", {
            param: myCheckboxes,
            to: to
          },function(result){
            if(result=="Y"){
              window.location = 'param.php';
            }else{
              alert(result);
              alert('Can not add database');
            }
          });
}
