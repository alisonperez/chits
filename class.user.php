<?
class User {
//
// Module User
// Type: internal
// Author: Herman Tolentino MD
//
    function User () {
        $this->version = "0.3";
        $this->author = "Herman Tolentino MD";
        // version 0.4
        // added form_location, display_location and process_location
    }

    function count() {
        $sql = "select count(user_id) from game_user";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($count) = mysql_fetch_array($result);
                return $count;
            }
        }
    }

    function _game_user() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        if ($post_vars["submituser"]) {
            user::process_user($menu_id, $post_vars, $get_vars);
        }
        print "<table width='600' cellpadding='2'><tr valign='top'><td>";
        user::form_user($menu_id, $post_vars, $get_vars);
        print "</td><td>";
        user::display_users($menu_id, $post_vars, $get_vars);
        print "</td></tr></table>";
    }

    function show_cellular_number() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $cellnumber = $arg_list[0];
            //print_r($arg_list);
        }
        $sql = "select concat(user_lastname, ', ', user_firstname) name, user_cellular ".
               "from game_user where user_cellular <>'' order by user_lastname, user_firstname";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<select name='user_cellnumber' onchange='this.form.submit();' class='textbox'>";
                print "<option value=''>Select User</option>";
                while (list($name, $cell) = mysql_fetch_array($result)) {
                    print "<option value='$cell' ".($cellnumber==$cell?"selected":"").">$name</option>";
                }
                print "</select>";
            }
        }
    }

    function process_user() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            //print_r($arg_list);
        }
        // cannot delete admin user
        if ($post_vars["submituser"]=="Delete User" && $post_vars["user_id"]<>1) {
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                print $sql = "delete from game_user ".
                       "where user_id = '".$post_vars["user_id"]."'";
                if ($result = mysql_query($sql)) {
                    // delete module permissions
                    $sql_module_perms = "delete from module_permissions where user_id = '".$post_vars["user_id"]."'";
                    $result_module_perms = mysql_query($sql_module_perms);
                    // delete menu permissions
                    $sql_menu_perms = "delete from module_menu_permissions where user_id = '".$post_vars["user_id"]."'";
                    $result_menu_perms = mysql_query($sql_menu_perms);
                    header("location: ".$_SERVER["PHP_SELF"]."?page=ADMIN&method=USER");

                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=ADMIN&method=USER");
                }
            }
        } else {
            $errorinfo = $this->validate_user($post_vars);
        }
        if (strlen($errorinfo)>0) {
            print "<ol>$errorinfo</ol>";
        } else {
            list($month, $day, $year) = explode("/", $post_vars["user_dob"]);
            $dob = $year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-".str_pad($day, 2, "0", STR_PAD_LEFT);
            switch ($post_vars["submituser"]) {
            case "Add User":
                $active = ($post_vars["isactive"]?"Y":"N");
                $admin = ($post_vars["isadmin"]?"Y":"N");
                $sql = "insert into game_user (user_firstname, user_lastname, user_middle, user_lang, ".
                       "user_email, user_cellular, user_login, user_password, user_pin, user_dob, user_gender, ".
                       "user_active, user_admin, user_role) ".
                       "values ('".ucwords($post_vars["user_firstname"])."', '".ucwords($post_vars["user_lastname"])."', '".ucwords($post_vars["user_middle"])."', ".
                       "'".$post_vars["lang_id"]."', '".strtolower($post_vars["user_email"])."', '".$post_vars["user_cellular"]."', ".
                       "'".strtolower($post_vars["user_login"])."', old_password('".$post_vars["user_password"]."'), '".$post_vars["user_pin"]."', '$dob', '".$post_vars["user_gender"]."', ".
                       "'$active', '$admin', '".$post_vars["role_id"]."')";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=ADMIN&method=USER");
                }
                break;
            case "Update User":
                $active = ($post_vars["isactive"]?"Y":"N");
                $admin = ($post_vars["isadmin"]?"Y":"N");
                list($month, $day, $year) = explode("/", $post_vars["user_dob"]);
                if (strlen($post_vars["user_password"])>0 && strlen($post_vars["confirm_password"])>0 && $post_vars["password"]==$post_vars["confirm_password"]) {
                    print $sql = "update game_user set ".
                           "user_firstname = '".ucwords($post_vars["user_firstname"])."', ".
                           "user_lastname = '".ucwords($post_vars["user_lastname"])."', ".
                           "user_middle = '".ucwords($post_vars["user_middle"])."', ".
                           "user_email = '".strtolower($post_vars["user_email"])."', ".
                           "user_cellular = '".$post_vars["user_cellular"]."', ".
                           "user_login = '".$post_vars["user_login"]."', ".
                           "user_pin = '".$post_vars["user_pin"]."', ".
                           "user_lang = '".$post_vars["lang_id"]."', ".
                           "user_role = '".$post_vars["role_id"]."', ".
                           "user_dob = '$dob', ".
                           "user_gender = '".$post_vars["user_gender"]."', ".
                           "user_password = old_password('".$post_vars["user_password"]."'), ".
                           "user_active = '$active', ".
                           "user_admin = '$admin' ".
                           "where user_id = '".$post_vars["user_id"]."'";
                } else {
                    print $sql = "update game_user set ".
                           "user_firstname = '".ucwords($post_vars["user_firstname"])."', ".
                           "user_lastname = '".ucwords($post_vars["user_lastname"])."', ".
                           "user_middle = '".ucwords($post_vars["user_middle"])."', ".
                           "user_email = '".strtolower($post_vars["user_email"])."', ".
                           "user_cellular = '".$post_vars["user_cellular"]."', ".
                           "user_login = '".$post_vars["user_login"]."', ".
                           "user_pin = '".$post_vars["user_pin"]."', ".
                           "user_lang = '".$post_vars["lang_id"]."', ".
                           "user_role = '".$post_vars["role_id"]."', ".
                           "user_dob = '$dob', ".
                           "user_gender = '".$post_vars["user_gender"]."', ".
                           "user_password = old_password('".$post_vars["user_password"]."'), ".
                           "user_active = '$active', ".
                           "user_admin = '$admin' ".
                           "where user_id = '".$post_vars["user_id"]."'";
                }
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=ADMIN&method=USER");
                }
                break;
            }
        }
    }

    function validate_user() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $post_vars = $arg_list[0];
            if (empty($post_vars["user_firstname"])) {
                $retval .= "<li class='error'>First name field cannot be empty.</li>";
            }
            if (empty($post_vars["user_lastname"])) {
                $retval .= "<li class='error'>Last name field cannot be empty.</li>";
            }
            if (empty($post_vars["user_pin"])) {
                $retval .= "<li class='error'>PIN field cannot be empty.</li>";
            }
            if (empty($post_vars["role_id"])) {
                $retval .= "<li class='error'>Role field cannot be empty.</li>";
            }
            if (empty($post_vars["lang_id"])) {
                $retval .= "<li class='error'>Language/dialect cannot be empty.</li>";
            }
            if (empty($post_vars["user_gender"])) {
                $retval .= "<li class='error'>Gender cannot be empty.</li>";
            }
            if (!empty($post_vars["user_dob"])) {
                list($month, $day, $year) = explode("/", $post_vars["user_dob"]);
                if (!checkdate($month, $day, $year)) {
                    $retval .= "<li class='error'>Invalid date of birth.</li>";
                }
            }
            if (empty($post_vars["user_login"])) {
                $retval .= "<li class='error'>Login name field cannot be empty.</li>";
            }
            if ($post_vars["submituser"]<>"Update User") {
                if (empty($post_vars["user_password"])) {
                    $retval .= "<li class='error'>Password field cannot be empty.</li>";
                } else {
                    if ($post_vars["user_password"]<>$post_vars["confirm_password"]) {
                        $retval .= "<li class='error'>Password confirmation incorrect.</li>";
                    }
                }
            }
            return $retval;
        }
    }


    function display_users () {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        print "<table width='250' cellspacing='0' cellpadding='2'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='admin'>".FTITLE_SITE_USERS."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td colspan='3'>";
        print "<b>NOTE:</b> Greyed out names are inactive. <font color='red'><b>ADM</b></font> means Admin Account.<br><br>";
        print "</td></tr>";
        $sql = "select user_id, concat(user_lastname, ', ',user_firstname) name, user_admin, user_active ".
               "from game_user order by user_lastname, user_firstname";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $admin, $active) = mysql_fetch_array($result)) {
                    $bgcolor = ($active=='Y'?"white":"lightgray");
                    print "<tr valign='top' bgcolor='$bgcolor'><td nowrap>".($admin=="Y"?"<font color='red'><b>ADM</b></font> ":"")."<a href='".$_SERVER["SELF"]."?page=ADMIN&method=USER&user_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function form_user () {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["user_id"]) {
                $sql = "select user_id, user_lastname, user_firstname, user_lang, user_dob, user_gender, user_email, user_pin, user_login, user_admin, user_active, user_cellular, user_role ".
                       "from game_user where user_id = '".$get_vars["user_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $user = mysql_fetch_array($result);
                        //print_r($user);
                    }
                }
            }
        }
        print "<table wifth='300'>";
        print "<tr valign='top'><td>";
        print "<span class='admin'>".FTITLE_SITE_USER_FORM."</span><br><br>";
        print "</td></tr>";
        print "<form action = '".$_SERVER["SELF"]."?page=ADMIN&method=USER&user_id=".$get_vars["user_id"]."' name='form_user' method='post'>";
        print "<tr><td colspan='2'><b>NOTE: Fields marked with <font color='red'>*</font> are required.</b><br><br></td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_FIRST_NAME."</span><br> ";
        print "<input type='text' maxlength='50' class='textbox' name='user_firstname' value='".($user["user_firstname"]?$user["user_firstname"]:$post_vars["user_firstname"])."' style='border: 1px solid #000000'> <font color='red'>*</font><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_MIDDLE_NAME."</span><br> ";
        print "<input type='text' maxlength='50' class='textbox' name='user_middle' value='".($user["user_middle"]?$user["user_middle"]:$post_vars["user_middle"])."' style='border: 1px solid #000000'> <font color='red'>*</font><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LAST_NAME."</span><br> ";
        print "<input type='text' maxlength='50' class='textbox' name='user_lastname' value='".($user["user_lastname"]?$user["user_lastname"]:$post_vars["user_lastname"])."' style='border: 1px solid #000000'> <font color='red'>*</font><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_DATE_OF_BIRTH."</span><br> ";
        if ($user["user_dob"]) {
            list($year, $month, $day) = explode("-", $user["user_dob"]);
        }
        print "<input type='text' size='10' maxlength='10' class='textbox' name='user_dob' value='".($user["user_dob"]?"$month/$day/$year":$post_vars["user_dob"])."' style='border: 1px solid #000000'> &nbsp;";
        //print "<small>Use MM/DD/YYYY format.</small><br>";
        
        print "<a href=\"javascript:show_calendar4('document.form_user.user_dob', document.form_user.user_dob.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click Here to Pick up the date'></a>&nbsp;<font color='red'>*</font>";
        
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GENDER."</span> <font color='red'>*</font><br> ";
        print "<input type='radio' name='user_gender' value='M' ".(($user["user_gender"]?$user["user_gender"]:$post_vars["user_gender"])=="M"?"checked":"")."> Male &nbsp;&nbsp;";
        print "<input type='radio' name='user_gender' value='F' ".(($user["user_gender"]?$user["user_gender"]:$post_vars["user_gender"])=="F"?"checked":"")."> Female &nbsp;&nbsp;<br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PIN."</span><br> ";
        print "<input type='text' size='15' maxlength='15' class='textbox' name='user_pin' value='".($user["user_pin"]?$user["user_pin"]:$post_vars["user_pin"])."' style='border: 1px solid #000000'> <font color='red'>*</font><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ROLE."</span><br> ";
        print $this->show_roles($user["user_role"]?$user["user_role"]:$post_vars["role_id"]);
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LANGUAGE_DIALECT."</span><br> ";
        print $this->show_lang($user["user_lang"]?$user["user_lang"]:$post_vars["lang_id"]);
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_LOGIN_NAME."</span><br> ";
        print "<input type='text' size='15' maxlength='15' class='textbox' name='user_login' value='".($user["user_login"]?$user["user_login"]:$post_vars["user_login"])."' style='border: 1px solid #000000'> <font color='red'>*</font><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_PASSWORD."</span><br> ";
        print "<input type='password' size='15' maxlength='15' class='textbox' name='user_password' style='border: 1px solid #000000'> <font color='red'>*</font><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CONFIRM_PASSWORD_AGAIN."</span><br> ";
        print "<input type='password' size='15' maxlength='15' class='textbox' name='confirm_password' style='border: 1px solid #000000'> <font color='red'>*</font><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ACTIVE_USER."</span> ";
        print "<input type='checkbox' class='textbox' value='1' ".(($user["user_active"]?$user["user_active"]:$post_vars["user_active"])=="Y"?"checked":"")." name='isactive'> Check if active<br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ADMIN_USER."</span> ";
        print "<input type='checkbox' class='textbox' value='1' ".(($user["user_admin"]?$user["user_admin"]:$post_vars["user_admin"])=="Y"?"checked":"")." name='isadmin'> Check if admin<br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_EMAIL."</span><br> ";
        print "<input type='text' maxlength='100' class='textbox' name='user_email' value='".($user["user_email"]?$user["user_email"]:$post_vars["user_email"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CELLULAR."</span><br> ";
        print "<input type='text' maxlength='20' class='textbox' name='user_cellular' value='".($user["user_cellular"]?$user["user_cellular"]:$post_vars["user_cellular"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["user_id"]) {
            print "<input type='hidden' name='user_id' value='".$get_vars["user_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update User' class='textbox' name='submituser' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete User' class='textbox' name='submituser' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add User' class='textbox' name='submituser' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function _roles() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        if ($post_vars["submitrole"]) {
            user::process_role($menu_id, $post_vars, $get_vars);
        }
        user::display_role($menu_id, $post_vars, $get_vars);
        user::form_role($menu_id, $post_vars, $get_vars);
    }

    function show_roles() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $role_id = $arg_list[0];
        }
        $sql = "select role_id, role_name from role order by role_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $retval .= "<select name='role_id' class='textbox'>";
                    $retval .= "<option value='0'>Select Role</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $retval .= "<option value='$id' ".($role_id==$id?"selected":"").">$name</option>";
                }
                $retval .= "</select>";
                return $retval;
            }
        }
    }

    function display_role() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='400'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='admin'>".FTITLE_ROLE_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>".THEAD_NAME."</b></td><td><b>".THEAD_ACCESS."</b></td></tr>";
        $sql = "select role_id, role_name, role_dataaccess from role order by role_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name, $access) = mysql_fetch_array($result)) {
                    $access_string = "";
                    if (substr($access,0,1)=="1") {
                        $access_string .= "add";
                    }
                    if (substr($access,1,1)=="1") {
                        $access_string .= ", update";
                    }
                    if (substr($access,2,1)=="1") {
                        $access_string .= ", delete";
                    }
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]."&role_id=$id'>$name</a></td><td>$access_string</td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function process_role() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($post_vars);
        }
        $add = "0";
        $update = "0";
        $delete = "0";
        foreach($post_vars["access"] as $key=>$value) {
            if ($value=="add") {
                $add = "1";
            }
            if ($value=="update") {
                $update = "1";
            }
            if ($value=="delete") {
                $delete = "1";
            }
        }
        $access = $add.$update.$delete;
        switch ($post_vars["submitrole"]) {
        case "Add Role":
            if ($post_vars["role_id"] && $post_vars["role_name"]) {
                $sql = "insert into role (role_id, role_name, role_dataaccess) ".
                       "values ('".$post_vars["role_id"]."', '".$post_vars["role_name"]."', '$access')";
                $result = mysql_query($sql);
            }
            break;
        case "Update Role":
            if ($post_vars["role_id"] && $post_vars["role_name"]) {
                $sql = "update role set ".
                       "role_name = '".$post_vars["role_name"]."', ".
                       "role_dataaccess = '$access' ".
                       "where role_id = '".$post_vars["role_id"]."'";
                $result = mysql_query($sql);
            }
            break;
        case "Delete Role":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from role where role_id = '".$post_vars["role_id"]."'";
                $result = mysql_query($sql);
            }
            break;
        }
        // you can comment out line below for debugging
        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&method=ROLES");
    }

    function form_role() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            if ($get_vars["role_id"]) {
                $sql = "select role_id, role_name, role_dataaccess ".
                       "from role where role_id = '".$get_vars["role_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $role = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table wifth='300'>";
        print "<tr valign='top'><td>";
        print "<span class='admin'>".FTITLE_ROLE_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>".INSTR_ROLE_FORM."</b><br><br>";
        print "</td></tr>";
        print "<form action = '".$_SERVER["SELF"]."?page=ADMIN&method=ROLES&user_id=".$get_vars["role_id"]."' name='form_role' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ROLE_ID."</span><br> ";
        print "<input type='text' size='10' maxlength='10' class='textbox' name='role_id' value='".($role["role_id"]?$role["role_id"]:$post_vars["role_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ROLE_NAME."</span><br> ";
        print "<input type='text' maxlength='30' class='textbox' name='role_name' value='".($role["role_name"]?$role["role_name"]:$post_vars["role_name"])."' style='border: 1px solid #000000'> <br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_DATA_ACCESS."</span><br> ";
        if (substr($role["role_dataaccess"],0,1)=="1") {
            $add=true;
        }
        if (substr($role["role_dataaccess"],1,1)=="1") {
            $update=true;
        }
        if (substr($role["role_dataaccess"],2,1)=="1") {
            $delete=true;
        }
        print "<input type='checkbox' name='access[]' ".($add?"checked":"")." value='add'> Add<br>";
        print "<input type='checkbox' name='access[]' ".($update?"checked":"")." value='update'> Update<br>";
        print "<input type='checkbox' name='access[]' ".($delete?"checked":"")." value='delete'> Delete<br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["role_id"]) {
            print "<input type='hidden' name='role_id' value='".$get_vars["role_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Role' class='textbox' name='submitrole' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Role' class='textbox' name='submitrole' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Role' class='textbox' name='submitrole' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function get_access() {
    // get access flags based on role_id
        if (func_num_args()) {
            $arg_list = func_get_args();
            $role_id = $arg_list[0];
        }
        $sql = "select role_dataaccess from role where role_id = '".$role_id."'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($access_flags) = mysql_fetch_array($result);
                return $access_flags;
            }
        }
    }
    function _location() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        if ($post_vars["submitlocation"]) {
            user::process_location($menu_id, $post_vars, $get_vars);
        }
        user::display_location($menu_id, $post_vars, $get_vars);
        user::form_location($menu_id, $post_vars, $get_vars);
    }

    function show_locations() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $location_id = $arg_list[0];
        }
        $sql = "select location_id, location_name from location order by location_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $retval .= "<select name='location_id' class='textbox'>";
                    $retval .= "<option value='0'>Select Location</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $retval .= "<option value='$id' ".($location_id==$id?"selected":"").">$name</option>";
                }
                $retval .= "</select>";
                return $retval;
            }
        }
    }

    function display_location() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='400'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='admin'>LOCATION LIST</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>NAME</b></td></tr>";
        $sql = "select location_id, location_name from location order by location_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&method=".$get_vars["method"]."&location_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function process_location() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        switch ($post_vars["submitlocation"]) {
        case "Add Location":
            if ($post_vars["location_id"] && $post_vars["location_name"]) {
                $sql = "insert into location (location_id, location_name) ".
                       "values ('".$post_vars["location_id"]."', '".$post_vars["location_name"]."')";
                $result = mysql_query($sql);
            }
            break;
        case "Update Location":
            if ($post_vars["location_id"] && $post_vars["location_name"]) {
                $sql = "update location set ".
                       "location_name = '".$post_vars["location_name"]."' ".
                       "where location_id = '".$post_vars["location_id"]."'";
                $result = mysql_query($sql);
            }
            break;
        case "Delete Location":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from location where location_id = '".$post_vars["location_id"]."'";
                $result = mysql_query($sql);
            }
            break;
        }
        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&method=LOC");
    }

    function form_location() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            if ($get_vars["role_id"]) {
                $sql = "select location_id, location_name ".
                       "from location where location_id = '".$get_vars["location_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $location = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table wifth='300'>";
        print "<tr valign='top'><td>";
        print "<span class='admin'>USER LOCATION FORM</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>INSTRUCTIONS:</b> Users can be assigned to different locations and be given permission to access menu items assigned to those locations. ".
              "You can create those locations here and given them permissions in <b>MODULE-&gt;Menu By Location</b>.<br><br>";
        print "</td></tr>";
        print "<form action = '".$_SERVER["SELF"]."?page=ADMIN&method=LOC' name='form_location' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>LOCATION ID</span><br> ";
        print "<input type='text' size='10' maxlength='10' class='textbox' name='location_id' value='".($location["location_id"]?$location["location_id"]:$post_vars["location_id"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>LOCATION NAME</span><br> ";
        print "<input type='text' maxlength='30' class='textbox' name='location_name' value='".($location["location_name"]?$location["location_name"]:$post_vars["location_name"])."' style='border: 1px solid #000000'> <br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["role_id"]) {
            print "<input type='hidden' name='location_id' value='".$get_vars["location_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Location' class='textbox' name='submitlocation' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Location' class='textbox' name='submitlocation' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Location' class='textbox' name='submitlocation' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function show_lang() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $lang_id = $arg_list[0];
        }
        $sql = "select distinct languageid from terms order by languageid";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $retval .= "<select name='lang_id' class='textbox'>";
                    $retval .= "<option value='0'>Select Language/Dialect</option>";
                while (list($id) = mysql_fetch_array($result)) {
                    $retval .= "<option value='$id' ".($lang_id==$id?"selected":"").">$id</option>";
                }
                $retval .= "</select>";
                return $retval;
            }
        }
    }


    function authenticate () {
        print
        "<table width='160' style='border: 1px solid black' bgcolor='#FFFFCC' cellspacing='0' cellpadding='5'>".
        "<form action = '".$_SERVER["PHP_SELF"]."' name='form_auth' method='post'>".
        "<tr bgcolor='#CCCC00'><td>".
        "<b>".LBL_SIGN_IN."</b>".
        "</td></tr>".
        "<tr><td>".
        "<span class='tiny'>".LBL_LOGIN_NAME."</span><br>".
        "<input type='text' size='8' class='textbox' name='login' style='border: 1px solid #000000'><br>".
        "</td></tr>".
        "<tr><td>".
        "<span class='tiny'>".LBL_PASSWORD."</span><br>".
        "<input type='password' size='8' class='textbox' name='passwd' style='border: 1px solid #000000'><br>".
        "</td></tr>".
        "<tr><td>".
        "<input type='submit' value = 'Login' class='textbox' name='submitlogin' style='border: 1px solid #000000'><br>".
        "</td></tr>".
        "</form></table>";
    }

    function process_auth ($login, $password) {              
        $sql = "select user_id, user_firstname, user_lastname, user_login, user_password, user_dob, user_gender, user_lang, user_admin, user_role ".
               "from game_user where user_login = '$login' and user_password = old_password('$password') and user_active = 'Y'";
        
        /*$sql = "select user_id, user_firstname, user_lastname, user_login, user_password, user_dob, user_gender, user_lang, user_admin, user_role ".
               "from game_user where user_login = '$login' and user_password = password('$password') and user_active = 'Y'";*/
      
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $user_array = mysql_fetch_array($result);
            }
        }
        
        return $user_array;
    }

    function signoff() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $first = $arg_list[0];
            $last = $arg_list[1];
            $service = $arg_list[2];
            $isadmin = $arg_list[3];
            $ipaddress = $arg_list[4];
            $userid = $arg_list[5];
        }
        print
        "<table width='160' style='border: 1px solid black' bgcolor='#FFFFCC' cellspacing='0' cellpadding='5'>".
        "<form action = '".$_SERVER["PHP_SELF"]."' name='form_auth' method='post'>".
        "<tr bgcolor='#CCCC00'><td>".
        "<b>".LBL_SIGN_OUT."</b>".
        "</td></tr>".
        "<tr><td>".
        "<font size='2'><b>".ucwords("$first $last")."</b> $userid ".($isadmin?"<b>[admin]</b>":"")." from <b>$service</b> logged in from <b>$ipaddress</b>. Please do not forget to sign off.</font><br>".
        "<tr><td>".
        "<input type='submit' value = '".BTN_SIGN_OUT."' class='textbox' name='submitlogout' style='border: 1px solid #000000'><br>".
        "</td></tr>".
        "</form></table>";
    }

    function process_signoff () {
        
        $q_logout = mysql_query("UPDATE user_logs SET logout=NOW() WHERE log_id='$_SESSION[log_id]'") or die("Cannot query (775): ".mysql_error());
        
        session_destroy();
    }

    function print_error($error) {
        print "<ol>$error</ol>";
    }

    function show_users() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $user_id = $arg_list[0];
            //print_r($arg_list);
        }
        $sql = "select user_id, concat(user_lastname, ', ', user_firstname) as name from game_user order by name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $retval .= "<select name='user_id' class='textbox'>";
                $retval .= "<option value='0'>Select User</option>";
                while (list($id, $name) = mysql_fetch_array($result)) {
                    $retval .= "<option value='$id' ".($user_id==$id?"selected":"").">$name</option>";
                }
                $retval .= "</select>";
                return $retval;
            }
        }
    }

    function check_users() {
        if (func_num_args()) {
            $arg_list = func_get_args();
        }
        $sql = "select count(user_id) from game_user";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                if (list($count) = mysql_fetch_array($result)) {
                    if ($count==0) {
                        $sql = "insert into game_user (user_id, user_firstname, user_lastname, user_login, user_password, user_admin, user_active, user_lang) ".
                               "values (1, 'Admin', 'User', 'admin', old_password('admin'), 'Y', 'Y', 'english')";
                        return $result = mysql_query($sql);
                    }
                }
            }
        }
        return false;
    }

    function get_username() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $userid = $arg_list[0];
        }
        $sql = "select concat(user_lastname, ', ', user_firstname) from game_user where user_id = '$userid'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
   }

}
?>
