<?
  class widgets{
  
    function widgets(){
      $this->appName="CHITS Query Browser";
      $this->appVersion="0.17";
      $this->appAuthor="Alison Perez";
    }
  
    function query_class($dbquery,$classification,$query){
	  mysql_select_db($dbquery);
	  $query_cat = mysql_query("SELECT cat_id, cat_label FROM ques_cat ORDER by cat_label ASC") or die(mysql_error());
	

	  if(mysql_num_rows($query_cat)!=0):

	  echo "<table border=\"1\">";
          echo "<tr><td colspan=\"2\">Select Classification</td></tr>";
	  echo "<tr>";
	  echo "<td>Classification</td>";

  	  echo "<form action=\"$_SERVER[PHP_SELF]\" name=\"form_cat\" method=\"post\">";
	  echo "<td><select size=\"1\" name=\"sel_class\" onChange=\"autoSubmit();\">";
	  echo "<option value=\"0\">---SELECT CATEGORY---</option>";
	  while($res_ques=mysql_fetch_array($query_cat)){
		if($res_ques[cat_id]==$classification):
			echo "<option value=\"$res_ques[cat_id]\" SELECTED>$res_ques[cat_label]</option>";
		else:
			echo "<option value=\"$res_ques[cat_id]\">$res_ques[cat_label]</option>";
		endif;
	  }

	  echo "</select>";
  	  echo "</form>";

	  echo "</td></tr>";
	
	  if(isset($classification)):
	  
	  $query_ques = mysql_query("SELECT ques_id, ques_label, report_type FROM question WHERE cat_id='$classification' AND visible='Y' ORDER by ques_label ASC") or die("Cannot query: 40");

			  if(mysql_num_rows($query_ques)!=0):
			  
				echo "<tr>";
				echo "<td>Queries</td>";

				echo "<form action=\"$_SERVER[PHP_SELF]\" name=\"form_ques\" method=\"post\">";
				echo "<td><select size=\"1\" name=\"sel_ques\" onChange=\"submitQues();\">";
				echo "<option value=\"0\">--SELECT QUERY--</option>";

			  while($res_ques=mysql_fetch_array($query_ques)){
				
				if($res_ques[ques_id]==$query):
					echo "<option value=\"$res_ques[ques_id]\" SELECTED>$res_ques[ques_label]</option>";
				else:
					echo "<option value=\"$res_ques[ques_id]\">$res_ques[ques_label]</option>";
				endif;
			  }

			  echo "</select>";
			  echo "</form>";

			  echo "</td></tr>";	  

			  endif;	  
	  endif;

	  

      echo "</table>";
	  endif;
    }  


	function query_cat($dbname,$dbname2,$psdate,$pedate,$pbrgy){      	  

	  mysql_select_db($dbname2);
	  $query_cat = mysql_query("SELECT cat_label FROM ques_cat WHERE cat_id='$_SESSION[cat]'") or die("Cannot query: 77");
	  $query_ques = mysql_query("SELECT ques_label FROM question WHERE ques_id='$_SESSION[ques]'") or die("Cannot query: 78");
	  

	  $res_cat = mysql_fetch_array($query_cat);
	  $res_ques = mysql_fetch_array($query_ques);


	  
	  mysql_select_db($dbname);
	  echo "<form action=\"$_SERVER[PHP_SELF]\" method=\"POST\" name=\"form_query\">";
	  echo "<input type=\"hidden\" name=\"ques_name\" value=\"$res_ques[ques_label]\"></input>";

	  echo "<table border=\"1\">";
	  echo "<tr><td colspan=\"2\">SET FILTERS ($res_ques[ques_label])</td></tr>";		
        
		$query_brgy = mysql_query("SELECT barangay_id,barangay_name FROM m_lib_barangay ORDER by barangay_name ASC") or die(mysql_error());

		$set_filter = $this->get_filter();
		
		if($set_filter == 1): // start date, end date and barangay dropdown list


		echo "<tr><td>Start Date (yyyy-mm-dd)</td>";
		echo "<td><input name=\"sdate\" type=\"text\" size=\"12\" maxlength=\"10\" value=\"$psdate\" readonly></input>";		
		echo "<a href=\"javascript:show_calendar4('document.form_query.sdate', document.form_query.sdate.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";				
		echo "</td></tr>";
                echo "<tr><td>End Date (yyyy-mm-dd)</td>";
                echo "<td><input name=\"edate\" type=\"text\" size=\"12\" maxlength=\"10\" value=\"$pedate\" readonly>";
		echo "<a href=\"javascript:show_calendar4('document.form_query.edate', document.form_query.edate.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>";		
		echo "</td></tr>";
		echo "<tr><td>Barangay</td>";

		if(mysql_num_rows($query_brgy)!=0):        
		        echo "<td><select name=\"sel_brgy\" size=\"1\">";
                        echo "<option value=\"all\">All Barangay</option>";
                                while($res_brgy=mysql_fetch_array($query_brgy)){                  
                                        if($pbrgy==$res_brgy[barangay_id]):
                                                echo "<option value=\"$res_brgy[barangay_id]\" SELECTED>$res_brgy[barangay_name]</option>";
                                        else:
                                                echo "<option value=\"$res_brgy[barangay_id]\">$res_brgy[barangay_name]</option>";
                                        endif;
                                }
          
                        echo "</select></td>";                          
                else:
                        echo "<td>No barangays found</td>";
                endif;
        
                echo "</tr>";                        
		
		elseif($set_filter=='3'):
		        $this->disp_filter_quarterly($query_brgy);
                elseif($set_filter=='4'):
                        $this->disp_filter_monthly($query_brgy);
		else:
			$this->disp_filter_form2($query_brgy);
		endif;
        
        $this->additional_filter($_SESSION["ques"],"FP Methods");
        
        
        echo "<tr align=\"center\">";
        echo "<td colspan=\"2\"><input type=\"submit\" name=\"q_submit\" value=\"Submit\" target=\"new\"></input>&nbsp;&nbsp;&nbsp;";
        echo "<input type=\"reset\" name=\"q_reset\" value=\"Reset\"></input></td>";
        echo "</tr>";

      
      echo "</table>";      

    } 

	function get_filter(){ //set filter determines what date and barangay form shall be displayed. summary tables usually uses checkbox for brgy while tcl's are using dropdown list
                
                $q_type = mysql_query("SELECT report_type FROM question WHERE ques_id='$_SESSION[ques]'") or die("Cannot query (147)".mysql_error());
                list($report_type) = mysql_fetch_array($q_type);
                
 		if($report_type=='S'): //for other question codes, just add || here. this is for summary tables.
			$_SESSION[filter] = 2;
                elseif($report_type=='Q'):
                        $_SESSION[filter] = 3;
                elseif($report_type=='M'):
                        $_SESSION[filter] = 4;
		else:
			$_SESSION[filter] = 1;
		endif;
		
                return $_SESSION[filter];	
	}

	function disp_filter_form2($q_brgy){
		$buwan_label = array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June',07=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
                $buwan = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
		$_SESSION[months] = $buwan_label;

		echo "<tr><td>Start Month</td>";

		echo "<td>";
		echo "<select name='smonth' size='1'>";
		foreach($buwan as $key=>$value){
			echo "<option value='$key'>$value</option>";	
		}
		echo "</select>";
		echo "</td></tr>";


		echo "<tr><td>End Month</td>";
		echo "<td>";
		echo "<select name='emonth' size='1'>";
		foreach($buwan as $key=>$value){                        		
			echo "<option value='$key'>$value</option>";	
		}
		echo "</select>";
		echo "</td></tr>";


		echo "<tr><td>Year</td>";
		
		echo "<td><select name='year' size='1'>";

		for($i = (date('Y')-5);$i< (date('Y')+5);$i++){					
			if($i==date('Y')):
				echo "<option value='$i' selected>$i</option>";
			else:
				echo "<option value='$i'>$i</option>";
			endif;
		}
		echo "</select></td></tr>";

		
		
		echo "<tr><td valign='top'>Barangay</td><td>";
		
		echo "<input type='checkbox' name='brgy[]' value='all' checked>All</input>&nbsp;";
		$counter = 1;
		while(list($brgyid,$brgyname)=mysql_fetch_array($q_brgy)){
			echo "<input type='checkbox' name='brgy[]' value='$brgyid'>$brgyname</input>&nbsp;";
			$counter++;
			if(($counter%4)==0):
				echo "<br>";
			endif;
		}
		echo "</td></tr>";

	}
	
	function additional_filter($ques_id,$label){
        
        
        if($ques_id==40): //if the query is about FP TCL, display another list showing the FP methods
                $q_fp_method = mysql_query("SELECT method_name, method_id, fhsis_code FROM m_lib_fp_methods ORDER by method_name ASC") or die("Cannot query: Check FP tables");
	
                if(mysql_num_rows($q_fp_method)!=0):
                        echo "<tr><td>$label</td>";
                        echo "<td><select name='sel_fp_method' size='1'>";
                        while(list($method_name,$method_id,$fhsis_code) = mysql_fetch_array($q_fp_method)){
                                echo "<option value='$method_id'>$method_name ($fhsis_code)</option>";
                        }
                        echo "</select></td>";
                        echo "</tr>";
                        
                        /*echo "<tr><td>Year</td>";
                        echo "<td><select name='year' size='1'>";

                                for($i = (date('Y')-5);$i< (date('Y')+5);$i++){					
                                        if($i==date('Y')):
                                                echo "<option value='$i' selected>$i</option>";
                                        else:
                                                echo "<option value='$i'>$i</option>";
                                        endif;
                                }
                        echo "</tr>";
                        */
		echo "</select></td></tr>";                                                
                endif;
                
        
         endif;        	
	}
	
	function disp_filter_quarterly($q_brgy){
	        
	                echo "<tr><td>Quarter</td>";
	                echo "<td><select name='sel_quarter' size='1'>";
	                        for($i=1;$i<5;$i++){	                
                                        echo "<option value='$i'>$i</option>";                                        
	                        }
	                                
	                echo "</select></td></tr>";
	                
	                echo "<tr><td>Year</td>";
                        echo "<td><select name='year' size='1'>";
                                for($i = (date('Y')-5);$i< (date('Y')+5);$i++){					
                                        if($i==date('Y')):
                                                echo "<option value='$i' selected>$i</option>";
                                        else:
                                                echo "<option value='$i'>$i</option>";
                                        endif; 
                                }
                        echo "</tr>";
                        
                        echo "<tr><td>Barangay</td><td>";
                        echo "<input type='checkbox' name='brgy[]' value='all' checked>All</input>&nbsp;";
                        $counter = 1;
                        while(list($brgyid,$brgyname)=mysql_fetch_array($q_brgy)){
			        echo "<input type='checkbox' name='brgy[]' value='$brgyid'>$brgyname</input>&nbsp;";
			        $counter++;
			        if(($counter%4)==0):
				        echo "<br>";
                                endif;
                        }                        
                                                
                        echo "</td></tr>";
	}
	
	function disp_filter_monthly($q_brgy){
	        
	        $buwan_label = array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June',07=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
                $buwan = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
		$_SESSION[months] = $buwan_label;

		echo "<tr><td>Month</td>";

		echo "<td>";
		echo "<select name='smonth' size='1'>";
		foreach($buwan as $key=>$value){
			echo "<option value='$key'>$value</option>";	
		}
		echo "</select>";
		echo "</td></tr>";

		

		echo "<tr><td>Year</td>";
		
		echo "<td><select name='year' size='1'>";

		for($i = (date('Y')-5);$i< (date('Y')+5);$i++){					
			if($i==date('Y')):
				echo "<option value='$i' selected>$i</option>";
			else:
				echo "<option value='$i'>$i</option>";
			endif;
		}
		echo "</select></td></tr>";

		
		
		echo "<tr><td valign='top'>Barangay</td><td>";
		
		echo "<input type='checkbox' name='brgy[]' value='all' checked>All</input>&nbsp;";
		$counter = 1;
		while(list($brgyid,$brgyname)=mysql_fetch_array($q_brgy)){
			echo "<input type='checkbox' name='brgy[]' value='$brgyid'>$brgyname</input>&nbsp;";
			$counter++;
			if(($counter%4)==0):
				echo "<br>";
			endif;
		}
		echo "</td></tr>";	
	}     
  }
?>