<?
session_start();
class querydb{

    function querydb(){
      $this->app = "CHITS Query Browser";
      $this->version = "0.1";
      $this->author = "Alison Perez";    
    }
    
    function querycrit($dbname,$dbname2,$sdate,$edate,$brgy,$misc){   
      $sdate_orig = trim($sdate);
      $edate_orig = trim($edate);
      
	  list($smonth,$sday,$syr) = explode('/',$sdate);
	  list($emonth,$eday,$eyr) = explode('/',$edate);

	  $sdate = $syr.'-'.$smonth.'-'.$sday;
	  $edate = $eyr.'-'.$emonth.'-'.$eday;

	  $start_date=gregoriantojd($smonth, $sday, $syr);   
	  $end_date=gregoriantojd($emonth, $eday, $eyr);
	  $diff = $end_date - $start_date; 
	
      if(empty($sdate) || empty($edate)):
        echo "Please supply a date.";
      elseif($frage>$toage):
        echo "Start age should be lower than end age.";	  
	  elseif($diff < 0):
		echo 'End month should be on or after the start month';
	  elseif(empty($brgy)):
		echo 'Please select one or more barangays.';
      else:

      echo "<br><table border=\"1\">";
        $_SESSION[sdate] = $sdate.' 00:00:00';
        $_SESSION[edate] = $edate.' 23:59:59';
        $_SESSION[brgy] = $brgy;		
        $_SESSION[edate2] = $edate;
	$_SESSION[sdate2] = $sdate;
	$_SESSION[sdate_orig] = $sdate_orig;
	$_SESSION[edate_orig] = $edate_orig;
	
	$_SESSION[fp_method] = (isset($misc))?$misc:0; //assign fp method to a session if it exists from the form, otherwise place 0
	
	$this->stat_table($q,$_SESSION[ques]);
      
      endif;
      
      echo "</table>";
    }

	function exec_sql($quesno){
	
		if($quesno==1):
			$sql = "SELECT a.patient_lastname, a.patient_firstname, a.patient_gender, b.family_id, c.barangay_id, d.barangay_name FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.registration_date between '$_SESSION[sdate]' AND '$_SESSION[edate]' ORDER by d.barangay_name ASC, a.patient_gender DESC, a.patient_lastname ASC, a.patient_firstname ASC";

		elseif($quesno==3):
			$sql = "SELECT DISTINCT a.patient_id,a.patient_lastname, a.patient_firstname, f.disease_name FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d,m_consult_disease_notifiable e, m_lib_disease_notifiable f, m_consult g
			WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id
			AND e.consult_id=g.consult_id AND e.disease_id=f.disease_id ORDER by f.disease_name ASC";

		
		endif;	
		return $sql;
	}

