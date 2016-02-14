<?php
session_start();
error_reporting(0);

include "../../library/database/database.class.php";
$db = new database();
$db->connect();


$tbprefix = $db->getTablePrefix();
$sessionName = $db->getSessionname();

$strSQL = "SELECT * FROM ".$tbprefix."setupinfo WHERE 1";
$resultCheck = $db->select($strSQL,false,true);

$strSQL = "SELECT * FROM ".$tbprefix."database WHERE dbs_activestatus = 'Yes'";
$resultDataconnect = $db->select($strSQL,false,true);

if(!$resultDataconnect){
  alert('No database connected!');
  exit();
}

$strSQL = sprintf("SELECT * FROM ".$tbprefix."database WHERE dbs_id = '%s' ",mysql_real_escape_string($resultDataconnect[0]['dbs_id']));
$resultCheckrecord = $db->select($strSQL,false,true);

// $resultCheckrecord = true;
if($resultCheckrecord){
  $strSQL = sprintf("SELECT * FROM ".$tbprefix."discritize_range WHERE spa_id = '%s' and dsc_id = '%s' ",mysql_real_escape_string($_POST['spar']), mysql_real_escape_string($_POST['id']));
  $result = $db->select($strSQL,false,true);

  if($result){

    $strSQL = sprintf("UPDATE ".$tbprefix."discritize_range SET dsc_range_ref = '%s' WHERE spa_id = '%s' and dsc_id = '%s' ",mysql_real_escape_string($_POST['to']), mysql_real_escape_string($_POST['spar']), mysql_real_escape_string($_POST['id']));
    $resultUpdate2 = $db->update($strSQL);
    print "Y";
  }else{
    // print "N";
    print $strSQL;
    // print "Recode tabler error!";
    exit();
  }
}else{
  // print "N";
  print $strSQL;
}

$db->disconnect();


?>
