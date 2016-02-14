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
    <!-- <link href="../library/seven7/stylesheets/style.css" media="all" rel="stylesheet" type="text/css" /> -->
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
            <li role="presentation"><a href="conf_param2.php?id=<?php print $id; ?>" title="Basic statistic and Distribution"><i class="fa fa-bar-chart"></i> Statistics</a></li>
            <li role="presentation" class="active"><a href="conf_param3.php?id=<?php print $id; ?>" title="Configuration"><i class="fa fa-wrench"></i> Config</a></li>
          </ul>
          <div class="col-md-12" style="padding-top: 20px;">
            <h4 style="font-weight:bold;"><i class="fa fa-bars"></i>&nbsp;&nbsp;Basic information<br><small>Descripbe more information about this parameter</small></h4>
            <div class="row">
              <div class="col-sm-6">
                <?php
                $source = new database_source();
                $source->connect($resultDataconnect[0]['dbs_hostname'], $resultDataconnect[0]['dbs_username'], $resultDataconnect[0]['dbs_password'], $resultDataconnect[0]['dbs_dbname']);
                ?>
                <form action="#" class="form-horizontal">
                          <h4>Basic information</h4>
                          <div class="form-group" style="display:none;">
                            <label class="col-md-12">Parameter id:  <span style="color:red;">**</span></label>
                            <div class="col-md-12">
                              <input type="text" readonly name="txtVarid" id="txtVarid" class="form-control" placeholder="" value="<?php print $id;?>">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-12">Short information :  <span style="color:red;">**</span></label>
                            <div class="col-md-12">
                              <input type="text" name="txtShortDesc" id="txtShortDesc" class="form-control" placeholder="Enter varaiable description" value="<?php if($resultAvaiSelectedPar){ print $resultAvaiSelectedPar[0]['spar_short']; }?>">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-12">Full information :  <span style="color:red;">**</span></label>
                            <div class="col-md-12">
                              <textarea class="form-control" name="txtFullDesc" id="txtFullDesc" rows="3" placeholder="Enter full description" ><?php if($resultAvaiSelectedPar){ print $resultAvaiSelectedPar[0]['spar_full']; }?></textarea>
                            </div>
                          </div>
                          <p>
                            &nbsp;
                          </p>
                          <h4>Data type convertion</h4>
                          <div class="form-group">
                            <label class="control-label col-md-3"><strong>Convert to :</strong></label>
                            <div class="col-md-9">
                              <select class="form-control" id="changetofactor" name="changetofactor">
                                <option value="0" selected="">Do not change class</option>
                                <option value="1" <?php if($resultAvaiSelectedPar){ if($resultAvaiSelectedPar[0]['spar_conv']==1){ print "selected"; } }?>>Factor</option>
                              </select>
                              <div class="label-comment" style="padding-top: 5px;">
                                - You can change to factor if this variable is not contitues value<br>
                                - Convert to factor if this variaable is group value
                              </div>
                            </div>

                          </div>

                          <div class="" id="discritize" style="display:<?php if($resultAvaiSelectedPar){ if($resultAvaiSelectedPar[0]['spar_conv']=='0'){ print "none"; } }else{ print "none"; }?>;">
                            <p>
                              &nbsp;
                            </p>
                            <h4>Discritizing</h4>
                            <div class="form-group">
                              <label class="control-label col-md-3"><strong>Discritizing</strong></label>
                              <div class="col-md-9">
                                <label class="radio-inline" for="option1">
                                  <input  checked="" id="option1" name="optionsRadios1" type="radio" value="No"><span>No</span>
                                </label>
                                <label class="radio-inline">
                                  <input name="optionsRadios1" type="radio" value="Yes" id="option2" <?php if($resultAvaiSelectedPar){ if($resultAvaiSelectedPar[0]['spar_disc']=='Yes'){ print "checked"; } }?>><span>Yes</span>
                                </label>
                              </div>
                            </div>
                            <div id="discritizeby" style="display:<?php if($resultAvaiSelectedPar){ if($resultAvaiSelectedPar[0]['spar_disc']=='No'){ print "none"; } }else{ print "none"; }?>;">
                              <div class="form-group">
                                <label class="control-label col-md-3"><strong>By: </strong></label>
                                <div class="col-md-9">
                                  <select class="form-control" id="disby" name="disby">
                                    <option value="Range" selected="">Range</option>
                                    <option value="Value" <?php if($resultAvaiSelectedPar){ if($resultAvaiSelectedPar[0]['spar_disctype']=='Value'){ print "selected"; } } ?>>Value</option>
                                  </select>
                                  <div class="label-comment" style="padding-top: 5px; padding-bottom: 5px;">
                                    Choose range for integer or continueus value, and choose value for grouping value
                                  </div>
                                </div>

                                <div class="" id="byrangediv">
                                  <div class="" id="discritizeRange" style="display:;">
                                    <label class="control-label col-md-3"><strong>Cutting range : <span style="color:red;">**</span></strong></label>
                                    <div class="col-md-9">
                                      <input type="text" name="cutRangevalue" id="cutRangevalue" class="form-control" value='<?php if($resultAvaiSelectedPar){
                                        if($resultDizRange){
                                          print $resultDizRange[0]['dsc_range_value'];
                                        }
                                      }?>'>
                                      <div class="label-comment" style="padding-top: 5px; padding-bottom: 5px;">
                                        Split value by comma. <u>Example</u>: 0,15,30,46,60&nbsp;&nbsp;<i class="fa fa-exclamation-circle"></i>
                                      </div>
                                    </div>

                                    <label class="control-label col-md-3"><strong>Label : <span style="color:red;">**</span></strong></label>
                                    <div class="col-md-9">
                                      <input type="text" name="cutRangelabel" id="cutRangelabel" class="form-control" value='<?php if($resultAvaiSelectedPar){
                                        if($resultDizRange){
                                          print $resultDizRange[0]['dsc_range_label'];
                                        }
                                      }?>'>
                                      <div class="label-comment" style="padding-top: 5px; padding-bottom: 5px;">
                                        Split value by comma. <u>Example</u>: "<=15","16-30","31-45",">45"&nbsp;&nbsp;<i class="fa fa-exclamation-circle"></i>
                                      </div>
                                    </div>
                                  </div>

                                  <!-- End range -->
                                </div>

                                <div class="" id="byvaludiv" style="display:none;">
                                  <div class="" id="discritizeValue">
                                    <label class="control-label col-md-3"><strong>Value : <span style="color:red;">**</span></strong></label>
                                    <div class="col-md-9">
                                      <input type="text" name="txtValue" id="txtValue" class="form-control">
                                      <div class="label-comment" style="padding-top: 5px; padding-bottom: 5px;">
                                        Split value by comma. <u>Example</u>: Anna,Magaret,Tiny,Roby
                                      </div>
                                    </div>

                                    <label class="control-label col-md-3"><strong>Group name : <span style="color:red;">**</span></strong></label>
                                    <div class="col-md-9">
                                      <input type="text" name="labelGroupname2" id="labelGroupname2" class="form-control">
                                    </div>

                                    <div class="col-sm-12 text-right" style="padding-top: 10px;">
                                      <button type="button" name="btnAddRecode" id="btnAddRecode" class="btn btn-primary" style="width:36px;"><i class="fa fa-plus"></i></button>
                                    </div>


                                    <div class="col-sm-12">
                                      <h4><i class="fa fa-table"></i>&nbsp;&nbsp;Value list</h4>
                                      <table class="table table-bordered">
                                        <thead>
                                          <th width="100">
                                            Group name
                                          </th>
                                          <th>
                                            Value
                                          </th>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>

                                            </td>
                                            <td>

                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>

                                  </div>
                                </div>


                              </div>
                            </div>
                          </div>

                          <div id="labeltovalue1" style="display:<?php if($resultAvaiSelectedPar){ if($resultAvaiSelectedPar[0]['spar_disc']=='Yes'){ print "none"; }else{ if($resultAvaiSelectedPar[0]['spar_conv']=='0'){ print "none"; } } }else{ print "none"; }?>;">
                            <h4>Label value</h4>
                            <div class="row">
                              <div class="col-sm-12">
                                <table class="table table-bordered">
                                  <thead>
                                    <th width="100">
                                      Original
                                    </th>
                                    <th>
                                      Recode to
                                    </th>
                                    <th>
                                      Description
                                    </th>
                                  </thead>

                                  <tbody>
                                    <?php
                                    $strSQL = "SELECT ".$resultParam[0]['par_name']." as v, count(".$resultParam[0]['par_name'].") as c FROM ".$resultDataconnect[0]['dbs_tbname']." WHERE 1 GROUP BY ".$resultParam[0]['par_name'];
                                    $resultGroup = $source->select($strSQL,false,true);

                                    $sum = 0;
                                    $cumpct = 0;
                                    if($resultGroup){

                                      $db = new database();
                                      $db->connect();

                                      foreach($resultGroup as $val){
                                        ?>
                                        <tr>
                                          <td>
                                            <input type="text" name="labelOrigine[]" id="labelOrigine[]" readonly class="form-control" value="<?php print $val['v']; ?>" style="width: 100px;">
                                          </td>
                                          <td>
                                            <input type="text" name="labelValue[]" id="labelValue[]" class="form-control"
                                             value="<?php
                                              if($resultAvaiSelectedPar){
                                                $strSQL = "SELECT * FROM ".$tbprefix."recodetable WHERE spa_id = '".$resultAvaiSelectedPar[0]['spar_id']."' and rc_origin = '".$val['v']."' ";
                                                $resultLabelValS = $db->select($strSQL,false,true);

                                                if($resultLabelValS){
                                                  print $resultLabelValS[0]['rc_code'];
                                                }
                                              }else{
                                                print $val['v'];
                                              }
                                             ?>"
                                             >
                                          </td>
                                          <td>
                                            <input type="text" name="labelDesc[]" id="labelDesc[]" class="form-control" value="<?php
                                             if($resultAvaiSelectedPar){
                                               if($resultLabelValS){
                                                 print $resultLabelValS[0]['rc_desc'];
                                               }
                                             }
                                            ?>">
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
                          <!-- End label calue -->

                          <div class="row">
                            <div class="col-sm-12 text-right">
                              <?php
                              if($resultAvaiSelectedPar){
                                ?><button type="button" name="button" id="btnSaveRecode" class="btn btn-primary btn-block">Update</button><?php
                              }else{
                                ?><button type="button" name="button" id="btnSaveRecode" class="btn btn-primary btn-block">SAVE</button><?php
                              }
                              ?>

                            </div>
                            <?php
                            if($resultDizRange){
                              $value = explode(",", $resultDizRange[0]['dsc_range_value']);
                              $label = explode(",", $resultDizRange[0]['dsc_range_value']);

                              ?>
                              <?php
                            }
                            ?>
                          </div>
                        </form>
              </div>
              <!-- End col-sm-6 -->
              <div class="col-sm-6">
                <h4>Summary<br><small>(If parameter is factor > set up reference value)</small></h4>
                <hr>
                <table class="table table-bordered table-reponsive">
                  <thead>
                    <th>
                      #
                    </th>
                    <th>
                      Value
                    </th>
                    <th>
                      Group label
                    </th>
                    <th>
                      Description
                    </th>
                    <th>
                      Reference
                    </th>
                  </thead>
                  <tbody>
                    <?php
                     if($resultAvaiSelectedPar){
                      //  Convert to factor
                       if($resultAvaiSelectedPar[0]['spar_conv']=='1'){
                         //Not discritize
                         if($resultAvaiSelectedPar[0]['spar_disc']=='No'){
                           $strSQL = sprintf("SELECT * FROM ".$tbprefix."recodetable WHERE spa_id = '%s' ",mysql_real_escape_string($resultAvaiSelectedPar[0]['spar_id']));
                           $resultRecode = $db->select($strSQL,false,true);
                           if($resultRecode){
                             $c = 1;
                             foreach ($resultRecode as $value) {
                               ?>
                               <tr>
                                 <td>
                                   <?php print $c; ?>
                                 </td>
                                 <td>
                                   <?php print $value['rc_origin'];?>
                                 </td>
                                 <td>
                                   <?php print $value['rc_code'];?>
                                 </td>
                                 <td>
                                   <?php
                                   if($value['rc_desc']!=''){
                                      print $value['rc_code'];
                                   }
                                    else {
                                      print "-";
                                    }
                                   ?>
                                 </td>
                                 <td>
                                   <?php
                                     if($value['rc_ref']=='Yes'){
                                       ?>
                                       <a href="javascript: toggleStatus('<?php print $resultAvaiSelectedPar[0]['spar_id']; ?>','<?php print $value['rc_id'];?>','No','<?php print $id;?>')"><span class="label label-success">Yes</span></a>
                                       <?php
                                     }else{
                                       ?>
                                       <a href="javascript: toggleStatus('<?php print $resultAvaiSelectedPar[0]['spar_id']; ?>','<?php print $value['rc_id'];?>','Yes','<?php print $id;?>')"><span class="label label-danger">No</span></a>
                                       <?php
                                     }
                                   ?>
                                 </td>
                               </tr>
                               <?php
                               $c++;
                             }
                           }else{
                             ?>
                             <tr>
                               <td colspan="5">
                                 No configuration data
                                 <?php print $strSQL; ?>
                               </td>
                             </tr>
                             <?php
                           }
                         }
                         else //Dizcritized
                         {
                           //  discritize as Range
                           if($resultAvaiSelectedPar[0]['spar_disctype']=='Range'){
                             $strSQL = sprintf("SELECT * FROM ".$tbprefix."discritize_range WHERE spa_id = '%s' ",mysql_real_escape_string($resultAvaiSelectedPar[0]['spar_id']));
                             $resultRecode = $db->select($strSQL,false,true);

                             if($resultRecode){
                               $group = explode(",", $resultRecode[0]['dsc_range_label']);
                               $values = explode(",", $resultRecode[0]['dsc_range_value']);
                               $c = 1;
                               foreach ($group as $value) {
                                 ?>
                                 <tr>
                                   <td>
                                     <?php print $c; ?>
                                   </td>
                                   <td>
                                     <?php
                                      if($c!=1){
                                        // if($c==sizeof($group)){
                                        //   print ($values[$c-1]+1)."-".$values[$c];
                                        // }else{
                                        //   print $values[$c-1]."-".$values[$c];
                                        // }
                                        print ($values[$c-1]+1)."-".$values[$c];
                                      }else{
                                          print ($values[$c-1])."-".($values[$c]-1);
                                      }
                                      ?>
                                   </td>
                                   <td>
                                     <?php print substr($value,1,strlen($value)-2);?>
                                   </td>
                                   <td>
                                     -
                                   </td>
                                   <td>
                                     <?php
                                       if($resultRecode[0]['dsc_range_ref']==''){
                                         ?>
                                         <a href="javascript: toggleReference2('<?php print $resultRecode[0]['dsc_id']; ?>','<?php print substr($value,1,strlen($value)-2);?>','<?php print $resultAvaiSelectedPar[0]['spar_id'];?>','<?php print $id;?>')"><span class="label label-danger">No</span></a>
                                         <?php
                                       }else{
                                         if($resultRecode[0]['dsc_range_ref']==substr($value,1,strlen($value)-2)){
                                           ?>
                                           <a href="javascript: toggleReference2('<?php print $resultRecode[0]['dsc_id']; ?>','<?php print substr($value,1,strlen($value)-2);?>','<?php print $resultAvaiSelectedPar[0]['spar_id'];?>','<?php print $id;?>')"><span class="label label-success">Yes</span></a>
                                           <?php
                                         }else{
                                           ?>
                                           <a href="javascript: toggleReference2('<?php print $resultRecode[0]['dsc_id']; ?>','<?php print substr($value,1,strlen($value)-2);?>','<?php print $resultAvaiSelectedPar[0]['spar_id'];?>','<?php print $id;?>')"><span class="label label-danger">No</span></a>
                                           <?php
                                         }

                                       }
                                     ?>
                                   </td>
                                 </tr>
                                 <?php
                                 $c++;
                               }
                             }


                           }
                           else //Discritize as Value
                           {

                           }

                         }
                       }
                       else // Not convert
                       {
                         # code...
                       }
                     }else{
                       ?>
                       <tr>
                         <td colspan="5">
                           No configuration data
                         </td>
                       </tr>
                       <?php
                     }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- End col-sm-6 -->
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
