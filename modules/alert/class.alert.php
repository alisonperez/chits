<?php

class alert extends module{

	function alert(){
		$this->description = "CHITS Reminder and Alert Module";
		$this->version = "0.1-".date('Y-m-d');
		$this->authod = "darth_ali";
		$this->module = "alert";
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
	}

	function _alert(){
		echo "this is the container for the alert and reminder master list";
	}

}
	
	
?>