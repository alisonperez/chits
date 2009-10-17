<?
Class Module {

    // GAME Project 2004
    // Author: Herman Tolentino MD
    // Generic Architecture for Modular Enterprise
    // This is the internal module class that handles all
    // module activities.

    // IMPORTANT SECURITY VARIABLES:
    //
    // $_SESSION["validuser"]: is user logged in? true if logged in
    // $_SESSION["isadmin"]: global privileges if true
    // $_SESSION["userid"]: user_id of person logged in
    //
    // - the following are set in role table
    // $_SESSION["priv_add"]: sees add buttons
    // $_SESSION["priv_update"]: sees update button
    // $_SESSION["priv_delete"]: sees delete button
    //
    // NODE VARIABLES:
    // derived from config.xml
    // $_SESSION["datanode"]["name"]: name of org
    // $_SESSION["datanode"]["code"]: unique id of org
    // $_SESSION["datanode"]["telephone"]: phone number
    // $_SESSION["datanode"]["level"]: part of org in node tree
    //
    // WARNING: There is a not so nasty (non-fatal) bug when loading modules for the
    // first time, be forewarned. When loading a module for the first
    // time, it won't activate. We are looking for the source of the bug
    // but feel free to contribute... It's open source anyway! And remove this
    // comment when you have done so. Cheers!
    //

    function module () {
        $this->author = "Herman Tolentino MD";
        $this->version = "1.4";
        // version 0.7
        // added confirm_delete()
        // 0.8: moved module functions out of index.php
        // 0.9: added load sql
        // 1.1: debugged permissions and added menu access by location
        //      security check precedence:
        //      location > module > menu
        // 1.2  removed source code from main body (opens in separate window)
        // 1.3  revamped consult notes
        // 1.4 fixed formatting of module menu order
    }

    function _module() {
    //
    // main function for module
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            $get_vars = $arg_list[1];
            $post_files = $arg_list[2];  // comes from index.php
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }

        $module = new module;

        switch ($get_vars["method"]) {
        case "SQL":
            if ($post_vars["submitsql"]) {
                $module->process_module_sql($post_vars, $get_vars, $post_files);
            }
            $module->form_module_sql($post_vars, $get_vars);
            break;
        case "LOC":
            if ($post_vars["submitaccess"]) {
                $module->process_permissions($post_vars, $get_vars);
            }
            $module->form_locations($post_vars, $get_vars);
            break;
        case "MENU":
            if ($get_vars["moveup"] || $get_vars["movedown"]) {
                $module->process_order($post_vars, $get_vars);
                //header("location: ".$_SERVER["PHP_SELF"]."?page=MODULES&method=MENU");
            }
            $module->form_menuorder($post_vars, $get_vars);
            break;
        case "HELP":
            site::display_file("../module.help.php");
            break;
        case "PERMS":
            if ($post_vars["submitaccess"]) {
                $module->process_permissions($post_vars, $get_vars);
            }
            $module->form_permissions($post_vars, $get_vars);
            break;
        case "INIT":
            if ($post_vars["submitinitmod"]=="Update Activation Status") {
                $module->process_initmodule($post_vars);
                // comment out line below for debugging
                // while developing modules
                //header("location: ".$_SERVER["PHP_SELF"]."?page=MODULES&method=INIT&initmod=1");
            }
            // module activation
            $module->form_initmodule();
            break;
        case "MODDB":
        default:
            switch ($post_vars["submitmodule"]) {
            case "Yes, Delete Module":
                $module->delete_module($post_vars);
                header("location: ".$_SERVER["PHP_SELF"]."?page=MODULES");
                break;
            case "Delete Module":
                print "<font color='red' size='3'><b>Are you sure you want to DELETE this module?</b></font>";
                $module->form_module($get_vars, $post_vars);
                break;
            case "Add Module":
            default:
                if ($module_id = $module->process_module($post_vars, $post_files)) {
                    // comment out line below for debugging
                    // while developing modules
                    //header("location: ".$_SERVER["PHP_SELF"]."?page=MODULES");
                }
                $module->form_module($get_vars, $post_vars);
                $module->display_modules();
            }
        }
    }

    function get_version() {
        return $this->version;
    }

    function confirm_delete() {
    //
    // global confirm delete using regular expressions
    // include this for confirmation of all sql delete statements
    //
    // Example Usage:
    //  if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
    //      $sql = "delete from <table> where ...";
    //      if ($result = mysql_query($sql)) {
    //          header("location: "<previous page URL>);
    //      }
    //  } else {
    //      if ($post_vars["confirm_delete"]=="No") {
    //          header("location: "<previous page URL>);
    //      }
    //  }
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($post_vars);
        }
        if ($post_vars["confirm_delete"]) {
            switch ($post_vars["confirm_delete"]) {
            case "Yes":
                return true;
                break;
            case "No":
                header("location: ".$_SERVER["HTTP_REFERRER"]);
                return false;
                break;
            }
        } else {
            print "<table>";
            print "<form method='post' action='".$_SERVER["HTTP_REFERRER"]."'>";
            print "<tr><td>";
            foreach($get_vars as $key => $value) {
                if (ereg("^delete", $value)) {
                    $delete_button = "Delete Icon";
                }
            }
            foreach($post_vars as $key => $value) {
                // store postvars again!
                print "<input type='hidden' name='$key' value='$value' />";
                if (ereg("^Delete", $value)) {
                    $delete_button = $value;
                }
                //print_r($value);
            }
            if ($delete_button) {
                print "<font color='red' size='5'><b>You clicked on <u>$delete_button</u> button.</b></font><br />";
            }
            print "<font color='red' size='5'><b>Are you sure you want to do this?</b></font><br />";
            print "<input type='submit' name='confirm_delete' value='Yes' class='textbox' style='font-weight: bold; font-size:14pt; background-color: #FFFF33; border: 2px solid black' id='Yes' /> ";
            print "<input type='submit' name='confirm_delete' value='No' class='textbox' style='font-weight: bold; font-size:14pt; background-color: #FFCC33; border: 2px solid black' id='No' /> ";
            print "</td></tr>";
            print "</form>";
            print "</table>";
            return false;
        }
    }

    function count() {
        $sql = "select count(module_id) from modules";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($count) = mysql_fetch_array($result);
                return $count;
            }
        }
    }

    function exec_menu() {
        //
        // this executes class methods based on menu id
        //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        $sql = "select c.module_name, m.menu_action ".
               "from module_menu m, modules c ".
               "where m.menu_id = $menu_id and c.module_id = m.module_id";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($module_name, $menu_action) = mysql_fetch_array($result);
                // this string simulates a PHP command
                // and is evaluated with PHP eval() below
                $eval_string = $module_name."::".$menu_action."(\$menu_id,\$post_vars,\$get_vars,\$validuser,\$isadmin)".";";
                eval("$eval_string");
            }
        }
    }

    // ---------------- MODULE INITIALIZATION METHODS -------------------

    function set_dep() {
    //
    // establish dependencies through the
    // table module_dependencies
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $internal_module = $arg_list[0];
            $external_module = $arg_list[1];
        }
        $sql = "insert into module_dependencies (module_id, req_module) ".
               "values ('$internal_module','$external_module')";
        if ($result = mysql_query($sql)) {
            return true;
        }
    }

    function set_lang() {
    //
    // creates entries in table term
    // multilingualization engine from MBDSNET (c)2002-2004
    // http://www.mbdsnet.org/
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $term = $arg_list[0];       // constant term
            $lang = $arg_list[1];       // language or dialect
            $text = $arg_list[2];       // translated text
            $is_english = $arg_list[3]; // is it english: Y or N
        }
        $sql = "insert into terms (termid, languageid, langtext, isenglish) ".
               "values ('$term','$lang', '$text', '$english');";
        if ($result = mysql_query($sql)) {
            return true;
        }
    }

    function set_menu() {
    //
    // creates entries in table module_menu
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $module = $arg_list[0];       // module name
            $menu_title = $arg_list[1];   // menu title (what is displayed)
            $menu_cat = $arg_list[2];     // menu categories (top menu)
            $menu_action = $arg_list[3];  // script executed in class
            //print_r($arg_list);
        }
        // insert new menu entry
        $sql = "insert into module_menu (module_id, menu_title, menu_cat, menu_action) ".
               "values ('$module', '$menu_title', '$menu_cat', '$menu_action')";
        if ($result = mysql_query($sql)) {
            // make menu_rank equal menu_id as default
            $insert_id = mysql_insert_id();
            $sql_rank = "update module_menu set menu_rank = menu_id where menu_id = '$insert_id'";
            $result_rank = mysql_query($sql_rank);
            return true;
        }
    }

    function set_detail() {
    //
    // updates table module
    // for final activation verification
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $description = $arg_list[0];    // module description
            $version = $arg_list[1];        // module version
            $author = $arg_list[2];         // module author
            $module = $arg_list[3];         // module name
        }
        
        //print_r($arg_list);
        
        $sql = "update modules set module_desc = '$description', ".
               "module_version = '".$version."', module_author = '".$author."' ".
               "where module_id = '$module';";
        if ($result = mysql_query($sql)) {

            $sql_flag = "update modules set module_init = 'Y' where module_id = '$module'";
            $result_flag = mysql_query($sql_flag);

            return true;
        }
    }

    function execsql() {
    //
    // generic sql execution method
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            // print $sql below for diagnostics
            $sql = $arg_list[0];    // SQL statement
        }
        if ($result = mysql_query($sql)) {
            return true;
        }
    }

    function load_sql () {
    //
    // load sql files from sql directory
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $sql = $arg_list[0];    // SQL file
        }
        if (file_exists("../sql/$sql")) {
            if ($fp = fopen("../sql/$sql", "r")) {
                while (!feof ($fp)) {
                    $sql_buffer = fgets($fp, 4096);
                    if ($result = mysql_query($sql_buffer)) {
                        $sql_status[] .= $sql_buffer." - SUCCESS\n";
                    } else {
                        $sql_status[] .= $sql_buffer." - FAIL\n";
                    }
                }
                foreach($sql_status as $key =>$value) {
                    print nl2br($value);
                }
                fclose ($fp);
                return true;
            }
        }
    }

    // ---------------- END OF MODULE INITIALIZATION METHODS ---------------

    function display_modules() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
        }
        print "<table width='650'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='module'>LOADED MODULES</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td colspan='5'>";
        print "<b>INSTRUCTIONS: Click on module name to see details (and source code). Do not forget to set module and menu item permissions for site users in <b>MODULES->Grant Permissions</b>.</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>NAME</b></td><td><b>INIT STATUS</b></td><td><b>VERSION</b></td><td><b>DESCRIPTION</b></td></tr>";
        $sql = "select module_id, module_name, module_init, module_version, module_desc ".
               "from modules order by module_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $init, $version, $desc) = mysql_fetch_array($result)) {
                    print "<tr class='tinylight' valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=MODULES&module_id=$id#mod_$id'>$name</a></td><td>".($init=="N"?"No <a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=INIT'>[<b>activate</b>]</a>":"Yes")."</td><td>$version</td><td>".($desc?"$desc":"<font color='red'>empty</font>")."</td></tr>";
                }
            } else {
                print "<tr valign='top'><td colspan='4'>";
                print "<font color='red'>No modules loaded.</font><br>";
                print "</td></tr>";
            }
        }
        print "</table><br>";
    }

    function show_modules() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
        }
        $sql = "select module_id, module_name ".
               "from modules order by module_name";
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

    function delete_module() {
    //
    // delete module from database
    // including all references
    // delete all files
    // Author: Herman Tolentino
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            print_r($post_vars);
            // important variables
            $module_id = $post_vars["module_id"];
            $module_name = $this->module_name($module_id);

            // rewrite _modules.php so that only registered
            // modules appear
            //$this->rewrite_modules();

            if (!$post_vars["leave_sql"]) {
                if (class_exists($module_name)) {
                    $eval_string = $module_name."::drop_tables()".";";
                    eval("$eval_string");
                }
            }
            $sql = "delete from modules where module_id = '$module_name'";
            if ($result = mysql_query($sql)) {
                // remove files
                unlink("../modules/_uploads/class.$module_name.php.gz");
                unlink("../modules/$module_name/class.$module_name.php");
                // remove directory
                rmdir("../modules/$module_name");
            }

        }
    }

    function module_name() {
    //
    // gets module name based on module_id
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $id = $arg_list[0];
            $sql = "select module_name from modules where module_id = '$id'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    list($name) = mysql_fetch_array($result);
                    return $name;
                }
            }
        }
    }

    function module_id() {
    //
    // gets module id based on module_name
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $name = trim($arg_list[0]);
            $sql = "select module_id from modules where module_name = '$name'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    list($id) = mysql_fetch_array($result);
                    return $id;
                }
            }
        }
    }

    function menu_module_id() {
    //
    // gets module id for menu item
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu = trim($arg_list[0]);
            $sql = "select module_id from module_menu where menu_id = '$menu'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    list($id) = mysql_fetch_array($result);
                    return $id;
                }
            }
        }
    }

    // ---------------------- MODULE SUBMISSION METHODS ----------------------

    function process_module() {
    //
    // process module submissions
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            $post_files = $arg_list[1];
            //print_r($post_files);
            $uploadfile = "../modules/_uploads/".$_FILES["module_file"]["name"];
            if (move_uploaded_file($_FILES["module_file"]["tmp_name"], $uploadfile)) {
                return $this->uncompress($uploadfile);
            }
        }
    }

    function form_module() {
    //
    // for uploading new modules
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $get_vars = $arg_list[0];
            $post_vars = $arg_list[1];
            if ($get_vars["module_id"]) {
                $sql = "select module_id, module_name, module_desc, module_version, module_init, module_author from modules ".
                       "where module_id = '".$get_vars["module_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $module = mysql_fetch_array($result);
                        //print_r($image);
                    }
                }
            }
        }
        print "<table width='500'>";
        print "<form enctype='multipart/form-data' action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&module_id=".$get_vars["module_id"]."' name='form_module' method='post'>";
        print "<tr valign='top'><td>";
        print "<a name='mod_".$module["module_id"]."'>";
        print "<span class='module'>MODULE DATABASE</span><br><br>";
        print "</td></tr>";
        if ($get_vars["module_id"]) {
            print "<tr valign='top'><td>";
            print "<table width='500' cellpadding='2' cellspacing='0' style='border: 2px solid black'>";
            print "<tr valign='top'><td bgcolor='#FFFF99'>";
            print "<b>Module Name:</b> ".$module["module_name"]."<br>";
            print "<b>Module Version:</b> ".$module["module_version"]."<br>";
            print "<b>Module Author:</b><br>".$module["module_author"]."<br>";
            print "<b>Module Description:</b><br>".$module["module_desc"]."<br><br>";
            $class = "class.".$module["module_name"].".php";
            print "<b>Source Code:</b> <a href='../source.php?class=$class' target='_blank'>VIEW</a>";
            print "</td><td bgcolor='#99FF66'>";
            print "<b>Module Requires:</b><br>".$this->requires($module["module_name"])."<br>";
            print "<b>Module Dependents:</b><br>".$this->depends($module["module_name"])."<br>";
            print "<br></td></tr>";
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>LEAVE SQL TABLES</span><br> ";
            print "<input type='checkbox' name='leave_sql' checked value='1' ".($post_vars["leave_sql"]?"checked":"")."> Check to leave tables intact<br>";
            print "</td></tr></table>";
            print "</td></tr>";

        } else {
            print "<tr valign='top'><td>";
            print "<span class='boxtitle'>MODULE FILE</span><br> ";
            print "<input type='file' class='textbox' size='35' style='border: 1px solid black' maxlength='100' name='module_file' value='".($image["image_filename"]?$image["module_file"]:$post_vars["module_file"])."'>";
            print "</td></tr>";
        }
        print "<tr><td><br>";
        if ($get_vars["module_id"]) {
            print "<input type='hidden' name='module_id' value='".$get_vars["module_id"]."'>";
            if ($post_vars["noconfirm"]) {
                print "<input type='submit' value = 'Yes, Delete Module' class='textbox' name='submitmodule' style='border: 1px solid #000000'> ";
            } else {
                print "<input type='hidden' name='noconfirm' value='1'>";
                print "<input type='submit' value = 'Delete Module' class='textbox' name='submitmodule' style='border: 1px solid #000000'> ";
            }
        } else {
            print "<input type='hidden' name='MAX_FILE_SIZE' value='200000'>";
            print "<input type='submit' value = 'Add Module' class='textbox' name='submitmodule' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
        if ($get_vars["module_id"]) {
            print "<small>";
            //show_source("../modules/".$module["module_name"]."/class.".$module["module_name"].".php");
            print "</small>";
        }
    }

    function requires() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $module_name = $arg_list[0];
        }
        $sql = "select req_module ".
               "from module_dependencies ".
               "where module_id = '$module_name' order by req_module";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $i=0;
                while (list($name) = mysql_fetch_array($result)) {
                    $i++;
                    $ret_val .= "$i. $name -> ".(class_exists($name)?"<font color='#6666FF'><b>installed</b></font>":"<b><a href='".$_SERVER["PHP_SELF"]."?page=MODULES&method=MODDB'><font color='red'>missing</font></a></b>")."<br>";
                }
                return $ret_val;
            }
        }
    }

    function depends() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $module_name = $arg_list[0];
        }
        $sql = "select module_id ".
               "from module_dependencies ".
               "where req_module = '$module_name' order by req_module";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $i=0;
                while (list($name) = mysql_fetch_array($result)) {
                    $i++;
                    $ret_val .= "$i. $name -> ".(class_exists($name)?"<font color='#6666FF'><b>installed</b></font>":"<font color='red'><b>missing</b></font>")."<br>";
                }
                return $ret_val;
            } else {
                return "<font color='red'>none</font>";
            }
        }
    }


    function uncompress() {
        // WHAT THIS DOES:
        // 1. Reads file uploaded to modules/_uploads
        // 2. Uncompresses it and stores it to $contents
        // 3. Writes directory modules/$main
        // 4. Writes class file into modules/$main
        //
        // IMPORTANT
        // File must be of format class.<modname>.php.gz
        //
        $maxfilesize = 250000;
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $file = $arg_list[0];
        }
        $fp = gzopen("$file", "r");
        $contents = gzread($fp, $maxfilesize);
        // the following are placeholders, only $main matters:
        // $xx = class
        // $yy = php
        // $zz = gz
        list($xx,$main,$yy,$zz) = explode(".",basename($file));

        // create module directory
        $dir = @mkdir("../modules/$main");

        // write $contents to file
        if ($fp2 = fopen("../modules/$main/class.$main.php", "w+")) {
            fwrite($fp2, $contents);
            fclose($fp2);
            // update database
            $sql = "insert into modules (module_id, module_name) values ('$main', '$main')";
            if ($result = mysql_query($sql)) {
                $id = mysql_insert_id();
                return $id;
            } else {
                $sql = "select module_id from modules where module_name = '$main'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        list($id) = mysql_fetch_array($result);
                        return $id;
                    }
                }
            }
        }
        fclose($fp);
    }

    // ---------------------- MODULE ACTIVATION METHODS ----------------------

    function form_initmodule() {
    //
    // activation form for modules
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
            $post_vars = $arg_list[1];
        }
        print "<table width='500'>";
        print "<form action = '".$_SERVER["SELF"]."?page=MODULES&method=INIT' name='form_initmodule' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='module'>MODULE ACTIVATION</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>CHECK TO ACTIVATE MODULES</span><br> ";
        $sql = "select module_id, module_name, module_version, module_init from modules order by module_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<tr valign='top'><td>";
                print "<b>NOTE:</b> The unchecked modules can be activated. Do not forget to set module permissions in <b>MODULES-&gt;Grant Permissions</b> to enable site users to see corresponding menu entries for this module.<br>";
                print "</td></tr>";
                print "<tr valign='top'><td>";
                while (list($mid, $mname, $mversion, $minit) = mysql_fetch_array($result)) {
                    print "<input type='checkbox' name='initmod[]' value='$mid' ".($minit["module_init"]=='Y'?"checked":"")."> $mname ($mversion) ".($mversion?"<font color='red'><b>active</b></font>":"<font color='#CCCCCC'><b>inactive</b></font>")."<br>";
                }
                print "</td></tr>";
            }
        }
        print "<tr><td>";
        print "<input type='hidden' name='module_id' value='$module_id'>";
        print "<br><input type='submit' value = 'Update Activation Status' class='textbox' name='submitinitmod' style='border: 1px solid #000000'> ";
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function get_menu_id() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_action = $arg_list[0];
        }
        $sql = "select menu_id from module_menu where menu_action = '$menu_action'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($menu_id) = mysql_fetch_array($result);
                return $menu_id;
            }
        }
    }

    function error_message() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $error_id = $arg_list[0];
        }
        $sql = "select error_name from errorcodes where error_id = '$error_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($error_name) = mysql_fetch_array($result);
                print "<font color='red' size='4'><b>".$error_name."</b></font><br><br>";
            }
        }
    }

    function process_initmodule() {
        //
        // called from menu
        // writes ../modules/_modules.php and ../modules/_menu.php
        //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            //print_r($post_vars);
            if ($post_vars["initmod"]) {
                $sql = "update modules set module_init = 'N'";
                $result = mysql_query($sql);
                $generation_date = date("Y-m-d H:i:s");

                $modswitch .= "<?\n";
                $modswitch .= "// BEGIN SERVER CODE: DO NOT EDIT\n".
                              "// Server generated code\n".
                              "// Generated ".$generation_date."\n".
                              "// Module: _menu.php\n".
                              "// Author: Herman Tolentino MD\n".
                              "//\n";
                $modswitch .= "if (\$HTTP_GET_VARS[\"menu_id\"]) {\n";
                $modswitch .= "\tswitch (\$HTTP_GET_VARS[\"menu_id\"]) {\n";

                $modphp .= "<?\n";
                $modphp .= "// BEGIN SERVER CODE: DO NOT EDIT\n".
                           "// Server generated code\n".
                           "// Generated ".$generation_date."\n".
                           "// Module: _module.php\n".
                           "// Author: Herman Tolentino MD\n".
                           "//\n";
                foreach ($post_vars["initmod"] as $key=>$value) {
                    $modname = $this->module_name($value);
                    $sql_menu = "select menu_id, menu_action from module_menu where module_id = '".$modname."'";
                    if ($result = mysql_query($sql_menu)) {
                        if (mysql_num_rows($result)) {
                            while (list($m_id,$m_action) = mysql_fetch_array($result)) {
                                $modswitch .= "\tcase $m_id:\n";
                                $modswitch .= "\t\t\$".$modname."->".$m_action."(\$menu_id, \$HTTP_POST_VARS, \$HTTP_GET_VARS,\$_SESSION[\"validuser\"],\$_SESSION[\"isadmin\"]);\n";
                                $modswitch .= "\t\tbreak;\n";
                            }
                        }
                    }
                    $modphp .= "if (file_exists('../modules/$modname/class.$modname.php')) {\n";
                    $modphp .= "\tinclude '../modules/$modname/class.$modname.php';\n";
                    $modphp .= "\t\$$modname = new $modname;\n";
                    $modphp .= "\tif (!\$module->activated('$value') && \$initmod) {\n";
                    $modphp .= "\t\t\$".$modname."->init_sql();\n";
                    $modphp .= "\t\t\$".$modname."->init_menu();\n";
                    $modphp .= "\t\t\$".$modname."->init_deps();\n";
                    $modphp .= "\t\t\$".$modname."->init_lang();\n";
                    $modphp .= "\t\t\$".$modname."->init_help();\n";
                    $modphp .= "\t}\n";
                    $modphp .= "}\n";

                    // flag module as activated
                    $sql_flag = "update modules set module_init = 'Y' where module_id = '$value'";
                    $result_flag = mysql_query($sql_flag);

                }
                $modphp .= "\n// END SERVER CODE\n";
                $modphp .= "\n?>";

                $modswitch .= "\t}\n";
                $modswitch .= "}\n";
                $modswitch .= "\n// END SERVER CODE\n";
                $modswitch .= "\n?>";

                if ($fp_modphp = fopen("../modules/_modules.php", "w+")) {
                    if (fwrite($fp_modphp, $modphp)) {
                        fclose($fp_modphp);
                    }
                }
                if ($fp_modswitch = fopen("../modules/_menu.php", "w+")) {
                    if (fwrite($fp_modswitch, $modswitch)) {
                        fclose($fp_modswitch);
                    }
                }
            } else {
                $sql = "update modules set module_init = 'N'";
                $result = mysql_query($sql);
            }
        }
    }

    function activated() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
        }
        $sql = "select module_init from modules where module_id = '$module_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($init) = mysql_fetch_array($result);
                if ($init=='Y') {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    function req_activation() {
        // answers question: are there modules that require activation?
        // returns Y if there are any
        $sql = "select count(module_id) from modules where module_init = 'N'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($count) = mysql_fetch_array($result);
                if ($count>0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }


    // ---------------------- MODULE PERMISSIONS METHODS ----------------------

    function module_permission_status() {
    //
    // get permission status for modules
    // per user
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $module = $arg_list[0];
            $user_id = $arg_list[1];
        }
        $sql = "select module_id from module_permissions ".
               "where module_id = '".$module."' and user_id = '".$user_id."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                if (list($module_id) = mysql_fetch_array($result)) {
                    return $module_id;
                }
            }
        }
        return 0;
    }

    function menu_permission_status() {
    //
    // get permission status for menu items per module
    // per user
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

    function location_permission_status() {
    //
    // get permission status for menu items per location
    // per user
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $location_id = $arg_list[1];
        }
        $sql = "select menu_id from module_menu_location ".
               "where menu_id = '".$menu_id."' and location_id = '".$location_id."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                if (list($menu_id) = mysql_fetch_array($result)) {
                    return $menu_id;
                }
            }
        }
        return 0;
    }

    function location_user_permission_status() {
    //
    // get permission status for menu items per location
    // per user
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $location_id = $arg_list[0];
            $user_id = $arg_list[1];
        }
        $sql = "select user_id from module_user_location ".
               "where user_id = '".$user_id."' and location_id = '".$location_id."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                if (list($user_id) = mysql_fetch_array($result)) {
                    return $user_id;
                }
            }
        }
        return 0;
    }

    function process_permissions() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            $get_vars = $arg_list[1];
            //print_r($post_vars);
        }
        if ($post_vars["submitaccess"]=="Update Module Access") {
            // delete all references of user in module_permissions
            $sql_delete = "delete from module_permissions where user_id = '".$post_vars["module_user"]."'";
            $result_delete = mysql_query($sql_delete);
            if ($post_vars["modules"] && $post_vars["module_user"]) {
                // .. update with new one
                foreach($post_vars["modules"] as $key=>$value) {
                    $sql_new = "insert into module_permissions (module_id, user_id) ".
                               "values ('$value', '".$post_vars["module_user"]."')";
                    $result_new = mysql_query($sql_new);
                }
            }
        }
        if ($post_vars["submitaccess"]=="Update Menu Access") {
            // delete all references of user in module_menu_permissions
            $sql_delete = "delete from module_menu_permissions where user_id = '".$post_vars["module_user"]."'";
            $result_delete = mysql_query($sql_delete);
            if ($post_vars["menus"] && $post_vars["module_user"]) {
                // .. update with new one
                foreach($post_vars["menus"] as $key=>$value) {
                    $module_id = $this->menu_module_id($value);
                    $sql_new = "insert into module_menu_permissions (module_id, menu_id, user_id) ".
                               "values ('$module_id', '$value', '".$post_vars["module_user"]."')";
                    $result_new = mysql_query($sql_new);
                }
            }
        }
        if ($post_vars["submitaccess"]=="Update Location Access") {
            // delete all references of user in module_menu_location
            $sql_delete = "delete from module_menu_location where location_id = '".$post_vars["location_id"]."'";
            $result_delete = mysql_query($sql_delete);
            if ($post_vars["menus"] && $post_vars["location_id"]) {
                // .. update with new one
                foreach($post_vars["menus"] as $key=>$value) {
                    $module_id = $this->menu_module_id($value);
                    $sql_new = "insert into module_menu_location (module_id, menu_id, location_id) ".
                               "values ('$module_id', '$value', '".$post_vars["location_id"]."')";
                    $result_new = mysql_query($sql_new);
                }
            }
        }
        if ($post_vars["submitaccess"]=="Update User Location Access") {
            // delete all references of user in module_menu_location
            $sql_delete = "delete from module_user_location where user_id = '".$post_vars["module_user"]."'";
            $result_delete = mysql_query($sql_delete);
            if ($post_vars["location"] && $post_vars["module_user"]) {
                // .. update with new one
                foreach($post_vars["location"] as $key=>$value) {
                    $module_id = $this->menu_module_id($value);
                    $sql_new = "insert into module_user_location (location_id, user_id) ".
                               "values ('$value', '".$post_vars["module_user"]."')";
                    $result_new = mysql_query($sql_new);
                }
            }
        }
    }

    function form_permissions() {
    //
    // shows what permissions can be granted
    // at module and menu level
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            $get_vars = $arg_list[1];
            //print_r($arg_list);
            if ($get_vars["module_id"]) {
                $sql = "select module_id, module_name, module_desc, module_version, module_init from modules ".
                       "where module_id = '".$get_vars["module_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $module = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='600'>";
        print "<tr valign='top'><td colspan='2'>";
        print "<span class='module'>MODULE PERMISSIONS</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        // column 1
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=MODULES&method=PERMS#permtop' name='form_moduleperms' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='service'>GLOBAL PERMISSIONS</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "Check module name to give the user access to.<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>ALLOW USER</span><br> ";
        print $this->show_users($post_vars["module_user"]);
        print "<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>...ACCESS TO MODULE(S)</span><br> ";
        print $this->show_module_selection($post_vars["modules"], $post_vars["module_user"]);
        print "<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<input type='submit' value = 'Update Module Access' class='textbox' name='submitaccess' style='border: 1px solid #000000'> ";
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
        // end of column 1
        print "</td><td>";
        // column 2
        print "<a name=permtop>";
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=MODULES&method=PERMS#permtop' name='form_moduleperms' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='service'>MENU-LEVEL PERMISSIONS</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "Check menu name to give the user access to. The format ".
              "MENU_CATEGORY::MENU_ITEM means the menu item is ".
              "found under this menu category.<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>ALLOW USER</span><br> ";
        print $this->show_users($post_vars["module_user"]);
        print "<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>...ACCESS TO MENU(S)</span><br> ";
        print $this->show_module_menu_selection($post_vars["menus"], $post_vars["module_user"]);
        print "<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<input type='submit' value = 'Update Menu Access' class='textbox' name='submitaccess' style='border: 1px solid #000000'> ";
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
        // end of column 2
        print "</td></tr></table><br>";
    }

    function form_locations() {
    //
    // shows what permissions can be granted
    // at module and menu level
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            $get_vars = $arg_list[1];
            //print_r($arg_list);
        }
        print "<table width='600'>";
        print "<tr valign='top'><td colspan='2'>";
        print "<span class='module'>MENUS BY LOCATION</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        // column 1
        print "<a name=permtop>";
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=MODULES&method=LOC#permtop' name='form_moduleperms' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='service'>MENU LOCATION PERMISSIONS</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>Check menu name to give the location access to. The format ".
              "MENU_CATEGORY::MENU_ITEM means the menu item is ".
              "found under this menu category.</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>ALLOW LOCATION</span><br> ";
        print $this->show_locations($post_vars["location_id"]);
        print "<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>...ACCESS TO MENU(S)</span><br> ";
        print $this->show_module_menubylocation_selection($post_vars["menus"], $post_vars["location_id"]);
        print "<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<input type='submit' value = 'Update Location Access' class='textbox' name='submitaccess' style='border: 1px solid #000000'> ";
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
        print "</td><td>";
        // column 2
        print "<a name=permtop>";
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=MODULES&method=LOC#permtop' name='form_moduleperms' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='service'>USER LOCATION PERMISSIONS</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>Check location name to give the user access to.</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>ALLOW USER</span><br> ";
        print $this->show_users($post_vars["module_user"]);
        print "<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>...ACCESS TO LOCATION(S)</span><br> ";
        print $this->show_module_location_selection($post_vars["location"], $post_vars["module_user"]);
        print "<br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<input type='submit' value = 'Update User Location Access' class='textbox' name='submitaccess' style='border: 1px solid #000000'> ";
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
        print "</td></tr></table><br>";
    }

    function show_locations() {
    //
    // difference with user::show_locations is the refresh on selection by
    // onchange; for exclusive use of menu by location
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $location_id = $arg_list[0];
        }
        $sql = "select location_id, location_name from location order by location_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $retval .= "<select name='location_id' onchange='this.form.submit();' class='textbox'>";
                    $retval .= "<option value='0'>Select Location</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $retval .= "<option value='$id' ".($location_id==$id?"selected":"").">$name</option>";
                }
                $retval .= "</select>";
                return $retval;
            }
        }
    }

    function show_users() {
    //
    // difference with user::show_users is the refresh on selection by
    // onchange; for exclusive use of module and menu permissions
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $module_user = $arg_list[0];
        }
        $sql = "select user_id, concat(user_lastname, ', ', user_firstname) as name from game_user order by name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $retval .= "<select name='module_user' onchange='this.form.submit();' class='textbox'>";
                $retval .= "<option value='0'>Select User</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $retval .= "<option value='$id' ".($module_user==$id?"selected":"").">$name</option>";
                }
                $retval .= "</select>";
                return $retval;
            }
        }
    }

    function show_module_selection() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $module = $arg_list[0];
            $module_user = $arg_list[1];
            //print_r(array_values($module));
        }
        $sql = "select module_id, module_desc from modules order by module_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $retval .= "<input type='checkbox' value='$id' name='modules[]' ".($this->module_permission_status($id, $module_user)?"checked":"")."> $name<br>";
                }
                return $retval;
            }
        }
    }

    function show_module_menubylocation_selection() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu = $arg_list[0];
            $location = $arg_list[1];
            //print_r($arg_list);
        }
        $sql = "select menu_id, concat(menu_cat, '::', menu_title) module from module_menu order by menu_cat, menu_title";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $retval .= "<input type='checkbox' value='$id' name='menus[]' ".($this->location_permission_status($id, $location)>0?"checked":"")."> $name<br>";
                }
                return $retval;
            }
        }
    }

    function show_module_location_selection() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $location = $arg_list[0];
            $user_id = $arg_list[1];
            //print_r($arg_list);
        }
        $sql = "select location_id, location_name from location order by location_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $retval .= "<input type='checkbox' value='$id' name='location[]' ".($this->location_user_permission_status($id, $user_id)>0?"checked":"")."> $name<br>";
                }
                return $retval;
            }
        }
    }

    function show_module_menu_selection() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu = $arg_list[0];
            $module_user = $arg_list[1];
            //print_r($arg_list);
        }
        $sql = "select menu_id, concat(menu_cat, '::', menu_title) module from module_menu order by menu_cat, menu_title";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $retval .= "<input type='checkbox' value='$id' name='menus[]' ".($this->menu_permission_status($id, $module_user)>0?"checked":"")."> $name<br>";
                }
                return $retval;
            }
        }
    }

    // ----------------MODULE SQL FILE PROCESSING METHODS ----------------------

    function form_module_sql() {
    //
    // for uploading SQL files for modules
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $get_vars = $arg_list[0];
            $post_vars = $arg_list[1];
            //print_r($arg_list);
        }
        print "<table width='500'>";
        print "<form enctype='multipart/form-data' action = '".$_SERVER["PHP_SELF"]."?page=MODULES&method=SQL' name='form_module_sql' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='module'>LOAD MODULE SQL</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>SQL FILE</span><br> ";
        print "<input type='file' class='textbox' size='35' style='border: 1px solid black' maxlength='100' name='sql_file' value='".($image["image_filename"]?$image["module_file"]:$post_vars["module_file"])."'>";
        print "</td></tr>";
        print "<tr><td><br>";
        print "<input type='hidden' name='MAX_FILE_SIZE' value='2000000'>";
        print "<input type='submit' value = 'Add Module SQL' class='textbox' name='submitsql' style='border: 1px solid #000000'> ";
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_module_sql() {
    //
    // for processing uploaded SQL files for modules
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            $get_vars = $arg_list[1];
            $post_files = $arg_list[2];
            //print_r($arg_list);
        }
        if (file_exists($_FILES["sql_file"]["tmp_name"])) {
            if ($fp = fopen($_FILES["sql_file"]["tmp_name"], "r")) {
                while (!feof ($fp)) {
                    $sql_buffer = fgets($fp, 4096);
                    if ($result = mysql_query($sql_buffer)) {
                        $sql_status[] .= $sql_buffer." - <font color='gree'>SUCCESS</font>\n";
                    } else {
                        $sql_status[] .= $sql_buffer." - <font color='red'>FAIL</font>\n";
                    }
                }
                print "<span class='tinylight'>";
                foreach($sql_status as $key =>$value) {
                    print nl2br($value);
                }
                print "</span>";
                fclose ($fp);
                return true;
            }
            // uncomment line below for diagnostics
            header("location: ".$_SERVER["PHP_SELF"]."?page=MODULES&method=SQL");
        }
    }

    // ---------------------- MODULE MENU SYSTEM METHODS ----------------------

    function in_menu() {
    //
    // looks for $page in menu array
    // called from index.php
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $page = $arg_list[0];
            $menu = $arg_list[1];
        }
        //print_r($arg_list);
        foreach ($menu as $key=>$value) {
            if ($page===$value) {
                return true;
            }
        }
        return false;
    }

    function default_action() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $page = $arg_list[0];
        }
        $status = false;
        $sql = "select menu_id, module_id from module_menu where menu_cat = '$page' order by menu_cat, menu_rank";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($menu_id, $module_id) = mysql_fetch_array($result)) {
                    if ($this->module_permission_status($module_id,$_SESSION["userid"])) {
                        $status = true;
                        break;
                    }
                    if ($this->menu_permission_status($menu_id,$_SESSION["userid"])) {
                        $status = true;
                        break;
                    }
                }
                if ($status) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=$page&menu_id=$menu_id");
                } else {
                    print "<font color='red'>You need authorization for this page.</font><br/>";
                }
            }
        }
        return $status;

    }

    function process_order() {
    //
    // reorder menu
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            $get_vars = $arg_list[1];
            //print_r($arg_list[1]);
        }
        if ($get_vars["movedown"]) {
            $sql = "select menu_id, menu_rank from module_menu ".
                   "where menu_cat = '".$get_vars["cat"]."' ".
                   "order by menu_cat, menu_rank";
            if ($result = mysql_query($sql)) {
                if ($count = mysql_num_rows($result)) {
                    $i = 1;
                    while (list($id, $rank) = mysql_fetch_array($result)) {
                        $pos[$i] = $rank;
                        $menu[$i] = $id;
                        $i++;
                    }
                    for($i=1; $i<=$count; $i++) {
                        if ($get_vars["movedown"]==$pos[$i] && $i<$count) {
                            print $sql1 = "update module_menu set menu_rank = '".$pos[$i+1]."' where menu_id = '".$menu[$i]."'";
                            $result1 = mysql_query($sql1);
                            print $sql2 = "update module_menu set menu_rank = '".$pos[$i]."' where menu_id = '".$menu[$i+1]."'";
                            $result2 = mysql_query($sql2);
                            break;
                        }
                    }
                }
            }
        }
        if ($get_vars["moveup"]) {
            $sql = "select menu_id, menu_rank from module_menu ".
                   "where menu_cat = '".$get_vars["cat"]."' ".
                   "order by menu_cat, menu_rank desc";
            if ($result = mysql_query($sql)) {
                if ($count = mysql_num_rows($result)) {
                    $i = 1;
                    while (list($id, $rank) = mysql_fetch_array($result)) {
                        $pos[$i] = $rank;
                        $menu[$i] = $id;
                        $i++;
                    }
                    for($i=1; $i<=$count; $i++) {
                        if ($get_vars["moveup"]==$pos[$i] && $i<$count) {
                            $sql1 = "update module_menu set menu_rank = '".$pos[$i+1]."' where menu_id = '".$menu[$i]."'";
                            $result1 = mysql_query($sql1);
                            $sql2 = "update module_menu set menu_rank = '".$pos[$i]."' where menu_id = '".$menu[$i+1]."'";
                            $result2 = mysql_query($sql2);
                            break;
                        }
                    }
                }
            }
        }
        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]."#".$get_vars["cat"]);
    }

    function form_menuorder() {
    //
    // reorder menu through this
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            $get_vars = $arg_list[1];
            //print_r($arg_list[1]);
        }
        print "<span class='module'>MENU ITEM ORDER FORM</span><br/><br/>";
        print "<b>NOTE: Use this to reorder menu items according to user preference:</b><br/>";
        print "<b><img src='../images/uparrow.gif' border='0'/> = move up item</b><br/>";
        print "<b><img src='../images/downarrow.gif' border='0'/> = move down item</b><br/><br/>";
        $sql = "select menu_id, menu_title, menu_cat, menu_rank from module_menu order by menu_cat, menu_rank";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<table width='300' cellspacing='0'>";
                while (list($id, $title, $cat, $rank) = mysql_fetch_array($result)) {
                    if ($prevcat<>$cat) {
                        print "<tr bgcolor='#66CC00'><td colspan='3'><a name='$cat'><b>$cat</b></td></tr>";
                    }
                    //print "<tr><td>&nbsp;</td><td>$title [$id/$rank]</td><td>";
                    print "<tr><td>&nbsp;</td><td>$title </td><td>";
                    print "<a href='".$_SERVER["PHPS_SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]."&menu_item=$id&cat=$cat&moveup=$rank'><img src='../images/uparrow.gif' border='0'/></a>";
                    print "<a href='".$_SERVER["PHPS_SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]."&menu_item=$id&cat=$cat&movedown=$rank'><img src='../images/downarrow.gif' border='0'/></a>";
                    print "</td></tr>";
                    $prevcat = $cat;
                }
                print "</table>";
            }
        }

    }

    // ---------------------- MODULE MISCELLANEOUS METHODS --------------------

    function missing_dependencies() {
    //
    // answers query: does this require any module?
    // if yes and missing, return false
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $module_name = $arg_list[0];
        }
        // there should be at least 1 required module
        $sql = "select req_module from module_dependencies where module_id = '$module_name'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($module_name) = mysql_fetch_array($result)) {
                    if (!class_exists("$module_name")) {
                        $ret_val .= "<font color='red'><b>WARNING</b></font>: <b>$module_name</b> missing.<br>";
                    }
                }
                if (strlen($ret_val)>0) {
                    return $ret_val;
                } else {
                    return false;
                }
            } else {
                $ret_valexec_menu .= "<font color='red'><b>WARNING</b></font>: <b>$module_name</b> is not loaded.<br>";
                return $ret_val;
            }
        }
    }

    function columnize_list() {
    //
    // convert vertical list of array elements into
    // two column list
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $list = $arg_list[0];
        }
        //print_r($list);
        $items_per_column = (count($list)/2);
        $retval .= "<table width='100%' cellpadding='2' cellspacing='1'>";
        $retval .= "<tr bgcolor='white' valign='top'>";
        for ($i=0; $i<count($list);$i++) {
            if ($i<($items_per_column)) {
                $col1 .= $list[$i]."<br>";
            } else {
                $col2 .= $list[$i]."<br>";
            }
        }
        $retval .= "<td>$col1</td><td>$col2</td>";
        $retval .= "</tr></table>";
        return $retval;
    }

    function strfraction() {
    // prevents long text running off
    // when displaying table contents
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $string = $arg_list[0];
            $strlen = $arg_list[1];
            $str_array = explode(" ", $string);
            if (count($str_array)>$strlen) {
                for ($i=0; $i<=$strlen; $i++) {
                    $retval .= $str_array[$i]." ";
                }
                return trim($retval);
            } else {
                return $string;
            }

        }
    }


    function parsexml($mvalues) {
    //
    // used in reading xml configuration files
    //
        for ($i=0; $i < count($mvalues); $i++)
            $node[$mvalues[$i]["tag"]] = $mvalues[$i]["value"];
        return ($node);
    }

    function readconfig() {
    // read the xml database
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $filename = $arg_list[0];
            $xmltag = $arg_list[1];
        }
        $data = implode("",file($filename));
        $parser = xml_parser_create();
        xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
        xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
        xml_parse_into_struct($parser,$data,$values,$tags);
        xml_parser_free($parser);

        // loop through the structures
        foreach ($tags as $key=>$val) {
            if ($key == "$xmltag") {
                $noderanges = $val;
                // each contiguous pair of array entries are the
                // lower and upper range for each node definition
                for ($i=0; $i < count($noderanges); $i+=2) {
                    $offset = $noderanges[$i] + 1;
                    $len = $noderanges[$i + 1] - $offset;
                    $tdb[] = module::parsexml(array_slice($values, $offset, $len));
                }
            } else {
                continue;
            }
        }
        return $tdb;
    }

    function compute_similarity () {
    //
    // to compute similarity between two terms
    //
        if (func_num_args()>=2) {
            $arg_list = func_get_args();
            $var1 = $arg_list[0]; // index string
            $var2 = $arg_list[1]; // test string
            $var3 = $arg_list[2]; // threshold in percent
            $ref = 0.00;          // percentage similarity
            $value = similar_text($var1, $var2, $ref);
            if ($ref>=$var3) {
                return $ref;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function show_military_time() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $injury_time = $arg_list[0];
        }
        $ret_val .= "<table cellpadding='1'><tr valign='top'><td>";
        $ret_val .= "<select name='military_time' size='5' class='textbox'>";
        for($h=0; $h<=23; $h++) {
            for($m=0; $m<=59; $m++) {
                $id = str_pad($h,2,"0", STR_PAD_LEFT).str_pad($m,2,"0", STR_PAD_LEFT);
                $ret_val .= "<option value='$id' ".($injury_time==$id?"selected":"").">$id HRS</option>";
            }
        }
        $ret_val .= "</select>";
        $ret_val .= "</td><td>";
        $ret_val .= "<span class='tinylight'>";
        $ret_val .= "<b>MILITARY TIME EXAMPLES</b><br/>";
        $ret_val .= "1:00AM - 0100 HRS<br/>";
        $ret_val .= "5:00AM - 0500 HRS<br/>";
        $ret_val .= "1:00PM - 1300 HRS<br/>";
        $ret_val .= "8:00PM - 2000 HRS<br/>";
        $ret_val .= "</span>";
        $ret_val .= "</td></tr></table>";
        return $ret_val;
    }

    function pad_zero() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $input_string = $arg_list[0];
            $pad_length = $arg_list[1];
        }
        return str_pad($input_string, $pad_length,"0", STR_PAD_LEFT);
    }

}
?>
