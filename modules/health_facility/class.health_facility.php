<?php

class health_facility extends module{

	function health_facility(){
		$this->author="darth_ali";
		$this->version="0.1-".date("Y-m-d");
		$this->module="health_facility";
		$this->description="CHITS Module - Health Facility";

		/* the health_facility module will: 
			1. allow assigning of barangays to health facility (i.e. municipality w/ two or more centers)
			2. embed the GPS latitude and longitude of the facility for mapping purposes
			3. contains official DOH health facility code for integration purposes
			4. and many more !
		*/
	}

	function init_deps(){
		module::set_dep($this->module,"module");
		module::set_dep($this->module,"barangay");
		module::set_dep($this->module,"healthcenter");
	}	

	function init_menu(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
		endif;

		module::set_menu($this->module,"Health Facility","LIBRARIES","_health_center");

		module::set_detail($this->description,$this->version,$this->author,$this->module);
	}

	function init_help(){}

	function init_lang(){}

	function init_sql(){}

	function drop_tables(){}

	
	//----- CUSTOM BUILT FUNCTION ---- //
	
	function _health_center(){
		echo "nosila";
	}


}
?>