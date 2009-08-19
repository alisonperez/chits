<?php

class family_planning extends module{

	function family_planning(){ // class constructor
		$this->author = "darth_ali";
		$this->version = "0.1-".date("Y-m-d");
		$this->module = "family_planning";
		$this->description = "CHITS Module - Family Planning";
	}

	//standard module functions 

	function init_deps(){
	    module::set_dep($this->module, "module");
	    module::set_dep($this->module, "healthcenter");
	    module::set_dep($this->module, "patient");		
	}

	function init_lang(){
		module::set_lang("THEAD_FP_HEADER", "english", "FAMILY PLANNING SERVICE RECORD", "Y");
	}

	function init_stats(){

	}

	function init_help(){

	}

	function init_menu(){
		if(func_num_args()>0){
			$arg_list = func_get_args();
		}
	}

	function init_sql(){

		if(func_num_args()>0){
			$arg_list = func_get_args();
		}
		

		//m_lib_fp_methods -- create
		module::execsql("CREATE TABLE `m_lib_fp_methods` (".
			      "`method_id` varchar(10) NOT NULL default '',".
      			      "`method_name` varchar(100) NOT NULL default '',".
			      "`method_gender` SET('M','F') NOT NULL default '',".
			      "PRIMARY KEY (`method_id`)".
			      ") TYPE=MyISAM; ");
		//m_lib_fp_methods -- populate contents
	
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, 					`method_name`,`method_gender`) VALUES ('PILLS', 'Pills','F')");	    	
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, 					`method_name`,`method_gender`) VALUES ('CONDOM', 'Condom'),'M')");
	        module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, 					`method_name`,`method_gender`) VALUES ('IUD', 'IUD','F')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, 					`method_name`,`method_gender`) VALUES ('NFPLAM', 'NFP Lactational amenorrhea','F')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, 					`method_name`,`method_gender`) VALUES ('DMPA', 'Depo-Lactational Amenorrhea ','F')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, 					`method_name`,`method_gender`) VALUES ('NFPBBT', 'NFP Basal Body Temperature','F')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, 					`method_name`,`method_gender`) VALUES ('NFPCM', 'NFP Cervical Mucus Method','F')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, 					`method_name`,`method_gender`) VALUES ('NFPSTM', 'NFP Sympothermal Method','F')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, 					`method_name`,`method_gender`) VALUES ('NFPSDM', 'NFP Standard Days Method','F')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, 					`method_name`,`method_gender`) VALUES ('FSTRBTL', 'Female Sterilization /Bilateral Tubal Ligation','F')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, 					`method_name`,`method_gender`) VALUES ('MSV', 'Male Sterilization /Vasectomy','M')");	      
	}


	function drop_tables(){
		module::execsql("DROP table m_lib_fp_methods");
	}


	//custom-built functions
	//all function starts here

	function _consult_family_planning(){
		
		if($exitinfo = $this->missing_dependencies('family_planning')){
			return print($exitinfo);
		}
		
		if(func_num_args()>0){
		      $menu_id = $arg_list[0];	   //from $_GET
		      $post_vars = $arg_list[1];   //from form submissions
		      $get_vars = $arg_list[2];    //from $_GET
		      $validuser = $arg_list[3];   //from $_SESSION
		      $isadmin = $arg_list[4];	   //from $_SESSION	
		}

		$fp = new family_planning;
		$fp->fp_menu($_GET["menu_id"],$_POST,$_GET,$_SESSION["validuser"],$_SESSION["isadmin"]);
		$fp->form_fp($menu_id,$post_vars,$get_vars,$isadmin);
	}


	function form_fp(){
		switch($_GET["fp"]){

		case "VISIT1":
			$this->form_fp_visit1();
			break;
		case "HX":
			
			break;
		case "PE":

			break;

		case "PELVIC":

			break;

		case "CHART":

		
		case "SVC":		


		default:

			break;
		}
		
		echo "<table>";
		echo "<tr><td>".THEAD_FP_HEADER."</td></tr>";
		
		echo "</table>";
	}

	function fp_menu(){   			 /* displays main menus for FP */

		//this will redirect view to the VISIT1 interface
		if(!isset($get_vars[fp])){ 
			//header("location: $_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=VISIT1");
		}

		echo "<table>";
		echo "<tr><td>";
	        echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=VISIT1' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'VISIT1','VISIT1')."</a>";
				
	        echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=HX' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'HX','FP HX')."</a>";

	        echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=PE' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'PE','FP PE')."</a>";

	        echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=PELVIC' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'PELVIC','PELVIC EXAM')."</a>";

		echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=CHART' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'CHART','FP CHART')."</a>";		

		echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=SERVICES' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'SVC','SERVICES')."</a>";		

				
		echo "</td></tr>";
		echo "<table>";
	}
	
	function form_fp_visit1(){
		echo "<form name='form_visit1' action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GE[module]&fp=VISIT1' method='POST'>";

		echo "<table>";
		echo "<tr><td colspan='2'>FAMILY PLANNING DATA</td></tr>";
		echo "<tr><td>PLANNING FOR MORE CHILDREN?</td>";
		echo "<td>";
		echo "<select name='form_visit1_children' size='1'>";
		echo "<option value='Y' selected>Yes</option>";
		echo "<option value='N'>No</option>";		
		echo "</select>";
		echo "</td></tr>";

		echo "<tr><td>TYPE OF METHOD</td><td>";
		$this->get_methods("sel_method");
		echo "</td></tr>";


		echo "<tr><td>NO. OF LIVING CHILDREN</td><td>";
		echo "<input name='form_visit1_' type='text' size='3' maxlength='2'></input>";
		echo "</td></tr>";


		echo "<tr><td>HIGHEST EDUCATIONAL ATTAINMENT</td><td>";
		$this->get_education("mother_educ");
		echo "</td></tr>";

		echo "<tr><td>OCCUPATION</td><td>";
		$this->get_occupation("mother_occupation");
		echo "</td></tr>";

		echo "<tr><td>NAME OF SPOUSE</td>";
		echo "<td><input name='spouse_name' type='text' size='20' disabled></input>&nbsp;<input type='button' name='btn_search_spouse' value='Search'></input>";

		echo "</td></tr>";
		echo "<tr><td>HIGHEST EDUCATIONAL ATTAINMENT</td><td>";
		$this->get_education("spouse_educ");
		echo "</td></tr>";

		echo "<tr><td>OCCUPATION</td><td>";
		$this->get_occupation("spouse_occupation");
		echo "</td></tr>";

		echo "<tr><td>AVERAGE MONTHLY FAMILY INCOME</td>";
		echo "<td>";
		echo "<input name='ave_income' type='text' size='5'></input>";
		echo "</td></tr>";

		
		echo "</table>";

		echo "</form>";
	}


	function menu_highlight(){
		if(func_num_args()>0){
			$val = func_get_args();
			$get_val = $val[0];
			$str = $val[1];	
			$disp_str = $val[2];
		}

		if(strtoupper($get_val)==$str):
			return "<b>".$disp_str."</b>";
		else:
			return $disp_str;
		endif;
	}


	function _details_family_planning(){
		if(func_num_args()>0){
			$menu_id = $arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
			$validuser = $arg_list[3];
			$isadmin = $arg_list[4];		
		}
		

	}

	function get_education($form_name){
		$q_educ = mysql_query("select * from m_lib_education order by educ_name") or die("cannot query 187");

		if(mysql_num_rows($q_educ)!=0):
			echo "<select name='$form_name' size='1'>";
			while($r_educ = mysql_fetch_array($q_educ)){
				echo "<option value='$r_educ[educ_id]'>$r_educ[educ_name]</option>";
			}
			echo "</select>";
		else:
			echo "<font color='red'>Education library not found.</font>";
		endif;
	}

	function get_occupation($form_name){
		$q_job = mysql_query("SELECT occup_id, occup_name FROM m_lib_occupation ORDER by occup_name") or die("Cannot query: 187");
		
		if(mysql_num_rows($q_job)!=0):
			echo "<select name='$form_name' size='1'>";

			while($r_job = mysql_fetch_array($q_job)){
				echo"<option value='$r_job[occup_id]'>$r_job[occup_name]</option>";
			}
			echo "</select>";
		else:
			echo "<font color='red'>Occupation library not found.</font>";
		endif;
	}

	function get_methods($form_name){
		$q_methods = mysql_query("SELECT method_id,method_name FROM m_lib_fp_methods ORDER by method_name ASC") or die("Cannot query: 268");

		if(mysql_num_rows($q_methods)!=0):
			echo "<select name='$form_name'>";
			while($r_methods = mysql_fetch_array($q_methods)){
				echo "<option value='$r_methods[method_id]'>$r_methods[method_name]</option>";	
			}
			echo "</select>";
		else:
		endif;
	}
}
?>










