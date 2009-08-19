<?
class quote extends module {

    // Author: Herman Tolentino MD
    // CHITS Project 2004

    function quote() {
        //
        // do not forget to update version
        //
        $this->author = 'Herman Tolentino MD';
        $this->version = "0.1-".date("Y-m-d");
        $this->module = "quote";
        $this->description = "CHITS Module - Quotations";
    }

    // --------------- STANDARD MODULE FUNCTIONS ------------------

    function init_deps() {
    //
    // insert dependencies in module_dependencies
    //
        module::set_dep($this->module, "module");
        module::set_dep($this->module, "user");
    }

    function init_lang() {
    //
    // insert necessary language directives
    //
        module::set_lang("LBL_COMPLAINTCAT", "english", "COMPLAINT CATEGORY", "Y");
        module::set_lang("FTITLE_CONSULT_COMPLAINT", "english", "CONSULT COMPLAINT", "Y");
        module::set_lang("LBL_COMPLAINT", "english", "COMPLAINT", "Y");
        module::set_lang("LBL_COMPLAINT_MODULE", "english", "COMPLAINT MODULE", "Y");
        module::set_lang("THEAD_MODULE", "english", "COMPLAINT MODULE", "Y");
        module::set_lang("LBL_COMPLAINT_ID", "english", "COMPLAINT ID", "Y");
        module::set_lang("LBL_COMPLAINT_NAME", "english", "COMPLAINT NAME", "Y");
        module::set_lang("FTITLE_COMPLAINT_LIST", "english", "COMPLAINT LIST", "Y");

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
        module::set_menu($this->module, "Frontpage Quotes", "CONTENT", "_quotes");
        module::set_menu($this->module, "Quotes Archive", "CONTENT", "_quotes_archive");

        // add more detail
        module::set_detail($this->description, $this->version, $this->author, $this->module);

    }

    function init_sql() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
        }

        // this table relates complaint to loadable modules:
        // imci_cough, imci_diarrhea, imci_fever
        module::execsql("CREATE TABLE `m_quotations` (".
            "`quote_id` int(11) NOT NULL auto_increment,".
            "`quote_text` text NOT NULL,".
            "PRIMARY KEY  (`quote_id`)".
            ") TYPE=MyISAM; ");

        // load initial data

    }

    function drop_tables() {
        module::execsql("DROP TABLE `m_quotations`;");
    }


    // --------------- CUSTOM MODULE FUNCTIONS ------------------

    function _quotes() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
        if ($post_vars["submitcode"]) {
            $$this->process_quotes($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
        }
        $this->display_quotes($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
        $this->form_quotes($menu_id,$post_vars,$get_vars,$validuser,$isadmin);
    }

    function form_quotes() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
    }

    function process_quotes() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
    }

    function display_quotes() {
        if (func_num_args()>0) {
            $arg_list = func_get_args();
            $menu_id = $arg_list[0];
            $post_vars = $arg_list[1];
            $get_vars = $arg_list[2];
            $validuser = $arg_list[3];
            $isadmin = $arg_list[4];
        }
    }


// end of class
}
?>
