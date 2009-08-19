<?
class calendar extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // for module guidelines see MODULES.TXT

    function calendar() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.2-".date("Y-m-d");
        $this->module = "calendar";
        $this->description = "CHITS Module - Calendar";
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");

    }

    function init_lang() {
    //
        // none for this module
    }

    function init_help() {
    }

    function init_menu() {
    //
    // menu entries
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }
        // none for this module
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
        // none for this module
    }

    function drop_tables() {
    //
    // called from delete_module()
    //

        // none for this module
    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _calendar() {
    //
    // main method for calendar
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('calendar')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
    }

    function display_calendar() {
    //
    // adapted from
    //
    global $day, $month, $year;

        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            $base_url = $arg_list[5];
            //print_r($arg_list);
        }
        $timenow = mktime();
        $where = $base_url;
        print "<div style='z-order:0'>";
        print "<table width='192' bgcolor='black' cellpadding='0' cellspacing='1'>";
        print "<tr bgcolor='white'>";
        print "<td width='178'>";

        //set the calendar initial vars
        if ($post_vars["nextm"]==">") {
            if ($month==12) {
                $month=1;
                $year++;
            } else {
                $month++;
            }
        }
        if ($post_vars["previousm"]=="<") {
            if ($month==1) {
                $month=12;
                $year--;
            } else {
                $month--;
            }
        }
        // pads date with zeros
        if ($day<="9"&ereg("(^[1-9]{1})",$day)) {
            $day="0".$day;
        }
        if ($month<="9"&ereg("(^[1-9]{1})",$month)) {
            $month="0".$month;
        }
        // default date
        if (!$year) {
            //$year=date("Y");
            $year=date("Y",mktime());
        }
        if (!$month) {
            //$month=date("m");
            $month=date("m",mktime());
        }
        if (!$day) {
            //$day=date("d");
            $day=date("d",mktime());
        }

        $_SESSION["thisday"] = "$year-$month-$day";
        $day_name = array("M","T","W","Th","F","S","<font color=red>S</font>");
        $month_abbr = array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");// set an initial bogey for loop beginning 1 not 0

        print "<form action='$where' method='post'>";
        print "<table width='177' height='100' cellpadding='2' cellspacing='1' bgcolor='white'>";
        // begin row 1
        print "<tr bgcolor='#ff9900'>";
        print "<td align='center' colspan='8' nowrap>";
        print "<input  type='submit' class='btext' style='background=#6699ff' name='previousm' value='<'>&nbsp;";
        print "<select name='month' class='btext'>";
        // use a bogey here a=1 last=13 then we keep the correct month number in the array
        for ($a=1; $a<13; $a++) {
          	$mo=date("n",mktime(0,0,0,$a,1,$year));
          	$name_of_month=$month_abbr[$mo];
          	if ($mo==$month) {
                print "<option  value=$a selected>$name_of_month\n";
            } else {
                print "<option value=$a>$name_of_month\n";
            }
        }
        print "</select> ";
        print "<input type='hidden' name='s' value='1'>";
        print "<input  type='submit' style='background=#6699ff' value='go' class='btext'>&nbsp;";
        print "<select name='year' class='btext'>";
        $y=date("Y");
        if ($year<$y-2) {
            print "<option value='$year' selected >$year\n";
        }
        for ($i=$y-2;$i<=$y+5;$i++) {
            if ($i==$year) {
                print "<option value='$i' selected>$i";
            } else {
                print "<option value='$i'>$i";
            }
        }
        if ($year>$y+5) {
            print "<option value=$year selected>$year";
        }
        print "</select>&nbsp;";
        if ($day) {
            print "<input type='hidden' name='day' value='$day'>";
        }
        print  "<input type='submit' style='background=#6699ff' name='nextm' value='>' class='btext'>";
        print  "</td>";
        print  "</tr>";
        // end of row 1
        // begin row 2
        //week day names
        print "<tr align='center' bgcolor='#cccccc' class='btext'>";
        print "<td width='25' align='center'><b>Wk</b></td>";
        for ($i=0; $i<7; $i++) {
            print "<td width='25'><b>$day_name[$i]</b></td>";
        }
        print  "</tr>";

        // begin row 2
        print "<tr>";
        //first week if in previous month
        if (date("w",mktime(0,0,0,$month,1,$year))==0) {
            $da=-6;
        } else if (date("w",mktime(0,0,0,$month,1,$year))<>1) {
            $da= - date("w",mktime(0,0,0,$month,1,$year))+1;
        } else {
            $da = 1;
        }

        //week number 	//iron out the 00/53 week number
        if (strftime('%W',mktime(0,0,0,$month,($da+2),$year))==00) {
            print (strftime("<td align='center' bgcolor='#666666'><i><a href=\"$where&s=2&year=%Y&month=%m&w=%W&day=%d\" class='btext'><span class='wtext'><b>%W</b></span<></a></i></td>\n", mktime(0,0,0,$month,$da+1,$year)));
        } else {
            print (strftime("<td align='center' bgcolor='#666666'><i><a href=\"$where&s=2&year=%Y&month=%m&w=%W&day=%d\" class='btext'><span class='wtext'><b>%W</b></span></a></i></td>\n", mktime(0,0,0,$month,$da+1,$year)));
        }
        // show overlap days of previous month
        if (date("w",mktime(0,0,0,$month,1,$year))==0) {
            $start=7;}else{$start=date("w",mktime(0,0,0,$month,1,$year));
        }
        for ($a=($start-2);$a>=0;$a--) {
            $d=date("t",mktime(0,0,0,$month,0,$year))-$a;
            print "<td bgcolor='white' align='center' class='gtext'>$d</td>\n";
        }

        //days of the month
        for ($d=1;$d<=date("t",mktime(0,0,0,($month+1),0,$year));$d++) {
            //day link - today with different color bg
            if ($month==date("m", $timenow) & $year==date("Y", $timenow) & $d==date("d", $timenow)) {
                $bg = "bgcolor='#ffff33'";
            } else {
                $bg = "bgcolor='#FFFFFF'";
            }
            print "<td $bg class='btext' align='center'><a href=\"$where&year=$year&month=$month&day=$d&s=0\" class='btext'>$d</a></td>\n";

            if(date("w",mktime(0,0,0,$month,$d,$year))==0&date("t",mktime(0,0,0,($month+1),0,$year))>$d) {
                print "</tr><tr class='btext'>\n";
                $da = $d + 1;
        	    //iron out the 00/53 week number
                if (strftime('%W',mktime(0,0,0,$month,($d+2),$year))==00) {
                    print "<td align='center' bgcolor='#666666'><i><a href=\"$where&s=2&year=$year&month=$month&w=53&day=$da\" class='btext'><span class='wtext'><b>53</b></span></a></i></td>\n";
                } else {
                    print (strftime("<td align=center bgcolor=#666666><i><a href=\"$where&s=2&year=%Y&month=%m&w=%W&day=%d\" class='btext'><span class='wtext'><b>%W</b></span></a></i></td>\n",mktime(0,0,0,$month,($d+1),$year)));
                }
            }
        }

        // days of next month
        if (date("w",mktime(0,0,0,$month+1,1,$year))<>1) {
            $d=1;
            while (date("w",mktime(0,0,0,($month+1),$d,$year))<>1) {
                print "<td class='btext' bgcolor='#cccccc' align=center>$d</td>\n";
                $d++;
            }
        }


        print  "</tr></table>";
        print "</form>";

        print "</td>";
        print "</tr>";
        print "</table>";
        print "</div><br/>";
    }

}
// end of class
?>
