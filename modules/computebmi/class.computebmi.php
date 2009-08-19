<?php

	class computebmi extends module{

		function computebmi(){
			$this->author = "alison perez";
			$this->module = "computebmi";
			$this->version = "0.1-".date("Y-m-d");
			$this->description = "computes BMI";
		}


		//standard module methods

		function init_deps(){
			module::set_dep($this->module,"module");
		}

		function init_lang(){
		        module::set_lang("THEAD_NAME", "english", "NAME", "Y");
			module::set_lang("FLABEL_BMI_HEADER","english","Compute BMI","Y");
			module::set_lang("FLABEL_BMI_HT","english","Enter Height (cm)","Y");
			module::set_lang("FLABEL_BMI_WT","english","Enter Weight (kg)","Y");
		}

		function init_sql(){
		
		}

		function init_stats(){

		}
		
		function init_help(){

		}

		function init_menu(){
			module::set_menu($this->module,"Compute BMI","PATIENTS",_computebmi);
			module::set_detail($this->description,$this->version,$this->author,$this->module);
		}

		function drop_tables(){

		}		


		//custom-built functions

		function _computebmi(){ //execution of module starts here
  		     if(func_num_args() > 0):
		            $arg_list = func_get_args();
		            $menu_id = $arg_list[0];
		            $post_vars = $arg_list[1];
		            $get_vars = $arg_list[2];
		            $isadmin = $arg_list[3];
		     endif;

			$this->form_bmi($menu_id,$post_vars,$get_vars,$isadmin);

			if($post_vars["submit_bmi"]):
				//print_r($get_vars);
				if(empty($post_vars["ht"]) && empty($post_vars["wt"])):
					echo "<script language='Javascript'>";
					echo "window.alert('Please supply height or weight.')";
					echo "</script>";
				else:
					$bmi = round($post_vars["wt"] / pow($post_vars["ht"],2),2);				
				
					if($bmi < 18.5):
						$status = "Underweight. Avoid junk foods";
					elseif($bmi >= 18.5 && $bmi < 25):
						$status = "Normal. Continue the good job!";
					elseif($bmi >= 25 && $bmi < 30):
						$status = "Overweight. Need start dieting.";
					else: //obese
						$status = "Obese. Need heavy exercise";
					endif;
					
					echo "<font color='red' size='16'>Your BMI is ".$bmi.'. Your status is <b>'.$status."</b></font>";
				endif;
			endif;
		}


		function form_bmi(){

  		     if(func_num_args() > 0):
		            $arg_list = func_get_args();
		            $menu_id = $arg_list[0];
		            $post_vars = $arg_list[1];
		            $get_vars = $arg_list[2];
		            $isadmin = $arg_list[3];
		     endif;

			echo "<form method='POST' action='".$_SERVER["SELF"]."?page=PATIENTS&menu_id=$menu_id' name='formbmi'>";
			echo "<table>";
			//echo "<thead align='center'><td rowspan='2'>Compute Your BMI</td></thead>";
			echo "<thead align='center'><td rowspan='2'>".FLABEL_BMI_HEADER."</td></thead>";
			echo "<tr><td>Enter Height (m)</td><td><input type='textbox' name='ht' size='5'></input></td></tr>";
			echo "<tr><td>Enter Weight (kg)</td><td><input type='textbox' name='wt' size='5'></input></td></tr>";
			echo "<tr align='center'><td rowspan='2'><input type='submit' name='submit_bmi' value='Compute BMI'></td></tr>";
			echo "</table>";
			echo "</form>";
		}

		


	}
?>
