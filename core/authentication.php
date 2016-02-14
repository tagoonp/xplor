<?php
session_start();
include "../library/database/database.class.php";
$db = new database();
$db->connect();

$tbprefix = $db->getTablePrefix();
$sessionName = $db->getSessionname();

$strSQL = sprintf("SELECT * FROM ".$tbprefix."user WHERE username = '%s' and password = '%s' and active_status = '%s' ",mysql_real_escape_string($_POST['username']),mysql_real_escape_string(md5($_POST['password'])),mysql_real_escape_string('Yes'));
$resultAuthen = $db->select($strSQL,false,true);

if($resultAuthen){
  $_SESSION[$sessionName.'sessID'] = session_id();
  $_SESSION[$sessionName.'sessUsername'] = $resultAuthen[0]['username'];
  $_SESSION[$sessionName.'sessUtype'] = $resultAuthen[0]['usertype_id'];
  session_write_close();
  print "Y";
}else{
  print "N";
}

$db->disconnect();


?>
