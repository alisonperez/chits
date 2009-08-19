<!--/*FORM
*ask for start date and end date
*/FORM

*pRocess

*actual fic (9-11MONTHS): GET DATA FROM m_patient_ccdev

*select ccdev_dob, fully_immunized_date, patient_id, mother_name from m_patient_ccdev where MONTH(CURRENT_DATE - ccdev_dob)<12; 

*B-DATE FIC (1 yr old)

*select ccdev_dob, fully
*query m_consult_ccdev_vaccine

*BCG, etc

*select vaccine_id, count(vaccine_id) from m_consult_ccdev_vaccine where actual_vaccine_date < 20040717 AND actual_vaccine_date>20040711 GROUP BY vaccine_id;

* TOXOID IMMUNIZATION

* FULLY IMMUNIZED

* select distinct(patient_id) from m_consult_mc_vaccine where vaccine_id='TT5';

* select actual_vaccine_date,source_module,vaccine_id from m_consult_vaccine where actual_vaccine_date<20040717 and actual_vaccine_date>20040711 and source_module='vaccine'

*SECOND PART OF REPORT

*details
*/ -->

<?
$conn = mysql_connect("localhost", "root", "kambing");
$db=mysql_select_db("game");

//$start_date = '20040704';
//$end_date = '20040710';
//$start_date = $http_post_vars['start_date'];
//$end_date = $http_post_vars['end_date'];
//echo $http_post_vars[start_date];

$actual_fic = mysql_result(mysql_query("select count(fully_immunized_date) from m_patient_ccdev where MONTH(CURRENT_DATE - ccdev_dob)<12 and MONTH(CURRENT_DATE - ccdev_dob)>9 and fully_immunized_date<$end_date and fully_immunized_date>$start_date"),0);

