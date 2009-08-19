<?
class database extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function database() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.3-".date("Y-m-d");
        $this->module = "database";
        $this->description = "CHITS Module - Database Support";
        // 0.3 added delete for backup files

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
        module::set_lang("MENU_STATS", "english", "DATABASE STATS", "Y");
        module::set_lang("MENU_BACKUP", "english", "DATABASE BACKUP", "Y");

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
        module::set_menu($this->module, "Database Support", "SUPPORT", "_database_support");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // load initial data
        // none to load

    }

    function drop_tables() {

    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _database_support() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        $d = new database;
        print "<span class='library'>DATABASE SUPPORT</span><br/><br/>";
        $d->database_menu($menu_id, $post_vars, $get_vars);
        if ($get_vars["db_menu"]) {
            switch($get_vars["db_menu"]) {
            case "STATS":
                $d->display_stats();
                break;
            case "BACKUP":
                $d->database_backup($menu_id, $post_vars, $get_vars);
                break;
            }
        }
    }

    function database_menu() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        if (!$get_vars["db_menu"]) {
            header("location: ".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&db_menu=STATS");
        }
        print "<table width='600' cellpadding='2' bgcolor='#CCCC99' cellspacing='1' style='border: 2px solid black'><tr align='left'>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&db_menu=STATS' class='ptmenu'>".($get_vars["db_menu"]=="STATS"?"<b>".MENU_STATS."</b>":MENU_STATS)."</a></td>";
        print "<td><a href='".$_SERVER["PHP_SELF"]."?page=".$get_vars["page"]."&menu_id=".$get_vars["menu_id"]."&db_menu=BACKUP' class='ptmenu'>".($get_vars["db_menu"]=="BACKUP"?"<b>".MENU_BACKUP."</b>":MENU_BACKUP)."</a></td>";
        print "</tr></table>";
        print "<br/>";
    }

    function database_backup() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<table width='600'><tr valign='top'><td width='50%'>";
        // column 1
        if ($post_vars["submitdb"]) {
            database::process_backup($menu_id, $post_vars, $get_vars);
        }
        database::form_backup($menu_id, $post_vars, $get_vars);
        print "</td><td>";
        // column 2
        database::display_backups($menu_id, $post_vars, $get_vars);
        print "</td></tr></table>";
    }

    function form_backup() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
            //print_r($arg_list);
        }
        print "<b>DATABASE BACKUP</b><br/><br/>";
        print "You are backing up this database in SQL format. ".
              "Make sure nobody else is using the system to get a complete ".
              "image of the database. The database is automatically named ".
              "in timestamp format. ";
        print "<br/><br/>";
        print "<form method='post' action=''>";
        print "<input type='submit' name='submitdb' value='Backup Database' class='textbox' style='border: 1px solid black'/>";
        print "</form>";
    }

    function display_backups() {
    //
    // display and manage backup files
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
        //
        // manage delete here
        //
        if ($post_vars["submitfile"]=="Delete Selected Files" && $post_vars["backup_files"]) {
            foreach ($post_vars["backup_files"] as $key=>$value) {
                @unlink("../dump/".$value);
            }
        }
        print "<b>BACKUP FILES</b><br/><br/>";
        $d = dir("../dump");
        print "<span class='tinylight'>";
        print "<form method='post' action=''>";
        while (false !== ($entry = $d->read())) {
            if ($entry<>"." && $entry<>"..") {
                print "<input type='checkbox' name='backup_files[]' value='$entry'/> ";
                print "<a href='../dump/$entry' target='_blank'>".$entry."</a><br>\n";
            }
        }
        print "<br/>";
        print "<input type='submit' class='textbox' name='submitfile' value='Delete Selected Files' style='border: 1px solid black; background-color: #FF3300' />";
        print "</form>";
        print "</span>";
        $d->close();
    }

    function process_backup() {
    //
    // assumes mysqldump is present in path
    // test this first before implementing
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
        if (file_exists("/usr/bin/mysqldump")) {
            // backtick operator
            $user = $_SESSION["dbuser"];
            $pass = $_SESSION["dbpass"];
            $dbname = $_SESSION["dbname"];
            $filename = "$dbname-".module::pad_zero($_SESSION["userid"],3)."-".date("Ymd-His").".sql";
            // -t for no create table
            // -n for no create db
            // --single-transaction get consistent state for InnoDB tables
            // --quick do not buffer query
            // --extended-insert
            `/usr/bin/mysqldump -u $user -p$pass -t -n --quick --extended-insert --single-transaction $dbname > ../dump/$filename`;
        }
    }

    function display_stats() {
    //
    // display internal mysql stats nicely
    //
        print "<b>DATABASE STATS</b><br/><br/>";
        print "<table width='600'><tr valign='top'><td width='50%'>";
        // column 1
        print "MYSQL STATS<br/><br/>";
        print "<span class='tinylight'>";
        print "<b>Version:</b> ".mysql_get_server_info();
        $stats = mysql_stat();
        $stats = ereg_replace("Uptime:","<br/><b>Uptime:</b>", $stats);
        $stats = ereg_replace("Threads:","<br/><b>Threads:</b>", $stats);
        $stats = ereg_replace("Questions:","<br/><b>Questions:</b>", $stats);
        $stats = ereg_replace("Slow queries:","<br/><b>Slow queries:</b>", $stats);
        $stats = ereg_replace("Opens:","<br/><b>Opens:</b>", $stats);
        $stats = ereg_replace("Flush tables:","<br/><b>Flush tables:</b>", $stats);
        $stats = ereg_replace("Open tables:","<br/><b>Open tables:</b>", $stats);
        $stats = ereg_replace("Queries per second avg:","<br/><b>Queries per second avg:</b>", $stats);
        print $stats;
        print "</span>";
        print "<br/><br/>";
        print "BACKUP DIRECTORY<br/><br/>";
        if (is_dir("../dump")) {
            print "<font color='green'>Data dump directory OK.</font><br/>";
            if (is_writable("../dump")) {
                print "<font color='green'>Data dump directory writable.</font><br/>";
            } else {
                print "<font color='red'>Data dump directory not writable.</font><br/>";
            }
        } else {
            print "<font color='red'>Data dump directory not accessible.</font><br/>";
        }
        print "</td><td>";
        // column 2
        print "MYSQL INNODB VARIABLES<br/>";
        print "<span class='tinylight'>";
        $sql = "show variables;";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                while ($var = mysql_fetch_array($result)) {
                    foreach($var as $key=>$value) {
                        if ($key=="Variable_name") {
                            $field_name = $value;
                        }
                        if ($key=="Value") {
                            $field_value = $value;
                        }
                        if (eregi("innodb", $field_name)) {
                            if ($field_name && $field_value && $field_name<>$field_value) {
                                print "$field_name: $field_value<br/>";
                            }
                        }
                    }
                }
            }
        }
        print "</span><br/>";
        print "</td></tr>";
        print "<tr><td colspan='2'>";
        $sql = "show table status from game";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                print "<table width='600' bgcolor='black' cellspacing='1' cellpadding='3'>";
                print "<tr bgcolor='#CCCCCC'>";
                print "<td class='tinylight'><b>NAME</b></td>";
                print "<td class='tinylight'><b>TYPE</b></td>";
                print "<td class='tinylight'><b>FMT</b></td>";
                print "<td class='tinylight'><b>ROWS</b></td>";
                print "<td class='tinylight'><b>LENGTH</b></td>";
                print "<td class='tinylight'><b>OPTIONS</b></td>";
                print "</tr>";
                while ($stats = mysql_fetch_array($result)) {
                    print "<tr bgcolor='white' valign='top'><td class='tinylight'>".$stats["Name"]."</td>";
                    print "<td class='tinylight'>".$stats["Type"]."</td>";
                    print "<td class='tinylight'>".$stats["Row_format"]."</td>";
                    print "<td class='tinylight'>".$stats["Rows"]."</td>";
                    print "<td class='tinylight'>".$stats["Data_length"]."</td>";
                    print "<td class='tinylight'>".$stats["Comment"]."</td>";
                    print "</tr>";
                }
                print "</table>";
            }
        }
        print "</td></tr></table>";
    }

// end of class
}
?>
