<?
ob_start();
// buffer everything till the end
// -------------------------------------------------------------------------------
// Main Index Page
// Type: internal
// Author: Herman Tolentino MD
// Description: this is level one script for GAME
//              everything starts from here!!!
//
// Version 0.9
// 0.9 nothing done
// -------------------------------------------------------------------------------

// start session
session_start();

// expire page so no cookies will be stored
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

// standard class includes
include "../class.mysqldb.php";
include "../class.user.php";
include "../class.site.php";
include "../class.module.php";
include "../class.datanode.php";

// load PDF open source modules
// courtesy of http://www.fpdf.org/
include "../class.fpdf.php";
include "../class.pdf.php";
// very important setting for FPDF
define(FPDF_FONTPATH,'../fonts/');

include "../initsession.php";

// initialize database connection
$db = new MySQLDB;
$conn = $db->connid();
include "../modules/_dbselect.php";

// these are the key modules for game engine
$user = new User;
$site = new Site;
$module = new Module;

// load language constants
// for multilingualization
// courtesy of the MBDS Project
// http://www.mbdsnet.org/
// to expand functionality, load/use class.language.php
include "../lang.php";

// check if any users exist
if ($user->check_users()) {
    // redirect to welcome page
    header("location: ".$_SERVER["PHP_SELF"]."?page=WELCOME");
}


