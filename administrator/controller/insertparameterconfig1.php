<?php
session_start();
// include "../../configuration/database.class.php";
include "../../library/database/database.class.php";
$db = new database();
$db->connect();

$tbprefix = $db->getTablePrefix();
$sessionName = $db->getSessionname();

//Par id
$param = $_POST['txtVarid'];

$strSQL = sprintf("SELECT * FROM ".$tbprefix."parameter WHERE par_id = '%s' ",mysql_real_escape_string($param));
$resultCheckAvailableDatabase = $db->select($strSQL,false,true);

if(!$resultCheckAvailableDatabase){
  print "Parameter not found!";
  exit();
}

$strSQL = sprintf("SELECT spar_id FROM ".$tbprefix."selectedparameter WHERE par_id = '%s' ",mysql_real_escape_string($param));
$resultAvaiSelectedPar = $db->select($strSQL,false,true);

// ถ้ามี select parameter นี้อยู่แล้ว
if($resultAvaiSelectedPar){

}
// ถ้ายังไม่มี
else
{
    $strSQL = sprintf("INSERT INTO ".$tbprefix."selectedparameter VALUE ('','%s','%s','0','No','None','".$param."')", mysql_real_escape_string($_POST['txtShortDesc']), mysql_real_escape_string($_POST['txtFullDesc']));
    $resultInsert = $db->insert($strSQL,false,true);

    $strSQL = "SELECT spar_id FROM ".$tbprefix."selectedparameter WHERE par_id = '".$param."'";
    $resultSelectPar2 = $db->select($strSQL,false,true);

    // ถ้าบันทึกได้
    if($resultSelectPar2){
      print "Y";
    }else{
      print "Error-2";
    }
}



$db->disconnect();


?>
