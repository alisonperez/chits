<?php
  
  $dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]);
  mysql_select_db($_SESSION["dbname"],$dbconn);
  
  //print_r($_GET);

  $q_fp_service = mysql_query("SELECT a.date_service,b.source_name,a.remarks,a.next_service_date FROM m_patient_fp_method_service a,m_lib_supply_source b WHERE a.fp_px_id='$_GET[id]' AND a.patient_id='$_GET[px]' AND a.source_id=b.source_id") or die("Cannot query 8 ".mysql_error());
  
  //print_r($_SESSION);
  
  if(mysql_num_rows($q_fp_service)!=0):
    
    echo "Method: ".$_GET[method_id];
    echo "<table bgcolor='#66FF66'>";
    echo "<tr align='center' bgcolor='#339966' style='font-family: verdana, arial, sans serif; font-size: 8pt; font-weight: bold'><td>Date of Service</td><td>Source</td><td>Remarks</td><td>Next Service Date</td></tr>";
    
    while(list($date_service,$source_name,$remarks,$next_service_date)=mysql_fetch_array($q_fp_service)){
      echo "<tr style='font-family: verdana, arial, sans serif; font-size: 8pt; font-weight: bold'>";
      echo "<td>".$date_service."</td>";
      echo "<td>".$source_name."</td>";
      echo "<td>".$remarks."</td>";
      echo "<td>".$next_service_date."</td>";
      echo "</tr>";
    }
    
    echo "</table>";
  
  else: //no services has been made for this FP method
    echo "<font color='red'>No FP services has been given for this enrollment.</font>";
  endif;
?>