// load module class includes
// this is server-generated code
// do not edit or delete
include "../modules/_modules.php";

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$HTTP_SESSION_VARS["myencoding"]?>">
<title><?=strtoupper($_SESSION["datanode"]["name"])?> Info Desktop</title>
<style type="text/css">
<!--
td { font-size: 10pt; font-family: verdana, sans-serif }
small { font-family: verdana, sans serif}
.textbox { font-family: verdana, arial, sans serif; font-size: 10pt; }
.whitetext { color: white; font-family: verdana, arial, sans serif; font-size: 10pt; }
.error { font-family: verdana, arial, sans serif; font-size: 12pt; color: red; }
.service { font-family: verdana, arial, sans serif; font-size: 12pt; font-weight: bold; color: #585858; }
.pt_menu { font-family: verdana, arial, sans serif; padding-top: 0px; padding-bottom: 0px; font-size: 8pt; font-weight: bold; color: black; }
.topmenu { font-family: verdana, arial, sans serif; font-size: 10pt; text-decoration: none; padding-left: 4px; padding-right: 4px; }
.topmenu:hover { font-family: verdana, arial, sans serif; font-size: 10pt; text-decoration: none; background-color: #FFFF00; border: 1px solid black; padding-left: 3px; padding-right: 3px;}
.groupmenu { font-family: verdana, arial, sans serif; font-size: 8pt; text-decoration: none; padding-left: 4px; padding-right: 4px; }
.groupmenu:hover { font-family: verdana, arial, sans serif; font-size: 8pt; text-decoration: none; background-color: #CCCCFF; border: 1px solid black; padding-left: 3px; padding-right: 3px; }
.fpmenu { font-family: verdana, arial, sans serif; font-size: 8pt; text-decoration: none; padding-left: 4px; padding-right: 4px; }
.fpmenu:hover { font-family: verdana, arial, sans serif; font-size: 8pt; text-decoration: none; background-color: #99FF33; border: 1px solid black; padding-left: 3px; padding-right: 3px; }
.complaintmenu { font-family: verdana, arial, sans serif; font-size: 8pt; text-decoration: none; padding-left: 4px; padding-right: 4px; }
.complaintmenu:hover { font-family: verdana, arial, sans serif; font-size: 8pt; text-decoration: none; background-color: #99FF99; border: 1px solid black; padding-left: 3px; padding-right: 3px;}
.sidemenu { font-family: verdana, arial, sans serif; font-size: 10pt; text-decoration: none; padding-left: 3px; padding-right: 3px; }
.sidemenu:hover { font-family: verdana, arial, sans serif; font-size: 10pt; text-decoration: none; background-color: #66FF33; border: 1px solid black; padding-left: 2px; padding-right: 2px;}
.catmenu { font-family: verdana, arial, sans serif; font-size: 10pt; text-decoration: none; padding-left: 3px; padding-right: 3px; }
.catmenu:hover { font-family: verdana, arial, sans serif; font-size: 10pt; text-decoration: none; background-color: #99FFFF; border: 1px solid black; padding-left: 2px; padding-right: 2px;}
.ptmenu { font-family: verdana, arial, sans serif; font-size: 10pt; text-decoration: none; padding-left: 3px; padding-right: 3px; }
.ptmenu:hover { font-family: verdana, arial, sans serif; font-size: 10pt; text-decoration: none; background-color: #FFFF33; border: 1px solid black; padding-left: 2px; padding-right: 2px;}
.boxtitle { font-family: verdana, arial, sans serif; font-size: 8pt; font-weight: bold;}
.tiny { font-family: arial, sans serif; font-size: 7pt; font-weight: bold; color: black; }
.tinylight { font-family: verdana, arial, sans serif; font-size: 7pt; font-weight: normal; color: black; }
.copyright { font-family: verdana, arial, sans serif; font-size: 7pt; font-weight: normal; color: black; }
.admin { font-family: verdana, arial, sans serif; font-size: 14pt; font-weight: bold; color: #FF3300; }
.module { font-family: verdana, arial, sans serif; font-size: 14pt; font-weight: bold; color: #9999FF; }
.library { font-family: verdana, arial, sans serif; font-size: 14pt; font-weight: bold; color: #999999; }
.patient { font-family: verdana, arial, sans serif; font-size: 14pt; font-weight: bold; color: #99CC66; }
.newstitle { font-family: verdana, arial, sans serif; font-size: 12pt; font-weight: bold; color: #666699; }
.newsbody { font-family: Georgia, Times New Roman, Serif; font-size: 12pt; font-weight: normal; color: black; }
-->
</style>
</head>
<script language="javascript" src="../popups.js"></script>
<script language="JavaScript" src="../ts_picker4.js"></script>
<script language="JavaScript" src="../js/functions.js"></script>


<body text="black" bgcolor="#FFFFCC" link="black" vlink="black">
<? 
	//echo "Print page module: ";
	//print_r($_GET);

?>
<br/>
<table border="0" cellspacing="0" bgcolor="#000000" style="border: 4px solid black" width="100%" cellpadding="0">
  <tr bgcolor="#FF0000">
    <td valign="top"><img src="../images/chitsbanner.png" border="0"></td>
  </tr>
  <tr>
    <td>
    </td>
  </tr>
</table><br>
<table  bgcolor="#FFCC00" cellpadding="1" style="border: 1px solid black">
  <tr>
   <td>
   <?
   // horizontal menu
   if ($_SESSION["isadmin"]) {
       // display two-tier admin menu
       $site->displaymenu($_SESSION["validuser"],"ADMIN", $_SESSION["isadmin"]);
   } else {
       // display menus as top level menu
       $site->displaymenu($_SESSION["validuser"],"USER", $_SESSION["isadmin"]);
   }
   ?>
   </td>
  </tr>
</table><br>
<table style="border:1px solid black" bgcolor='white' width="100%" cellspacing="2" cellpadding="2">
    <tr>
    <td width="150" valign="top">
    <table>
        <tr>
            <td>
            <?
            if ($_POST["submitlogin"]) {                
                $user = $user->process_auth($_POST["login"], $_POST["passwd"]);
                if (count($user)>0) {
                    //print_r($user);                                        
                    $site->session_user($user);
                    //$site->record_access($_SESSION["userid"],$HTTP_USER_AGENT,"ASC","login");
                    header("location: ".$_SERVER["PHP_SELF"]);
                } else {
                    // Invalid account
                    header("location: ".$_SERVER["PHP_SELF"]."?errorinfo=001");
                }
            }
            if ($_POST["submitlogout"]) {
                $user->process_signoff();
                header("location: ".$_SERVER["PHP_SELF"]);
            }
            if (!$_SESSION["validuser"]) {
                $user->authenticate();
            } else {
                $user->signoff($_SESSION["user_first"], $_SESSION["user_last"], $_SESSION["datanode"]["name"], $_SESSION["isadmin"], $_SERVER["REMOTE_ADDR"], $_SESSION["userid"]);
            }
            ?>
            </td>
        </tr>
        <tr>
            <td>
            <?
            $menu_array = module::readconfig("../menu.xml", "menu");
            if ($_SESSION["isadmin"]) {
                $site->main_cat($_SESSION["validuser"], $_SESSION["isadmin"], $_GET, $menu_array);
            }
            ?>
            </td>
        </tr>
        <tr>
            <td>
            <?
            if ($_SESSION["isadmin"]) {
                $site->main_menu($_SESSION["validuser"], $_SESSION["isadmin"], $_GET["page"], $menu_array);
            }
            ?>
            </td>
        </tr>
        <tr>
            <td>
            <?
            $site->main_stats();
            ?>
            </td>
        </tr>
    </table>
    </td>
    <td width="450" valign="top">
    <br/>
    <blockquote>
    <?
    if ($_GET["menu_id"]) {
        // module below is server-generated code
        // do not edit
        include "../modules/_menu.php";
    } else {
        switch ($page) {
        case "WELCOME":
            $site->welcome();
            break;
        case "ABOUT":
            $site->print_about();
            break;
        case "HOWTO":
            $site->print_howto();
            break;
        case "CREDITS":
            $site->print_credits();
            break;
        case "ADMIN":
            if ($_SESSION["isadmin"]) {
                switch ($method) {
                case "CONTENT":
                    $site->_content($menu_id, $_POST,$_GET, $_SESSION["validuser"], $_SESSION["isadmin"]);
                    break;
                case "LOC":
                    $user->_location($menu_id, $_POST,$_GET);
                    break;
                case "ROLES":
                    $user->_roles($menu_id, $_POST,$_GET);
                    break;
                case "USER":
                default:
                    $user->_game_user($menu_id, $_POST,$_GET);
                }
            } else {
                print "<font color='red'>You have no authorization for this page.</font>";
            }
            break;
        case "MODULES":
            if ($_SESSION["validuser"] && $_SESSION["isadmin"]) {
                $module->_module($_POST,$_GET, $HTTP_POST_FILES, $_SESSION["validuser"], $_SESSION["isadmin"]);
            } else {
                print "<font color='red'>You have no authorization for this page.</font>";
            }
		            break;
        default:
            if ($errorinfo) {
                // since this the default page errors appear here
                // after page refresh
                // error codes are stored in errorcodes table
                $module->error_message($errorinfo);
            }
            if (module::in_menu($_GET["page"],array_values($menu_array[0]))) {
                $module->default_action($_GET["page"]);
            } else {
                $site->content($menu_id, $_POST, $_GET);

            }

        }
    }
    ?>
    </blockquote>
    <br/>
    </td>
    </tr>
</table>
<br/>
<div align="center" class='copyright'>
  &copy;2004-2009 Generic Architecture for Modular Enterprise (GAME) Engine Version <?=$module->get_version()?> Herman Tolentino MD / UPCM Medical Informatics Unit / License - GPL<br>
</div>
</body>
</html>
<?
//phpinfo();
ob_end_flush();
?>

