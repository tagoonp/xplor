<?php
session_start();
include "../../library/database/database.class.php";
$db = new database();
$db->connect();

$tbprefix = $db->getTablePrefix();
$sessionName = $db->getSessionname();

$strSQL = sprintf("SELECT * FROM ".$tbprefix."database WHERE hostname = '%s' and dbname = '%s' and tbname = '%s' ",mysql_real_escape_string($_POST['hostname']),mysql_real_escape_string($_POST['dbname']),mysql_real_escape_string($_POST['dbtable']));
$resultCheckduplicate = $db->select($strSQL,false,true);

if($resultCheckduplicate){
  // print "N";
  print "1 ".$strSQL;
}else{
  $strSQL1 = sprintf("INSERT INTO ".$tbprefix."database (dbs_hostname, dbs_username, dbs_password, dbs_dbname, dbs_tbname ) VALUES ('%s', '%s', '%s', '%s', '%s')",
            mysql_real_escape_string($_POST['hostname']),
            mysql_real_escape_string($_POST['dbuser']),
            mysql_real_escape_string(md5($_POST['dbpassword'])),
            mysql_real_escape_string($_POST['dbname']),
            mysql_real_escape_string($_POST['dbtable'])
          );
  $resultInsert = $db->insert($strSQL1,false,true);

  $strSQL = sprintf("SELECT * FROM ".$tbprefix."database WHERE dbs_hostname = '%s' and dbs_dbname = '%s' and dbs_tbname = '%s' ",mysql_real_escape_string($_POST['hostname']),mysql_real_escape_string($_POST['dbname']),mysql_real_escape_string($_POST['dbtable']));
  $resultCheckAvailable = $db->select($strSQL,false,true);

  if($resultCheckAvailable){

    $strSQL = sprintf("SELECT * FROM ".$tbprefix."hash WHERE HID = '%s'",mysql_real_escape_string($_POST['dbpassword']));
    $resultCheckHashAvailable = $db->select($strSQL,false,true);

    if(!$resultCheckHashAvailable){
      $strSQL = sprintf("INSERT INTO ".$tbprefix."hash (HID) VALUE ('%s')", mysql_real_escape_string($_POST['dbpassword']));
      $resultHash = $db->insert($strSQL,false,true);
    }

    print "Y";

  }else{

    print "N";
    // print "2 ".$strSQL1;

  }
}

$db->disconnect();


?>
