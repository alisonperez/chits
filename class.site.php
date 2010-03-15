<?
class Site {
//
// Module Site
// Type: internal
// Author: Herman Tolentino MD
//

    var $sql;

    function Site () {
        $this->author = "Herman Tolentino MD";
        $this->version = "1.1";
        // 0.7 added add, update delete permissions as session variables
        // 0.8 added content
        // 0.9 added menu by location (user-requested)
        // 1.0 added term count in stats
        // 1.1 added edit/delete to site content
    }

    function content() {
    //
    // distributes content on the frontpage
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        $sql = "select content_column, content_level, content_module from content ".
               "where content_active='Y' order by content_column, content_level";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $content = array();
                while(list($col, $level, $module) = mysql_fetch_array($result)) {
                    $content[$col][$level] = $module;
                    $maxcol = ($maxcol>$col?$maxcol:$col);
                    $maxlev = ($maxlev>$level?$maxlevel:$level);
                }
                //print "maxcol: $maxcol<br/>";
                //print "maxlev: $maxlev<br/>";
                print "<table width='600' cellspacing='0' cellpadding='0'>";
                print "<tr valign='top'>";
                for ($j=1; $j<=$maxcol; $j++) {
                    print "<td>";
                    print "<table width='300'>";
                    for ($i=1; $i<=$maxlev; $i++) {
                        print "<tr><td>";
                        // execute module from content table
                        // naming convention:
                        // _<module_name> = frontpage display
                        $class = $content[$j][$i];
                        $class_method = $content[$j][$i]."::_".$content[$j][$i]."(\$menu_id, \$post_vars, \$get_vars);";
                        if (class_exists("$class")) {
                            if (strlen($content[$j][$i])>0) {
                                eval($class_method);
                            }
                        } else {
                            if (strlen($content[$j][$i])>0) {
                                print "<font color='red'><b>WARNING</b></font>: <b>".$content[$j][$i]."</b> missing.<br>";
                            }
                        }
                        print "</td></tr>";
                    }
                    print "</table>";
                    print "</td>";
                }
                print "</tr>";
                print "</table>";
                return true;
            } else {
                print "<font color='red'>No content modules loaded.</font><br/>";
            }
            return false;
        }
    }

    function _content() {
    //
    // main module so for managing content modules
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitmodule"]) {
            site::process_content($menu_id, $post_vars, $get_vars);
        }
        site::form_content($menu_id, $post_vars, $get_vars);
        site::display_content($menu_id, $post_vars, $get_vars);
    }

    function form_content() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $sql = "select content_id, content_module, content_column, content_level, content_display, content_active ".
               "from content where content_id = '".$get_vars["content_id"]."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $content = mysql_fetch_array($result);
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]."' name='form_module' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='admin'><b>CONTENT MODULE</b></span><br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>NOTE:</b> You are adding a content module to the site (Have you loaded it already in the <b>MODULES-&gt;Module Database</b>?). Do not forget to set module permissions in <b>MODULES-&gt;Grant Permissions</b> to enable site users to see menu items for this module.<br/><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>CONTENT MODULE</span><br> ";
        print site::show_modules($content["content_module"]?$content["content_module"]:$post_vars["module"]);
        print "<br/><br/></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>DISPLAY CONTENT ON THIS COLUMN</span><br> ";
        print "<select name='mod_column' class='textbox'>";
        print "<option value=''>Select Display Column</option>";
        print "<option value='1' ".($content["content_column"]=="1"?"selected":"").">Column 1</option>";
        print "<option value='2' ".($content["content_column"]=="2"?"selected":"").">Column 2</option>";
        print "</select>";
        print "<br/></td></tr>";
        print "<tr><td>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>PLACE THE CONTENT AT LEVEL</span><br> ";
        print "<select name='mod_level' class='textbox'>";
        print "<option value=''>Select Display Level</option>";
        print "<option value='1' ".($content["content_level"]=="1"?"selected":"").">Level 1</option>";
        print "<option value='2' ".($content["content_level"]=="2"?"selected":"").">Level 2</option>";
        print "<option value='3' ".($content["content_level"]=="3"?"selected":"").">Level 3</option>";
        print "<option value='4' ".($content["content_level"]=="4"?"selected":"").">Level 4</option>";
        print "<option value='5' ".($content["content_level"]=="5"?"selected":"").">Level 5</option>";
        print "</select>";
        print "<br/></td></tr>";
        print "<tr><td><br/>";
        if ($get_vars["content_id"]) {
            print "<input type='hidden' name='content_id' value='".$get_vars["content_id"]."'/>";
            print "<input type='submit' value = 'Update Module' class='textbox' name='submitmodule' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Module' class='textbox' name='submitmodule' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Module' class='textbox' name='submitmodule' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function show_modules() {
    //
    // adapted from module::show_modules
    // but shows only content modules
    // make sure the word 'Content' is present in module_desc
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
        }
        $sql = "select module_id, module_name ".
               "from modules where module_desc like '% Content %' order by module_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='module' class='textbox'>";
                $ret_val .= "<option value=''>Select Module</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($module_id==$id?"selected":"").">$name</option>";
                }
                $ret_val .= "</select>";
                return $ret_val;
            }
        }
    }

    function display_content() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='620'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='admin'>LOADED CONTENT MODULES</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td colspan='4'>";
        print "<b>Click on module name to see details (and source code). You can load modules through the MODULES-&gt;Module Database Section.</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>NAME</b></td><td><b>INIT STATUS</b></td><td><b>VERSION</b></td><td><b>DESCRIPTION</b></td></tr>";
        $sql = "select c.content_id, c.content_module, m.module_name, m.module_init, m.module_version, m.module_desc ".
               "from modules m, content c ".
               "where m.module_id = c.content_module order by m.module_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($cid, $id, $name, $init, $version, $desc) = mysql_fetch_array($result)) {
                    print "<tr class='tinylight' valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]."&content_id=$cid'>$name</a></td><td>".($init=="N"?"No <a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=INIT'>[<b>activate</b>]</a>":"Yes")."</td><td>$version</td><td>".($desc?"$desc":"<font color='red'>empty</font>")."</td></tr>";
                }
            } else {
                print "<tr valign='top'><td colspan='4'>";
                print "<font color='red'>No modules loaded.</font><br>";
                print "</td></tr>";
            }
        }
        print "</table><br>";
    }

    function process_content() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($get_vars["delete_id"]) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from content where module_id = '".$post_vars["module_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]);
                }
            }
        }
        switch ($post_vars["submitmodule"]) {
        case "Add Module":
            if ($post_vars["module"] && $post_vars["mod_column"] && $post_vars["mod_level"]) {
                $sql = "insert into content (content_module, content_column, content_level, content_active) ".
                       "values ('".$post_vars["module"]."', '".$post_vars["mod_column"]."', '".$post_vars["mod_level"]."', 'Y')";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]);
                }
            }
            break;
        case "Update Module":
            if ($post_vars["module"] && $post_vars["mod_column"] && $post_vars["mod_level"]) {
                $sql = "update content set content_module = '".$post_vars["module"]."', ".
                       "content_column = '".$post_vars["mod_column"]."', ".
                       "content_level = '".$post_vars["mod_level"]."' ".
                       "where content_id = '".$post_vars["content_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]);
                }
            }
            break;
        case "Delete Module":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from content where content_id = '".$post_vars["content_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]);
                }
            }
            break;
        }
    }

    function session_user() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $user = $arg_list[0];
        }
        $_SESSION["validuser"] = true;
        $_SESSION["userid"] = $user["user_id"];
        $_SESSION["user_first"] = $user["user_firstname"];
        $_SESSION["user_last"] = $user["user_lastname"];
        $_SESSION["user_gender"] = $user["user_gender"];
        $_SESSION["user_dob"] = $user["user_dob"];
        // convert admin flag to true or false
        $_SESSION["isadmin"] = ($user["user_admin"]=="Y"?1:0);
        $_SESSION["user_role"] = $user["user_role"];
        // load data access flags
        if ((substr(user::get_access($user["user_role"]),0,1)=="1")||$_SESSION["isadmin"]) {
            $_SESSION["priv_add"] = true;
        }
        if ((substr(user::get_access($user["user_role"]),1,1)=="1")||$_SESSION["isadmin"]) {
            $_SESSION["priv_update"]=true;
        }
        if ((substr(user::get_access($user["user_role"]),2,1)=="1")||$_SESSION["isadmin"]) {
            $_SESSION["priv_delete"]=true;
        }
        $_SESSION["user_lang"] = $user["user_lang"];
        
        $this->log_user();        
    }
    
    function log_user(){
      $create_log = mysql_query("CREATE TABLE IF NOT EXISTS `user_logs` (
             `log_id` bigint(20) NOT NULL,
             `userid` int(5) NOT NULL,
             `login` datetime NOT NULL,
             `logout` date NOT NULL,
             `pc_ip` text NOT NULL
           ) ENGINE=MyISAM DEFAULT CHARSET=latin1; ") or ("Cannot query: 304".mysql_error());
      
      $q_user = mysql_query("INSERT INTO user_logs SET userid='$_SESSION[userid]',login=NOW(),pc_ip='$_SERVER[REMOTE_ADDR]'") or die("Cannot query (305): ".mysql_error());
      $log_id = mysql_insert_id();
      $_SESSION[log_id] = $log_id;
    }                  

    function check_dbfile() {
    //
    // check if db has been defined
    // returns true if there is a db
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
        }
        if (!file_exists("../modules/_dbselect.php")) {
            if ($post_vars["submitdb"]) {
                if ($post_vars["db_name"]) {
                    if ($fp = fopen("../modules/_dbselect.php","w+")) {
                        $t .= "<?\n";
                        $t .= "\$db = new MySQLDB;\n";
                        $t .= "\$db->selectdb(\"".$post_vars["db_name"]."\");\n";
                        $t .= "\$_SESSION[\"gamedb\"] = \"".$post_vars["db_name"]."\"\n";
                        $t .= "?>\n";
                        if (fwrite($fp, $t)) {
                            fclose($fp);
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            }
            $this->form_dbselect($post_vars);
        } else {
            return true;
        }
    }

    function welcome() {
        print "<h2><font color='red'>WELCOME</font></h2>";
        print "<p>You have just installed the GAME Engine successfully. An admin user ".
              "has been created for you:<br><br>".
              "Login: <b>admin</b><br>".
              "Password: <b>admin</b><br><br>".
              "<b><font color='red'>IMPORTANT:</font></b> Please login and change the password right away.</p>";
    }

    function main_stats () {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $validuser = $arg_list[0];
        }
        print "<table width='160' style='border: 1px solid black' bgcolor='#99FF66' cellspacing='0' cellpadding='5'>";
        print "<tr bgcolor='#33CC00'><td>";
        print "<b>STATS</b>";
        print "</td></tr>";
        print "<tr><td>";
        print "<b>Users</b> ".user::count()."<br/>";
        print "<b>Modules</b> ".module::count()."<br/>";
        print "<b>Terms</b> ".site::term_count()."<br/>";
        print "</td></tr>";
        print "</table>";
    }

    function term_count() {
    //
    // provides number language terms processed
    // for multilingualization engine
    //
        $sql = "select count(termid) term_count from terms";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($count) = mysql_fetch_array($result);
                return $count;
            }
        }
    }

    function main_cat() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $validuser = $arg_list[0];
            $isadmin = $arg_list[1];
            $get_vars = $arg_list[2];
            $menu_array = $arg_list[3];
        }
        //print_r($arg_list);
        if ($validuser && $isadmin) {
            print "<table width='160' style='border: 1px solid black' bgcolor='#99CCFF' cellspacing='0' cellpadding='5'>";
            print "<tr bgcolor='#669999'><td>";
            print "<b>MAIN MENU</b><br>";
            print "</td></tr>";
            print "<tr><td>";
            foreach($menu_array as $key=>$subarray) {
                foreach($subarray as $key=>$pagename) {					                                      
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=$pagename' class='catmenu'>".($get_vars["page"]==$pagename?"<b>$pagename</b>":"$pagename")."</a><br/>";
                }
            }
            print "</td></tr>";
            print "</table>";
        }
    }

    function location_user_permission_status() {
    //
    // get permission status for menu items per location
    // per user
    // adapted from module::location_user_permission_status()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $user_id = $arg_list[1];
        }
        $sql = "select m.menu_id from module_menu_location m, module_user_location u ".
               "where m.location_id = u.location_id and u.user_id = '".$user_id."' and m.menu_id = '".$menu_id."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                if (list($menu_id) = mysql_fetch_array($result)) {
                    return $menu_id;
                }
            }
        }
        return 0;
    }

    function menu_permission_status() {
    //
    // get permission status for menu items per module
    // per user
    // adapted from module::menu_permission_status()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $user_id = $arg_list[1];
        }
        $sql = "select menu_id from module_menu_permissions ".
               "where menu_id = '".$menu_id."' and user_id = '".$user_id."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                if (list($menu_id) = mysql_fetch_array($result)) {
                    return $menu_id;
                }
            }
        }
        return 0;
    }

    function main_menu() {
    //
    // vertical side menu
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $validuser = $arg_list[0];
            $isadmin = $arg_list[1];
            $page = $arg_list[2];
            $menu_array = $arg_list[3];
        }
        if ($page && $page<>"HOWTO" && $page<>"ABOUT" && $page<>"CREDITS") {
            if ($isadmin) {
                print "<table width='160' style='border: 1px solid black' bgcolor='#CCFF33' cellspacing='0' cellpadding='5'>";
                print "<tr bgcolor='#99CC00'><td>";
                print "<b>MENU :: ".($page?$page:"HOME")."</b><br>";
                print "</td></tr>";
                print "<tr><td>";
                if (in_array($page, $menu_array[0])) {
                    print "<div align='left'>";
                    print "<font face='Verdana'>";
                    $sql = "select m.menu_id, m.module_id, c.module_name, m.menu_cat, m.menu_title, m.menu_action ".
                           "from module_menu m, modules c ".
                           "where m.menu_cat = '$page' and m.module_id = c.module_id and menu_visible='Y' order by m.menu_cat, menu_rank";
                    if ($result = mysql_query($sql)) {
                        if (mysql_num_rows($result)) {
                            while (list($menu_id, $module_id, $module_name, $menu_cat, $menu_title, $menu_action) = mysql_fetch_array($result)) {
                                // take circuitous route to location permissions
                                $location_allowed = $this->location_user_permission_status($menu_id, $_SESSION["userid"]);
                                $module_allowed = module::module_permission_status($module_id, $_SESSION["userid"], $menu_id);
                                $menu_allowed = module::menu_permission_status($menu_id, $_SESSION["userid"]);
                                // default to no menus
                                $show_menu = false;
                                $flag = '';
                                // ... then cascade through different authorization
                                // levels
                                if ($location_allowed===$menu_id) {
                                    // show only menus by locations allowed for user
                                    $show_menu = true;
                                }
                                if ($module_allowed) {
                                    // show only modules allowed
                                    $show_menu = true;
                                }
                                if ($menu_allowed) {
                                    // show only menus allowed
                                    $show_menu = true;
                                }
                                if ($_SESSION["isadmin"]==="Y") {
                                    // show all
                                    $show_menu = true;
                                }
                                if ($show_menu) {
                                    $menu_list .= "<a href='".$_SERVER["PHP_SELF"]."?page=$menu_cat&menu_id=$menu_id' class='sidemenu'>".($get_vars["menu_id"]==$menu_id?"<b>$menu_title</b>":"$menu_title")."</a><br>";
                                }
                            }
                            if (strlen($menu_list)>0) {
                                print $menu_list;
                            } else {
                                print "No menu item to display";
                            }
                        } else {
                            print "No menu items";
                        }
                    }
                    print "</font>";
                    print "</div>";
                } else {
                    switch ($page) {
                    case "MODULES":
                        print "<div align='left'>";
                        print "<font face='Verdana'>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=MODDB' class='sidemenu'>Module Database</a><br>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=INIT' class='sidemenu'>Activate Modules</a><br>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=PERMS#permtop' class='sidemenu'>Grant Permissions</a><br>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=LOC' class='sidemenu'>Menu By Location</a><br>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=MENU' class='sidemenu'>Menu Order</a><br>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=SQL' class='sidemenu'>Load Module SQL</a><br>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=HELP' class='sidemenu'>Module Help</a><br>";
                        print "</font>";
                        print "</div>";
                        break;
                    case "ADMIN":
                        print "<div align='left'>";
                        print "<font face='Verdana'>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=ADMIN&method=USER' class='sidemenu'>User Admin</a><br>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=ADMIN&method=ROLES' class='sidemenu'>User Roles</a><br>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=ADMIN&method=LOC' class='sidemenu'>User Location</a><br>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=ADMIN&method=NODES' class='sidemenu'>Node Information</a><br>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=ADMIN&method=CONTENT' class='sidemenu'>Content Modules</a><br>";
                        print "<a href='".$_SERVER["PHP_SELF"]."?page=ADMIN&method=LOGS' class='sidemenu'>Logs</a><br>";
                        print "</font>";
                        print "</div>";
                        break;
                    }
                }
                print "</td></tr>";
                print "</table>";

            }
        }
    }

    function displaymenu() {
    //
    // horizontal menu at the top
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $validuser = $arg_list[0];
            $site = $arg_list[1];
            $isadmin = $arg_list[2];
        }
        switch ($site) {
        case "ADMIN":
            print "<div align='center'>";
            print "<font face='Verdana'>";
            print "<b>".LBL_NAVIGATION." > </b>";
            print "<a href='".$_SERVER["PHP_SELF"]."' class='topmenu'>".LBL_HOME."</a>";
            if ($_SESSION["validuser"]) {
                if ($_SESSION["isadmin"]) {
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=ADMIN' class='topmenu'>ADMIN</a>";
                    print "<a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=MODDB' class='topmenu'>MODULES</a>";
                    print "<a href='../chits_query/' target='new' class='topmenu'>QUERY BROWSER</a>";                                    
                }                
            }
            
            print "<a href='".$_SERVER["PHP_SELF"]."?page=HOWTO' class='topmenu'>".MENU_HOWTO."</a>";
            print "<a href='".$_SERVER["PHP_SELF"]."?page=ABOUT' class='topmenu'>".MENU_ABOUT."</a>";
            print "<a href='".$_SERVER["PHP_SELF"]."?page=CREDITS' class='topmenu'>".MENU_CREDITS."</a>";
            print "</font>";
            print "</div>";
            break;
        case "USER":
            print "<div align='center'>";
            print "<font face='Verdana'>";
            print "<b>".LBL_NAVIGATION." > </b>";
            print "<a href='".$_SERVER["PHP_SELF"]."' class='topmenu'>".LBL_HOME."</a>";
            print "<a href='../chits_query/' target='new' class='topmenu'>QUERY BROWSER</a>";                                    
            $sql = "select menu_id, menu_cat, module_id, menu_title from module_menu order by menu_rank";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    while (list($menu_id, $menu_cat, $module_id, $menu_title) = mysql_fetch_array($result)) {
                            $location_allowed = $this->location_user_permission_status($menu_id, $_SESSION["userid"]);
                            $module_allowed = module::module_permission_status($module_id, $_SESSION["userid"]);
                            $menu_allowed = module::menu_permission_status($menu_id, $_SESSION["userid"]);
                            // default to no menus
                            $show_menu = false;
                            // ... then cascade through different authorization
                            // levels
                            if ($location_allowed===$menu_id) {
                                // show only menus by locations allowed for user
                                $show_menu = true;
                            }
                            if ($module_allowed===$menu_id) {
                                // show only modules allowed
                                $show_menu = true;
                            }
                            if ($menu_allowed===$menu_id) {
                                // show only menus allowed
                                $show_menu = true;
                            }
                            if ($_SESSION["isadmin"]==="Y") {
                                // show all
                                $show_menu = true;
                            }
                            if ($show_menu) {
                                $menu_list .= "<a href='".$_SERVER["PHP_SELF"]."?page=$menu_cat&menu_id=$menu_id' class='topmenu'>".strtoupper(($get_vars["menu_id"]==$menu_id?"<b>$menu_title</b>":"$menu_title"))."</a>";
                            }
                    }
                        if (strlen($menu_list)>0) {
                            print $menu_list;
                        } else {
                            print "NO MENUS";
                        }
                }
            }
            print "</font>";
            print "</div>";
            break;
        }
    }


    function print_howto () {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $site = $arg_list[0];
        }
        switch ($site) {
        case "INFO":
        default:
            $this->display_file("../site/howto.html");
        }
    }

    function display_file() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $filename = $arg_list[0];
        }
        if ($fp = fopen($filename, "r")) {
            $contents = fread($fp, filesize($filename));
            fclose($fp);
            print $contents;
        }
    }

    function print_credits () {
        $this->display_file("../site/credits.html");
    }

    function print_about () {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $site = $arg_list[0];
        }
        switch ($site) {
        case "INFO":
        default:
            $this->display_file("../site/siteinfo.html");
        }
    }

    function record_access() {
    //
    // saves data to loginhx table
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $userid = $arg_list[0];
            $browser = $arg_list[1];
            $site = $arg_list[2];
            $action = $arg_list[3];
        }
        $_useragent = $browser;
        $_today = date("Y-m-d H:m:s");
        $_ipaddress = $_SERVER["REMOTE_ADDR"];
        list($_date, $_time) = explode(" ", $_today);
        list($_year, $_month, $_day) = explode("-", $_date);
        list($_hour, $_minute, $_second) = explode(":", $_time);
        $_sessionid = session_id();
        $userid = ($userid?$userid:'unknown');
        $sql_find = "select * from agentlog ".
                    "where logdate = '$_date' and useragent = '$_useragent' and ".
                    "userip = '$_ipaddress' and sessionid = '$_sessionid' and ".
                    "userid = '$userid'";
        if ($result = mysql_query($sql_find)) {
            if (mysql_num_rows($result)>=1) {
                // update
                $sql_agentlog = "update agentlog set ".
                       "actions = concat(actions,'|$action') ".
                       "where logdate = '$_date' and useragent = '$_useragent' and ".
                       "userip = '$_ipaddress' and sessionid = '$_sessionid' and ".
                       "userid = '$userid'";

            } else {
                // insert
                $sql_agentlog = "insert into agentlog (logdate, logtime, useragent, userip, sessionid, userid, site, actions) ".
                        "values ('$_date', '$_time', '$_useragent', '$_ipaddress', '$_sessionid', '$userid', '$site', '$action')";
            }
            $result_agentlog = mysql_query($sql_agentlog) or die(mysql_errno().": ".mysql_error());
        }
    }


    function print_error($error) {
        print "<ol>$error</ol>";
    }

    

}
?>
