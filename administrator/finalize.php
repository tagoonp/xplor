<?php
session_start();
include "../library/database/database.class.php";
$db = new database();
$db->connect();

$tbprefix = $db->getTablePrefix();
$strSQL = "SELECT * FROM ".$tbprefix."setupinfo WHERE 1";
$resultCheck = $db->select($strSQL,false,true);

$strSQL = "SELECT * FROM ".$tbprefix."database WHERE dbs_activestatus = 'Yes'";
$resultDataconnect = $db->select($strSQL,false,true);

if(!$resultDataconnect){
  header('Location: 404-page.php?msg=Database%20connection%20error!&prev=connection.php');
  exit();
}

$resultEnabledvar = false;
$strSQL = "SELECT * FROM ".$tbprefix."parameter WHERE par_db_id = '".$resultDataconnect[0]['dbs_id']."' and par_status = 'Yes'";
$resultEnabledvar = $db->select($strSQL,false,true);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Xplor : Administrator</title>
    <link href='https://fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css'>
    <!-- <link href="../library/seven7/stylesheets/style.css" media="all" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="../library/xplor/css/style.css" >
    <script type="text/javascript" src="../library/jquery/jquery.js"></script>
    <link rel="stylesheet" href="../library/bootstrap/css/bootstrap.css" >
    <script src="../library/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../library/font-awesome/css/font-awesome.min.css">

    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top" style="background: #000; border:solid; border-width: 0px 0px 1px 0px; border-color: #ccc;">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">
            <strong style="font-size:1.3em;"><font class="ccolor-1">X</font><font class="ccolor-2">plor</font></strong>
          </a>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li ><a href="index.php">Home</a></li>
                <li><a href="about.php">About project</a></li>
                <li class="dropdown active">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Application <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="connection.php" class="a-link">Datasource connection</a></li>
                    <li><a href="param.php">Parameter</a></li>
                    <li><a href="finalize.php">Finalization</a></li>
                  </ul>
                </li>
                <li><a href="visualization.php">Visualization</a></li>
                <li><a href="document.php">Document</a></li>
                <li><a href="contact.php">Contact</a></li>

              </ul>

              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><strong>Welcome</strong> : Administrator <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="info.php">User info.</a></li>
                    <li><a href="update_info.php">Update info.</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="../signout.php">Signout</a></li>
                  </ul>
                </li>
              </ul>
            </div>


      </div>
    </nav>


    <div class="container-fluid">
      <div class="row" style="padding-top:60px;">
        <div class="col-lg-12">
          <h2 style="font-weight:bold;">Finalization</h2>
          <hr>
          <h3>Overview</h3>
          <div class="row">
            <div class="col-md-6">
              <h4>Database configuration check</h4>
              <table class="table table-bordered table-condensed table-striped">
                <tr>
                  <td style="width: 50%;">
                    <strong>Hostname</strong>
                  </td>
                  <td>

                  </td>
                </tr>
                <tr>
                  <td>
                    <strong>Username</strong>
                  </td>
                  <td>

                  </td>
                </tr>
                <tr>
                  <td>
                    <strong>Password</strong>
                  </td>
                  <td>

                  </td>
                </tr>
                <tr>
                  <td>
                    <strong>Database name</strong>
                  </td>
                  <td>

                  </td>
                </tr>
                <tr>
                  <td>
                    <strong>Data table</strong>
                  </td>
                  <td>

                  </td>
                </tr>
              </table>
            </div>
            <!-- End col-md-6 -->

            <div class="col-md-6">
              <h4>Parameter configuration check</h4>
              <table class="table table-bordered table-condensed table-striped">
                <tr>
                  <td style="width: 50%;">
                    <strong>Parameter selection</strong>
                  </td>
                  <td>

                  </td>
                </tr>
                <tr>
                  <td>
                    <strong>Parameter configuration status</strong>
                  </td>
                  <td>

                  </td>
                </tr>
                <tr>
                  <td>
                    <strong>Outcome parameter setting</strong>
                  </td>
                  <td>

                  </td>
                </tr>
                <tr>
                  <td>
                    <strong>Date period setting</strong>
                  </td>
                  <td>

                  </td>
                </tr>
              </table>
            </div>
            <!-- End col-md-6 -->
          </div>
          <!-- End row -->


          <h3>Parameter configuration</h3>

          <?php
          $resultEnabledvar = false;
          $strSQL = "SELECT * FROM ".$tbprefix."parameter WHERE par_db_id = '".$resultDataconnect[0]['dbs_id']."' and par_status = 'Yes'";
          $resultEnabledvar = $db->select($strSQL,false,true);

          $column = array();
          $strSelect = '';
          $strCode = "library(RMySQL)<br>
          library(epicalc)<br>
          zap()<br>
          mydb = dbConnect(MySQL(), user='".$resultDataconnect[0]['dbs_username']."', password='".$resultDataconnect[0]['dbs_password']."', dbname='".$resultDataconnect[0]['dbs_dbname']."', host='".$resultDataconnect[0]['dbs_hostname']."')<br>
          ";
          if($resultEnabledvar){
            foreach ($resultEnabledvar as $value) {
              $column[] = $value['par_name'];
            }

            $colString = implode("," , $column);


            $strSelect = "SELECT $colString FROM ".$resultDataconnect[0]['dbs_tbname']." WHERE WHERE hos_id in (11,18) and status != 0";
          }

          $strCode .= 'rs = dbSendQuery(mydb, "'.$strSelect.'")<br>data = fetch(rs, n=-1)<br>use(data)<br>';
          print $strCode;
          ?>


        </div>



      </div>
      <hr>
      <div class="exp-footer text-center" style="">
        Copyright © 2015 - <font class="ccolor-1">Xplor</font> : Factor visulization tools | All Rights Reserved
      </div>
    </div>

  </body>
  <script type="text/javascript" src="../library/jquery/jquery.js"></script>
  <script type="text/javascript" src="js/finalize.js"></script>
</html>
