<?php

class weekly_calendar extends module{


	function weekly_calendar(){
		$this->author = 'darth_ali';
		$this->version = "0.3-".date("Y-m-d");
		$this->module = "weekly_calendar";
		$this->description = "CHITS Library - Weekly Calendar";
		
		/*
		
		0.1 - front-end interface for entering morbidity calendar per week
		0.2 - view functionality of previous year's morbidity weekly calendar
		0.3 - delete and update function
		0.4 - added year selector
		*/
	}


	function init_deps() {
        	module::set_dep($this->module, "module");
        }
	
	
	function init_lang(){
		module::set_lang("FTITLE_WEEKLY_FORM", "english", "MORBIDITY WEEKLY CALENDAR", "Y");		
		module::set_lang("LBL_YR", "english", "YEAR", "Y");
		module::set_lang("LBL_WEEK", "english", "WEEK", "Y");		
		module::set_lang("LBL_SDATE", "english", "START DATE", "Y");
		module::set_lang("LBL_EDATE", "english", "END DATE", "Y");
	}


	function init_sql(){
		if (func_num_args()>0) {
		$arg_list = func_get_args();
		}

		module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_weekly_calendar` (				
				`year` YEAR( 4 ) NOT NULL ,
				`week` INT( 2 ) NOT NULL,
				`start_date` DATE NOT NULL ,
				`end_date` DATE NOT NULL
				) ENGINE = MYISAM DEFAULT CHARSET=latin1");		
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
        // _weekly_calendar in SQL refers to function _weekly_calendar() below;
        	module::set_menu($this->module, "Weekly Calendar", "LIBRARIES", "_weekly_calendar");

        // put in more details
	        module::set_detail($this->description, $this->version, $this->author, $this->module);
        }


	function drop_tables(){
		if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

		module::execsql("DROP TABLE `m_lib_weekly_calendar`");		
	}



	/* -- CUSTOM BUILT FUNCTIONS FOR weekly_calendar */

	//main method for population module
	function _weekly_calendar(){

		if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }		
	
		//display dependecy error

		if($exitinfo = $this->missing_dependencies('weekly_calendar')):
			return print($exitinfo);
		endif;


		if($post_vars["submit_calendar"]):
			$this->process_calendar($menu_id, $post_vars, $get_vars);
		        //    header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");
		endif;

		$this->form_weekly_calendar($menu_id, $post_vars, $get_vars);
		$this->display_calendar($menu_id,$post_vars,$get_vars);


	}

	function process_calendar(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id=$arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
		endif;
		
		//add action button here!
		//print_r($_POST);
		
		if(!empty($_POST[txt_sdate]) && !empty($_POST[txt_edate])):
		
		list($smonth,$sdate,$syear) = explode('/',$_POST[txt_sdate]);
		list($emonth,$edate,$eyear) = explode('/',$_POST[txt_edate]);
		
		$date_start = gregoriantojd($smonth,$sdate,$syear);
		$date_end = gregoriantojd($emonth,$edate,$eyear);
		$diff = $date_end - $date_start;
		
		if($diff < 0):
			echo "<font color='red'>Start date should be before the end date.</font>";
		else:
		
			$start_date = $syear.'-'.$smonth.'-'.$sdate;
			$end_date = $eyear.'-'.$emonth.'-'.$edate;
			
			
			$q_cal = mysql_query("SELECT year,start_date,end_date,week FROM m_lib_weekly_calendar WHERE year='$_POST[taon]' AND week='$_POST[sel_week]'") or die("Cannot query: 125");
		
			if(mysql_num_rows($q_cal)!=0):
				
				$update_cal = mysql_query("UPDATE m_lib_weekly_calendar SET start_date='$start_date',end_date='$end_date' WHERE week='$_POST[sel_week]' AND year='$_POST[taon]'") or die("Cannot query 128 ".mysql_error());
			else:
				$update_cal = mysql_query("INSERT INTO m_lib_weekly_calendar SET year='$_POST[taon]',week='$_POST[sel_week]',start_date='$start_date',end_date='$end_date'") or die("Cannot query 147 ".mysql_error());
			endif;
			
			if($update_cal):
				echo "<font color='red'>Calendar was successfully been updated.</font>";
			else:
				echo "<font color='red'>Calendar was not updated.</font>";				
			endif;
		endif;
		
		else:
			echo "<font color='red'>Start and end date cannot be empty.</font>";			
		endif;
		
	}


	function form_weekly_calendar(){
		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id=$arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
		endif;
		
		echo "<form name='form_calendar' method='POST'>";
		
		echo "<table>";
		echo "<tr><td colspan='2' align='center'><span class='library'>".FTITLE_WEEKLY_FORM."</span></td></tr>";		
		$this->show_year();
		
		echo "<tr><td class='boxtitle'>".LBL_WEEK."</td>";
		echo "<td><select name='sel_week' value='1'>";
		
		for($i=1;$i<53;$i++){
			echo "<option value='$i'>$i</option>";
		}
		echo "</select></td></tr>";		
		
		echo "<tr><td class='boxtitle'>".LBL_SDATE."</td>";
		echo "<td><input type='text' name='txt_sdate' maxlength='10' size='7'></input>&nbsp;";
		echo "<a href=\"javascript:show_calendar4('document.form_calendar.txt_sdate', document.form_calendar.txt_sdate.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
		echo "</td>";						
		echo "</tr>";
		
		
		echo "<tr><td class='boxtitle'>".LBL_EDATE."</td>";
		echo "<td><input type='text' name='txt_edate' maxlength='10' size='7'></input>&nbsp;";
		echo "<a href=\"javascript:show_calendar4('document.form_calendar.txt_edate', document.form_calendar.txt_edate.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";
		echo "</td>";
		echo "</td>";
		
		
		echo "<tr align='center'><td colspan='2'>";
		echo "<input type='submit' name='submit_calendar' value='Add Calendar Week' style='border: 1px solid #000000'>&nbsp;";
		echo "<input type='reset' name='reset' value='Reset' style='border: 1px solid #000000'></input>";
		echo "</td></tr>";
		
		
		echo "</table>";
		
		echo "</form>";
	}

	function display_calendar(){

		if(func_num_args()>0):
			$arg_list = func_get_args();
			$menu_id=$arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
		endif;				
		
		echo "<form name='form_disp_calendar' method='POST' action='$_SERVER[PHP_SELF]?page=$get_vars[page]&menu_id=$get_vars[menu_id]'>";
		
		echo "<table>";
		
		echo "<tr><td colspan='2' class='center'><span class='library'>View Weekly Calendar</span></td>";
		$this->show_year();
		echo "<tr><td colspan='2' align='center'><input type='submit' name='submit_year' value='Select Year' style='border: 1px solid #000000'></td></tr>";						
		echo "</table>";
		echo "</form>";
		
		
		if($post_vars["submit_year"]):
			$disp_year = $_POST["taon"];
		else:
			$disp_year = date('Y');
		endif;
		
		echo "<table><tr><td colspan='3'><span class='library'>MORBIDITY WEEKLY CALENDAR FOR $disp_year</span></td></tr>";
		
		echo "<tr align='center'><td>WEEK #</td><td>START DATE</td><td>END DATE</td>";
		for($i=1;$i<53;$i++){
			$q_cal = mysql_query("SELECT date_format(start_date,'%m/%d/%Y'), date_format(end_date,'%m/%d/%Y') FROM m_lib_weekly_calendar WHERE year='$disp_year' AND week='$i'") or die("Cannot query 243 ".mysql_error());
			if(mysql_num_rows($q_cal)==0):				
				$sdate = '-';
				$edate = '-';
			else:
				list($sdate,$edate) = mysql_fetch_array($q_cal);
			endif;
			
			echo "<tr align='center'>";
			echo "<td>$i</td>";
			echo "<td>$sdate</td>";
			echo "<td>$edate</td>";
			echo "</tr>";	
		}
		
		echo "</table>";
		
	}
	
	
	function show_year(){
		
						
		echo "<tr>";
		echo "<td><span class='boxtitle'>".LBL_YR."</td><td>";

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
			
	
	
	}

}
?>