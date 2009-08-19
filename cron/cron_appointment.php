#!/usr/bin/php
<?php
// standard class includes
include "../class.mysqldb.php";
    $db = new MySQLDB;
    $conn = $db->connid();
    include "../modules/_dbselect.php";

$query_app=mysql_query("
	SELECT 
	p2.cellular_phone, 
	p1.template_text 
	FROM 
	m_consult_appointments p3 LEFT JOIN m_patient_reminder_data p2 ON (p3.patient_id=p2.patient_id) LEFT JOIN m_lib_reminder_sms_template p1 ON p1.appointment_id=p3.appointment_id  
	WHERE 
	DATE_FORMAT( p3.schedule_timestamp,  '%Y-%m-%d'  )=CURRENT_DATE AND
	!(p2.cellular_phone is null) AND
	p3.reminder_flag='Y'
	") or die (mysql_error());
$x=0;
while($get_app=mysql_fetch_row($query_app))
	{
	echo($x++.")");
	echo($get_app[0]."--");
	echo($get_app[1]."\n");
	}

?>