	function compute_count($row,$col,$quesno){

		if($quesno==1):
			$query = mysql_query("SELECT DISTINCT a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND d.barangay_name='$row' AND a.registration_date between '$_SESSION[sdate]' AND '$_SESSION[edate]' AND a.patient_gender='$col'") or die(mysql_error());

		elseif($quesno==2):
			$sex = $this->get_gender($col);
			$agerange = substr($this->get_range($col),0,strlen($this->get_range($col)));

			$query = mysql_query("SELECT DISTINCT a.patient_id from m_patient a, m_family_members b, m_family_address c, m_lib_barangay d
			WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND d.barangay_name='$row' AND round((to_days(now())-to_days(a.patient_dob))/365 , 1) $agerange AND a.patient_gender='$sex' AND a.registration_date between '$_SESSION[sdate]' AND '$_SESSION[edate]'") or die("Cannot query: 62");
		
		elseif($quesno==3):
			//note: if the patient_id in m_consult is 0 the disease will still be shown even though there is not count
			$disId = $this->parse_disid($row);

			$query = mysql_query("SELECT distinct e.consult_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d,m_consult_disease_notifiable e, m_lib_disease_notifiable f, m_consult g
			WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=e.patient_id
			AND e.consult_id=g.consult_id AND e.disease_id='$disId' AND d.barangay_name='$col' AND e.disease_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'") or die("Cannot query: 68");
			

		elseif($quesno==4):

			if(ereg("Consulted",$col)):
				$query = mysql_query("SELECT distinct b.family_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d,m_consult e WHERE a.patient_id=b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=e.patient_id AND d.barangay_name='$row' AND e.consult_date BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'") or die("Cannot query: 73");


			elseif(ereg("with PhilHealth",$col)):
				$query = mysql_query("SELECT distinct b.family_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d,m_patient_philhealth e WHERE a.patient_id=b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=e.patient_id AND d.barangay_name='$row' AND e.expiry_date >= '$_SESSION[edate2]'") or die("Cannot query: 73");
				
			elseif(ereg("Services",$col)):
				$query = mysql_query("SELECT distinct b.family_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d,m_patient_philhealth e,m_consult f,m_consult_philhealth_services g WHERE a.patient_id=b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=f.patient_id AND a.patient_id=e.patient_id AND f.consult_id=g.consult_id AND e.expiry_date>=g.service_timestamp AND g.service_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]' AND d.barangay_name='$row'") or die("Cannot query: 81");
			
			elseif(ereg("Labs",$col)):
				$query = mysql_query("SELECT distinct b.family_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d,m_patient_philhealth e,m_consult f,m_consult_philhealth_labs g WHERE a.patient_id=b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=f.patient_id AND a.patient_id=e.patient_id AND f.consult_id=g.consult_id AND e.expiry_date>=g.lab_timestamp AND g.lab_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]' AND d.barangay_name='$row'") or die("Cannot query: 81");

			else:
			endif;


		elseif($quesno==29):
			$sex = $this->get_gender($col);
			$agerange = substr($this->get_range($col),0,strlen($this->get_range($col)));
			$disId = $this->parse_disid($row);
			
			if($_SESSION[brgy]=="all"):
			
				$query = mysql_query("SELECT distinct e.consult_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d,m_consult_disease_notifiable e, m_lib_disease_notifiable f, m_consult g WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=e.patient_id AND e.consult_id=g.consult_id AND e.disease_id='$disId' AND round((to_days(now())-to_days(a.patient_dob))/365 , 1) $agerange AND a.patient_gender='$sex' AND e.disease_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'") or die("Cannot query: 77");
			
			else:
				$query = mysql_query("SELECT distinct e.consult_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d,m_consult_disease_notifiable e, m_lib_disease_notifiable f, m_consult g WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=e.patient_id AND e.consult_id=g.consult_id AND e.disease_id='$disId' AND round((to_days(now())-to_days(a.patient_dob))/365 , 1) $agerange AND a.patient_gender='$sex' AND e.disease_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]' AND d.barangay_id='$_SESSION[brgy]'") or die("Cannot query: 77");
			endif;

		elseif($quesno==30):
			mysql_select_db($_SESSION["query"]);
 
			$q_mat = mysql_query("SELECT mat_id FROM maternal_indicators WHERE ques_id='$_SESSION[ques]' AND mat_label='$row'") or die("Cannot query: 101");			
			$mat_res = mysql_fetch_array($q_mat);												

			mysql_select_db($_SESSION["dbname"]);
							
			$q = $this->get_query($mat_res[mat_id]);
			$query = mysql_query($q) or die(mysql_error());


		elseif($quesno==31):
			mysql_select_db($_SESSION["query"]);	
			$q_mat = mysql_query("SELECT ind_id FROM childcare_indicators WHERE ques_id='$_SESSION[ques]' AND childcare_label='$row'") or die("Cannot query: 124");

			$cc_res = mysql_fetch_array($q_mat);

			mysql_select_db($_SESSION["dbname"]);

			$q = $this->get_query($cc_res[ind_id]);

			if(isset($q)):
				$query = mysql_query($q) or die(mysql_error());
			endif;

		elseif($quesno==32):
			
			$query = mysql_query("SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_patient_ccdev e, m_consult_ccdev_vaccine f WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=e.patient_id AND e.ccdev_id=f.ccdev_id AND f.vaccine_id='$row' AND f.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'") or die("Cannot query: 138");

		elseif($quesno==33):

			$query = mysql_query("SELECT patient_lastname, patient_firstname FROM m_patient WHERE patient_id='$row'") or die("Cannot query: 142");
			$res = mysql_fetch_array($query);

		elseif($quesno==34): //prenatal TCL

		
		else:
			//echo "No available query for this indicator.";
		endif;		
			if(!isset($res) && $query):
				$bilang = mysql_num_rows($query);			
			elseif(isset($res) && $query):
				$bilang = $res[patient_lastname].' ,'.$res[patient_firstname];
			else:
				$bilang = 'x';
			endif;

			return $bilang;
	}

	function stat_table($qres,$quesno){
		$x = array();
		$y = array();
		$rowtotal = 1;
		$coltotal = 1;
		$gentotal = 1;

		if($quesno==1):
			array_push($x,'M','F');
			$y = $this->populate_brgy();

		elseif($quesno==2):
			array_push($x,'<1(M)','<1(F)','1-4(M)','1-4(F)','5-14(M)','5-14(F)','15-49(M)','15-49(F)','50-64(M)','50-64(F)','65+(M)','65+(F)');
			$y = $this->populate_brgy();

		elseif($quesno==3):
			$x = $this->populate_brgy();
			$y = $this->get_topmorb();

		elseif($quesno==4):
			array_push($x,'No. of Families Consulted','No. of Families with PhilHealth','No. of Families Availed PhilHealth Services','No. of Families Availed PhilHealth Labs');
			$y = $this->populate_brgy();

			$rowtotal = 0;
			$gentotal = 0;

		elseif($quesno==29):
			array_push($x,'<1(M)','<1(F)','1-4(M)','1-4(F)','5-14(M)','5-14(F)','15-49(M)','15-49(F)','50-64(M)','50-64(F)','65+(M)','65+(F)');
			$y = $this->get_sakit();

		elseif($quesno==30):
			array_push($x,'Total');
			$y = $this->set_maternal_indicators();
				
			$rowtotal = 0;
			$gentotal = 0;
			$coltotal = 0;

		elseif($quesno==31):
			array_push($x,'Total');
			$y = $this->set_cc_indicators();

			$rowtotal = 0;
			$gentotal = 0;
			$coltotal = 0;
		
		elseif($quesno==32):
			array_push($x,'Total');			
			$y = $this->get_vaccs();
			
			$rowtotal = 0;
			$gentotal = 0;
			$coltotal = 0;

		elseif($quesno==33):
			
			array_push($x,'Patient Name');
			$q_px = mysql_query("SELECT patient_id FROM m_patient WHERE registration_date BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]' ORDER by patient_lastname ASC, patient_firstname ASC") or die("Cannot query: 208");

			while($res = mysql_fetch_array($q_px)){
				$q_family = mysql_query("SELECT patient_id FROM m_family_members WHERE patient_id='$res[patient_id]'") or die("Cannot query: 212");
							
				if(mysql_num_rows($q_family)==0):
					$r_family = mysql_fetch_array($q_family);
					array_push($y,$res[patient_id]);
				endif;
			}

			$rowtotal = 0;
			$gentotal = 0;
			$coltotal = 0;			

		elseif($quesno==34): //prenatal TCL
			$this->process_prenatal();

		elseif($quesno==35):
			$this->process_postpartum();

		elseif($quesno==36):
			$this->process_mc_indicators();

		elseif($quesno==37):
			$this->process_underone();

		elseif($quesno==38):
			$this->process_sickchild();
		
		elseif($quesno==39):
			$this->process_ccdev_summary();
		elseif($quesno==40): //FP TCL
			$this->process_fp_tcl();
		elseif($quesno==41): //FP summary table
			$this->process_fp_summary();
		else:
				echo "No available query for this indicator.";
		endif;
		
		if(!empty($x) && !empty($y)):
			$this->table_creator($x,$y,$rowtotal,$coltotal,$gentotal);
		endif;
	}

	function table_creator($x,$y,$row,$col,$gt){ //accepts array as an input and parses it to tables
		$col_total = array();
		$row_total = array();

		echo "<table border=\"1\">";
		//create columns
		echo "<tr>";
		echo "<td>&nbsp;</td>";
		for($i=0;$i<sizeof($x);$i++){
			echo "<td>".$x[$i]."</td>";		
		}
		if($row==1):
		echo "<td>Total</td>";
		endif;
		echo "</tr>";

		//create rows
		for($j=0;$j<sizeof($y);$j++){
			$row_total[$j] = 0;
			echo "<tr>";
			echo "<td>".$y[$j]."</td>";

		    for($i=0;$i<sizeof($x);$i++){
				$sum = $this->compute_count($y[$j],$x[$i],$_SESSION[ques]);
				$row_total[$j] = $row_total[$j] + $sum;
				$col_total[$i] = $col_total[$i] + $sum;
				echo "<td>".$sum."</td>";						
	
			}
			if($row==1):
				echo "<td>".$row_total[$j]."</td>";
			endif;

			echo "</tr>";
		}	
		
		if($col==1):
		
		echo "<tr><td>Total</td>";
		for($k=0;$k<sizeof($col_total);$k++){
			echo "<td>$col_total[$k]</td>";
			$total = $col_total[$k] + $total;
		}

		if($gt==1):
			echo "<td>$total</td>";
		endif;

		echo "</tr>";
		endif;

		echo "</table>";
	}

	function populate_brgy(){
		$y = array();
		$q = mysql_query("SELECT barangay_name FROM m_lib_barangay ORDER by barangay_name ASC") or die("Cannot query 51");	
			while($res = mysql_fetch_array($q)){  
				array_push($y,$res[barangay_name]);
			}		
		return $y;	
	}

	function get_gender($str){
		if(substr_count($str,'M')>0):
			$sex = 'M';
		elseif(substr_count($str,'F')>0):
			$sex = 'F';
		else:
		endif;

		return $sex;	
	}
	

	function get_vaccs(){
		$y = array();
		$query_vac = mysql_query("SELECT vaccine_id FROM m_lib_vaccine ORDER by vaccine_id ASC") or die("Cannot query: 195");

		while($res_vac = mysql_fetch_array($query_vac)){
			array_push($y,$res_vac[vaccine_id]);
		}
		return $y;
	
	}
	function get_range($range){
		$arr = explode('(',$range);
		
		if($arr[0]=='<1'):
			$edad = '< 1';
		elseif($arr[0]=='65+'):
			$edad = '>= 65';
		else:
			$arr2 = explode('-',$arr[0]);
			$edad = 'BETWEEN '.$arr2[0].' AND '.$arr2[1];
		endif;
		
		return $edad;
	}

	function get_topmorb(){
		$sakit = array();
		$bilang= array();

		$query_sakit = mysql_query("SELECT distinct a.disease_id, b.disease_name FROM m_consult_disease_notifiable a, m_lib_disease_notifiable b, m_consult c WHERE c.consult_id=a.consult_id AND a.disease_id=b.disease_id AND a.disease_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'") or die(mysql_error());
		
		if(mysql_num_rows($query_sakit)!=0):
		while($res_dis = mysql_fetch_array($query_sakit)){
			$cont = $res_dis[disease_name].' ('.$res_dis[disease_id].')';
			array_push($sakit,$cont);
		}

		//get count and sort
		for($i=0;$i<sizeof($sakit);$i++){
			$arr = explode('(',$sakit[$i]);
			$arr2 = explode(')',$arr[1]);

			$q_sak = mysql_query("SELECT DISTINCT consult_id FROM m_consult_disease_notifiable WHERE disease_id='$arr2[0]' AND disease_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'") or die("Cannot query: 179");
			$num = mysql_num_rows($q_sak);

			array_push($bilang,$num);
		}
		array_multisort($bilang,SORT_DESC,SORT_NUMERIC,$sakit);
//		print_r($sakit);
//		print_r($bilang);
		return $sakit;	
		
		else:
			echo "<font class=\"warning\">No notifiable disease was recorded in the specified period.</font>";
		endif;
	}

	function get_sakit(){
		$sakit = array();

		$query_sakit = mysql_query("SELECT disease_name, disease_id FROM m_lib_disease_notifiable ORDER by disease_name ASC") or die("Cannot query: 210");

		while($res_dis = mysql_fetch_array($query_sakit)){
			$label = $res_dis[disease_name].' ('.$res_dis[disease_id].')';
			array_push($sakit,$label);
		}

		return $sakit; 
	}
	

	function parse_disid($dislabel){

		$arr = explode('(',$dislabel);
		$arr2 = explode(')',$arr[1]);
		return $arr2[0];
	}

	function set_maternal_indicators(){
		$mat_ind = array();
		
		mysql_select_db($_SESSION["query"]);

		$query_mat = mysql_query("SELECT mat_label FROM maternal_indicators WHERE ques_id='$_SESSION[ques]' ORDER by seq_id ASC") or die("Cannot query: 291");

		while($res_mat=mysql_fetch_array($query_mat)){
			array_push($mat_ind,$res_mat[mat_label]);		
		}
		
		mysql_select_db($_SESSION["dbname"]);

		return $mat_ind;
	}


	function get_query($indicator){

		if($_SESSION[ques]==30):
			switch($indicator){
				case 1: 
					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_prenatal f,m_patient_mc g WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.visit_sequence>=4 AND f.prenatal_date BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'";					
					break;
				
				case 2:
					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_vaccine f,m_patient_mc g,m_consult_mc_prenatal h WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.mc_id=h.mc_id AND f.vaccine_id='TT1' AND f.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'";				
					break;
					
				case 3:					
					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_vaccine f,m_patient_mc g,m_consult_mc_prenatal h WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.mc_id=h.mc_id AND f.vaccine_id='TT2' AND f.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'";				
					break;
				case 4:

					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_vaccine f,m_patient_mc g,m_consult_mc_prenatal h WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.mc_id=h.mc_id AND f.vaccine_id='TT3' AND f.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'";				
					break;

				case 5:

					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_vaccine f,m_patient_mc g,m_consult_mc_prenatal h WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.mc_id=h.mc_id AND f.vaccine_id='TT4' AND f.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'";				

					break;

				case 6:

					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_vaccine f,m_patient_mc g,m_consult_mc_prenatal h WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.mc_id=h.mc_id AND f.vaccine_id='TT5' AND f.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'";				
					break;

				case 7:
					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_vaccine f,m_patient_mc g,m_consult_mc_prenatal h WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.mc_id=h.mc_id AND f.vaccine_id='TT2+' AND f.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'";				
					break;

				case 8:

					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_services f,m_patient_mc g, m_consult_mc_prenatal h WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.mc_id=h.mc_id AND f.service_id='IRON' AND f.mc_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'";

					break;

				case 9:
					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_services f,m_patient_mc g, m_consult_mc_prenatal h WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.mc_id=h.mc_id AND f.service_id='VITA' AND f.mc_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'";
					break;

				case 10:

					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_postpartum f,m_patient_mc g WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.visit_sequence>=1 AND f.postpartum_date BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'";
					
					break;
					
				case 11:
					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_services f,m_patient_mc g,m_consult_mc_postpartum h WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.mc_id=h.mc_id AND f.service_id='VITA' AND f.mc_timestamp BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'";

					break;

			    case 12:

					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_consult_mc_postpartum f,m_patient_mc g WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id
					AND c.barangay_id = d.barangay_id AND a.patient_id=g.patient_id AND f.patient_id=g.patient_id AND f.visit_sequence=1 AND f.breastfeeding_flag='Y' AND f.postpartum_date BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'";
					
					break;

				default:

					break;
		
			}

		elseif($_SESSION[ques]==31):

			switch($indicator){
		
			case 1:

					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_patient_ccdev e, m_consult_ccdev_vaccine f WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=e.patient_id AND e.ccdev_id=f.ccdev_id AND f.age_on_vaccine>='36' AND f.age_on_vaccine < '48' AND f.vaccine_id='MSL' AND f.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'";

					break;

			case 2:	
					
					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_patient_ccdev e, m_consult_ccdev_vaccine f WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=e.patient_id AND e.ccdev_id=f.ccdev_id AND f.vaccine_id='HEPB3' AND f.actual_vaccine_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]'";

					break;

			case 3: 
					$q = "SELECT distinct a.patient_id FROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d, m_consult e, m_patient_ccdev f WHERE a.patient_id = b.patient_id AND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.patient_id=e.patient_id AND e.patient_id=f.patient_id AND round((to_days(e.consult_date)-to_days(a.patient_dob))/30,1)>=6 AND round((to_days(e.consult_date)-to_days(a.patient_dob))/30,1)<7 AND e.consult_date BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]'";
		
					break;
		
			default: 

				break;
			}
	
		else:
			$q = "";
		endif;
		
		return $q;
	
	}

	function set_cc_indicators(){
		mysql_select_db($_SESSION["query"]);
		$cc = array();
		$q_cc = mysql_query("SELECT childcare_label, ind_id FROM childcare_indicators ORDER by seq_id ASC") or die(mysql_error());

		while($res = mysql_fetch_array($q_cc)){
			array_push($cc,$res[childcare_label]);
		}
		mysql_select_db($_SESSION["dbname"]);
		return $cc;
	}

	function init_set_vars($cat,$query){
		$_SESSION[cat] = $cat;
		$_SESSION[ques] = $query;
	}


	function process_prenatal(){
		
		if($_SESSION[brgy]=='all'):
			$check_query = mysql_query("SELECT a.patient_id,b.prenatal_date, a.mc_id FROM m_patient_mc a,m_consult_mc_prenatal b where a.patient_id=b.patient_id AND a.end_pregnancy_flag='N' AND b.visit_sequence='1' AND b.prenatal_date BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]' ORDER by b.prenatal_date ASC") or die(mysql_error());
		else:
			$check_query = mysql_query("SELECT a.patient_id,b.prenatal_date, a.mc_id FROM m_patient_mc a,m_consult_mc_prenatal b,m_family_members c,m_family_address d where a.patient_id=b.patient_id AND a.end_pregnancy_flag='N' AND b.visit_sequence='1' AND b.prenatal_date BETWEEN '$_SESSION[sdate]' AND '$_SESSION[edate]' AND a.patient_id=c.patient_id AND c.family_id=d.family_id AND d.barangay_id=$_SESSION[brgy] ORDER by b.prenatal_date ASC") or die(mysql_error());			
		endif;


		if(mysql_num_rows($check_query)==0):
			echo "<font color='red'>No results found</font>";
		else:
			$r_prenatal_id = array();
			$r_mc_id = array();
			while($res_prenatal = mysql_fetch_array($check_query)){
				array_push($r_prenatal_id,$res_prenatal[patient_id]);
				array_push($r_mc_id,$res_prenatal[mc_id]);
			}

			//print_r($r_prenatal_id);
			$_SESSION[prenatal_mc_px] = $r_prenatal_id;
			$_SESSION[mc_id] = $r_mc_id;
			echo "Show Prenatal TCL:&nbsp;&nbsp;&nbsp;<a href='./pdf_reports/prenatal.php?page=1' target='new'>Page 1</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='./pdf_reports/prenatal.php?page=2' target='new'>Page 2</a>";

		endif;			
	}

	function process_postpartum(){
		if($_SESSION[brgy]=='all'):
			$q_postpartum = mysql_query("SELECT a.postpartum_date, a.mc_id, a.patient_id,b.delivery_date,b.breastfeeding_asap,	b.date_breastfed FROM m_consult_mc_postpartum a, m_patient_mc b WHERE a.postpartum_date BETWEEN '$_SESSION[sdate]' and '$_SESSION[edate]' AND a.mc_id=b.mc_id AND a.visit_sequence='1' ORDER by b.delivery_date ASC") or die(mysql_error());

		else:		
			$q_postpartum = mysql_query("SELECT a.postpartum_date, a.mc_id, a.patient_id,b.delivery_date,b.breastfeeding_asap,	b.date_breastfed FROM m_consult_mc_postpartum a, m_patient_mc b, m_family_members c,m_family_address d WHERE a.postpartum_date BETWEEN '$_SESSION[sdate]' and '$_SESSION[edate]' AND a.mc_id=b.mc_id AND a.visit_sequence='1' AND a.patient_id=c.patient_id AND c.family_id=d.family_id AND d.barangay_id='$_SESSION[brgy]' ORDER by b.delivery_date ASC") or die(mysql_error());

		endif;
		
		if(mysql_num_rows($q_postpartum)!=0):
			$r_post_px = array();
			$r_post_mc = array();

			while($r_postpartum = mysql_fetch_array($q_postpartum)){
				array_push($r_post_px,$r_postpartum[patient_id]);
				array_push($r_post_mc,$r_postpartum[mc_id]);
			}

			$_SESSION[r_post_px] = $r_post_px;
			$_SESSION[r_post_mc] = $r_post_mc;

			echo "Show Postpartum TCL:&nbsp;&nbsp;&nbsp;<a href='./pdf_reports/postpartum.php?page=1' target='new'>Page 1</a>";
		else:
			echo "<font color='red'>No record/s found.</font>";
		endif;

	}

	function process_mc_indicators(){
		echo "<a href='./pdf_reports/mc_summary.php' target='new'>Show Maternal Care Summary Table</a>";
	}

	function process_underone(){
		if($_SESSION[brgy]=='all'):
			$q_ccdev = mysql_query("SELECT ccdev_id, patient_id FROM m_patient_ccdev WHERE date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' ORDER by date_registered ASC, ccdev_timestamp ASC") or die("Cannot query: 629");
		else:
			$q_ccdev = mysql_query("SELECT a.ccdev_id,a.patient_id FROM m_patient_ccdev a,m_family_members b,m_family_address c WHERE a.patient_id=b.patient_id AND b.family_id=c.family_id AND c.barangay_id='$_SESSION[brgy]' ORDER by date_registered ASC, ccdev_timestamp ASC") or die("Cannot query: 631");
		endif;

		if(mysql_num_rows($q_ccdev)!=0):
			$r_ccdev_id = array();
			$r_pxid = array();

			while(list($ccdev_id,$pxid)=mysql_fetch_array($q_ccdev)){
				array_push($r_ccdev_id,$ccdev_id);
				array_push($r_pxid,$pxid);
			}
			$_SESSION[ccdev_id]	= $r_ccdev_id;
			$_SESSION[ccdev_pxid] = $r_pxid;
			
			echo "Show Child Care Under 1 TCL:&nbsp;<a href='./pdf_reports/ccdev_underone.php?page=1'>Page 1</a>&nbsp;&nbsp;<a href='./pdf_reports/ccdev_underone.php?page=2'>Page 2</a>";
		else:
			echo "<font class='red'>No result/s found.</font>";
		endif;
	}

	function process_sickchild(){
		$array_sakit = array('measles','severe pneumonia','persistent diarrhea','malnutrition','xerophthalmia','night blindness','bitot','corneal xerosis','corneal ulcerations','keratomalacia','anemia','diarrhea','pneumonia');
		$r_consultid = array();
		$r_dates = array();

		for($i=0;$i<count($array_sakit);$i++){
			$r_sakit = explode(' ',$array_sakit[$i]);

			if($_SESSION[brgy]=='all'):
				$str_sick = "SELECT a.consult_id,a.patient_id,d.patient_lastname,date_format(a.consult_date,'%Y-%m-%d') FROM m_consult a, m_consult_notes b, m_lib_notes_dxclass c,m_patient d,m_consult_notes_dxclass e WHERE a.consult_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.consult_id=b.consult_id AND a.patient_id=d.patient_id AND round((TO_DAYS(a.consult_date)-TO_DAYS(d.patient_dob))/7,2) <= 260 AND b.notes_id=e.notes_id AND c.class_id=e.class_id";

			else:
				$str_sick = "SELECT a.consult_id,a.patient_id,d.patient_lastname,date_format(a.consult_date,'%Y-%m-%d') FROM m_consult a, m_consult_notes b, m_lib_notes_dxclass c,m_patient d,m_consult_notes_dxclass e,m_family_members f,m_family_address g WHERE a.consult_date BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND a.consult_id=b.consult_id AND a.patient_id=d.patient_id AND round((TO_DAYS(a.consult_date)-TO_DAYS(d.patient_dob))/7,2) <= 260 AND b.notes_id=e.notes_id AND c.class_id=e.class_id AND a.patient_id=f.patient_id AND f.family_id=g.family_id AND g.barangay_id='$_SESSION[brgy]'";
			endif;			
			
			for($j=0;$j<count($r_sakit);$j++){
				$str_sick .= " AND c.class_name LIKE '%$r_sakit[$j]%'";
			}

		$q_sick = mysql_query($str_sick) or die(mysql_error());					
		
			if(mysql_num_rows($q_sick)>0):
				while(list($consult_id,$pxid,$pxlname,$consult_date) = mysql_fetch_array($q_sick)){
					array_push($r_consultid,$consult_id);
					array_push($r_dates,$consult_date);
				}
			endif;
		}
			//echo count($r_dates);
		if(!empty($r_consultid)):
			//$r_consultid = array_unique($r_consultid);
			//$r_dates = array_unique($r_dates);

			array_multisort($r_dates,SORT_ASC,$r_consultid); //pang-sort ng consultation dates

			$_SESSION[ccdev_consultid] = $r_consultid;

			echo "Show Child Care Sick Child TCL:&nbsp;<a href='./pdf_reports/ccdev_sick.php?page=1'>Page 1</a>&nbsp;&nbsp;<a href='./pdf_reports/ccdev_sick.php?page=2'>Page 2</a>";

		else:
			echo "<font color='red'>No result/s found.</font>";
		endif;
	}	

	function process_ccdev_summary(){
		echo "<a href='./pdf_reports/ccdev_summary.php'>Show Child Care Summary Table</a>";
	}
	
	function process_fp_tcl(){
			
		//check the existence of any FP patient record that passed the criteria for query
		if($_SESSION[brgy]=='all'):
			//commented $q_fp checks if the record is not yet a drop out, uncommented does not
			//$q_fp = mysql_query("SELECT a.patient_id,b.fp_px_id FROM m_patient_fp a, m_patient_fp_method b WHERE a.patient_id=b.patient_id AND a.fp_id=b.fp_id AND b.date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.drop_out='N' AND b.method_id='$_SESSION[fp_method]' ORDER by b.date_registered DESC") or die("Cannot query (704): mysql_error()");
			 
			$q_fp = mysql_query("SELECT a.patient_id,b.fp_px_id FROM m_patient_fp a, m_patient_fp_method b WHERE a.patient_id=b.patient_id AND a.fp_id=b.fp_id AND b.date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.method_id='$_SESSION[fp_method]' ORDER by b.date_registered DESC") or die("Cannot query (704): mysql_error()");			
		else:			
			//$q_fp = mysql_query("SELECT a.patient_id,b.fp_px_id FROM m_patient_fp a, m_patient_fp_method b,m_family_members c,m_family_address d WHERE a.patient_id=b.patient_id AND a.fp_id=b.fp_id AND b.date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.drop_out='N' AND b.method_id='$_SESSION[fp_method]' AND a.patient_id=c.patient_id AND c.family_id=d.family_id AND d.barangay_id='$_SESSION[brgy]' ORDER by b.date_registered DESC") or die("Cannot query (710): mysql_error()");
			$q_fp = mysql_query("SELECT a.patient_id,b.fp_px_id FROM m_patient_fp a, m_patient_fp_method b,m_family_members c,m_family_address d WHERE a.patient_id=b.patient_id AND a.fp_id=b.fp_id AND b.date_registered BETWEEN '$_SESSION[sdate2]' AND '$_SESSION[edate2]' AND b.method_id='$_SESSION[fp_method]' AND a.patient_id=c.patient_id AND c.family_id=d.family_id AND d.barangay_id='$_SESSION[brgy]' ORDER by b.date_registered DESC") or die("Cannot query (710): mysql_error()");			
		endif;
	
		//if there is a query result, save the content in an array 
		
		if(mysql_num_rows($q_fp)!=0):
			$r_fp_px = array(); //this should contain the patient id of FP px who qualified to the query
			$r_fp_id = array(); //this should contain the fp method id for the FP patients
			
			while(list($pxid,$fpid)=mysql_fetch_array($q_fp)){
				array_push($r_fp_px,$pxid);
				array_push($r_fp_id,$fpid);
			}
			
			$_SESSION[fp_px] = $r_fp_px;
			$_SESSION[fp_method_id] = $r_fp_id;
			
			echo "Show Family Planning TCL:&nbsp;<a href='./pdf_reports/fp_tcl.php?page=1'>Page 1</a>&nbsp;&nbsp;<a href='./pdf_reports/fp_tcl.php?page=2'>Page 2</a>";			
		else:
			echo "<font color='red'>No result/s found.</font>";
		endif;
	}
	
	function process_fp_summary(){
		echo "<a href='./pdf_reports/fp_summary.php'>Show Family Planning Summary Table</a>";
	}

}
?>