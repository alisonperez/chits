<?php

class alert extends module{

	function alert(){
		$this->description = "CHITS Reminder and Alert Module";
		$this->version = "0.1-".date('Y-m-d');
		$this->authod = "darth_ali";
		$this->module = "alert";
		
		$this->mods = array('mc'=>array("Maternal Care"),'epi'=>array("Expanded Program for Immunization"),'fp'=>array("Birth Spacing / Family Planning"),'notifiable'=>array("Notifiable Diseases"));
	}


	function init_deps(){
		module::set_dep($this->module,"module");
		module::set_dep($this->module, "healthcenter");
        	module::set_dep($this->module, "patient");
        	module::set_dep($this->module, "calendar");
        	module::set_dep($this->module, "ptgroup");
        	module::set_dep($this->module, "family");
        	module::set_dep($this->module, "barangay");
	}

	function init_lang(){
		
	}

	function init_stats(){

	}

	function init_help(){

	}

	function init_menu(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
		endif;
		
	
		module::set_menu($this->module,"Alert Types","LIBRARIES","_alert_type");
		module::set_menu($this->module,"Alerts","CONSULTS","_alert");
		module::set_detail($this->description,$this->version,$this->author,$this->module);
	
	}
	
	function init_sql(){
		
		//create m_lib_alert_table. this table will contain user-defined alerts and reminders
		module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_alert_type` (
			`alert_id` int(11) NOT NULL AUTO_INCREMENT,
  			`module_id` varchar(50) NOT NULL, `alert_indicator_id` int(2) NOT NULL,,
  			`date_pre` date NOT NULL,`date_until` date NOT NULL,
  			`alert_message` text NOT NULL,`alert_action` text NOT NULL,
  			`date_basis` varchar(50) NOT NULL,`alert_url_redirect` text NOT NULL,
  			PRIMARY KEY (`alert_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
			
		module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_alert_indicators` (
		  	`alert_indicator_id` int(11) NOT NULL AUTO_INCREMENT,`main_indicator` varchar(10) NOT NULL,
		  	`sub_indicator` text NOT NULL,`efhsis_code` varchar(25) NOT NULL,
		         PRIMARY KEY (`alert_indicator_id`)
		        ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;");
		        
		module::execsql("INSERT INTO `m_lib_alert_indicators` (`alert_indicator_id`, `main_indicator`, `sub_indicator`, `efhsis_code`) VALUES
		(1, 'mc', 'Quality Prenatal Visit', ''),(2, 'mc', 'Expected Date of Delivery', ''),(3, 'mc', 'Postpartum Visit', ''),(4, 'mc', 'Tetanus Toxoid Intake (CPAB)', ''),
		(5, 'mc', 'Vitamin A Intake (20,000 unit)', ''),(6, 'mc', 'Iron with Folic Acid Intake', ''),(7, 'epi', 'BCG Immunization', ''),(8, 'epi', 'DPT 1 Immunization', ''),
		(9, 'epi', 'DPT 2 Immunization', ''),(10, 'epi', 'DPT 3 Immunization', ''),(11, 'epi', 'OPV 1 Immunization', ''),(12, 'epi', 'OPV 2 Immunization', ''),
		(13, 'epi', 'OPV 3 Immunization', ''),(14, 'epi', 'Hepa B1 Immunization', ''),(15, 'epi', 'Hepa B2 Immunization', ''),(16, 'epi', 'Hepa B3 Immunization', ''),
		(17, 'epi', 'Measles Immunization', ''),(18, 'epi', 'Fully Immunized Child', ''),(19, 'epi', 'Completely Immunized Child', ''),(20, 'notifiable', 'Vitamin A Supplementation', ''),
		(21, 'notifiable', 'Diarrhea Case for 6-11 and 12-72', ''),(22, 'fp', 'Pill Intake Follow-Up', ''),(23, 'fp', 'Condom Replenishment Follow-Up', ''),
		(24, 'fp', 'IUD Follow-Up', ''),(25, 'fp', 'Injectables Follow-Up', ''),(26, 'fp', 'Pills Dropout Alert', ''),
		(27, 'fp', 'Condom Dropout Alert', ''),(28, 'fp', 'IUD Dropout Alert', ''),(29, 'fp', 'Injectables Dropout Alert', ''),
		(30, 'fp', 'Female Sterilization Dropout Alert', ''),(31, 'fp', 'Male Sterilization Dropout Alert', ''),(32, 'fp', 'NFP LAM Dropout Alert', '');");
		
	}

	function drop_tables(){
		module::execsql("DROP TABLE `m_lib_alert_type`;");
		module::execsql("DROP TABLE `m_lib_alert_indicators`;");
	}



	// custom-built functions
	
	function _alert_type(){
		echo "this is the container for the alert and reminder adminstration interface.";
		
		if($_POST[submit_alert]):
			print_r($_POST);
			$q_alert = mysql_query("SELECT alert_id,alert_indicator_id FROM m_lib_alert_type WHERE alert_indicator_id='$_POST[sel_alert_indicators]'") or die("Cannot query 74 ".mysql_error());
			
			if(mysql_num_rows($q_alert)!=0):				
				echo "<script language='javascript'>";
				echo "window.alert('There is already a definition for this alert. To update click the alert link on the right side panel.')";
				echo "</script>";				
			else:				
				
				if(empty($_POST[txt_msg]) || empty($_POST[txt_action])):
					
					echo "<script language='javascript'>";
					echo "window.alert('Please supply entriend for reminder / alert message or actions.')";
					echo "</script>";
					
				else:
					$alert_insert = mysql_query("INSERT INTO m_lib_alert_type SET module_id='$_POST[sel_mods]',alert_indicator_id='$_POST[sel_alert_indicators]',date_pre='$_POST[sel_days_before]',date_until='$_POST[sel_days_after]',alert_message='$_POST[txt_msg]',alert_action='$_POST[txt_action]'") or die("Cannot query: 107");
					
					if($alert_insert):
						echo "<script language='javascript'>";
						echo "window.alert('Alert was successfully been saved. To edit, click the alert link on the right side panel.')";
						echo "</script>";
					endif;
								
				endif;
			endif;
		endif;
		
		$vals_update = $this->set_vals_update($_GET);
		print_r($vals_update);
		
		$main_indicator = (!empty($_POST[sel_mods]))?($_POST[sel_mods]):($vals_update["module_id"]);
		

		$q_indicator = mysql_query("SELECT alert_indicator_id,main_indicator,sub_indicator FROM m_lib_alert_indicators WHERE main_indicator='$main_indicator' ORDER by sub_indicator ASC") or die("Cannot query: 94 ".mysql_error());
		
		echo "<form name='form_alert_lib' method='POST' action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]#alert'>";
		
		
		echo "<input type='hidden' name='tbl_name' value=''>";
		
		echo "<a name='alert'></a>";
		
		echo "<table border='1'>";
		echo "<tr><td width='65%'>";
		
		echo "<table>";
		echo "<thead colspan='2'>REMINDER & ALERT ADMINISTRATION</thead>";

		echo "<tr>";
		echo "<td>Health Program</td>";
		echo "<td>";
		echo "<select name='sel_mods' size='1' onchange=\"autoSubmit();\">";
		
		echo "<option value='0'>---- SELECT PROGRAM ----</option>";
		
		foreach($this->mods as $key=>$value){
			foreach($value as $key2=>$value2){
				if($key==$_POST[sel_mods]):
					echo "<option value='$key' SELECTED>$value2</option>";
				elseif($key==$vals_update["module_id"]):
					echo "<option value='$key' SELECTED>$value2</option>";
				else:
					echo "<option value='$key'>$value2</option>";
				endif;
			}
		}

		echo "</select>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr>";
		
		echo "<td>Reminder/Alert Event</td>";
		echo "<td>";
				
		echo "<select name='sel_alert_indicators' size='1'>";
		
		if(mysql_num_rows($q_indicator)!=0):
			while(list($ind_id,$main_ind,$sub_ind)=mysql_fetch_array($q_indicator)){
				if($ind_id==$vals_update["alert_indicator_id"]):
					echo "<option value='$ind_id' SELECTED>$sub_ind</option>";				
				else:
					echo "<option value='$ind_id'>$sub_ind</option>";				
				endif;
				
			}
		else:
			echo "<option value='$ind_id' disabled>$sub_ind</option>";
		endif;

		echo "</select>";

		echo "</td>";	
		echo "</tr>";

		echo "<tr>";
		echo "<td valign='top'>Reminder/Alert Message</td>";
		echo "<td>";
		echo "<textarea name='txt_msg' cols='25' rows='3'>$vals_update[alert_message]";
		echo "</textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td valign='top'>Recommended Actions</td>";
		echo "<td>";
		echo "<textarea name='txt_action' cols='25' rows='3'>$vals_update[alert_action]";
		echo "</textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td valign='top'>No. of Days Reminder is posted before base date</td>";
		echo "<td>";
		echo "<select name='sel_days_before' size='1'>";
		
		for($i=0;$i<=100;$i++){
			if($i==$vals_update[date_pre]):
				echo "<option value='$i' SELECTED>$i</option>";
			else:
				echo "<option value='$i'>$i</option>";
			endif;
		}
		
		echo "</select>";
		echo "&nbsp;&nbsp;days (setting to 0 means actual date)</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>No. of Days Reminder is posted after base date</td>";
		echo "<td>";
		echo "<select name='sel_days_after' size='1'>";
		
		for($i=0;$i<=100;$i++){
			if($i==$vals_update[date_until]):
				echo "<option value='$i' SELECTED>$i</option>";
			else:
				echo "<option value='$i'>$i</option>";
			endif;
		}
		echo "</select>";
		echo "&nbsp;&nbsp;days (setting to 0 means actual date)</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td></td>";
		echo "</tr>";

		/*echo "<tr>";
		echo "<td>Base Date for Reminder/Alert</td>";
		echo "<td>";
		echo "<select name='sel_base_date' size='1'>";  //list will display date fields based on selected health program
		
		echo "<option value='test'>test date</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		*/

		echo "<tr>";
		echo "<td>URL for data entry</td>";
		echo "<td>";
		echo "<input type='text' name='txt_url' size='25'></input>";
		echo "</td>";
		echo "</tr>";
		
		
		echo "<tr align='center'>";
		echo "<td colspan='2'>";
		echo "<input type='submit' name='submit_alert' value='Save Reminder / Alert'></input>&nbsp;&nbsp;";
		echo "<input type='reset' name='clear' value='Clear'></input>";
		echo "</td>";
		echo "</tr>";
		
		echo "</table>";

		echo "</td>";

		echo "<td>";
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		echo "</td>";


		echo "<td valign='top'>";
		
		echo "<table border='1'>";
		echo "<thead colspan='2' valign='top'>LIST of REMINDERS & ALERTS</thead>";
		
		$this->list_alert();

		echo "</table>";
		
		echo "</td>";

		echo "</table>";

		echo "</form>";
	}

	function _alert(){
		echo "this is the container for the alert and reminder master list";
	}

	function list_alert(){
		echo "<tr><td>Program</td><td>Indicators</td></tr>";
		foreach($this->mods as $key=>$value){
			$q_mods = mysql_query("SELECT a.alert_id,a.module_id,a.alert_indicator_id,a.date_pre,a.date_until,a.alert_message,a.alert_action,a.date_basis,a.alert_url_redirect,b.sub_indicator FROM m_lib_alert_type a, m_lib_alert_indicators b WHERE a.module_id='$key' AND a.alert_indicator_id=b.alert_indicator_id ORDER by b.sub_indicator ASC") or die("Cannot query 285 ".mysql_error());
			
			$rec_num = mysql_num_rows($q_mods);
			if(mysql_num_rows($q_mods)!=0):
				echo "<tr>";
				echo "<td valign='top'>$value[0]</td>";
				echo "<td>";
				while($r_ind = mysql_fetch_array($q_mods)){
					echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&indicator_id=$r_ind[alert_indicator_id]&action=update#alert'>$r_ind[sub_indicator]</a><br>";
				}
				echo "</td>";
				echo "</tr>";
			endif;
		}
	}

	function set_vals_update($get_arr){

		if($get_arr["action"]=='update'):
			
			$q_indicator = mysql_query("SELECT a.alert_id,a.module_id,a.alert_indicator_id,a.date_pre,a.date_until,a.alert_message,a.alert_action,a.date_basis,a.alert_url_redirect,b.sub_indicator FROM m_lib_alert_type a, m_lib_alert_indicators b WHERE a.alert_indicator_id='$get_arr[indicator_id]' AND a.alert_indicator_id=b.alert_indicator_id") or die("Cannot query 306 ".mysql_error());

			if(mysql_num_rows($q_indicator)!=0):
				$indicator_arr = mysql_fetch_array($q_indicator);
			endif;
		endif;
		
		return $indicator_arr;
	}

}
	
	
?>