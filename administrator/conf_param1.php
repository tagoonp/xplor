<?php
session_start();
include "../library/database/database.class.php";
include "../library/database/database-datasource.class.php";

$db = new database();
$db->connect();

$tbprefix = $db->getTablePrefix();
$strSQL = "SELECT * FROM ".$tbprefix."setupinfo WHERE 1";
$resultCheck = $db->select($strSQL,false,true);

$strSQL = "SELECT * FROM ".$tbprefix."database WHERE dbs_activestatus = 'Yes'";
$resultDataconnect = $db->select($strSQL,false,true);

if(!$resultDataconnect){
  //header('Location: 404-page.php?msg=Database%20connection%20error!&prev=connection.php');
  print "a";
  exit();
}

// Define var $id and Check GET id
$id = 0;
if(isset($_GET['id'])){
  $id = $_GET['id'];
}
$resultEnabledvar = false;
$strSQL = sprintf("SELECT * FROM ".$tbprefix."parameter WHERE par_db_id = '%s' and par_status = 'Yes' and par_id = '%s'"
          , mysql_real_escape_string($resultDataconnect[0]['dbs_id'])
          , mysql_real_escape_string($id));
$resultParam = $db->select($strSQL,false,true);

$paramID = '';
if(!$resultParam){
  // header('Location: 404-page.php?msg=Database%20connection%20error!&prev=connection.php');
  print "No resultParam<br>";
  print $strSQL;
  exit();
}else{
  $paramID = $resultParam[0]['par_id'];
}

$strSQL = sprintf("SELECT * FROM ".$tbprefix."selectedparameter WHERE par_id = '%s' ",mysql_real_escape_string($paramID));
$resultAvaiSelectedPar = $db->select($strSQL,false,true);

$resultDizRange = false;
if($resultAvaiSelectedPar){
  if($resultAvaiSelectedPar[0]['spar_disctype']=='Range'){
    $strSQL = sprintf("SELECT * FROM ".$tbprefix."discritize_range WHERE spa_id = '%s' ",mysql_real_escape_string($resultAvaiSelectedPar[0]['spar_id']));
    $resultDizRange = $db->select($strSQL,false,true);
  }
}


?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Xplor : Administrator</title>
    <link href='https://fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../library/xplor/css/style.css" >
    <script type="text/javascript" src="../library/jquery/jquery.js"></script>
    <link rel="stylesheet" href="../library/bootstrap/css/bootstrap.css" >
    <script src="../library/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../library/font-awesome/css/font-awesome.min.css">
    <!-- <link href="../libraries/seven7/stylesheets/style.css" media="all" rel="stylesheet" type="text/css" /> -->
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
                    <li><a href="finalize.php">Finalize</a></li>
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
          <h2>Parameter configuration</h2>
          <div class="row" style="padding-bottom: 20px;">
            <div class="col-md-12">
              <button type="button" name="button" id="btnBack2Selectlist" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> Back to selected parameters list</button>
            </div>
          </div>

          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="conf_param1.php?id=<?php print $id; ?>" title="Basic information"><i class="fa fa-info"></i> Info</a></li>
            <li role="presentation"><a href="conf_param2.php?id=<?php print $id; ?>" title="Basic statistic and Distribution"><i class="fa fa-bar-chart"></i> Statistics</a></li>
            <li role="presentation"><a href="conf_param3.php?id=<?php print $id; ?>" title="Configuration"><i class="fa fa-wrench"></i> Config</a></li>
          </ul>
          <div class="col-md-12" style="padding-top: 20px;">
            <h4><i class="fa fa-bars"></i>&nbsp;&nbsp;Variables information (original)</h4>
                    <div class="row">
                      <div class="col-sm-6 text-left">
                        Variable name :
                      </div>
                      <div class="col-sm-6">
                        <?php print $resultParam[0]['par_name']; ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-6 text-left">
                        Current data type :
                      </div>
                      <div class="col-sm-6">
                        <?php
                        $source = new database_source();
                        $source->connect($resultDataconnect[0]['dbs_hostname'], $resultDataconnect[0]['dbs_username'], $resultDataconnect[0]['dbs_password'], $resultDataconnect[0]['dbs_dbname']);

                        $n = 0;
                        $strSQL = "SELECT data_type, character_maximum_length, COLUMN_COMMENT
                                    FROM `INFORMATION_SCHEMA`.`COLUMNS`
                                    WHERE `TABLE_SCHEMA`='".$resultDataconnect[0]['dbs_dbname']."'
                                          AND `TABLE_NAME`='".$resultDataconnect[0]['dbs_tbname']."'
                                          AND `COLUMN_NAME` = '".$resultParam[0]['par_name']."'";

                        $result = $source->select($strSQL,false,true);
                        if($result){
                          print $result[0]['data_type'];
                        }else{
                          print "N/A";
                        }
                        ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 text-left">
                        Length :
                      </div>
                      <div class="col-md-6">
                        <?php
                        if($result){
                          print $result[0]['character_maximum_length'];
                        }else{
                          print "N/A";
                        }
                        ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-6 text-left">
                        Comment:
                      </div>
                      <div class="col-sm-6">
                        <?php
                        if($result){
                          print $result[0]['COLUMN_COMMENT'];
                        }else{
                          print "N/A";
                        }
                        ?>
                      </div>
                    </div>
                    <!-- End row -->
          </div>

        </div>



      </div>
      <hr>
      <div class="exp-footer text-center" style="">
        Copyright © 2015 - <font class="ccolor-1">Xplor</font> : Factor visulization tools | All Rights Reserved
      </div>
    </div>

  </body>
  <script type="text/javascript" src="../library/jquery/jquery.js"></script>
  <script type="text/javascript" src="js/param.js"></script>
</html>
