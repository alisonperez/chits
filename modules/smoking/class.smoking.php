<?php
	class smoking extends module{

	function smoking(){
		$this->author = 'darth_ali';
        $this->version = "0.3-".date("Y-m-d");
        $this->module = "smoking";
        $this->description = "CHITS Smoking";		
	}

	function init_deps(){
		module::set_dep($this->module,"module");
	}

	function init_lang(){
		module::set_lang("FLABEL_SMOKE","Smoking?");
		module::set_lang("FLABEL_NUM","Number of Stick/Days");
	}

	function init_sql(){
		module::execsql("ALTER TABLE `m_patient` ADD `smoking_flag` SET( 'Y', 'N' ) NOT NULL DEFAULT 'N' AFTER `uniquid`");
		module::execsql("ALTER TABLE `m_patient` ADD `num_sticks` INT( 3 ) NOT NULL AFTER `smoking_flag`");
	}

	function init_stats(){
	}

	function init_help(){

	}

	function init_menu(){
        module::set_detail($this->description, $this->version, $this->author, $this->module);
	}

	function drop_table(){
	
	}

	//custom-built function
	function _smoking(){
		if (func_num_args()>0) {
  	          $arg_list = func_get_args();
  	          $menu_id = $arg_list[0];
  	          $post_vars = $arg_list[1];
  	          $get_vars = $arg_list[2];
  	          $validuser = $arg_list[3];
  	          $isadmin = $arg_list[4];
  	      }
		if($exitinfo = $this->missing_dependencies('population')):
  	          return print($exitinfo);
		endif;

		if($post_vars["submitsmoke"]):
			$this->process_smoke($menu_id, $post_vars, $get_vars);
		endif;
		
		$this->form_smoke($menu_id, $post_vars, $get_vars);
	}

	function form_smoke($pxid){
		$smoke_info = $this->get_smoker_detail($pxid);
		
		print "<table>";
		print "<tr><td>Smoking?</td>";
		print "<td>";
		print "<select size='1' name='flag_smoking'>";

		if($smoke_info[smoking_flag]=='Y'):
			print "<option value='Y' selected>Yes</option>";
			print "<option value='N'>No</option>";
		else:
			print "<option value='Y'>Yes</option>";
			print "<option value='N' selected>No</option>";
		endif;

		print "</select></td></tr>";
		
		
		print "<tr><td>No. of Stick / Day</td>";
		print "<td>";
		print "<input type='text' size='3' maxlength='3' name='smoke_stick' value='$smoke_info[num_sticks]'></input>";
		print "</td>";
		print "</tr>";
		print "</table><br>";

	
	}

	function get_smoker_detail($pxid){
		$q_smoke = mysql_query("SELECT smoking_flag,num_sticks FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query: 89");
		$r_smoke = mysql_fetch_array($q_smoke);
		return $r_smoke;

	}



}
?>
