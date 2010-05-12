<?
class graph extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function graph() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.4-".date("Y-m-d");
        $this->module = "graph";
        $this->description = "CHITS Module - Graph Abstraction";
        // 0.3 added bar graph
        // 0.4 added line graph

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
    //
        module::set_lang("FTITLE_PIEGRAPH_FORM", "english", "PIE GRAPH FORM", "Y");
        module::set_lang("FTITLE_BARGRAPH_FORM", "english", "BAR GRAPH FORM", "Y");
        module::set_lang("LBL_GRAPH_TITLE", "english", "GRAPH TITLE", "Y");
        module::set_lang("LBL_GRAPH_MODULE", "english", "GRAPH MODULE", "Y");
        module::set_lang("LBL_GRAPH_HEIGHT", "english", "GRAPH HEIGHT", "Y");
        module::set_lang("LBL_GRAPH_WIDTH", "english", "GRAPH WIDTH", "Y");
        module::set_lang("LBL_GRAPH_SQL", "english", "GRAPH SQL STATEMENT", "Y");
        module::set_lang("LBL_GRAPH_BARCOLOR", "english", "BAR COLOR", "Y");
        module::set_lang("LBL_GRAPH_XLABEL", "english", "X-AXIS (HORIZONTAL) LABEL", "Y");
        module::set_lang("LBL_GRAPH_YLABEL", "english", "Y-AXIS (VERTICAL) LABEL", "Y");
        module::set_lang("LBL_GRAPH_FLAGS", "english", "GRAPH FLAGS", "Y");
        module::set_lang("FTITLE_PIEGRAPH_LIST", "english", "PIE GRAPHS", "Y");
        module::set_lang("FTITLE_BARGRAPH_LIST", "english", "BAR GRAPHS", "Y");
        module::set_lang("FTITLE_LINEGRAPH_LIST", "english", "LINE GRAPHS", "Y");
        module::set_lang("INSTR_PIEGRAPH_LIST", "english", "CLICK ON GRAPH TITLE TO EDIT GRAPH DEFINITION.", "Y");
        module::set_lang("INSTR_BARGRAPH_LIST", "english", "CLICK ON GRAPH TITLE TO EDIT GRAPH DEFINITION.", "Y");
        module::set_lang("LBL_GRAPH_ORIENTATION", "english", "GRAPH ORIENTATION", "Y");
        module::set_lang("FTITLE_LINEGRAPH_FORM", "english", "LINE GRAPH FORM", "Y");
        module::set_lang("INSTR_LINEGRAPH_LIST", "english", "CLICK ON GRAPH TITLE TO EDIT GRAPH DEFINITION.", "Y");
        module::set_lang("LBL_GRAPH_TYPE", "english", "LINE GRAPH TYPE", "Y");
        module::set_lang("LBL_GRAPH_FILLCOLOR", "english", "LINE FILL COLOR", "Y");
        module::set_lang("LBL_GRAPH_Y1LABEL", "english", "Y-AXIS 1 LABEL", "Y");
        module::set_lang("LBL_GRAPH_Y2LABEL", "english", "Y-AXIS 2 LABEL", "Y");
        module::set_lang("INSTR_Y2_LABEL", "english", "Leave this blank for simple line (one-plot) graphs.", "Y");

    }

    function init_stats() {
    }

    function init_help() {
    }

    function init_menu() {
        // use this for updating menu system
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // menu entries

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // graph flags:
        // R = date range
        // Y = year
        // M = month
        // W = week
        // D = day
        // empty = all records
        module::execsql("CREATE TABLE `m_lib_graph_piegraph` (".
            "`graph_id` int(11) NOT NULL auto_increment,".
            "`module_id` varchar(25) NOT NULL default '',".
            "`graph_sql` tinytext NOT NULL,".
            "`graph_title` varchar(50) NOT NULL default '',".
            "`graph_flag` char(1) NOT NULL default 'N',".
            "`graph_height` int(11) NOT NULL default '0',".
            "`graph_width` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`graph_id`),".
            "KEY `key_module` (`module_id`)".
            ") TYPE=MyISAM; ");

        module::execsql("CREATE TABLE `m_lib_graph_bargraph` (".
            "`graph_id` int(11) NOT NULL auto_increment,".
            "`module_id` varchar(25) NOT NULL default '',".
            "`graph_sql` tinytext NOT NULL,".
            "`graph_title` varchar(50) NOT NULL default '',".
            "`graph_orientation` char(1) NOT NULL default 'V',".
            "`graph_xlabel` varchar(25) NOT NULL default '',".
            "`graph_ylabel` varchar(25) NOT NULL default '',".
            "`graph_barcolor` varchar(10) NOT NULL default '',".
            "`graph_flag` char(1) NOT NULL default 'N',".
            "`graph_height` int(11) NOT NULL default '0',".
            "`graph_width` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`graph_id`),".
            "KEY `key_module` (`module_id`)".
            ") TYPE=MyISAM; ");

        module::execsql("CREATE TABLE `m_lib_graph_linegraph` (".
            "`graph_id` int(11) NOT NULL auto_increment,".
            "`module_id` varchar(25) NOT NULL default '',".
            "`graph_sql` tinytext NOT NULL,".
            "`graph_title` varchar(50) NOT NULL default '',".
            "`graph_type` varchar(10) NOT NULL default 'S',".
            "`graph_xlabel` varchar(25) NOT NULL default '',".
            "`graph_y1label` varchar(25) NOT NULL default '',".
            "`graph_y2label` varchar(25) NOT NULL default '',".
            "`graph_barcolor` varchar(10) NOT NULL default '',".
            "`graph_flag` char(1) NOT NULL default 'N',".
            "`graph_height` int(11) NOT NULL default '0',".
            "`graph_width` int(11) NOT NULL default '0',".
            "PRIMARY KEY  (`graph_id`),".
            "KEY `key_module` (`module_id`)".
            ") TYPE=MyISAM; ");

        // load initial data
        // none to load

    }

    function drop_tables() {

        module::execsql("DROP TABLE `m_lib_graph_piegraph`;");
        module::execsql("DROP TABLE `m_lib_graph_bargraph`;");
        module::execsql("DROP TABLE `m_lib_graph_linegraph`;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function graph_menu() {
       if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        //if (!$get_vars["graph_menu"]) {
        //    header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=PIE");
        //}
        print "<table cellpadding='1' cellspacing='1' width='300' bgcolor='#9999FF' style='border: 1px solid black'><tr valign='top'><td nowrap>";
        print "<span class='groupmenu'><font color='#666699'><b>GRAPH TYPE</b></font></span> ";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=".$get_vars["report_menu"]."&graph=PIE&module=".$get_vars["module"]."' class='groupmenu'>".strtoupper(($get_vars["graph"]=="PIE"?"<b>PIE</b>":"PIE"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=".$get_vars["report_menu"]."&graph=BAR&module=".$get_vars["module"]."' class='groupmenu'>".strtoupper(($get_vars["graph"]=="BAR"?"<b>BAR</b>":"BAR"))."</a>";
        print "<a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=".$get_vars["report_menu"]."&graph=LINE&module=".$get_vars["module"]."' class='groupmenu'>".strtoupper(($get_vars["graph"]=="LINE"?"<b>LINE</b>":"LINE"))."</a>";
        print "</td></tr></table>";
    }

    // ---------------------------- SIMPLE PIE ---------------------------------

    function graph_pie() {
    //
    // main submodule for pie graph definitions
    //
    // calls form_graph_pie()
    //       display_graph_pie()
    //       process_graph_pie()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('graph')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $g = new graph;
        print "<table width='600'><tr valign='top'><td>";
        // column 1
        if ($post_vars["submitgraph"]) {
            $g->process_piegraph($menu_id, $post_vars, $get_vars);
        }
        if ($get_vars["view_id"]) {
            $g->draw_piegraph($menu_id, $post_vars, $get_vars);
        } else {
            $g->form_piegraph($menu_id, $post_vars, $get_vars);
        }
        print "</td><td>";
        // column 2
        $g->display_piegraph($menu_id, $post_vars, $get_vars);
        print "</td></tr></table>";
    }

    function draw_piegraph() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($get_vars["view_id"]) {
            // find out what flag is on
            $sql = "select graph_flag ".
                   "from m_lib_graph_piegraph where graph_id = '".$get_vars["view_id"]."'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    list($graph_flag) = mysql_fetch_array($result);
                    switch ($graph_flag) {
                    // date range, ask for start and end dates
                    case "R":
                        break;
                    // ask for year
                    case "Y":
                        break;
                    // ask for month
                    case "M":
                        break;
                    // ask for week
                    case "W":
                        break;
                    // ask for day
                    case "D":
                        break;
                    }
                }
            }
            if (file_exists("../graphs/pie_simple.php")) {
                print "<br/>";
                print "<img src='../graphs/pie_simple.php?graph_id=".$get_vars["view_id"]."' border='0' />";
            } else {
                print "<font color='red'>File not found</font>";
            }
        }
    }


    function form_piegraph() {
    //
    // called by graph_pie()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["graph_id"]) {
                $sql = "select graph_title, graph_width, graph_height, graph_sql, ".
                       "graph_flag, module_id ".
                       "from m_lib_graph_piegraph where graph_id = '".$get_vars["graph_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $graph = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=REPORTS&menu_id=".$get_vars["menu_id"]."&report_menu=".$get_vars["report_menu"]."&graph=PIE&module=".$get_vars["module"]."' name='form_education' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_PIEGRAPH_FORM."</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_TITLE."</span><br> ";
        print "<input type='text' class='textbox' size='20' maxlength='50' name='graph_title' value='".($graph["graph_title"]?$graph["graph_title"]:$post_vars["graph_title"])."' style='border: 1px solid #000000'><br>";
        print "<input type='hidden' name='module' value='".$get_vars["module"]."'/>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_GRAPH_WIDTH."</span><br> ";
        print "<select name='graph_width' class='textbox'>";
        print "<option value='0'>Select Width</option>";
        print "<option value='300' ".($graph["graph_width"]=='300'?"selected":"").">300</option>";
        print "<option value='350' ".($graph["graph_width"]=='350'?"selected":"").">350</option>";
        print "<option value='400' ".($graph["graph_width"]=='400'?"selected":"").">400</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_HEIGHT."</span><br> ";
        print "<select name='graph_height' class='textbox'>";
        print "<option value='0'>Select Height</option>";
        print "<option value='300' ".($graph["graph_height"]=='300'?"selected":"").">300</option>";
        print "<option value='350' ".($graph["graph_height"]=='350'?"selected":"").">350</option>";
        print "<option value='400' ".($graph["graph_height"]=='400'?"selected":"").">400</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_GRAPH_FLAGS."</span><br> ";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="R"?"checked":"")." value='R' > Set date range<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="Y"?"checked":"")." value='Y' > Set year<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="M"?"checked":"")." value='M' > Set month<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="W"?"checked":"")." value='W' > Set week<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="D"?"checked":"")." value='D' > Set day<br>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_GRAPH_SQL."</span><br> ";
        print "<textarea name='graph_sql' cols='45' rows='7' class='tinylight' style='border: 1px solid black'>".$graph["graph_sql"]."</textarea>";
        print "<small>CHITS graphs are generated from SQL statements. You have to put in a <b>valid</b> SQL ".
              "statement here! Valid variables for graph flags: __START_DATE__, __END_DATE__, ".
              "__WEEK__ and __MONTH__.</small><br/>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["graph_id"]) {
            print "<input type='hidden' name='graph_id' value='".$get_vars["graph_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Graph' class='textbox' name='submitgraph' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Graph' class='textbox' name='submitgraph' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Graph' class='textbox' name='submitgraph' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_piegraph() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            print_r($arg_list);
        }
        if ($post_vars["submitgraph"]) {
            if ($post_vars["graph_title"] && $post_vars["graph_width"] && $post_vars["graph_height"] && $post_vars["graph_sql"]) {
                switch($post_vars["submitgraph"]) {
                case "Add Graph":
                    print $sql = "insert into m_lib_graph_piegraph (graph_title, graph_width, graph_height, ".
                           "graph_sql, graph_flag, module_id) ".
                           "values ('".$post_vars["graph_title"]."', '".$post_vars["graph_width"]."', ".
                           "'".$post_vars["graph_height"]."', '".$post_vars["graph_sql"]."', ".
                           "'".$post_vars["graph_flag"]."', '".$post_vars["module"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=PIE");
                    }
                    break;
                case "Update Graph":
                    $sql = "update m_lib_graph_piegraph set ".
                           "graph_title = '".$post_vars["graph_title"]."', ".
                           "graph_height = '".$post_vars["graph_height"]."', ".
                           "graph_width = '".$post_vars["graph_width"]."', ".
                           "graph_flag = '".$post_vars["graph_flag"]."', ".
                           "graph_sql = '".$post_vars["graph_sql"]."' ".
                           "where graph_id = '".$post_vars["graph_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=PIE");
                    }
                    break;
                case "Delete Graph":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_graph_piegraph where graph_id = '".$post_vars["graph_id"]."'";
                        if ($result = mysql_query($sql)) {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=PIE");
                        }
                    } else {
                        if ($post_vars["confirm_delete"]=="No") {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=PIE");
                        }
                    }
                    break;
                }
            }
        }
    }

    function display_piegraph() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='300'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_PIEGRAPH_LIST."</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='tinylight'>".INSTR_PIEGRAPH_LIST."</span><br>";
        print "</td></tr>";
        $sql = "select graph_id, graph_title ".
               "from m_lib_graph_piegraph ".
               "where module_id = '".$get_vars["module"]."' ".
               "order by graph_title";
        if ($result = mysql_query($sql)) {
            print "<tr valign='top'><td>";
            if (mysql_num_rows($result)) {
                while (list($id, $title) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=PIE&module=notifiable_report&graph_id=$id'>$title</a> ";
                    print "<a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=PIE&module=notifiable_report&view_id=$id'><img src='../images/view.png' border='0'/></a><br/>";
                }
            } else {
                print "<font color='red'>No pie graphs on record</font><br/>";
            }
            print "</td></tr>";
        }
        print "</table><br>";
    }

    // -------------------------- SIMPLE BAR VERTICAL AND HORIZONTAL ---------

    function graph_bar() {
    //
    // main submodule for bar graph definitions
    //
    // calls form_graph_bar()
    //       display_graph_bar()
    //       process_graph_bar()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('graph')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $g = new graph;
        print "<table width='600'><tr valign='top'><td>";
        // column 1
        if ($post_vars["submitgraph"]) {
            $g->process_bargraph($menu_id, $post_vars, $get_vars);
        }
        if ($get_vars["view_id"]) {
            $g->draw_bargraph($menu_id, $post_vars, $get_vars);
        } else {
            $g->form_bargraph($menu_id, $post_vars, $get_vars);
        }
        print "</td><td>";
        // column 2
        $g->display_bargraph($menu_id, $post_vars, $get_vars);
        print "</td></tr></table>";
    }

    function form_bargraph() {
    //
    // called by graph_pie()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["graph_id"]) {
                $sql = "select graph_title, graph_width, graph_height, graph_sql, ".
                       "graph_xlabel, graph_ylabel, graph_barcolor, graph_orientation, ".
                       "graph_flag, module_id ".
                       "from m_lib_graph_bargraph where graph_id = '".$get_vars["graph_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $graph = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=REPORTS&menu_id=".$get_vars["menu_id"]."&report_menu=".$get_vars["report_menu"]."&graph=BAR&module=".$get_vars["module"]."' name='form_bargraph' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_BARGRAPH_FORM."</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_TITLE."</span><br> ";
        print "<input type='text' class='textbox' size='20' maxlength='30' name='graph_title' value='".($graph["graph_title"]?$graph["graph_title"]:$post_vars["graph_title"])."' style='border: 1px solid #000000'><br>";
        print "<input type='hidden' name='module' value='".$get_vars["module"]."'/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_XLABEL."</span><br> ";
        print "<input type='text' class='textbox' size='20' maxlength='30' name='graph_xlabel' value='".($graph["graph_xlabel"]?$graph["graph_xlabel"]:$post_vars["graph_xlabel"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_YLABEL."</span><br> ";
        print "<input type='text' class='textbox' size='20' maxlength='30' name='graph_ylabel' value='".($graph["graph_ylabel"]?$graph["graph_ylabel"]:$post_vars["graph_ylabel"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_BARCOLOR."</span><br> ";
        print "<select name='graph_barcolor' class='textbox'>";
        print "<option value='0'>Select Bar Color</option>";
        print "<option value='red' ".($graph["graph_barcolor"]=='red'?"selected":"").">RED</option>";
        print "<option value='orange' ".($graph["graph_barcolor"]=='orange'?"selected":"").">ORANGE</option>";
        print "<option value='blue' ".($graph["graph_barcolor"]=='blue'?"selected":"").">BLUE</option>";
        print "<option value='yellow' ".($graph["graph_barcolor"]=='yellow'?"selected":"").">YELLOW</option>";
        print "<option value='green' ".($graph["graph_barcolor"]=='green'?"selected":"").">GREEN</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_GRAPH_ORIENTATION."</span><br> ";
        print "<select name='graph_orientation' class='textbox'>";
        print "<option value=''>Select Orientation</option>";
        print "<option value='H' ".($graph["graph_orientation"]=='H'?"selected":"").">Horizontal Bar</option>";
        print "<option value='V' ".($graph["graph_orientation"]=='V'?"selected":"").">Vertical Bar</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_GRAPH_WIDTH."</span><br> ";
        print "<select name='graph_width' class='textbox'>";
        print "<option value='0'>Select Width</option>";
        print "<option value='300' ".($graph["graph_width"]=='300'?"selected":"").">300</option>";
        print "<option value='350' ".($graph["graph_width"]=='350'?"selected":"").">350</option>";
        print "<option value='400' ".($graph["graph_width"]=='400'?"selected":"").">400</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_HEIGHT."</span><br> ";
        print "<select name='graph_height' class='textbox'>";
        print "<option value='0'>Select Height</option>";
        print "<option value='300' ".($graph["graph_height"]=='300'?"selected":"").">300</option>";
        print "<option value='350' ".($graph["graph_height"]=='350'?"selected":"").">350</option>";
        print "<option value='400' ".($graph["graph_height"]=='400'?"selected":"").">400</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_GRAPH_FLAGS."</span><br> ";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="R"?"checked":"")." value='R' > Set date range<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="Y"?"checked":"")." value='Y' > Set year<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="M"?"checked":"")." value='M' > Set month<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="W"?"checked":"")." value='W' > Set week<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="D"?"checked":"")." value='D' > Set day<br>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_GRAPH_SQL."</span><br> ";
        print "<textarea name='graph_sql' cols='45' rows='7' class='tinylight' style='border: 1px solid black'>".$graph["graph_sql"]."</textarea>";
        print "<small>CHITS graphs are generated from SQL statements. You have to put in a <b>valid</b> SQL ".
              "statement here! Valid variables for graph flags: __START_DATE__, __END_DATE__, ".
              "__WEEK__ and __MONTH__.</small><br/>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["graph_id"]) {
            print "<input type='hidden' name='graph_id' value='".$get_vars["graph_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Graph' class='textbox' name='submitgraph' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Graph' class='textbox' name='submitgraph' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Graph' class='textbox' name='submitgraph' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_bargraph() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            print_r($arg_list);
        }
        if ($post_vars["submitgraph"]) {
            if ($post_vars["graph_title"] && $post_vars["graph_width"] && $post_vars["graph_height"] && $post_vars["graph_sql"]) {
                switch($post_vars["submitgraph"]) {
                case "Add Graph":
                    print $sql = "insert into m_lib_graph_bargraph (graph_title, graph_width, graph_height, graph_orientation, ".
                           "graph_xlabel, graph_ylabel, graph_barcolor, graph_sql, graph_flag, module_id) ".
                           "values ('".$post_vars["graph_title"]."', '".$post_vars["graph_width"]."', ".
                           "'".$post_vars["graph_height"]."', '".$post_vars["graph_orientation"]."', '".$post_vars["graph_xlabel"]."', ".
                           "'".$post_vars["graph_ylabel"]."', '".$post_vars["graph_barcolor"]."', '".$post_vars["graph_sql"]."', ".
                           "'".$post_vars["graph_flag"]."', '".$post_vars["module"]."')";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=BAR");
                    }
                    break;
                case "Update Graph":
                    $sql = "update m_lib_graph_bargraph set ".
                           "graph_title = '".$post_vars["graph_title"]."', ".
                           "graph_orientation = '".$post_vars["graph_orientation"]."', ".
                           "graph_xlabel = '".$post_vars["graph_xlabel"]."', ".
                           "graph_ylabel = '".$post_vars["graph_ylabel"]."', ".
                           "graph_barcolor = '".$post_vars["graph_barcolor"]."', ".
                           "graph_height = '".$post_vars["graph_height"]."', ".
                           "graph_width = '".$post_vars["graph_width"]."', ".
                           "graph_flag = '".$post_vars["graph_flag"]."', ".
                           "graph_sql = '".$post_vars["graph_sql"]."' ".
                           "where graph_id = '".$post_vars["graph_id"]."'";
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=BAR");
                    }
                    break;
                case "Delete Graph":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_graph_bargraph where graph_id = '".$post_vars["graph_id"]."'";
                        if ($result = mysql_query($sql)) {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=BAR");
                        }
                    } else {
                        if ($post_vars["confirm_delete"]=="No") {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=BAR");
                        }
                    }
                    break;
                }
            }
        }
    }

    function draw_bargraph() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($get_vars["view_id"]) {
            // find out what flag is on
            $sql = "select graph_flag, graph_orientation ".
                   "from m_lib_graph_bargraph where graph_id = '".$get_vars["view_id"]."'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    list($graph_flag, $graph_orientation) = mysql_fetch_array($result);
                    switch ($graph_flag) {
                    // date range, ask for start and end dates
                    case "R":
                        break;
                    // ask for year
                    case "Y":
                        break;
                    // ask for month
                    case "M":
                        break;
                    // ask for week
                    case "W":
                        break;
                    // ask for day
                    case "D":
                        break;
                    }
                }
            }
            if ($graph_orientation=="V") {
                if (file_exists("../graphs/bar_simple_vertical.php")) {
                    print "<br/>";
                    print "<img src='../graphs/bar_simple_vertical.php?graph_id=".$get_vars["view_id"]."' border='0' />";
                } else {
                    print "<font color='red'>File not found</font>";
                }
            } elseif ($graph_orientation=="H") {
                if (file_exists("../graphs/bar_simple_horizontal.php")) {
                    print "<br/>";
                    print "<img src='../graphs/bar_simple_horizontal.php?graph_id=".$get_vars["view_id"]."' border='0' />";
                } else {
                    print "<font color='red'>File not found</font>";
                }
            }
        }
    }

    function display_bargraph() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='300'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_BARGRAPH_LIST."</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='tinylight'>".INSTR_BARGRAPH_LIST."</span><br>";
        print "</td></tr>";
        $sql = "select graph_id, graph_title ".
               "from m_lib_graph_bargraph ".
               "where module_id = '".$get_vars["module"]."' ".
               "order by graph_title";
        if ($result = mysql_query($sql)) {
            print "<tr valign='top'><td>";
            if (mysql_num_rows($result)) {
                while (list($id, $title) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=BAR&module=consult_report&graph_id=$id'>$title</a> ";
                    print "<a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=BAR&module=consult_report&view_id=$id'><img src='../images/view.png' border='0'/></a><br/>";
                }
            } else {
                print "<font color='red'>No bar graphs on record</font><br/>";
            }
            print "</td></tr>";
        }
        print "</table><br>";
    }

    // ----------------------- LINE GRAPH, SIMPLE AND FILLED 2 PLOTS ----------------------

    function graph_line() {
    //
    // main submodule for line graph definitions
    //
    // calls form_graph_line()
    //       display_graph_line()
    //       process_graph_line()
    //
        // always check dependencies
        if ($exitinfo = $this->missing_dependencies('graph')) {
            return print($exitinfo);
        }
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $g = new graph;
        print "<table width='600'><tr valign='top'><td>";
        // column 1
        if ($post_vars["submitgraph"]) {
            $g->process_linegraph($menu_id, $post_vars, $get_vars);
        }
        if ($get_vars["view_id"]) {
            $g->draw_linegraph($menu_id, $post_vars, $get_vars);
        } else {
            $g->form_linegraph($menu_id, $post_vars, $get_vars);
        }
        print "</td><td>";
        // column 2
        $g->display_linegraph($menu_id, $post_vars, $get_vars);
        print "</td></tr></table>";
    }

    function form_linegraph() {
    //
    // called by graph_line()
    //
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            if ($get_vars["graph_id"]) {
                $sql = "select graph_title, graph_width, graph_height, graph_sql, ".
                       "graph_xlabel, graph_y1label, graph_y2label, graph_barcolor, graph_type, ".
                       "graph_flag, module_id ".
                       "from m_lib_graph_linegraph where graph_id = '".$get_vars["graph_id"]."'";
                if ($result = mysql_query($sql)) {
                    if (mysql_num_rows($result)) {
                        $graph = mysql_fetch_array($result);
                    }
                }
            }
        }
        print "<table width='300'>";
        print "<form action = '".$_SERVER["SELF"]."?page=REPORTS&menu_id=".$get_vars["menu_id"]."&report_menu=".$get_vars["report_menu"]."&graph=LINE&module=".$get_vars["module"]."' name='form_linegraph' method='post'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_LINEGRAPH_FORM."</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_TITLE."</span><br> ";
        print "<input type='text' class='textbox' size='20' maxlength='30' name='graph_title' value='".($graph["graph_title"]?$graph["graph_title"]:$post_vars["graph_title"])."' style='border: 1px solid #000000'><br>";
        print "<input type='hidden' name='module' value='".$get_vars["module"]."'/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_XLABEL."</span><br> ";
        print "<input type='text' class='textbox' size='20' maxlength='30' name='graph_xlabel' value='".($graph["graph_xlabel"]?$graph["graph_xlabel"]:$post_vars["graph_xlabel"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_Y1LABEL."</span><br> ";
        print "<input type='text' class='textbox' size='20' maxlength='30' name='graph_y1label' value='".($graph["graph_y1label"]?$graph["graph_y1label"]:$post_vars["graph_y1label"])."' style='border: 1px solid #000000'><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_Y2LABEL."</span><br> ";
        print "<input type='text' class='textbox' size='20' maxlength='30' name='graph_y2label' value='".($graph["graph_y2label"]?$graph["graph_y2label"]:$post_vars["graph_y2label"])."' style='border: 1px solid #000000'><br>";
        print "<small>".INSTR_Y2_LABEL."</small><br/>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_FILLCOLOR."</span><br> ";
        print "<select name='graph_barcolor' class='textbox'>";
        print "<option value='0'>Select Fill Color</option>";
        print "<option value='red' ".($graph["graph_barcolor"]=='red'?"selected":"").">RED</option>";
        print "<option value='orange' ".($graph["graph_barcolor"]=='orange'?"selected":"").">ORANGE</option>";
        print "<option value='blue' ".($graph["graph_barcolor"]=='blue'?"selected":"").">BLUE</option>";
        print "<option value='yellow' ".($graph["graph_barcolor"]=='yellow'?"selected":"").">YELLOW</option>";
        print "<option value='green' ".($graph["graph_barcolor"]=='green'?"selected":"").">GREEN</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_GRAPH_TYPE."</span><br> ";
        print "<select name='graph_type' class='textbox'>";
        print "<option value=''>Select Graph Type</option>";
        print "<option value='2PL' ".($graph["graph_type"]=='2PL'?"selected":"").">2-PLOT FILLED</option>";
        print "<option value='S' ".($graph["graph_type"]=='S'?"selected":"").">Simple Line</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_GRAPH_WIDTH."</span><br> ";
        print "<select name='graph_width' class='textbox'>";
        print "<option value='0'>Select Width</option>";
        print "<option value='300' ".($graph["graph_width"]=='300'?"selected":"").">300</option>";
        print "<option value='350' ".($graph["graph_width"]=='350'?"selected":"").">350</option>";
        print "<option value='400' ".($graph["graph_width"]=='400'?"selected":"").">400</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='boxtitle'>".LBL_GRAPH_HEIGHT."</span><br> ";
        print "<select name='graph_height' class='textbox'>";
        print "<option value='0'>Select Height</option>";
        print "<option value='300' ".($graph["graph_height"]=='300'?"selected":"").">300</option>";
        print "<option value='350' ".($graph["graph_height"]=='350'?"selected":"").">350</option>";
        print "<option value='400' ".($graph["graph_height"]=='400'?"selected":"").">400</option>";
        print "</select>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_GRAPH_FLAGS."</span><br> ";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="R"?"checked":"")." value='R' > Set date range<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="Y"?"checked":"")." value='Y' > Set year<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="M"?"checked":"")." value='M' > Set month<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="W"?"checked":"")." value='W' > Set week<br>";
        print "<input type='radio' name='graph_flag' ".($graph["graph_flag"]=="D"?"checked":"")." value='D' > Set day<br>";
        print "</td></tr>";
        print "<tr valign='top'><td><br/>";
        print "<span class='boxtitle'>".LBL_GRAPH_SQL."</span><br> ";
        print "<textarea name='graph_sql' cols='45' rows='7' class='tinylight' style='border: 1px solid black'>".$graph["graph_sql"]."</textarea>";
        print "<small>CHITS graphs are generated from SQL statements. You have to put in a <b>valid</b> SQL ".
              "statement here! Valid variables for graph flags: __START_DATE__, __END_DATE__, ".
              "__WEEK__ and __MONTH__.</small><br/>";
        print "</td></tr>";
        print "<tr><td><br>";
        if ($get_vars["graph_id"]) {
            print "<input type='hidden' name='graph_id' value='".$get_vars["graph_id"]."'>";
            if ($_SESSION["priv_update"]) {
                print "<input type='submit' value = 'Update Graph' class='textbox' name='submitgraph' style='border: 1px solid #000000'> ";
            }
            if ($_SESSION["priv_delete"]) {
                print "<input type='submit' value = 'Delete Graph' class='textbox' name='submitgraph' style='border: 1px solid #000000'> ";
            }
        } else {
            if ($_SESSION["priv_add"]) {
                print "<input type='submit' value = 'Add Graph' class='textbox' name='submitgraph' style='border: 1px solid #000000'> ";
            }
        }
        print "</td></tr>";
        print "</form>";
        print "</table><br>";

    }

    function process_linegraph() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($post_vars["submitgraph"]) {
            if ($post_vars["graph_title"] && $post_vars["graph_width"] && $post_vars["graph_height"] && $post_vars["graph_sql"]) {
                switch($post_vars["submitgraph"]) {
                case "Add Graph":
                    if ($post_vars["graph_type"]=="S") {
                        $sql = "insert into m_lib_graph_linegraph (graph_title, graph_width, graph_height, graph_type, ".
                               "graph_xlabel, graph_y1label, graph_barcolor, graph_sql, graph_flag, module_id) ".
                               "values ('".$post_vars["graph_title"]."', '".$post_vars["graph_width"]."', ".
                               "'".$post_vars["graph_height"]."', '".$post_vars["graph_type"]."', '".$post_vars["graph_xlabel"]."', ".
                               "'".$post_vars["graph_y1label"]."', '".$post_vars["graph_barcolor"]."', '".$post_vars["graph_sql"]."', ".
                               "'".$post_vars["graph_flag"]."', '".$post_vars["module"]."')";
                    } else {
                        print $sql = "insert into m_lib_graph_linegraph (graph_title, graph_width, graph_height, graph_type, ".
                               "graph_xlabel, graph_y1label, graph_y2label, graph_barcolor, graph_sql, graph_flag, module_id) ".
                               "values ('".$post_vars["graph_title"]."', '".$post_vars["graph_width"]."', ".
                               "'".$post_vars["graph_height"]."', '".$post_vars["graph_type"]."', '".$post_vars["graph_xlabel"]."', ".
                               "'".$post_vars["graph_y1label"]."', '".$post_vars["graph_y2label"]."', '".$post_vars["graph_barcolor"]."', '".$post_vars["graph_sql"]."', ".
                               "'".$post_vars["graph_flag"]."', '".$post_vars["module"]."')";
                    }
                    if ($result = mysql_query($sql)) {
                        //header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=LINE");
                    }
                    break;
                case "Update Graph":
                    if ($post_vars["graph_type"]=="S") {
                        $sql = "update m_lib_graph_linegraph set ".
                               "graph_title = '".$post_vars["graph_title"]."', ".
                               "graph_type = '".$post_vars["graph_type"]."', ".
                               "graph_xlabel = '".$post_vars["graph_xlabel"]."', ".
                               "graph_y1label = '".$post_vars["graph_y1label"]."', ".
                               "graph_barcolor = '".$post_vars["graph_barcolor"]."', ".
                               "graph_height = '".$post_vars["graph_height"]."', ".
                               "graph_width = '".$post_vars["graph_width"]."', ".
                               "graph_flag = '".$post_vars["graph_flag"]."', ".
                               "graph_sql = '".$post_vars["graph_sql"]."' ".
                               "where graph_id = '".$post_vars["graph_id"]."'";
                        } else {
                        $sql = "update m_lib_graph_linegraph set ".
                               "graph_title = '".$post_vars["graph_title"]."', ".
                               "graph_type = '".$post_vars["graph_type"]."', ".
                               "graph_xlabel = '".$post_vars["graph_xlabel"]."', ".
                               "graph_y1label = '".$post_vars["graph_y1label"]."', ".
                               "graph_y2label = '".$post_vars["graph_y2label"]."', ".
                               "graph_barcolor = '".$post_vars["graph_barcolor"]."', ".
                               "graph_height = '".$post_vars["graph_height"]."', ".
                               "graph_width = '".$post_vars["graph_width"]."', ".
                               "graph_flag = '".$post_vars["graph_flag"]."', ".
                               "graph_sql = '".$post_vars["graph_sql"]."' ".
                               "where graph_id = '".$post_vars["graph_id"]."'";
                        }
                    if ($result = mysql_query($sql)) {
                        header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=LINE");
                    }
                    break;
                case "Delete Graph":
                    if (module::confirm_delete($menu_id, $post_vars, $get_vars)) {
                        $sql = "delete from m_lib_graph_linegraph where graph_id = '".$post_vars["graph_id"]."'";
                        if ($result = mysql_query($sql)) {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=LINE");
                        }
                    } else {
                        if ($post_vars["confirm_delete"]=="No") {
                            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=LINE");
                        }
                    }
                    break;
                }
            }
        }
    }

    function draw_linegraph() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if ($get_vars["view_id"]) {
            // find out what flag is on
            $sql = "select graph_flag, graph_type ".
                   "from m_lib_graph_linegraph where graph_id = '".$get_vars["view_id"]."'";
            if ($result = mysql_query($sql)) {
                if (mysql_num_rows($result)) {
                    list($graph_flag, $graph_type) = mysql_fetch_array($result);
                    switch ($graph_flag) {
                    // date range, ask for start and end dates
                    case "R":
                        break;
                    // ask for year
                    case "Y":
                        break;
                    // ask for month
                    case "M":
                        break;
                    // ask for week
                    case "W":
                        break;
                    // ask for day
                    case "D":
                        break;
                    }
                }
            }
            if ($graph_type=="2PL") {
                if (file_exists("../graphs/line_filled_2plots.php")) {
                    print "<br/>";
                    print "<img src='../graphs/line_filled_2plots.php?view_id=".$get_vars["view_id"]."' border='0' />";
                } else {
                    print "<font color='red'>File not found</font>";
                }
            }
        }
    }

    function display_linegraph() {
        if (func_num_args()) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        print "<table width='300'>";
        print "<tr valign='top'><td>";
        print "<b>".FTITLE_LINEGRAPH_LIST."</b><br><br>";
        print "</td></tr>";
        print "<tr valign='top'><td>";
        print "<span class='tinylight'>".INSTR_LINEGRAPH_LIST."</span><br>";
        print "</td></tr>";
        $sql = "select graph_id, graph_title ".
               "from m_lib_graph_linegraph ".
               "where module_id = '".$get_vars["module"]."' ".
               "order by graph_title";
        if ($result = mysql_query($sql)) {
            print "<tr valign='top'><td>";
            if (mysql_num_rows($result)) {
                while (list($id, $title) = mysql_fetch_array($result)) {
                    print "<img src='../images/arrow_redwhite.gif' border='0'/> ";
                    print "<a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=LINE&module=consult_report&graph_id=$id'>$title</a> ";
                    print "<a href='".$_SERVER["SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&report_menu=GRAPHS&graph=LINE&module=consult_report&view_id=$id'><img src='../images/view.png' border='0'/></a><br/>";
                }
            } else {
                print "<font color='red'>No bar graphs on record</font><br/>";
            }
            print "</td></tr>";
        }
        print "</table><br>";
    }

// end of class
}
?>
