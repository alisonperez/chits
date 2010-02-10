<?php
  
  
$dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die("Cannot query 4 ".mysql_error());
mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");

echo "<html>";

echo "<head>";
echo "<title>Search Patient</title>";
echo "<script language='Javascript' src='../js/functions.js'></script>";
echo "</head>";


echo "<body>";

if($_SESSION[userid]):
  
  $q_sputum = mysql_query("SELECT request_id,date_format(sp1_collection_date,'%m/%d/%Y') as sp1,sp2_collection_date,sp3_collection_date,sp1_reading,sp2_reading,sp3_reading from m_consult_lab_sputum WHERE patient_id='$_GET[id]' ORDER BY sp1_collection_date DESC,sp2_collection_date DESC, sp3_collection_date DESC") or die("Cannot query 5 ".mysql_error());
  
  if(mysql_num_rows($q_sputum)!=0):
    echo "Select row for the sputum exam<br>";
    echo "<table>";
    echo "<tr align='center'><td>1</td><td>2</td><td>3</td><td>Select</td></tr>";
    
    while($r_sputum = mysql_fetch_array($q_sputum)){
      echo "<tr>";
      echo "<td>$r_sputum[sp1] ($r_sputum[sp1_reading])</td>";
      echo "<td>$r_sputum[sp2_collection_date] ($r_sputum[sp2_reading])</td>";
      echo "<td>$r_sputum[sp3_collection_date] ($r_sputum[sp3_reading])</td>";
      echo "<td><a href=\"javascript:pick_sputum($r_sputum[sp1],$r_sputum[sp2_collection_date],$r_sputum[sp3_collection_date])\">select</a></td>";
      echo "</tr>";
    }
    
    echo "</table>";
  
  else:    
  endif;
    
else:
  echo 'perez';
endif;


echo "</body>";
echo "</html>";

?>