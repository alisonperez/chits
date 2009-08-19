<?
if ($_SESSION["user_lang"]) {
    $sql_lang = "select termid, langtext from terms where languageid = '".$_SESSION["user_lang"]."'";
} else {
    $sql_lang = "select termid, langtext from terms where languageid = 'en'";
}
if ($result_lang = mysql_query($sql_lang)) {
    if (mysql_num_rows($result_lang)) {
      while (list($constantname, $constantvalue) = mysql_fetch_array($result_lang)) {
        define("$constantname", "$constantvalue");
     }
  }
}
?>
