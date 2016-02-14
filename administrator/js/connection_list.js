function toggleStatus(id, to){
  $.post("controller/togglestatus.php", {
      id: id,
      to: to
    },function(result){
      if(result=='Y'){
        window.location = 'connection_list.php';
      }else{
        alert('Can not add database');
        return false;
      }
    });
}
