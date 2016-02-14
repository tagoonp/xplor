<?php
session_start();
include "../../configuration/database.class.php";
$db = new database();
$db->connect();

$tbprefix = $db->getTablePrefix();
$sessionName = $db->getSessionname();

$param = $_POST['param'];

$strSQL = sprintf("SELECT dbs_id FROM ".$tbprefix."database WHERE dbs_activestatus = '%s' ",mysql_real_escape_string('Yes'));
$resultCheckAvailableDatabase = $db->select($strSQL,false,true);

if(!$resultCheckAvailableDatabase){
  print "No databse active!";
  exit();
}

$strSQL = sprintf("UPDATE ".$tbprefix."parameter SET par_status = 'No' WHERE par_db_id = '%s'", mysql_real_escape_string($resultCheckAvailableDatabase[0]['dbs_id']));
$resultUpdate = $db->update($strSQL);

$check = 0;
for($i = 0; $i < sizeof($param); $i++){
  $strSQL = sprintf("SELECT par_id FROM ".$tbprefix."parameter WHERE par_db_id = '%s' and par_name = '%s'",mysql_real_escape_string($resultCheckAvailableDatabase[0]['dbs_id']),mysql_real_escape_string($param[$i]));
  $resultCheckduplicate = $db->select($strSQL,false,true);

  if($resultCheckduplicate){
    $strSQL = sprintf("UPDATE ".$tbprefix."parameter SET par_status = 'Yes' WHERE par_id = '%s'", mysql_real_escape_string($resultCheckduplicate[0]['par_id']));
    $resultUpdate = $db->update($strSQL);
  }else{
    $strSQL = sprintf("INSERT INTO ".$tbprefix."parameter VALUE ('','%s','Yes','%s')", mysql_real_escape_string($param[$i]), mysql_real_escape_string($resultCheckAvailableDatabase[0]['dbs_id']));
    $resultInsert = $db->insert($strSQL,false,true);
  }

  $check++;

  if($check==sizeof($param)-1){
    print "Y";
  }else{
    // print "N";
  }
}



$db->disconnect();


?>
