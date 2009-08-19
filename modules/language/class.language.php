<?
class language extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004
    // for module guidelines see MODULES.TXT

    function language() {
    //
    // constructor
    // do not forget to update version
    //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.4-".date("Y-m-d");
        $this->module = "language";
        $this->description = "BBIS Module - Language";
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

    }

    function init_menu() {
        // use this for updating menu system
        // under LIBRARIES
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }
        // _<modulename> in SQl refers to function _<modulename>() below
        // _barangay in SQL refers to function _barangay() below;
        module::set_menu($this->module, "Language", "LIBRARIES", "_language");

        // put in more details
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // none for this as term is an internal table

    }

    function drop_tables() {

        // none for this as term is an internal table
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function display_language() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='600' cellpadding='2' cellspacing='0'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='library'>LANGUAGE TERMS LIST</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>LANGUAGE</b></td><td><b>TEXT</b></td></tr>";
        $sql = "select termid, languageid, langtext from terms order by termid";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $lang, $text) = mysql_fetch_array($result)) {
                    if ($prev_id<>$id) {
                        if ($bgcolor=="#FFFFCC") {
                            $bgcolor = "#CCCC99";
                        } else {
                            $bgcolor = "#FFFFCC";
                        }
                    }
                    print "<tr valign='top' bgcolor='$bgcolor'><td><a href='".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id&term_id=$id'><b>$id</b></a></td><td>$lang</td><td>".strip_tags($this->strfraction($text,4))."</td></tr>";
                    $prev_id = $id;
                }
            }
        }
        print "</table><br>";
    }

    function process_language() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($post_vars);
        }
        if ($post_vars["submitterm"]) {
            if (strlen($post_vars["termid"])>0) {
                $termid = $post_vars["termid"];
            } else {
                $termid = $post_vars["new_termid"];
            }
            if (strlen($post_vars["languageid"])>0) {
                $languageid = $post_vars["languageid"];
            } else {
                $languageid = $post_vars["new_languageid"];
            }
            switch($post_vars["submitterm"]) {
            case "Add Term":
                if ($termid && $languageid && $post_vars["language_text"]) {
                    $sql = "insert into terms (termid, languageid, langtext) ".
                           "values ('$termid', '$languageid', '".strip_tags($post_vars["language_text"],"<b><u><i><br>")."')";
                    $result = mysql_query($sql);
                }
                break;
            case "Update Term":
                if ($languageid && $post_vars["language_text"]) {
                    $sql = "update terms set ".
                           "languageid = '$languageid', ".
                           "langtext = '".strip_tags($post_vars["language_text"],"<b><u><i><br>")."' ".
                           "where termid = '".$post_vars["term_id"]."'";
                    $result = mysql_query($sql);
                }
                break;
            case "Delete Term":
                $sql = "delete from terms where termid = '".$post_vars["term_id"]."'";
                $result = mysql_query($sql);
                break;
            }
        }
    }

    function form_language() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["term_id"]) {
                $sql = "select termid, languageid, langtext ".
                       "from terms where termid = '".$get_vars["term_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $terms = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_barangay' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>LANGUAGE TERMS FORM</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<b>NOTE ON PREFIXES:</b><br>";
        print "<b>FTITLE_</b> - form titles<br>";
        print "<b>INFO_</b> - generic HTML text<br>";
        print "<b>THEAD_</b> - table headings<br>";
        print "<b>BTN_</b> - button labels<br>";
        print "<b>LBL_</b> - form field labels<br>";
        print "<b>MENU_</b> - menu titles<br>";
        print "<br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<table width='100%' cellpadding='1'><tr valign='top'><td>";
        print "<span class='boxtitle'>SELECT TERM TO TRANSLATE</span><br> ";
        print $this->show_terms(($terms["termid"]?$terms["termid"]:$post_vars["termid"]));
        print "<br><span class='boxtitle'>...OR CREATE A NEW ONE</span><br> ";
        print "<input type='text' class='textbox' size='25' maxlength='50' name='new_termid' value='".($barangay["barangay_id"]?$barangay["barangay_id"]:$post_vars["barangay_id"])."' style='border: 1px solid #000000'><br><br>";
        print "</td><td>";
        print "<span class='boxtitle'>SELECT LANGUAGE</span><br> ";
        print $this->show_languages(($terms["languageid"]?$terms["languageid"]:$post_vars["languageid"]));
        print "<br><span class='boxtitle'>...OR TYPE IN A NEW ONE</span><br> ";
        print "<input type='text' class='textbox' size='15' maxlength='15' name='new_languageid' value='".($barangay["area_code"]?$barangay["area_code"]:$post_vars["barangay_areacode"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "</table>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>LANGUAGE TEXT</span><br> ";
        print "<textarea class='textbox' rows='10' cols='40' name='language_text'style='border: 1px solid #000000'>".($terms["langtext"]?$terms["langtext"]:$post_vars["language_text"])."</textarea><br>";
        print "<small>You can use &lt;b&gt;, &lt;u&gt;, &lt;i&gt;, &lt;br&gt; tags.</small>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["term_id"]) {
            print "<input type='hidden' name='term_id' value='".$get_vars["term_id"]."'>";
            print "<input type='submit' value = 'Add Term' class='textbox' name='submitterm' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Update Term' class='textbox' name='submitterm' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Term' class='textbox' name='submitterm' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Term' class='textbox' name='submitterm' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function show_terms() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $termid = $arg_list[0];
        }
        $sql = "select distinct termid from terms order by termid";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $retval .= "<select size='9' name='termid' class='textbox'>";
                while (list($id) = mysql_fetch_array($result)) {
                    $retval .="<option value='$id' ".($termid==$id?"selected":"").">$id</option>";
                }
                $retval .= "</select><br>";
                return $retval;
            }
        }
    }

    function show_languages() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $languageid = $arg_list[0];
        }
        $sql = "select distinct languageid from terms order by languageid";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                $retval .= "<select size='5' name='languageid' class='textbox'>";
                while (list($id) = mysql_fetch_array($result)) {
                    $retval .="<option value='$id' ".($languageid==$id?"selected":"").">$id</option>";
                }
                $retval .= "</select><br>";
                return $retval;
            }
        }
    }

    function _language() {
        //
        // main method for barangay
        // calls form_barangay, process_barangay, display_barangay
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
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('language')) {
            return print($exitinfo);
        }

        if ($post_vars["submitterm"]) {
            $this->process_language($menu_id, $post_vars, $get_vars);
            //header("location: ".$_SERVER["PHP_SELF"]."?page=LIBRARIES&menu_id=$menu_id");
        }
        $this->form_language($menu_id, $post_vars, $get_vars);
        $this->display_language($menu_id);
    }

}
?>
