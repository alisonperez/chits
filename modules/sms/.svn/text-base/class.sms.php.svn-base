<?
class sms extends module {

    // Author: Herman Tolentino MD
    // PGH SMS Project

    function sms() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.1-".date("Y-m-d");
        $this->module = "sms";
        $this->description = "PGH Module - Short Message System";
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
    // insert necessary language directives
    // NOTES:
    // 1. refer to table term
    // 2. skip remarks and translationof since this term is manually entered
    //
        module::set_lang("LBL_SYSTEM_USER", "english", "SYSTEM USER", "Y");
        module::set_lang("LBL_CELLULAR_NUMBER", "english", "CELLULAR NUMBER", "Y");
        module::set_lang("LBL_SMS_MESSAGE", "english", "SMS MESSAGE", "Y");

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

        module::set_menu($this->module, "SMS Message", "SUPPORT", "_sms");

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
        mkdir("../modules/".$this->module."/gsm");
        mkdir("../modules/".$this->module."/gsm/out");
        mkdir("../modules/".$this->module."/gsm/in");
        mkdir("../modules/".$this->module."/gsm/sent");
        mkdir("../modules/".$this->module."/gsm/fail");

    }

    function drop_tables() {
    //
    // called from delete_module()
    //

    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _sms() {
    //
    // main method for vaccine
    // called from database menu entry
    // calls form_vaccine(), process_vaccine(), display_vaccine()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('sms')) {
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
        if ($post_vars["submitreminder"]) {
            $this->process_sms($menu_id, $post_vars, $get_vars);
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
        }
        $this->form_sms($menu_id, $post_vars, $get_vars, $isadmin);
        $this->display_sms($menu_id, $post_vars, $get_vars, $isadmin);
    }

    function display_sms() {
    //
    // called from _sms()
    //
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
        print "<span class='library'>".FTITLE_SMS_MESSAGES_TODAY."</span><br>";
        print "</td></tr>";
        print "</table><br>";
    }

    function form_sms() {
    //
    // called from _sms()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $isadmin = $arg_list[3];
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_user' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_SMS_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_SYSTEM_USER."</span><br> ";
        print user::show_cellular_number($post_vars["user_cellnumber"]);
        print "</td></tr>";
        print "</form>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_sms' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CELLULAR_NUMBER."</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='20' name='vaccine_id' value='".($vaccine["vaccine_id"]?$vaccine["vaccine_id"]:$post_vars["user_cellnumber"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_SMS_MESSAGE."</span><br> ";
        print "<textarea name='msg_body' onChange='document.form_sms.count_display.value=document.form_sms.msg_body.value.length;' ".
              "onkeypress='document.form_sms.count_display.value=document.form_sms.msg_body.value.length+1;' ".
              "onBlur='document.form_sms.count_display.value=document.form_sms.msg_body.value.length;' rows='5' cols='30' class='textbox'>".
              (isset($msg_body)?stripslashes($msg_body):'')."</textarea>".
              "<br><span class='btext'>Character count</span> ".
              "<input type='text' name='count_display' class='textbox' size='4' style='background: #ffff00;' readonly>".
              " <span class='small'>Max of 160 characters</span>";
        print "</td></tr>";
        print "<tr><td><br>";
        print "<input type='submit' value = 'Send SMS' class='textbox' name='submitsms' style='border: 1px solid #000000'> ";
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_sms() {
    //
    // called from _vaccine()
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        if ($post_vars["submitvaccine"]) {
            if ($post_vars["vaccine_id"] && $post_vars["vaccine_name"]) {
                switch($post_vars["submitvaccine"]) {
                case "Add Vaccine":
                    $sql .= "insert into m_lib_vaccine (vaccine_id, vaccine_name, vaccine_desc) ";
                    $sql .= "values ('".$post_vars["vaccine_id"]."', '".$post_vars["vaccine_name"]."', '".$post_vars["vaccine_desc"]."')";
                    $result = mysql_query($sql);
                    break;
                case "Update Vaccine":
                    $sql = "update m_lib_vaccine set ".
                           "vaccine_name = '".$post_vars["vaccine_name"]."', ".
                           "vaccine_desc = '".$post_vars["vaccine_desc"]."' ".
                           "where vaccine_id = '".$post_vars["vaccine_id"]."'";
                    $result = mysql_query($sql);
                    break;
                case "Delete Vaccine":
                    $sql = "delete from m_lib_vaccine ".
                           "where vaccine_id = '".$post_vars["vaccine_id"]."'";
                    $result = mysql_query($sql);
                    break;
                }
            }
        }
    }

    function send_sms ($msg, $cellular) {
        $tmpname = tempnam("../gsm/out/", "SMS");
        $msg_string = "NUMBER: \+$cellular\n$msg\n";
        if ($smsfile = fopen("$tmpname", "w+")) {
                if (fwrite($smsfile, $msg_string)) {
                        $message = "$tmpname sent to $cellular";
                        tsyslog("access", $message);
                        sleep(1);
                        return true;
                } else {
                        return false;
                }
                fclose($smsfile);
        } else {
                return false;
        }
    }

}
?>
