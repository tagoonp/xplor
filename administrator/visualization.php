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
    <link rel="stylesheet" href="../library/xplor/css/style.css" >
    <script type="text/javascript" src="../library/jquery/jquery.js"></script>
    <link rel="stylesheet" href="../library/bootstrap/css/bootstrap.css" >
    <script src="../library/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../library/font-awesome/css/font-awesome.min.css">
    <link href="../library/seven7/stylesheets/style.css" media="all" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../library/d3/d3.min.js"></script>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
  </head>


  <body>

    <div class="left-panel shadow1">
      <div class="">
        <div class="" style="background: #000; padding: 10px; margin: 0px;">
          <div class="" style="padding-top: 10px;">
            <a href="index.php">
              <strong style="font-size:2.3em; line-height: 0px;"><font class="ccolor-1">X</font><font class="ccolor-1">plor</font><small style="font-size: 0.5em;" class="ccolor-2">Visualization</small></strong>
            </a>
          </div>

        </div>
        <div class="method">
          <div class="" style="font-size: 30px; font-weight:bold;">
            <h5 class="ccolor-1">1. Parameter selection method</h5>
          </div>
          <div class="form-group" style="padding: 0px; margin: 0px;">
            <div class="col-md-12" style="padding: 0px; margin: 0px; margin-bottom: 20px;">
              <select class="form-control" id="methodSelection" name="methodSelection">
                <option value="0" selected="">Stepwise</option>
                <option value="1">Forward</option>
                <option value="2">Backward</option>
              </select>
            </div>
            <button type="button" name="button" class="btn btn-primary btn-block" id="btnRun">Run</button>
          </div>
        </div>
        <div class="paramList" style="padding-top: 20px;">
          <div class="" style="padding: 0px 10px ;">
            <div class="" style="font-size: 30px; font-weight:bold;">
              <h5 class="ccolor-1" style=" ">2. Choose parameter</h5>
            </div>
          </div>

          <?php
                    if($resultDataconnect){
                      $strSQL = "SELECT * FROM ".$tbprefix."parameter WHERE par_db_id = '".$resultDataconnect[0]['dbs_id']."' and par_status = 'Yes'";
                      $resultEnabledvar = $db->select($strSQL,false,true);

                      if($resultEnabledvar){
                        foreach($resultEnabledvar as $param){

                              $strSQL = "SELECT * FROM ".$tbprefix."selectedparameter WHERE par_id = '".$param['par_id']."'";
                              $resultParcontent = $db->select($strSQL,false,true);
                              if($resultParcontent){
                                if($resultParcontent[0]['spar_short']!=''){
                                  ?>
                                  <button type="button" name="button" class="btn-param btn btn-primary btn-block" style="border-radius:0px; margin:0px; border:solid; border-width: 0px 0px 1px 0px; border-color: white; text-align: left;"><i class="fa fa-plus"></i><?php print $resultParcontent[0]['spar_short']; ?></button>
                                  <?php
                                }else{
                                  ?>
                                  <button type="button" name="button" class="btn-param btn btn-primary btn-block" style="border-radius:0px; margin:0px; border:solid; border-width: 0px 0px 1px 0px; border-color: white; text-align: left;"><?php print $param['par_name']; ?></button>
                                  <?php

                                }
                              }else{
                                // print $param['par_name'];
                              }

                        }
                      }
                    }
                    ?>
        </div>
        <!-- End param list -->
      </div>

    </div>
    <div class="main-panel">
      <div class="row">
        <div class="col-lg-12">
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#main" data-toggle="tab" title="Basic statistic and Distribution"><i class="fa fa-bar-chart"></i> Visual</a></li>
            <li role="presentation"><a href="#code" data-toggle="tab" title="Configuration"><i class="fa fa-code"></i> Background</a></li>
            <li role="presentation"><a href="#history" data-toggle="tab" title="Configuration"><i class="fa fa-list"></i> History</a></li>
          </ul>

          <div class="tab-content">
            <div id="main" class="tab-pane fade in active">
              <div style="padding: 10px 0px; background: #ccc; position: absolute;
                height: 500px;
                bottom:0px;
                top: 50px;
                left: 10px;
                right: 10px;
                background: #fff;
                padding: 0px;" class="chartResult-panel">

              </div>

            </div>
            <div id="code" class="tab-pane fade">
              <div style="padding: 10px 0px;">
                <h3>Menu 1</h3>
                <p>Some content in menu 1.</p>
              </div>
            </div>
            <div id="history" class="tab-pane fade">
              <div style="padding: 10px 0px;">
                <h3>Menu 2</h3>
                <p>Some content in menu 2.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </body>
  <script type="text/javascript" src="../library/jquery/jquery.js"></script>
  <script type="text/javascript" src="../library/xplor/js/visualization.js"></script>
</html>
