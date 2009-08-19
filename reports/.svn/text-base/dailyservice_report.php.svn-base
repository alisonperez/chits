<?
$conn = mysql_connect("localhost", "root", "kambing");
$db = mysql_select_db("game");

$result = mysql_query("select * from m_consult_report_dailyservice where service_date=20040730 order by patient_name") or die(mysql_error());
//$sql_report = "select p.patient_id, p.patient_name, p.patient_gender, round(p.patient_age), ".
//	  "p.patient_address, p.patient_bgy, p.family_id, a.complaint_name, d.class_name from ".
//	  "m_consult_report_dailyservice p, m_lib_notes_dxclass d, m_consult_notes_dxclass l, ".
//	  "m_lib_complaint a, m_consult_notes_complaint b where p.service_date=to_days(now()) and ".
//	  "to_days(l.diagnosis_date) = to_days(now()) and to_days(b.complaint_date) = to_days(now()) ".
//	  "and p.patient_id=l.patient_id and p.patient_id=b.patient_id and a.complaint_id=b.complaint_id ".
//	  "and d.class_id=l.class_id and l.notes_id=b.notes_id order by p.patient_name"; 
//$result_report = mysql_query($sql_report);
//$dx = mysql_query("select m_lib_notes_dxclass.class_name from m_patient, m_lib_notes_dxclass, ".
//      "m_consult_notes_dxclass where m_patient.patient_id=m_consult_notes_dxclass.patient_id and ".
//      "m_lib_notes_dxclass.class_id=m_consult_notes_dxclass.class_id") or die(mysql_error());

print "<title>DAILY SERVICE REPORT</title>";

print "<table width=100% cellspacing=1>";
print "<tr>";
print "<td align=center colspan=3><blockquote><strong>DAILY SERVICE REPORT</strong></td>";
print "</tr>";
print "<tr>";
print "<td align=center><blockquote>Date : &nbsp;&nbsp; 06/29/2004</td>";
print "</tr>";
print "</table>";
print "<br/>";
print "<table width=100% border=1 bordercolor=black>";
print "<tr>";
print "<td><b>Patient ID</b></td>";
print "<td><b>Patient Name/Sex/Age</b></td>";
print "<td><b>Address</b></td>";
print "<td><b>Brgy</b></td>";
print "<td><b>Family ID</b></td>";
print "<td><b>Complaints</b></td>";
print "<td><b>Diagnosis</b></td>";
print "<td><b>Tx</b></td>";

if ($result) {
  while (list($pid,$pname,$sex,$age,$addr,$bgy,$fid,$cc,$dx,$tx) = mysql_fetch_array($result)) {
     print "<tr>";
     print "<td>$pid</td>";
     print "<td>$pname / $sex / $age</td>";
     //print "<td>$sex</td>";
     //print "<td>$age</td>";
     print "<td>$addr</td>";
     print "<td>$bgy</td>";
     print "<td>$fid</td>";
     print "<td>$cc</td>";
     print "<td>$dx</td>";
     print "<td>$tx</td>";
     print "</tr>";
  }
}
//if ($dx) {
//   while ($diagnosis = mysql_fetch_row($dx)) {
     //print "<td>$cc</td>";
//     print "<td>$diagnosis</td>";
     //print "<td>$tx</td>";
//     print "</tr>";
//   }
//}
       

print "</table>";

$total = mysql_result(mysql_query("select count(patient_id) from m_consult_report_dailyservice where service_date='20040629'"),0);

print "<blockquote>No. of Today's Consults : &nbsp;&nbsp; $total";
     
?>     
