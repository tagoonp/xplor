<?php
session_start();
error_reporting(0);

include "../../library/database/database.class.php";
$db = new database();
$db->connect();


$tbprefix = $db->getTablePrefix();
$sessionName = $db->getSessionname();

$strSQL = sprintf("SELECT * FROM ".$tbprefix."database WHERE dbs_id = '%s' ",mysql_real_escape_string($_POST['id']));
$resultCheckrecord = $db->select($strSQL,false,true);

if($resultCheckrecord){
  $strSQL = sprintf("UPDATE ".$tbprefix."database SET dbs_activestatus = '%s' WHERE 1", mysql_real_escape_string("No"));
  $resultUpdate = $db->update($strSQL);

  $strSQL = sprintf("UPDATE ".$tbprefix."database
            SET dbs_activestatus = '%s'
            WHERE dbs_id = '%s' ",mysql_real_escape_string($_POST['to']), mysql_real_escape_string($_POST['id']));
  $resultUpdate = $db->update($strSQL);

  print "Y";
}else{
  print "N";
}

$db->disconnect();


?>
