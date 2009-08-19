<?
class MySQLDB {
    var $conn = 0;

    function mysqldb () {
    //
    // database module
    //
        $this->version = "0.2";
        $this->author = "Herman Tolentino MD";
        if (!file_exists("../modules/_dbselect.php")) {
            $this->setup("../db.xml");
        }
    }

    function setup() {
    //
    // writes down _dbselect.php
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $filename = $arg_list[0];
        }
        if (file_exists($filename)) {
            $db = $this->readconfig($filename);
            foreach ($db as $key=>$dbname) {
                foreach($dbname as $key=>$value) {
                    //print $value;
                    $_SESSION[$key] = $value;
                }
            }
            if ($fp = @fopen("../modules/_dbselect.php", "w+")) {
                $dbfile .= "<?php\n";
                $dbfile .= "\$_SESSION[\"dbname\"] = \"".$_SESSION["dbname"]."\";\n";
                $dbfile .= "\$_SESSION[\"dbuser\"] = \"".$_SESSION["dbuser"]."\";\n";
                $dbfile .= "\$_SESSION[\"dbpass\"] = \"".$_SESSION["dbpass"]."\";\n";
                $dbfile .= "\$conn = mysql_connect(\"".$_SESSION["dbhost"]."\", \"".$_SESSION["dbuser"]."\", \"".$_SESSION["dbpass"]."\");\n";
                $dbfile .= "\$db->connectdb(\"".$_SESSION["dbname"]."\");\n";
                $dbfile .= "?>";
                if (fwrite($fp, $dbfile)) {
                    fclose($fp);
                }
            }
        }
    }

    function connectdb() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $dbname = $arg_list[0];
        }
        //$self->conn = mysql_connect($_SESSION["dbhost"], $_SESSION["dbuser"], $_SESSION["dbpass"]) or die(mysql_errno().": ".mysql_error());
        if (!mysql_select_db($dbname)) {
            $this->create_system_tables($dbname);
            module::set_lang("LBL_NAVIGATION", "english", "NAVIGATION", "Y");
            module::set_lang("LBL_HOME", "english", "HOME", "Y");
            module::set_lang("LBL_SIGN_IN", "english", "SIGN IN", "Y");
            module::set_lang("LBL_LOGIN_NAME", "english", "LOGIN NAME", "Y");
            module::set_lang("LBL_PASSWORD", "english", "PASSWORD", "Y");
            module::set_lang("LBL_SIGN_OUT", "english", "SIGN OUT", "Y");
            module::set_lang("BTN_SIGN_OUT", "english", "SIGN OUT", "Y");
            module::set_lang("MENU_PATIENTS", "english", "PATIENTS", "Y");
            module::set_lang("BTN_SIGN_OUT", "english", "SIGN OUT", "Y");
            module::set_lang("MENU_ABOUT", "english", "ABOUT", "Y");
            module::set_lang("MENU_HOWTO", "english", "HOWTO", "Y");
            module::set_lang("MENU_CREDITS", "english", "CREDITS", "Y");
            module::set_lang("FTITLE_SITE_USER_FORM", "english", "SITE USER FORM", "Y");
            module::set_lang("FTITLE_SITE_USERS", "english", "SITE USERS", "Y");
            module::set_lang("LBL_FIRST_NAME", "english", "FIRST NAME", "Y");
            module::set_lang("LBL_MIDDLE_NAME", "english", "MIDDLE NAME", "Y");
            module::set_lang("LBL_LAST_NAME", "english", "LAST NAME", "Y");
            module::set_lang("LBL_LAST_NAME", "english", "LAST NAME", "Y");
            module::set_lang("LBL_DATE_OF_BIRTH", "english", "BIRTH DATE", "Y");
            module::set_lang("LBL_GENDER", "english", "GENDER", "Y");
            module::set_lang("LBL_PIN", "english", "4-NUMBER PIN", "Y");
            module::set_lang("LBL_ROLE", "english", "ROLE", "Y");
            module::set_lang("LBL_LANGUAGE_DIALECT", "english", "LANGUAGE/DIALECT", "Y");
            module::set_lang("LBL_CONFIRM_PASSWORD_AGAIN", "english", "CONFIRM PASSWORD BY TYPING AGAIN", "Y");
            module::set_lang("LBL_ACTIVE_USER", "english", "ACTIVE ACCOUNT", "Y");
            module::set_lang("LBL_ADMIN_USER", "english", "ADMIN USER", "Y");
            module::set_lang("LBL_EMAIL", "english", "EMAIL ACCOUNT", "Y");
            module::set_lang("LBL_CELLULAR", "english", "CELLULAR PHONE", "Y");
            module::set_lang("FTITLE_ROLE_FORM", "english", "ROLE FORM", "Y");
            module::set_lang("LBL_ROLE_ID", "english", "ROLE ID", "Y");
            module::set_lang("LBL_ROLE_NAME", "english", "ROLE NAME", "Y");
            module::set_lang("FTITLE_ROLE_LIST", "english", "ROLE LIST", "Y");
            module::set_lang("THEAD_NAME", "english", "NAME", "Y");
            module::set_lang("FTITLE_LOCATION_FORM", "english", "LOCATION", "Y");
            module::set_lang("INSTR_ROLE_FORM", "english", "<b>INSTRUCTIONS: After creating users, be sure to set data permissions under <b>MODULES-&gt;Data Access By Roles</b>.</b>", "Y");
            module::set_lang("INSTR_LOCATION_FORM", "english", "<b>INSTRUCTIONS: After creating users, be sure to set permissions for the modules and menu items under <b>MODULES-&gt;Menu Locations</b>.</b>", "Y");
            module::set_lang("LBL_LOCATION_ID", "english", "LOCATION ID", "Y");
            module::set_lang("LBL_LOCATION_NAME", "english", "LOCATION NAME", "Y");
            module::set_lang("FTITLE_LOCATION_LIST", "english", "LOCATION LIST", "Y");
            module::set_lang("LBL_REGISTERED_BY", "english", "REGISTERED BY", "Y");

        }
    }

    function selectdb ($db) {
        mysql_select_db("$db");
    }

    function connid () {
        return $self->conn;
    }

    function create_system_tables() {
    //
    // create system tables if not present
    //
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $dbname = $arg_list[0];
        }
        mysql_query("CREATE DATABASE `$dbname`;") or die(mysql_errno().": ".mysql_error());
        mysql_select_db($dbname);

        module::load_sql("setup.sql");
    }

    function parseXML($mvalues) {
        for ($i=0; $i < count($mvalues); $i++)
            $node[$mvalues[$i]["tag"]] = $mvalues[$i]["value"];
        return ($node);
    }

    function readconfig($filename) {
    // read the xml database
        $data = implode("",file($filename));
        $parser = xml_parser_create();
        xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
        xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
        xml_parse_into_struct($parser,$data,$values,$tags);
        xml_parser_free($parser);

        // loop through the structures
        foreach ($tags as $key=>$val) {
            if ($key == "db") {
                $noderanges = $val;
                // each contiguous pair of array entries are the
                // lower and upper range for each node definition
                for ($i=0; $i < count($noderanges); $i+=2) {
                    $offset = $noderanges[$i] + 1;
                    $len = $noderanges[$i + 1] - $offset;
                    $tdb[] = $this->parseXML(array_slice($values, $offset, $len));
                }
            } else {
                continue;
            }
        }
        return $tdb;
    }

    function backup_database() {
    }
    
// end of class
}

?>
