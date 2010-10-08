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

		module::set_menu($this->module,"Health Facility","LIBRARIES","_health_facility");

		module::set_detail($this->description,$this->version,$this->author,$this->module);
	}

	function init_help(){}

	function init_lang(){}

	function init_sql(){}

	function drop_tables(){}

	
	//----- CUSTOM BUILT FUNCTION ---- //
	
	function _health_facility(){

		if($_POST["submit_brgy"]):
			$this->assign_hf_brgy($_POST);
		endif;

		$q_health_facility = mysql_query("SELECT facility_id, facility_name FROM m_lib_health_facility ORDER by facility_name ASC") or die("Cannot query 47 ".mysql_error());

		$q_barangay = mysql_query("SELECT barangay_id, barangay_name FROM m_lib_barangay ORDER BY barangay_name ASC") or die("Cannot query 47 ".mysql_error());

		echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]' method='POST' name='form_health_facility'>";

		echo "<table border='1'>";
		echo "<tr><td colspan='2'>HEALTH FACILITY - BARANGAY MAPPING OF CATCHMENT AREA</td></tr>";
		echo "<tr>";
		
		echo "<td valign='top' colspan='2'>Health Facility&nbsp;&nbsp;<select name='sel_health_facility' size='1'>";
		
		while(list($fac_id,$fac_name)=mysql_fetch_array($q_health_facility)){
			echo "<option value='$fac_id'>$fac_name</option>";
		}
		echo "</select></td>";
		
		/*echo "<td valign='top'>Barangay&nbsp;&nbsp;<select name='sel_baragay' size='5' MULTIPLE>";
		while(list($barangay_id,$barangay_name)=mysql_fetch_array($q_barangay)){
			echo "<option value='$barangay_id'>$barangay_name</option>";
		}
		echo "</select></td>";*/
		//echo "<td><input type=>"
		echo "</tr>";

		echo "<tr>";
		echo "<td valign='center'>Select Barangay </td>";
		echo "<td>";
		echo "<table>";
		
		
		echo "<tr>";			
		echo "<select name='sel_barangay[]' size='10' MULTIPLE>";
		while(list($barangay_id,$barangay_name)=mysql_fetch_array($q_barangay)){
			echo "<option value='$barangay_id'>$barangay_name</option>";
		}
		echo "</select></td>";
		echo "</tr>";

		echo "</table>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td colspan='2' align='center'><input type='submit' name='submit_brgy' value='Assign Barangay/s to Health Facility' />&nbsp;&nbsp;&nbsp;<input type='reset' name='reset_brgy' value='Reset'></td>";
		echo "</tr>";

		echo "</table>";
		echo "</form>";


		$this->show_barangay_hf();
	}


	function assign_hf_brgy($post_vars){
		$str_brgy = implode(",",$post_vars["sel_barangay"]);
		
		echo $str_brgy;

		//$delete_hf = mysql_query("DELETE FROM m_lib_health_facility_barangay WHERE facility_id='$post_vars[sel_health_facility]'") or die("CAnnot query 106 ".mysql_error());

		foreach($post_vars["sel_barangay"] as $key=>$value){
			$q_brgy = mysql_query("SELECT barangay_id FROM m_lib_health_facility_barangay WHERE barangay_id='$value'") or die("Cannot query 116 ".mysql_error());
			if(mysql_num_rows($q_brgy)):
				echo "<script language='Javascript'>";
				echo "window.alert('Health facility and barangay was not mapped. Please check if the barangay is already assigned to a health facility. To unassign a barangay, click the REMOVE icon below next to the barangay name.')";
				echo "</script>";
			else:
				$insert_value = mysql_query("INSERT INTO m_lib_health_facility_barangay SET barangay_id='$value',facility_id='$post_vars[sel_health_facility]'") or die("Cannot query 109 ".mysql_error());
			endif;
		}

		if($insert_value):
			echo "<script language='Javascript'>";
			echo "window.alert('Health facility and barangay mapping done!')";
			echo "</script>";
		endif;
	}

	function show_barangay_hf(){
		$q_hf = mysql_query("SELECT DISTINCT(a.facility_id),b.facility_name FROM m_lib_health_facility_barangay a,m_lib_health_facility b WHERE a.facility_id=b.facility_id ORDER by b.facility_name ASC") or die("Cannot query 133 ".mysql_error());

		if(mysql_num_rows($q_hf)!=0):
			echo "<table border='1'>";
			echo "<tr><td>HEALTH FACILITY</td>";
			echo "<td>BARANGAYS</td>";
			echo "</tr>";

			while(list($fac_id,$fac_name) = mysql_fetch_array($q_hf)){	
				$arr_brgy = array();
				$q_brgy = mysql_query("SELECT a.barangay_name FROM m_lib_barangay a,m_lib_health_facility_barangay b WHERE a.barangay_id=b.barangay_id AND b.facility_id='$fac_id' ORDER BY a.barangay_name") or die("Cannot query 142 ".mysql_error());

				while(list($brgy_name) = mysql_fetch_array($q_brgy)){	
					array_push($arr_brgy,$brgy_name);
				}
				
				$str_brgy = implode(",",$arr_brgy);

				echo "<tr>";
				echo "<td>".$fac_name."</td>";
				echo "<td>".$str_brgy."</td>";
				echo "</tr>";
			}
			echo "</table>";
		else:

		endif;

	}
}
?>