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
          <h2>Parameters</h2>
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="param.php" title="All parameter list"><i class="fa fa-bars"></i> All</a></li>
            <li role="presentation" class="active"><a href="selected_param.php" title="Selected parameters"><i class="fa fa-check"></i> Selected</a></li>
          </ul>
          <div class="col-md-12" style="padding-top: 20px;">
            <h4><i class="fa fa-table"></i> Selected parameters</h4>
            <div class="widget-content padded clearfix">
                  <table class="table table-bordered table-striped" id="dataTable1">
                    <thead>
                      <th class="check-header hidden-xs">
                        #
                      </th>
                      <th>
                        Parameter's name
                      </th>
                      <th class="hidden-xs">
                        Data type
                      </th>

                      <th>
                        Description
                      </th>
                      <th class="hidden-xs" style="width: 50px;">
                        Config status
                      </th>
                      <th>
                        Config type
                      </th>
                      <th></th>
                    </thead>
                    <tbody>
                      <?php
                      $resultEnabledvar = false;
                      $strSQL = "SELECT * FROM ".$tbprefix."parameter WHERE par_db_id = '".$resultDataconnect[0]['dbs_id']."' and par_status = 'Yes'";
                      $resultEnabledvar = $db->select($strSQL,false,true);


                      $source = new database_source();
                      // $source->connect();

                      if($resultEnabledvar){
                        $c = 1;
                        foreach($resultEnabledvar as $v){
                          ?>
                          <tr>
                            <td class="check hidden-xs">
                              <?php print $c; $c++; ?>
                            </td>
                            <td>
                              <?php print $v['par_name']; ?>
                            </td>
                            <td class="hidden-xs">
                              <?php
                                $strSQL = "SELECT data_type, character_maximum_length, COLUMN_COMMENT
                                            FROM `INFORMATION_SCHEMA`.`COLUMNS`
                                            WHERE `TABLE_SCHEMA`='".$resultDataconnect[0]['dbs_dbname']."'
                                                  AND `TABLE_NAME`='".$resultDataconnect[0]['dbs_tbname']."'
                                                  AND `COLUMN_NAME` = '".$v['par_name']."'";

                                $result = $source->select($strSQL,false,true);
                                if($result){
                                  print $result[0]['data_type'];
                                }
                                ?>
                            </td>

                            <td>
                              <?php
                              $strSQL = "SELECT * FROM ".$tbprefix."selectedparameter WHERE par_id = '".$v['par_id']."'";
                              $resultInfo = $db->select($strSQL,false,true);
                              
                              if($resultInfo){
                                print $resultInfo[0]['spar_full'];
                              }
                              ?>
                            </td>

                            <td class="hidden-xs text-center">
                              <?php


                              if($resultInfo){
                                ?><i class="fa fa-check-square"></i><?php
                              }
                              ?>
                            </td>
                            <td>
                              <?php
                              if($resultInfo){
                                if($resultInfo[0]['spar_conv']=='0'){
                                  print "Org.";
                                }else{
                                  print "Factor";

                                  if($resultInfo[0]['spar_disc']=='No'){
                                    // print "Org.";
                                  }else{
                                    print ", Discritize";

                                    if($resultInfo[0]['spar_disctype']=='Range'){
                                      print ", Range";
                                    }else{
                                      print ", Value";
                                    }
                                  }
                                }


                              }else{
                                print "-";
                              }
                              ?>
                            </td>

                            <td class="actions">
                              <div class="action-buttons text-center">
                                <a class="table-actions" href=""><i class="fa fa-search"></i></a>&nbsp;&nbsp;
                                <a class="table-actions" href="conf_param1.php?id=<?php print $v['par_id']; ?>"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                                <a class="table-actions" href=""><i class="fa fa-trash-o"></i></a>
                              </div>
                            </td>
                          </tr>
                          <?php
                        }
                      }else{
                        ?>
                        <tr>
                          <td colspan="7">
                            No selected parameters
                          </td>
                        </tr>
                        <?php
                      }

                      ?>
                    </tbody>
                  </table>
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
  <script type="text/javascript" src="js/connection_list.js"></script>
</html>
