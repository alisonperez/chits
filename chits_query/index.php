<?
	ob_start();
    session_start();
    include('globals.php');
    include('layout/class.widgets.php');      
    include('scripts/class.querydb.php');
//	include('../modules/mc/class.mc.php');

//	$mc = new mc();
    $widconn = new widgets();
    $queryconn = new querydb();

	if($_POST[sel_class]!=0):
	//	$queryconn->init_set_vars($_POST[sel_class],$_POST[sel_ques]);
		$_SESSION[cat] = $_POST[sel_class];
	endif;

	if($_POST[sel_ques]!=0):
		$_SESSION[ques] = $_POST[sel_ques];
	endif;	
	
?>


<html>
<head>
<title>CHITS QUERY BROWSER</title>

<script language="JavaScript">

function autoSubmit()
{
	var formObject = document.forms['form_cat'];
	if(formObject!=0){
		formObject.submit();
	}
}

function submitQues()
{
	var formObject = document.forms['form_ques'];

	if(formObject!=0){
		formObject.submit();
	}
}

</script> 

<script language="javascript" src="../popups.js"></script>
<script language="JavaScript" src="../ts_picker4.js"></script>
<script language="JavaScript" src="../js/functions.js"></script>

</head>

<body>
<?
if($_SESSION["userid"]!=""):
      $db_conn = mysql_connect(localhost,$_SESSION["dbuser"],$_SESSION["dbpass"]) or mysql_error();
      mysql_select_db($_SESSION["dbname"],$db_conn) or mysql_error();

      echo "<table><tr valign=\"top\"><td rowspan=\"2\">";
      //container of questions

      $widconn->query_class($dbname2,$_SESSION[cat],$_SESSION[ques]);       
      echo "</td>";
      
      
      echo "<td valign=\"top\">";
	  if(isset($_SESSION[ques]) || $_POST[q_submit]):
	      $widconn->query_cat($dbname,$dbname2,$_POST[sdate],$_POST[edate],$_POST[sel_brgy]);
	   endif;

      echo "</tr>";      
            
      echo "</table>";        

	  echo "<br><br>";
	  
	  if($_POST[q_submit]):	  	  	                    
	        
		if($_SESSION[filter]==1):
			$queryconn->querycrit($dbname,$dbname2,$_POST[sdate],$_POST[edate],$_POST[sel_brgy],$_POST[sel_fp_method]);
		else:
			$end = array(01=>'31',02=>'29',03=>'31',04=>'30',05=>'31',06=>'30',07=>'31',08=>'31',09=>'30',10=>'31',11=>'30',12=>'31');
			
			$_SESSION[end_month] = $end;

			//$sdate = str_pad($_POST[smonth],2,"0",STR_PAD_LEFT).'/'.str_pad('1',2,"0",STR_PAD_LEFT).'/'.$_POST[year];
			//$edate = str_pad($_POST[emonth],2,"0",STR_PAD_LEFT).'/'.$end[$_POST[emonth]].'/'.$_POST[year];

			$sdate = $_POST[smonth].'/'.str_pad('1',2,"0",STR_PAD_LEFT).'/'.$_POST[year];
			$edate = $_POST[emonth].'/'.$end[$_POST[emonth]].'/'.$_POST[year];

			$queryconn->querycrit($dbname,$dbname2,$sdate,$edate,$_POST[brgy],0); //the fifth argument when set to zero, means that there is no form present in the query box

		endif;
	  endif;

else:  
  echo "<font color=\"red\">Access restricted. Please log your account in the CHITS main page.</font><br>";
  echo "<a href=\"$_SERVER[PHP_SELF]\">Try Again</a>";
endif;

?>
</body>
</html>