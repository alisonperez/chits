<?php
	
	$dbconn = mysql_connect('localhost',$_SESSION["dbuser"],$_SESSION["dbpass"]) or die(mysql_error());
	mysql_select_db($_SESSION["dbname"],$dbconn) or die("cannot select db");
	$sql_px = mysql_query("SELECT patient_id,patient_lastname,patient_firstname,patient_middle FROM m_patient WHERE patient_id='$id'") or die(mysql_error());
	
	if(mysql_num_rows($sql_px)!=0):
		list($pxid,$pxlast,$pxfirst,$pxmiddle)= mysql_fetch_array($sql_px);
		echo '<b>Patient Name:</b> '.$pxlast.', '.$pxfirst.' '.$pxmiddle.'<br>';
		echo '<b>Patient ID:</b> '.$pxid;
	else:
		echo "<font color='red'><b>Patient ID does not exists.</b></font>";
	endif;

	echo "<br><input type='button' value='Close this Window' onclick='window.close()'></input>";

?>