$bdate_fic = mysql_result(mysql_query("select count(fully_immunized_date) from m_patient_ccdev where MONTH(CURRENT_DATE - ccdev_dob)>12 
and fully_immunized_date<$end_date and fully_immunized_date>$start_date"),0);

$result = mysql_query("select vaccine_id, count(vaccine_id) from m_consult_ccdev_vaccine where actual_vaccine_date < $end_date and actual_vaccine_date > $start_date group by vaccine_id") or die(mysql_error());

$tt1 = mysql_result(mysql_query("select count(vaccine_id) from m_consult_mc_vaccine where vaccine_id='TT1' and actual_vaccine_date>$start_date and actual_vaccine_date<$end_date"),0);

$tt2 = mysql_result(mysql_query("select count(vaccine_id) from m_consult_mc_vaccine where vaccine_id='TT2' and
 actual_vaccine_date>$start_date and actual_vaccine_date<$end_date"),0);

$tt3 = mysql_result(mysql_query("select count(vaccine_id) from m_consult_mc_vaccine where vaccine_id='TT3' and
 actual_vaccine_date>$start_date and actual_vaccine_date<$end_date"),0);

$tt4 = mysql_result(mysql_query("select count(vaccine_id) from m_consult_mc_vaccine where vaccine_id='TT4' and
 actual_vaccine_date>$start_date and actual_vaccine_date<$end_date"),0);

$tt5 = mysql_result(mysql_query("select count(vaccine_id) from m_consult_mc_vaccine where vaccine_id='TT5' and
 actual_vaccine_date>$start_date and actual_vaccine_date<$end_date"),0);

$tt1_np = mysql_result(mysql_query("select count(patient_id) from m_consult_vaccine where actual_vaccine_date<$end_date and actual_vaccine_date>$start_date and source_module='vaccine' and vaccine_id='TT1'"),0);

$tt2_np = mysql_result(mysql_query("select count(patient_id) from m_consult_vaccine where actual_vaccine_date<$end_date and actual_vaccine_date>$start_date and source_module='vaccine' and vaccine_id='TT2'"),0);

$tt3_np = mysql_result(mysql_query("select count(patient_id) from m_consult_vaccine where actual_vaccine_date<$end_date and actual_vaccine_date>$start_date and source_module='vaccine' and vaccine_id='TT3'"),0);

$tt4_np = mysql_result(mysql_query("select count(patient_id) from m_consult_vaccine where actual_vaccine_date<$end_date and actual_vaccine_date>$start_date and source_module='vaccine' and vaccine_id='TT4'"),0);

$tt5_np = mysql_result(mysql_query("select count(patient_id) from m_consult_vaccine where actual_vaccine_date<$end_date and actual_vaccine_date>$start_date and source_module='vaccine' and vaccine_id='TT5'"),0);

$fully_immunized_mother = mysql_result(mysql_query("select count(distinct(patient_id)) from m_consult_mc_vaccine where vaccine_id='TT5' and actual_vaccine_date>$start_date and actual_vaccine_date<$end_date"),0);
?>


<html>
<title>LAGROSA Info Desktop</title>
<head>

</head>

<body>

<table cellspacing=0 cellpadding=0 width=75%>
<tr>
   <td width="450" valign=top>
   <blockquote><span>EPI WEEKLY MONITORING REPORT</span>
   </td>
</tr>
<tr>
   <td width="450"><br/>
   <blockquote><span>INCLUSIVE DATES FOR THIS REPORT<br/>
      <blockquote><span>START DATE : &nbsp;&nbsp; <? echo $start_date; ?><br/>
      END DATE : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <? echo $end_date; ?><br/></span>
   </span>
   </td>
</tr>
<tr>
   <td>
   <table cellspacing=0>
   <tr>
      <td><br/>
   </tr>
   <tr>
      <td width=300><blockquote><span>Actual FIC (9 mos.)</td>
      <td width=500><? echo $actual_fic; ?></td></span>
   </tr>
   <tr>
      <td width=300><blockquote><span>B-Date FIC (1 yr. old)</td>
      <td><? echo $bdate_fic; ?></td>
   </tr>

   <?
   if ($result) {
      while(list($vaccine, $vaccine_count) = mysql_fetch_array($result)) {
         print "<tr>";
         print "<td><blockquote><blockquote><span>$vaccine</td>";
         print "<td>$vaccine_count</td>";
      }
   }
   ?>
   <tr><td><br/></td></tr>
   <tr>
      <td width=300><blockquote><span>Tetanus Toxoid Immunization</span></td>
   </tr>
   <tr>
      <td><blockquote><blockquote>Pregnant</td>
   </tr>
   <tr>
      <td><blockquote><blockquote><blockquote>TT1</td>
      <td width=500><? echo $tt1; ?></td>
   </tr>
   <tr>
      <td><blockquote><blockquote><blockquote>TT2</td>
      <td width=500><? echo $tt2; ?></td>
   </tr>
   <tr>
      <td><blockquote><blockquote><blockquote>TT3</td>
      <td width=500><? echo $tt3; ?></td>
   </tr>
   <tr>
      <td><blockquote><blockquote><blockquote>TT4</td>
      <td width=500><? echo $tt4; ?></td>
   </tr>
   <tr>
      <td><blockquote><blockquote><blockquote>TT5</td>
      <td width=500><? echo $tt5; ?></td>
   </tr>
   <tr>
      <td><blockquote><blockquote>Non-Pregnant</td>
   </tr>
   <tr>
      <td><blockquote><blockquote><blockquote>TT1</td>
      <td width=500><? echo $tt1_np; ?></td>
   </tr>
   <tr>
      <td><blockquote><blockquote><blockquote>TT2</td>
      <td width=500><? echo $tt2_np; ?></td>
   </tr>
   <tr>
      <td><blockquote><blockquote><blockquote>TT3</td>
      <td width=500><? echo $tt3_np; ?></td>
   </tr>
   <tr>
      <td><blockquote><blockquote><blockquote>TT4</td>
      <td width=500><? echo $tt4_np; ?></td>
   </tr>
   <tr>
      <td><blockquote><blockquote><blockquote>TT5</td>
      <td width=500><? echo $tt5_np; ?></td>
   </tr>
   <tr>
      <td><blockquote><blockquote>FULLY IMMUNIZED MOTHER</td>
      <td><? echo $fully_immunized_mother; ?></td>
   </tr>
   </table>
</tr>
</table>
</body>
</html>
   
