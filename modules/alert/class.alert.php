<?php

class alert extends module{

	function alert(){
		$this->description = "CHITS Reminder and Alert Module";
		$this->version = "0.1-".date('Y-m-d');
		$this->authod = "darth_ali";
		$this->module = "alert";
		
		$this->mods = array('mc'=>array("Maternal Care"),'ccdev'=>array("Expanded Program for Immunization"),'fp'=>array("Birth Spacing / Family Planning"));
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
  			`module_id` varchar(50) NOT NULL,`label` text NOT NULL,
  			`date_pre` date NOT NULL,`date_until` date NOT NULL,
  			`alert_message` text NOT NULL,`alert_action` text NOT NULL,
  			`date_basis` varchar(50) NOT NULL,`alert_url_redirect` date NOT NULL,
  			PRIMARY KEY (`alert_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");				
	}

	function drop_tables(){
		module::execsql("DROP TABLE `m_lib_alert_type`;");
	}



	// custom-built functions
	
	function _alert_type(){
		echo "this is the container for the alert and reminder adminstration interface.";
		echo "<table border='1'>";
		echo "<tr><td width='65%'>";
		
		echo "<table>";
		echo "<thead colspan='2'>REMINDER & ALERT ADMINISTRATION</thead>";
		
		echo "<tr>";		
		echo "<td>Reminder/Alert Label</td>";
		echo "<td>";
		echo "<input type='text' name='txt_label' size='25'></input>";
		echo "</td>";								
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>Health Program</td>";		
		echo "<td>";
		echo "<select name='sel_mods' size='1'>";
		foreach($this->mods as $key=>$value){
			foreach($value as $key2=>$value2){
				echo "<option value='$key'>$value2</option>";
				
			}
		}
		echo "</select>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td valign='top'>Reminder/Alert Message</td>";
		echo "<td>";
		echo "<textarea name='txt_msg' cols='25' rows='3'>";
		echo "</textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td valign='top'>Recommended Actions</td>";
		echo "<td>";
		echo "<textarea name='txt_msg' cols='25' rows='3'>";
		echo "</textarea>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td valign='top'>No. of Days Reminder is posted before base date</td>";
		echo "<td>";
		echo "<select name='sel_days_before' size='1'>";
		
		for($i=0;$i<=100;$i++){
			echo "<option value='$i'>$i</option>";
		}
		
		echo "</select>";		
		echo "&nbsp;&nbsp;days</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>No. of Days Reminder is posted after base date</td>";
		echo "<td>";
		echo "<select name='sel_days_after' size='1'>";
		
		for($i=0;$i<=100;$i++){
			echo "<option value='$i'>$i</option>";
		}
		echo "</select>";		
		echo "&nbsp;&nbsp;days</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>Base Date for Reminder/Alert</td>";
		echo "<td>";
		echo "<select name='sel_base_date' size='1'>";  //list will display date fields based on selected health program
		
		echo "<option value='test'>test date</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>URL for data entry</td>";
		echo "<td>";
		echo "<input type='text' name='txt_label' size='25'></input>";
		echo "</td>";
		echo "</tr>";

		echo "</table>";

		echo "</td>";

		echo "<td>";
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		echo "</td>";


		echo "<td>";
		
		echo "<table>";
		echo "<thead>LIST of REMINDERS & ALERTS</thead>";
		echo "</table>";
		
		echo "</td>";

		echo "</table>";
	}

	function _alert(){
		echo "this is the container for the alert and reminder master list";
	}

}
	
	
?>