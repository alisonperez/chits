<?
class wtforage extends module {

    // Author: Herman Tolentino MD
    // CHITS 2004

    function wtforage() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Ariel Betan/Herman Tolentino';
        $this->version = "0.1-".date("Y-m-d");
        $this->module = "wtforage";
        $this->description = "CHITS Library - Weight for Age";
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
		module::set_dep($this->module, "healthcenter");
		module::set_dep($this->module, "patient");
		module::set_dep($this->module, "ccdev");

    }

    function init_lang() {
    //
    // insert necessary language directives
    // NOTES:
    //

    // not needed

    }

    function init_help() {
    }

    function init_menu() {
    //
    // menu entries
    // use multiple inserts (watch out for ;)
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // no menu
        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
    //
    // create module tables
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
        }

        module::execsql("CREATE TABLE `m_lib_wtforage` (".
  			"`age_month` int(11) NOT NULL default '0',".
  			"`weight_min` float NOT NULL default '0',".
  			"`weight_max` float NOT NULL default '0',".
  			"`gender` char(1) NOT NULL default '',".
  			"`wt_class` varchar(25) NOT NULL default ''".
			") TYPE=MyISAM; ");

    }

    function drop_tables() {
    //
    // called from delete_module()
    //

        module::execsql("DROP TABLE `m_lib_wtforage`");
    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _wtforage() {
    //
    // main method for wtforage
    // caution -> use only for age 5 and below
    // call method:
    // $wt_class = wtforage ($age_month, $gender, $actual_weight);
    // where $age_month = age of patient
    //              can be obtained from m_patient
    //       $gender = patient gender (M or F)
    //                 can be obtained from m_patient
    //       $weight = actual patient weight
    //                 can be obtained from m_consult_vitals
    // $wt_class: BELOW NORMAL (Very Low), BELOW NORMAL (Low), NORMAL, ABOVE NORMAL
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('wtforage')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $consult_id = $arg_list[0];
		}
        $patient_id = healthcenter::get_patient_id($consult_id);
		$age_month = round((ccdev::get_age_weeks($patient_id))/4.33,0);
		$gender = patient::get_gender($patient_id);
		$actual_weight = wtforage::get_body_weight($consult_id);
        /*
		print $sql = "select wt_class from m_lib_wtforage where age_month='$age_month' AND gender='$gender' ".
		 		"AND weight_min <= '$actual_weight' AND weight_max >= '$actual_weight'";
		if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($wt_class) = mysql_fetch_array($result);
				return $wt_class;
  		}
        */
        $sql = "select weight_min, weight_max, wt_class ".
               "from m_lib_wtforage ".
               "where age_month = '$age_month' and gender = '$gender'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($min, $max, $class) = mysql_fetch_array($result)) {
                    if ($max > $min) {
                        if ($actual_weight >= $min && $actual_weight <= $max) {
                            //print "Min $min<br/>";
                            //print "Max $max<br/>";
                            //print "Class $class<br/>";
                            $ret_val = array ($min, $max, $class);
                        }
                    }
                    if ($min === $max) {
                        if ($actual_weight >= $max) {
                            //print "Min $min<br/>";
                            //print "Max $max<br/>";
                            //print "Class $class<br/>";
                            $ret_val = array ($min, $max, $class);
                        }
                    }
                }
                return $ret_val;
            }
        }

	}

    function get_body_weight() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
			$consult_id = $arg_list[0];
        }
		$sql = "select vitals_weight from m_consult_vitals ".
               "where consult_id = '$consult_id' order by vitals_timestamp desc";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($weight) = mysql_fetch_array($result);
                return $weight;
            }
        }
    }
}
?>
