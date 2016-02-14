<?php
session_start();
include "../library/database/database.class.php";
include "../library/database/database-datasource.class.php";
include "../core/statistic/statistic.function.php";

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
    <link href="../libraries/seven7/stylesheets/style.css" media="all" rel="stylesheet" type="text/css" />
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
            <li role="presentation"><a href="conf_param1.php?id=<?php print $id; ?>" title="Basic information"><i class="fa fa-info"></i> Info</a></li>
            <li role="presentation" class="active"><a href="conf_param2.php?id=<?php print $id; ?>" title="Basic statistic and Distribution"><i class="fa fa-bar-chart"></i> Statistics</a></li>
            <li role="presentation"><a href="conf_param3.php?id=<?php print $id; ?>" title="Configuration"><i class="fa fa-wrench"></i> Config</a></li>
          </ul>
          <div class="col-md-12" style="padding-top: 20px;">
            <h4><i class="fa fa-bar-chart-o"></i>&nbsp;&nbsp;<strong>Basic statistic information</strong></h4>

                    <div class="row">
                      <div class="col-md-12">
                        <table class="table table-bodered table-responsive">
                          <thead>
                            <th>
                              Var.name
                            </th>
                            <th>
                              Obs.
                            </th>
                            <th>
                              Mean
                            </th>
                            <th>
                              Median
                            </th>
                            <th>
                              s.d.
                            </th>
                            <th>
                              Min
                            </th>
                            <th>
                              Max
                            </th>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                <?php print $resultParam[0]['par_name']; ?>
                              </td>
                              <td>
                                <?php
                                $source = new database_source();
                                $source->connect($resultDataconnect[0]['dbs_hostname'], $resultDataconnect[0]['dbs_username'], $resultDataconnect[0]['dbs_password'], $resultDataconnect[0]['dbs_dbname']);

                                $strSQL = "SELECT count(".$resultParam[0]['par_name'].") as obsc FROM ".$resultDataconnect[0]['dbs_tbname']." WHERE 1";
                                $result = $source->select($strSQL,false,true);
                                // print $strSQL;
                                if($result){
                                  print $result[0]['obsc'];
                                  $n = $result[0]['obsc'];
                                }else{
                                  print "0 (No result)";
                                }
                                ?>
                              </td>
                              <td>
                                <?php
                                $strSQL = "SELECT ".$resultParam[0]['par_name']." as v FROM ".$resultDataconnect[0]['dbs_tbname']." WHERE 1";
                                $result = $source->select($strSQL,false,true);

                                $x = 0;
                                $mean = 0;
                                $data = array();

                                if($result){
                                  foreach ($result as $v) {
                                    array_push($data, $v['v']);
                                  }
                                  print number_format(mmmr($data, 'mean'), 2, '.', '');
                                }else{
                                  print "N/A";
                                }
                                ?>
                              </td>
                              <td>
                                <?php
                                if($result){
                                  print mmmr($data, 'median');
                                }else{
                                  print "N/A";
                                }
                                ?>
                              </td>
                              <td>
                                <?php
                                if($result){
                                  print number_format(standard_deviation($data), 2, '.', '');
                                }else{
                                  print "N/A";
                                }
                                ?>
                              </td>
                              <td>
                                <?php
                                $strSQL = "SELECT MIN(".$resultParam[0]['par_name'].") as v FROM ".$resultDataconnect[0]['dbs_tbname']." WHERE 1";
                                $result = $source->select($strSQL,false,true);
                                if($result){
                                  print $result[0]['v'];
                                }else{
                                  print "N/A";
                                }
                                ?>
                              </td>
                              <td>
                                <?php
                                $strSQL = "SELECT MAX(".$resultParam[0]['par_name'].") as v FROM ".$resultDataconnect[0]['dbs_tbname']." WHERE 1";
                                $result = $source->select($strSQL,false,true);
                                if($result){
                                  print $result[0]['v'];
                                }else{
                                  print "N/A";
                                }
                                ?>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
          </div>

          <div class="col-md-12" style="padding-top: 10px;">
            <h4><i class="fa fa-th-large"></i>&nbsp;&nbsp;<strong>Distribution</strong></h4>
            <div class="row">
                      <div class="col-sm-12">
                        <table class="table table-bordered">
                          <thead>
                            <th>

                            </th>
                            <th>
                              Frequency
                            </th>
                            <th>
                              Percent
                            </th>
                            <th class="hidden-xs">
                              Cum. percent
                            </th>
                          </thead>

                          <tbody>
                            <?php
                            $strSQL = "SELECT ".$resultParam[0]['par_name']." as v, count(".$resultParam[0]['par_name'].") as c FROM ".$resultDataconnect[0]['dbs_tbname']." WHERE 1 GROUP BY ".$resultParam[0]['par_name'];
                            $resultGroup = $source->select($strSQL,false,true);

                            $sum = 0;
                            $cumpct = 0;
                            if($resultGroup){
                              foreach($resultGroup as $val){
                                ?>
                                <tr>
                                  <td>
                                    <?php print $val['v']; ?>
                                  </td>
                                  <td>
                                    <?php
                                    print $val['c'];
                                    $sum += intval($val['c']);
                                    ?>
                                  </td>
                                  <td>
                                    <?php
                                    $pct = (intval($val['c']) * 100)/$n;
                                    print number_format($pct, 2, '.', '');
                                    ?>
                                  </td>
                                  <td class="hidden-xs">
                                    <?php
                                    $cumpct += $pct;
                                    print number_format($cumpct, 2, '.', '');
                                    ?>
                                  </td>
                                </tr>
                                <?php
                              }
                            }
                            ?>

                            <tr>
                              <td>
                                <strong>Total :</strong>
                              </td>
                              <td>
                                <?php print $sum; ?>
                              </td>
                              <td>
                                <?php print number_format($cumpct, 2, '.', ''); ?>
                              </td>
                              <td class="hidden-xs">
                                <?php print number_format($cumpct, 2, '.', ''); ?>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
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
