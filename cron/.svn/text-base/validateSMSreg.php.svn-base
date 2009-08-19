<?php
// Module: validation module for SMS user registration
// Location: included from cronsmsdigest.php
// Author: Herman Tolentino MD
$intuid = (integer) $uid;
if (strlen(trim($uid))==3 && is_int($intuid)) {
    $validuid = $intuid;
} else {
    $errmsg = $errmsg . "Err R1: Invalid userid. ";
}
$namearray = explode(",", $name);
if (strlen($name)>0 && count($namearray)>1) {
    $lastname = $namearray[0];
    $firstname = $namearray[1];
} else {
    $errmsg = $errmsg . "Err R2: Invalid name. ";
}
$intpin = (integer) $pin;
if (strlen($pin)==4 && is_int($intpin)) {
        $validpin = $pin;
} else {
        $errmsg = $errmsg . "Err R3: Invalid PIN-01. ";
}
// check if PIN exists
$sql_select = "select pin from login where pin='$pin'";
if ($result_select = mysql_query($sql_select)) {
        if (mysql_num_rows($result_pin)) {
                $errmsg = $errmsg . "Err R3: Invalid PIN-02. ";
        }
}
//if (strlen($email)>0 && ereg("@", $email)) {
//        $validemail = $email;
//} else {
//        $errmsg = $errmsg . "Err R4: Invalid email. ";
//}
if (strlen(trim($login))==0 || strlen(trim($password))==0) {
        $errmsg = $errmsg . "Err R5: Invalid login/password. ";
}
if (strlen(trim($authcode))>0 && $authcode<>substr(md5(date("Y-m-d")),0,4)) {
        $errmsg = $errmsg . "Err R6: Invalid auth code. ";
}
$sql_cell = "select cellphone from login where cellphone = '$senderstring'";
if ($result_cell = mysql_query($sql_cell)) {
        if (mysql_num_rows($result_cell)) {
                $errmsg = $errmsg . "Err R7: Invalidg cell number. ";
        }
}
?>
