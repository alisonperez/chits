<?
    ob_start();
    session_start();
    include('globals.php');
    include('layout/class.widgets.php');      
    include('scripts/class.querydb.php');

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
	  
	  //upon setting filters, set the necessary sessions here
	  
	  if($_POST[q_submit]):	  	  	                    
	        // set the session for start date and end date
		if($_SESSION[filter]==1):
			$queryconn->querycrit($dbname,$dbname2,$_POST[sdate],$_POST[edate],$_POST[sel_brgy],$_POST[sel_fp_method]);
		elseif($_SESSION[filter]==2): //summary tables
			
			$_SESSION[smonth] = $_POST[smonth];
			$_SESSION[emonth] = $_POST[emonth];
			
			$sdate = strftime("%m/%d/%Y",mktime(0,0,0,$_POST[smonth],1,$_POST[year]));
			$edate = strftime("%m/%d/%Y",mktime(0,0,0,($_POST[emonth]+1),0,$_POST[year]));						
			$queryconn->querycrit($dbname,$dbname2,$sdate,$edate,$_POST[brgy],0); //the fifth argument when set to zero, means that there is no form present in the query box
			
                elseif($_SESSION[filter]==3): //quarterly tables
                        $arr_start_end = array('1'=>array('01/01','03/31'),'2'=>array('04/30','06/30'),'3'=>array('07/31','09/30'),'4'=>array('10/01','12/31'));                        
                        $sdate = $arr_start_end[$_POST[sel_quarter]][0].'/'.$_POST[year];
                        $edate = $arr_start_end[$_POST[sel_quarter]][1].'/'.$_POST[year];
                        
                        $_SESSION[quarter] = $_POST[sel_quarter];
                        $_SESSION[year] = $_POST[year];
                        //print_r($_POST);
                        
                        //echo $sdate.'/'.$edate;
                        
                        $queryconn->querycrit($dbname,$dbname2,$sdate,$edate,$_POST[brgy],0);
                        
                elseif($_SESSION[filter]==4): //monthly tables
                        $_SESSION[smonth] = $_POST[smonth];
                        $_SESSION[year] = $_POST[year];
                        
      			$sdate = strftime("%m/%d/%Y",mktime(0,0,0,$_POST[smonth],1,$_POST[year]));
			$edate = strftime("%m/%d/%Y",mktime(0,0,0,($_POST[smonth]+1),0,$_POST[year]));						
			
			$queryconn->querycrit($dbname,$dbname2,$sdate,$edate,$_POST[brgy],0); //the fifth argument when set to zero, means that there is no form present in the query box
                else:
                
		endif;
	  endif;

else:  
  echo "<font color=\"red\">Access restricted. Please log your account in the CHITS main page.</font><br>";
  echo "<a href=\"$_SERVER[PHP_SELF]\">Try Again</a>";
endif;

?>
</body>
</html>