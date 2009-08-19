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
	p1.reminder_text,
	p1.reminder_id 
	FROM 
	m_patient_reminder_data p2, 
	m_consult_reminder p1 
	WHERE p1.reminder_date=CURRENT_DATE AND
	!(p2.cellular_phone is null) AND
	p1.patient_id=p2.patient_id AND
	p1.sent_flag='N'
	") or die (mysql_error());
$x=0;
while($get_app=mysql_fetch_row($query_app))
	{
	/*echo($x++.")");
	echo($get_app[0]."--");
	echo($get_app[1]."\n");
	echo($msg);
	*/

	$msg=urlencode($get_app[1]);
	$x=shell_exec('lynx -dump "http://localhost:13013/sendsms?username=martian&password=martian&to="'.$get_app[0].'"&text="'.$msg);

	echo($x);	

	if(trim($x)=='Sent.')
		{
		mysql_query("UPDATE m_consult_reminder SET sent_flag='Y' WHERE reminder_id='$get_app[2]'");
		}	

	}

?>
