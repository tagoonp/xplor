<?php
session_start();
include "../../library/database/database.class.php";
$db = new database();
$db->connect();

$tbprefix = $db->getTablePrefix();
$sessionName = $db->getSessionname();

$strSQL = sprintf("SELECT * FROM ".$tbprefix."database WHERE dbs_id = '%s' ",mysql_real_escape_string($_POST['ids']));
$resultCheckduplicate = $db->select($strSQL,false,true);

if($resultCheckduplicate){

  $strSQL = sprintf("UPDATE ".$tbprefix."database SET
            dbs_hostname = '%s',
            dbs_username = '%s',
            dbs_password = '%s',
            dbs_dbname = '%s',
            dbs_tbname = '%s'
            WHERE dbs_id = '%s'
            ",
            mysql_real_escape_string($_POST['hostname']),
            mysql_real_escape_string($_POST['dbuser']),
            mysql_real_escape_string(md5($_POST['dbpassword'])),
            mysql_real_escape_string($_POST['dbname']),
            mysql_real_escape_string($_POST['dbtable']),
            mysql_real_escape_string($_POST['ids']));

  $resultUpdate = $db->update($strSQL);

  $strSQL = sprintf("INSERT INTO ".$tbprefix."hash VALUE ('','%s') ", mysql_real_escape_string($_POST['dbpassword']));
  $resultUpdate = $db->insert($strSQL ,false, true);

  print "Y";
}else{
  print "N";
  print $strSQL;
}

$db->disconnect();


?>
