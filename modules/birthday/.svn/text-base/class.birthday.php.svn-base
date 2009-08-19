<?
class birthday extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function birthday() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.2-".date("Y-m-d");
        $this->module = "birthday";
        $this->description = "CHITS Content - Birthday";

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

        // load initial data

        // load initial data

    }

    function drop_tables() {

    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _birthday() {
    //
    // main submodule for birthday
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
        $b = new birthday;
        $b->greet_celebrant($menu_id, $post_vars, $get_vars);
    }

    function greet_celebrant() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }
        print "<span class='library'>BIRTHDAY GREETINGS</span><br/><br/>";
        $sql = "select user_login, concat(user_firstname, ' ', user_lastname) name ".
               "from game_user where date_format(user_dob, '%m-%d') = date_format(sysdate(), '%m-%d')";
        if ($result = mysql_query($sql)) {
            if (mysql_num_rows($result)) {
                list($login, $name) = mysql_fetch_array($result);
                print "Happy Birthday <b>$name</b> ($login)!<br/>";
            } else {
                print "<font color='red'>No celebrants today.</font><br/>";
            }
        }
    }



// end of class
}
?>
