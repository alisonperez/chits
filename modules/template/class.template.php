<?
class template extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // for module guidelines see MODULES.TXT
    // Class Template
    // creates templates for text entries

    function template() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.2-".date("Y-m-d");
        $this->module = "template";
        $this->description = "CHITS Library - Template";
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    // use multiple inserts
    //
        module::set_dep($this->module, "module");

    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_TEMPLATE_LIST", "english", "TEMPLATE LIST", "Y");
        module::set_lang("THEAD_TEMPLATE_KEY", "english", "TEMPLATE KEY", "Y");
        module::set_lang("THEAD_TEMPLATE_TEXT", "english", "TEMPLATE TEXT", "Y");
        module::set_lang("FTITLE_TEMPLATE_FORM", "english", "TEMPLATE FORM", "Y");
        module::set_lang("LBL_TEMPLATE_KEY", "english", "TEMPLATE KEY", "Y");
        module::set_lang("LBL_TEMPLATE_TEXT", "english", "TEMPLATE TEXT", "Y");

    }

    function init_menu() {
        // use this for updating menu system
        // under LIBRARIES
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }
        // _<modulename> in SQl refers to function _<modulename>() below
        // _barangay in SQL refers to function _barangay() below;
        module::set_menu($this->module, "Templates", "LIBRARIES", "_templates");

        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_stats() {
    }

    function init_help() {
    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // use MyISAM for fast access
        // this is an almost independent table
        module::execsql("CREATE TABLE `m_template` (".
              "`template_key` varchar(30) NOT NULL default '',".
              "`template_name` varchar(50) NOT NULL default '',".
              "`template_text` text NOT NULL,".
              "PRIMARY KEY  (`template_key`)".
              ") TYPE=MyISAM;");

    }

    function drop_tables() {
        $sql = "DROP TABLE `m_template`;";
        module::execsql($sql);
    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function display_templates() {
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
        print "<span class='library'>".FTITLE_TEMPLATE_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>".THEAD_TEMPLATE_KEY."</b></td><td><b>".THEAD_TEMPLATE_TEXT."</b></td></tr>";
        $sql = "select template_key, template_text from m_template order by template_key";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $text) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td><a href='".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id&template_key=$id'>$id</a></td><td>".$this->strfraction($text, 5)."</td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function get_template() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $template_id = $arg_list[0];
        }
        $sql = "select template_text from m_template where template_key = '$template_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($text) = mysql_fetch_array($result);
                return $text;
            }
        }
    }

    function show_history_templates() {
    //
    // drop down select with
    // auto_submit for templates
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $template_id = $arg_list[0];
        }
        $sql = "select template_key from m_template where template_key like 'HX_%' order by template_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='hx_template' class='tinylight' onchange='this.form.submit();'>";
                $ret_val .= "<option value=''>Select HX Template</option>";
                while (list($id) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($template_id==$id?"selected":"").">$id</option>";
                }
                $ret_val .= "</select><br/>";
                return $ret_val;
            }
        }
    }

    function show_pe_templates() {
    //
    // drop down select with
    // auto_submit for templates
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $template_id = $arg_list[0];
        }
        $sql = "select template_key from m_template where template_key like 'PE_%' order by template_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='pe_template' class='tinylight' onchange='this.form.submit();'>";
                $ret_val .= "<option value=''>Select PE Template</option>";
                while (list($id) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($template_id==$id?"selected":"").">$id</option>";
                }
                $ret_val .= "</select><br/>";
                return $ret_val;
            }
        }
    }

    function show_plan_templates() {
    //
    // drop down select with
    // auto_submit for templates
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $template_id = $arg_list[0];
        }
        $sql = "select template_key from m_template where template_key like 'PL_%' order by template_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $ret_val .= "<select name='plan_template' class='tinylight' onchange='this.form.submit();'>";
                $ret_val .= "<option value=''>Select Plan Template</option>";
                while (list($id) = mysql_fetch_array($result)) {
                    $ret_val .= "<option value='$id' ".($template_id==$id?"selected":"").">$id</option>";
                }
                $ret_val .= "</select><br/>";
                return $ret_val;
            }
        }
    }

    function process_template() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["submittemplate"]) {
            if ($post_vars["template_key"] && $post_vars["template_text"]) {
                switch($post_vars["submittemplate"]) {
                case "Add Template":
                    $sql = "insert into m_template (template_key, template_text) ".
                           "values ('".$post_vars["template_key"]."', '".$post_vars["template_text"]."')";
                    $result = mysql_query($sql);
                    break;
                case "Update Template":
                    $sql = "update m_template set ".
                           "template_text = '".$post_vars["template_text"]."' ".
                           "where template_key = '".$post_vars["template_key"]."'";
                    $result = mysql_query($sql);
                    break;
                case "Delete Template":
                    $sql = "delete from m_template where template_key = '".$post_vars["template_key"]."'";
                    $result = mysql_query($sql);
                    break;
                }
            }
        }
    }

    function form_template() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["template_key"]) {
                $sql = "select template_key, template_text ".
                       "from m_template where template_key = '".$get_vars["template_key"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $template = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id' name='form_template' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_TEMPLATE_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TEMPLATE_KEY."</span><br> ";
        if ($template["template_key"]) {
            $disable = "disabled";
            print "<input type='hidden' name='template_key' value='".$template["template_key"]."'>";
        } else {
            $disable = "";
        }
        print "<input type='text' class='textbox' size='15' $disable maxlength='30' name='template_key' value='".($template["template_key"]?$template["template_key"]:$post_vars["template_key"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_TEMPLATE_TEXT."</span><br> ";
        print "<textarea class='tinylight' rows='10' cols='50' name='template_text'style='border: 1px solid #000000'>".($template["template_text"]?$template["template_text"]:$post_vars["template_text"])."</textarea><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["template_key"]) {
            print "<input type='hidden' name='template_key' value='".$get_vars["template_key"]."'>";
            print "<input type='submit' value = 'Update Template' class='textbox' name='submittemplate' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Template' class='textbox' name='submittemplate' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Template' class='textbox' name='submittemplate' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function _templates() {
    //
    // main method for class template
    // calls form_template, process_template, display_templates
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            print_r($arg_list);
        }
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('template')) {
            return print($exitinfo);
        }

        if ($post_vars["submittemplate"]) {
            $this->process_template($menu_id, $post_vars, $get_vars);
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id");
        }
        $this->display_templates($menu_id);
        $this->form_template($menu_id, $post_vars, $get_vars);
    }

}
?>
