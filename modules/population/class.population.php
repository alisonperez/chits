<?php

class population extends module{


	function population(){
		$this->author = 'darth_ali';
        $this->version = "0.3-".date("Y-m-d");
        $this->module = "population";
        $this->description = "CHITS Library - Population";			
		
		/*
		
		0.1 - population data entry
		0.2 - population display form
		0.3 - delete population function

		*/
	}


	function init_deps() {
        module::set_dep($this->module, "module");
    }
	
	function init_lang(){
		module::set_lang("FTITLE_POP_FORM", "english", "POPULATION DATA ENTRY", "Y");
		module::set_lang("FTITLE_POP_LIST","english","POPULATION DATA","Y");
		module::set_lang("LBL_POP_YR", "english", "YEAR", "Y");
        module::set_lang("LBL_POP_BRGY", "english", "BARANGAY", "Y");
        module::set_lang("LBL_POP_COUNT", "english", "POPULATION", "Y");
	}


	function init_sql(){
		if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

		module::execsql("CREATE TABLE `chits`.`m_lib_population` (
						`population_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
						`barangay_id` INT( 5 ) NOT NULL ,
						`population` INT( 10 ) NOT NULL ,
						`population_year` YEAR NOT NULL ,
						PRIMARY KEY ( `population_id` )
						) ENGINE = MYISAM;");		
	}

	function init_stats() {
    }

    function init_help() {
    }


	function init_menu() {
        // use this for updating menu system
        // under LIBRARIES
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }
        // _<modulename> in SQl refers to function _<modulename>() below
        // _population in SQL refers to function _barangay() below;
        module::set_menu($this->module, "Population", "LIBRARIES", "_population");

        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);
    }


	function drop_tables(){
		if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

		module::execsql("DROP TABLE `m_lib_population`");		
	}



	/* -- AWESOME CUSTOM BUILT FUNCTIONS */

	//main method for population module
	function _population(){

		if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }		
	
		//display dependecy error

		if($exitinfo = $this->missing_dependencies('population')):
        	    return print($exitinfo);
		endif;
		
		mysql_query("ALTER TABLE `m_lib_population` DROP PRIMARY KEY, ADD PRIMARY KEY(`population_id`)");

		if($post_vars["submitpop"]):
			$this->process_population($menu_id, $post_vars, $get_vars);
			//    header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");			
		endif;

		$this->form_population($menu_id, $post_vars, $get_vars);
		$this->display_population($menu_id,$post_vars,$get_vars);


	}

	function process_population(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id=$arg_list[0];
			$post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
		endif;

		switch($post_vars[submitpop]){
		
		case "Save Population":
			if(empty($_POST[popcount])):
				echo "<font color='red'>Record not saved. Please indicate population.</font>";

			elseif(!(is_numeric($_POST[popcount]))):
				echo "<font color='red'>Record not saved. Population should be a valid integer.</font>";
			else:
				$q_pop = mysql_query("SELECT barangay_id,population_year FROM m_lib_population WHERE barangay_id='$_POST[brgy]' AND population_year='$_POST[taon]'") or die("Cannot query: 119");

				if(mysql_num_rows($q_pop)==0):
					$insert_pop = mysql_query("INSERT INTO m_lib_population SET barangay_id='$_POST[brgy]',population_year='$_POST[taon]',population='$_POST[popcount]'") or die("Cannot query: 122");
					
					if($insert_pop):
						echo "<font color='red'>Record was successfully been saved.</font>";
					endif;
				else:
					echo "<font color='red'>Record not saved. There is already a population for selected barangay and year.</font>";
				endif;
			endif;

			break;

		default:

			break;

		}
	}


	function form_population(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id=$arg_list[0];
			$post_vars = $arg_list[1];
	            $get_vars = $arg_list[2];
		endif;


		$q_brgy = mysql_query("SELECT barangay_id, barangay_name FROM m_lib_barangay ORDER by barangay_name ASC") or die("cannot query 108");
		
		if(mysql_num_rows($q_brgy)!=0):

		echo "<form action='$_SERVER[PHP_SELF]?page=LIBRARIES&menu_id=$menu_id' method='POST' name='form_population'>";

		echo "<table>";
		echo "<tr><td colspan='2' align='center'><span class='library'>".FTITLE_POP_FORM."</span></td></tr>";
		echo "<tr><td><span class='boxtitle'>".LBL_POP_BRGY."</span></td>";
		
		echo "<td>";
		echo "<select name='brgy' size='1'>";
		
		while(list($brgyid,$brgyname)=mysql_fetch_array($q_brgy)){
			echo "<option value='$brgyid'>$brgyname</option>";
		}

		echo "</select>";
		echo "</td>";		
		echo "</tr>";

		echo "<tr>";
		echo "<td><span class='boxtitle'>".LBL_POP_YR."</td><td>";

		echo "<select name='taon' size='1'>";
		
		for($i=(date('Y')-5);$i<(date('Y')+5);$i++){
			if($i==date('Y')):
				echo "<option value='$i' selected>$i</option>";
			else:
				echo "<option value='$i'>$i</option>";
			endif;
		}

		echo "</select>";
		echo "</td></tr>";

		echo "<tr>";
		echo "<td><span class='boxtitle'>".LBL_POP_COUNT."</span></td>";
		echo "<td><input type='text' name='popcount' size='10'></input></td>";
		echo "</tr>";

		echo "<tr align='center'><td colspan='2' align='center'><input name='submitpop' type='submit' value='Save Population' style='border: 1px solid #000000'></input>&nbsp;&nbsp;<input name='clear' type='reset' value='Clear' style='border: 1px solid #000000'></input></td></tr>";
		echo "</table>";
		
		echo "</form>";
		
		else:
			echo "<font color='red'>Please enter barangay/s first before entering Population data (LIBRARIES > Population).</font>";
		endif;
	}

	function display_population(){

		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id=$arg_list[0];
			$post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
		endif;		
		

		$this->confirm_action($get_vars["rowid"],$get_vars["action"],$get_vars["menu_id"]);
		
		$q_brgy = mysql_query("SELECT barangay_name, barangay_id FROM m_lib_barangay ORDER by barangay_name ASC") or die("Cannot query: 213");
		
		if(mysql_num_rows($q_brgy)!=0):
		$date = date('Y');
		echo "<form method='POST' name='form_disp_pop'>";
		echo "<br><table>";
		echo "<tr><td colspan='2'><span class='library'>".FTITLE_POP_LIST.' '.date('Y')."</span></td></tr>";
		echo "<tr align='center'><td><span class='boxtitle'>BARANGAY</span></td><td><span class='boxtitle'>POPULATION</span></td><td></td></tr>";

		while(list($brgyname,$brgyid)=mysql_fetch_array($q_brgy)){
			$q_pop = mysql_query("SELECT population, population_id FROM m_lib_population WHERE barangay_id='$brgyid' AND population_year='$date'") or die(mysql_error());
				list($pop,$popid) = mysql_fetch_array($q_pop);

				echo "<tr><td>$brgyname</td><td align='center'>";

				echo (mysql_num_rows($q_pop)==0)?'0':$pop;

				echo "</td>";
				
				echo "<td align='center'><a href='$_SERVER[PHP_SELF]?page=LIBRARIES&menu_id=$menu_id&rowid=$popid&action=delete'><img src='../images/delete.png'/></a></td>";
				
				echo "</tr>";
		}
		
		echo "</table>";
		echo "</form>";

		endif;
	}
	
	function confirm_action($rowid,$action,$menu_id){
		$q_pop = mysql_query("SELECT a.population_year,b.barangay_name FROM m_lib_population a, m_lib_barangay b WHERE a.population_id='$rowid' AND a.barangay_id=b.barangay_id") or die("Cannot query: 248");
		
		list($popyr,$brgyname) = mysql_fetch_array($q_pop);

		switch($action){
		
			case "delete":
				echo "<font color='red'>Are you sure you want to delete population of $brgyname for year $popyr?</font>";
				echo "<a href='$_SERVER[PHP_SELF]?page=LIBRARIES&menu_id=$menu_id&rowid=$rowid&action=delete_final'>Yes</a>&nbsp;&nbsp;";
				echo "<a href='$_SERVER[PHP_SELF]?page=LIBRARIES&menu_id=$menu_id'>No</a>";
				break;
			
			case "delete_final":
				$del_pop = mysql_query("DELETE FROM m_lib_population WHERE population_id='$rowid'") or die("Cannot query: 261");
				
				if($del_pop):
					echo "<font color='red'>Population for $brgyname($popyr) was successfully been deleted!</font>";
				endif;
				
				break;
			default:
				echo $action;
				echo $popyr.$brgyname;
				break;				
		}
	
	}

}



?>