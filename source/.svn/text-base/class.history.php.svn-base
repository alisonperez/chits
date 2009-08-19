<?
class history extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function history() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.3-".date("Y-m-d");
        $this->module = "history";
        $this->description = "CHITS Module - History";
        // 0.1: BEGIN
        // 0.2: added question categories
        // 0.3: added question ordering
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "healthcenter");
        module::set_dep($this->module, "complaint");
    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("FTITLE_QUESTION_FORM", "english", "QUESTION FORM", "Y");
        module::set_lang("LBL_QUESTION", "english", "QUESTION TEXT", "Y");
        module::set_lang("LBL_ANSWER_TYPE", "english", "ANSWER TYPE", "Y");
        module::set_lang("FTITLE_QUESTION_LIST", "english", "QUESTION LIST", "Y");
        module::set_lang("THEAD_TEXT", "english", "QUESTION TEXT", "Y");
        module::set_lang("THEAD_TYPE", "english", "ANS TYPE", "Y");
        module::set_lang("THEAD_ALERT", "english", "ALERT FLAG", "Y");
        module::set_lang("FTITLE_QUESTION_CAT_LIST", "english", "QUESTION CATEGORY LIST", "Y");
        module::set_lang("FTITLE_QUESTION_CAT_FORM", "english", "QUESTION CATEGORY FORM", "Y");
        module::set_lang("LBL_CAT_ID", "english", "CATEGORY ID", "Y");
        module::set_lang("LBL_CAT_NAME", "english", "CATEGORY NAME", "Y");
        module::set_lang("LBL_ALERT_FLAG", "english", "ALERT FLAG", "Y");
        module::set_lang("LBL_ALERT_FLAG_TEXT", "english", "Check this to alert physician", "Y");
        module::set_lang("LBL_QUESTION_CAT", "english", "QUESTION CATEGORY", "Y");

    }

    function init_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $module_id = $arg_list[0];
        }
        module::set_menu($this->module, "History Questions", "LIBRARIES", "_questions");
        module::set_menu($this->module, "Question Category", "LIBRARIES", "_questioncat");
        module::set_menu($this->module, "Complaint Questions", "SUPPORT", "_complaint_questions");

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
            $module_id = $arg_list[0];
        }

        module::execsql("CREATE TABLE `m_lib_questions` (".
            "`question_id` float NOT NULL auto_increment,".
            "`question_rank` float NOT NULL default '0',".
            "`answer_type` varchar(5) NOT NULL default '',".
            "`alert_flag` char(1) NOT NULL default '',".
            "`question_cat` varchar(5) NOT NULL default '',".
            "`question_text` text NOT NULL,".
            "PRIMARY KEY  (`question_id`)".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_lib_questioncat` (".
            "`cat_id` varchar(5) NOT NULL default '',".
            "`cat_name` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`cat_id`)".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_complaint_question` (".
            "`complaint_id` varchar(10) NOT NULL default '',".
            "`question_id` float NOT NULL default '0',".
            "`question_frequency` float NOT NULL default '0',".
            "PRIMARY KEY  (`question_id`,`complaint_id`)".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_consult_complaint_question` (".
            "`consult_id` float NOT NULL default '0',".
            "`question_id` float NOT NULL default '0',".
            "`complaint_id` varchar(10) NOT NULL default '',".
            "`answer` varchar(10) NOT NULL default '',".
            "PRIMARY KEY  (`consult_id`,`question_id`,`complaint_id`)".
            ") TYPE=InnoDB; ");

        module::execsql("CREATE TABLE `m_complaint_question_diagnosis` (".
            "`complaint_id` varchar(10) NOT NULL default '',".
            "`question_id` float NOT NULL default '0',".
            "`answer` varchar(10) NOT NULL default '',".
            "`diagnosis_code` varchar(50) NOT NULL default '',".
            "PRIMARY KEY  (`complaint_id`,`question_id`,`answer`)".
            ") TYPE=InnoDB; ");

        module::load_sql("question.sql");

        module::execsql("UPDATE `m_lib_questions` SET question_rank = question_id;");
    }

    function drop_tables() {
        module::execsql("DROP TABLE `m_lib_questions`;");
        module::execsql("DROP TABLE `m_lib_questioncat`;");
        module::execsql("DROP TABLE `m_complaint_question`;");
        module::execsql("DROP TABLE `m_consult_complaint_question`;");
        module::execsql("DROP TABLE `m_complaint_question_diagnosis`;");

    }

    // --------------- CUSTOM MODULE FUNCTIONS ------------------


    function _questioncat () {
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
        if ($exitinfo = $this->missing_dependencies('patient')) {
            return print($exitinfo);
        }
        if ($post_vars["submitcat"]) {
            history::process_questioncat($menu_id,$post_vars,$get_vars);
        }
        history::form_questioncat($menu_id,$post_vars,$get_vars);
        history::display_questioncat($menu_id,$post_vars,$get_vars);
    }

    function form_questioncat() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["question_id"]) {
                $sql = "select cat_id, cat_name ".
                       "from m_lib_questioncat where cat_id = '".$get_vars["cat_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $cat = mysql_fetch_array($result);
                        //print_r($question);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_barangay' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_QUESTION_CAT_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CAT_ID."</span><br> ";
        print "<input type='text' class='textbox' size='10' maxlength='10' name='cat_id' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_CAT_NAME."</span><br> ";
        print "<input type='text' class='textbox' size='25' maxlength='50' name='cat_name' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["cat_id"]) {
            print "<input type='hidden' name='cat_id' value='".$get_vars["cat_id"]."'>";
            print "<input type='submit' value = 'Update Category' class='textbox' name='submitcat' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Category' class='textbox' name='submitcat' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'New Category' class='textbox' name='submitcat' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Category' class='textbox' name='submitcat' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_questioncat() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            print_r($arg_list);
        }
        switch($post_vars["submitcat"]) {
        case "New Category":
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
            break;
        case "Add Category":
            if ($post_vars["cat_id"] && $post_vars["cat_name"]) {
                $sql = "insert into m_lib_questioncat (cat_id, cat_name) ".
                       "values ('".strtoupper($post_vars["cat_id"])."', '".$post_vars["cat_name"]."')";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Update Category":
            if ($post_vars["cat_id"] && $post_vars["cat_name"]) {
                $sql = "update m_lib_questioncat set ".
                       "cat_name = '".$post_vars["cat_name"]."' ".
                       "where cat_id = '".$post_vars["cat_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Delete Category":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_lib_questioncat where cat_id = '".$post_vars["cat_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        }
    }

    function get_catname() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $cat_id = $arg_list[0];
        }
        $sql = "select cat_name from m_lib_questioncat where cat_id = '$cat_id'";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($name) = mysql_fetch_array($result);
                return $name;
            }
        }
    }

    function display_questioncat() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        print "<table width='400'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='library'>".FTITLE_QUESTION_CAT_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td><b>ID</b></td><td><b>".THEAD_NAME."</b></td></tr>";
        $sql = "select cat_id, cat_name from m_lib_questioncat order by cat_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<tr valign='top'><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&cat_id=$id'>$name</a></td></tr>";
                }
            }
        }
        print "</table><br>";
    }

    function _questions () {
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
        if ($exitinfo = $this->missing_dependencies('patient')) {
            return print($exitinfo);
        }
        if ($post_vars["submitquestion"]) {
            history::process_question($menu_id,$post_vars,$get_vars);
        }
        if ($get_vars["moveup"]||$get_vars["movedown"]) {
            print "hello";
            history::process_order($menu_id,$post_vars,$get_vars);
        }
        history::form_question($menu_id,$post_vars,$get_vars);
        history::display_questions($menu_id,$post_vars,$get_vars);
    }

    function form_question() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["question_id"]) {
                $sql = "select question_id, question_text, answer_type, question_cat, alert_flag ".
                       "from m_lib_questions where question_id = '".$get_vars["question_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $question = mysql_fetch_array($result);
                        //print_r($question);
                    }
                }
            }
        }
        print "<table width='400'>";
        print "<form action = '".$_SERVER["SELF"]."?page=LIBRARIES&menu_id=$menu_id' name='form_barangay' method='post'>";
        print "<tr valign='top'><td>";
        print "<span class='library'>".FTITLE_QUESTION_FORM."</span><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_QUESTION."</span><br> ";
        print "<textarea name='question_text' rows='5' cols='35' class='textbox' style='border: 1px solid black'>".stripslashes(($question["question_text"]?$question["question_text"]:$post_vars["question_text"]))."</textarea><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_ANSWER_TYPE."</span><br> ";
        print history::show_questiontypes($question["answer_type"]?$question["answer_type"]:$post_vars["type_id"]);
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_QUESTION_CAT."</span><br> ";
        print history::show_questioncat($question["question_cat"]?$question["question_cat"]:$post_vars["cat_id"]);
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_ALERT_FLAG."</span><br> ";
        print "<input type='checkbox' value='1' ".($question["alert_flag"]=="Y"?"checked":"")." name='alert_flag'/> ".LBL_ALERT_FLAG_TEXT."<br/>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["question_id"]) {
            print "<input type='hidden' name='question_id' value='".$get_vars["question_id"]."'>";
            print "<input type='submit' value = 'Update Question' class='textbox' name='submitquestion' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'Delete Question' class='textbox' name='submitquestion' style='border: 1px solid #000000'> ";
            print "<input type='submit' value = 'New Question' class='textbox' name='submitquestion' style='border: 1px solid #000000'> ";
        } else {
            print "<input type='submit' value = 'Add Question' class='textbox' name='submitquestion' style='border: 1px solid #000000'> ";
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";
    }

    function process_question() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            print_r($arg_list);
        }
        switch($post_vars["submitquestion"]) {
        case "New Category":
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
            break;
        case "Add Question":
            if ($post_vars["question_text"] && $post_vars["type_id"]) {
                $alert = ($post_vars["alert_flag"]?"Y":"N");
                $sql = "insert into m_lib_questions (question_text, answer_type, alert_flag, question_cat) ".
                       "values ('".addslashes($post_vars["question_text"])."', '".$post_vars["type_id"]."', '$alert', '".$post_vars["cat_id"]."')";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Update Question":
            if ($post_vars["question_text"] && $post_vars["type_id"]) {
                $alert = ($post_vars["alert_flag"]?"Y":"N");
                $sql = "update m_lib_questions set ".
                       "question_text = '".addslashes($post_vars["question_text"])."', ".
                       "answer_type = '".$post_vars["type_id"]."', ".
                       "question_cat = '".$post_vars["cat_id"]."', ".
                       "alert_flag = '$alert' ".
                       "where question_id = '".$post_vars["question_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        case "Delete Question":
            if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                $sql = "delete from m_lib_questions where question_id = '".$post_vars["question_id"]."'";
                if ($result = mysql_query($sql)) {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            } else {
                if ($post_vars["confirm_delete"]=="No") {
                    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
                }
            }
            break;
        }
    }

    function show_questiontypes() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $type_id = $arg_list[0];
        }
        print "<select name='type_id' class='textbox'>";
        print "<option value=''>Select Answer Type</option>";
        print "<option value='BOOL' ".($type_id=="BOOL"?"selected":"").">Yes/No</option>";
        print "<option value='NUM' ".($type_id=="NUM"?"selected":"").">Numeric</option>";
        print "<option value='TXT' ".($type_id=="TXT"?"selected":"").">Text</option>";
        print "</select>";
    }

    function show_questioncat() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $cat_id = $arg_list[0];
        }
        print "<select name='cat_id' class='textbox'>";
        print "<option value=''>Select Answer Type</option>";
        $sql = "select cat_id, cat_name from m_lib_questioncat order by cat_name";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $name) = mysql_fetch_array($result)) {
                    print "<option value='$id' ".($cat_id==$id?"selected":"").">$name</option>";
                }
            }
        }
        print "</select>";
    }

    function display_questions() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
        }
        print "<table width='500'>";
        print "<tr valign='top'><td colspan='3'>";
        print "<span class='library'>".FTITLE_QUESTION_LIST."</span><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>&nbsp;</td><td><b>ID</b></td><td><b>".THEAD_TEXT."</b></td><td><b>".THEAD_TYPE."</b></td><td><b>".THEAD_ALERT."</b></td></tr>";
        $sql = "select question_id, question_rank, question_text, answer_type, question_cat, alert_flag from m_lib_questions order by question_cat, question_rank";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while (list($id, $rank, $text, $type, $cat, $alert) = mysql_fetch_array($result)) {
                    if ($prevcat<>$cat) {
                        print "<tr bgcolor='#FFCC00'><td colspan='5'><b>".strtoupper(history::get_catname($cat))."</b></td></tr>";
                    }
                    print "<tr valign='top'><td>";
                    print "<a name='id$id'>";
                    print "<a href='".$_SERVER["PHPS_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&menu_item=$id&cat=$cat&moveup=$rank#id$id'><img src='../images/uparrow.gif' border='0'/></a>";
                    print "<a href='".$_SERVER["PHPS_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&menu_item=$id&cat=$cat&movedown=$rank#id$id'><img src='../images/downarrow.gif' border='0'/></a>";
                    print "</td>";
                    print "</td><td>$id</td><td><a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=$menu_id&question_id=$id'>".stripslashes($text)."</a></td><td>$type</td><td>$alert</td></tr>";
                    $prevcat = $cat;
                }
            }
        }
        print "</table><br>";
    }

    function process_order() {
    //
    // reorder question
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            print_r($arg_list[1]);
        }
        if ($get_vars["movedown"]) {
            print $sql = "select question_id, question_rank from m_lib_questions ".
                   "where question_cat = '".$get_vars["cat"]."' ".
                   "order by question_cat, question_rank";
            if ($result = mysql_query($sql)) {
                if ($count = mysql_num_rows($result)) {
                    $i = 1;
                    while (list($id, $rank) = mysql_fetch_array($result)) {
                        $pos[$i] = $rank;
                        $question[$i] = $id;
                        $i++;
                    }
                    for($i=1; $i<=$count; $i++) {
                        if ($get_vars["movedown"]==$pos[$i] && $i<$count) {
                            print $sql1 = "update m_lib_questions set question_rank = '".$pos[$i+1]."' where question_id = '".$question[$i]."'";
                            $result1 = mysql_query($sql1);
                            print $sql2 = "update m_lib_questions set question_rank = '".$pos[$i]."' where question_id = '".$question[$i+1]."'";
                            $result2 = mysql_query($sql2);
                            break;
                        }
                    }
                }
            }
        }
        if ($get_vars["moveup"]) {
            $sql = "select question_id, question_rank from m_lib_questions ".
                   "where question_cat = '".$get_vars["cat"]."' ".
                   "order by question_cat, question_rank desc";
            if ($result = mysql_query($sql)) {
                if ($count = mysql_num_rows($result)) {
                    $i = 1;
                    while (list($id, $rank) = mysql_fetch_array($result)) {
                        $pos[$i] = $rank;
                        $question[$i] = $id;
                        $i++;
                    }
                    for($i=1; $i<=$count; $i++) {
                        if ($get_vars["moveup"]==$pos[$i] && $i<$count) {
                            $sql1 = "update m_lib_questions set question_rank = '".$pos[$i+1]."' where question_id = '".$question[$i]."'";
                            $result1 = mysql_query($sql1);
                            $sql2 = "update m_lib_questions set question_rank = '".$pos[$i]."' where question_id = '".$question[$i+1]."'";
                            $result2 = mysql_query($sql2);
                            break;
                        }
                    }
                }
            }
        }
        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]);
    }

// end of class
}
?>
