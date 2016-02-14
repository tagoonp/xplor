<?php
session_start();
include "../library/database/database.class.php";
$db = new database();
$db->connect();
$sessionName = $db->getSessionname();

switch($_SESSION[$sessionName.'sessUtype']){
  case '01': header('Location: ../administrator/index.php'); break;
  case '02': header('Location: ../general/index.php'); break;
  case '03': header('Location: ../guest/index.php'); break;
}
$db->disconnect();
exit();

?>
