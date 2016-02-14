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

$resulteditrecord = false;
$id = '';
if(isset($_GET['id'])){
  $id = $_GET['id'];
  if($id!=''){
    $strSQL = sprintf("SELECT * FROM ".$tbprefix."database WHERE dbs_id = '%s' ", mysql_real_escape_string($id));
    $resulteditrecord = $db->select($strSQL,false,true);
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
          <h2>Datasource connection</h2>
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="connection.php">New connection</a></li>
            <li role="presentation"><a href="connection_list.php">Connection list</a></li>
          </ul>

          <div class="col-md-12" style="padding-top: 20px;">
            <h4><i class="fa fa-link"></i> Create new connection</h4>
            <div class="alert alert-danger" role="alert" id="alertDiv" style="display:none;">
              <strong>Sorry!</strong> Please complete all required field.
            </div>
            <form name="datasetForm" id="datasetForm" class="form-horizontal">
                      <div class="form-group" style="display:<?php if($resulteditrecord){}else{print "none";}?>;">
                        <label class="control-label col-sm-3">ID <span style="color: red;"> **</span></label>
                        <div class="col-sm-9">
                          <input class="form-control" type="text" name="txtID" id="txtID" <?php if($resulteditrecord){ print "readonly"; } ?> <?php if($resulteditrecord){ print "value=".$resulteditrecord[0]['dbs_id']; } ?>>
                          <div class="label-comment">
                            ID of database
                          </div>
                        </div>
                      </div>

                      <div class="form-group" id="reqHostname">
                        <label class="control-label col-md-3">Host name <span style="color: red;"> **</span></label>
                        <div class="col-md-9">
                          <input class="form-control" type="text" name="txtHostname" id="txtHostname" <?php if($resulteditrecord){ print "value=".$resulteditrecord[0]['dbs_hostname']; } ?>  >
                          <div class="label-comment">
                            Enter a remote hostname or IP address of database's host.
                          </div>
                        </div>
                      </div>

                      <div class="form-group" id="reqDbname">
                        <label class="control-label col-md-3">Database name <span style="color: red;"> **</span></label>
                        <div class="col-md-9">
                          <input class="form-control" type="text" name="txtDbname" id="txtDbname" <?php if($resulteditrecord){ print "value=".$resulteditrecord[0]['dbs_dbname']; } ?>>
                          <div class="label-comment">
                            Enter a remote database name
                          </div>
                        </div>
                      </div>

                      <div class="form-group" id="reqDbusername">
                        <label class="control-label col-md-3">Database username <span style="color: red;"> **</span></label>
                        <div class="col-md-9">
                          <input class="form-control" type="text" name="txtDbusername" id="txtDbusername" <?php if($resulteditrecord){ print "value=".$resulteditrecord[0]['dbs_username']; } ?>>
                          <div class="label-comment">
                            Enter a remote database username
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3">Database password</label>
                        <div class="col-md-9">
                          <input class="form-control" type="password" name="txtDbpassword" id="txtDbpassword" >
                          <div class="label-comment">
                            Enter a remote database password
                          </div>
                        </div>
                      </div>

                      <div class="form-group" id="reqTablename">
                        <label class="control-label col-md-3">Datasource table name <span style="color: red;"> **</span></label>
                        <div class="col-md-9">
                          <input class="form-control" type="text" name="txtTablename" id="txtTablename" <?php if($resulteditrecord){ print "value=".$resulteditrecord[0]['dbs_tbname']; } ?>>
                          <div class="label-comment">
                            Enter a remote datasource table name.
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3"></label>
                        <div class="col-md-9">
                          <button type="submit" name="button" class="btn btn-primary">Save</button>
                          <button type="reset" name="button" class="btn btn-primary">Reset</button>
                        </div>
                      </div>

                    </form>

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
  <script type="text/javascript" src="js/connection.js"></script>
</html>
