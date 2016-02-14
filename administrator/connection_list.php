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
  $strSQL = sprintf("SELECT * FROM ".$tbprefix."database WHERE dbs_id = '%s' ", mysql_real_escape_string($id));
  $resulteditrecord = $db->select($strSQL,false,true);
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
            <li role="presentation"><a href="connection.php">New connection</a></li>
            <li role="presentation" class="active"><a href="connection_list.php">Connection list</a></li>
          </ul>
          <div class="col-md-12" style="padding-top: 20px;">
            <h4><i class="fa fa-table"></i> Connection list</h4>
            <div class="widget-content padded clearfix">
                  <table class="table table-bordered table-striped" id="dataTable1">
                    <thead>
                      <th class="check-header hidden-xs">
                        #
                      </th>
                      <th>
                        Hostname
                      </th>
                      <th class="hidden-xs">
                        Database name
                      </th>
                      <th class="hidden-xs">
                        Connection status
                      </th>
                      <th class="hidden-xs">
                        Active status
                      </th>
                      <th></th>
                    </thead>
                    <tbody>
                      <?php
                      $strSQL = "SELECT * FROM ".$tbprefix."database WHERE 1";
                      $resultList = $db->select($strSQL,false,true);

                      include "../library/database/database-datasource.class.php";
                      $source = new database_source();

                      if($resultList){
                        $c = 1;
                        foreach($resultList as $v){
                          ?>
                          <tr>
                            <td class="check hidden-xs">
                              <?php print $c; $c++; ?>
                            </td>
                            <td>
                              <?php print $v['dbs_hostname']; ?>
                            </td>
                            <td class="hidden-xs">
                              <?php print $v['dbs_dbname']; ?>
                            </td>
                            <td class="hidden-xs">
                              <?php
                              if($v['dbs_activestatus']=='Yes'){
                                $connect = $source->checkConnection($v['dbs_hostname'], $v['dbs_dbname'], $v['dbs_username'], $v['dbs_password']);

                                if($connect){
                                  $strSQL = "SELECT * FROM ".$v['dbs_tbname']." WHERE 1 limit 0,3";
                                  // $strSQL = "SELECT * FROM tb_das WHERE 1 limit 0,3";
                                  $resultCheck = $db->select($strSQL,false,true);
                                  if($resultCheck){
                                    ?><font color="#60c560">Connection success!</font><?php
                                  }else{
                                    ?><font color="red">Connect to data table fail!</font><?php
                                  }

                                }else{
                                  ?><font color="red">Connection fail!</font><?php
                                }

                              }else{
                                ?>
                                <span style="background:none; color: #ccc;">Not applicable</span>
                                <?php
                              }
                               ?>
                            </td>
                            <td class="hidden-xs">
                              <?php
                                if($v['dbs_activestatus']=='Yes'){
                                  ?>
                                  <a href="javascript: toggleStatus('<?php print $v['dbs_id'];?>','No')"><span class="label label-success">Active</span></a>
                                  <?php
                                }else{
                                  ?>
                                  <a href="javascript: toggleStatus('<?php print $v['dbs_id'];?>','Yes')"><span class="label label-warning">Disbled</span></a>
                                  <?php
                                }
                              ?>

                            </td>
                            <td class="actions">
                              <div class="action-buttons text-center">
                                <a class="table-actions" href=""><i class="fa fa-search"></i></a>&nbsp;&nbsp;
                                <a class="table-actions" href="connection.php?id=<?php print $v['dbs_id']; ?>"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                                <a class="table-actions" href=""><i class="fa fa-trash-o"></i></a>
                              </div>
                            </td>
                          </tr>
                          <?php
                        }
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
