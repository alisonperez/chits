<?php
//      GNOKII PHONE STATUS MODULE
//
//      Author: Herman Tolentino MD
//      February 2003
//      Copyright 2003 All rights reserved
//      Version 0.50
//
//      This module runs as a cron job and performs the following:
//      1. checks phone status and saves status to database
//

// path where scripts are
$cqipath = "/home/herman/public_html/cqi";
$gnokii = "/usr/bin/gnokii";

include "$cqipath/conn.php";
include "$cqipath/functions.php";

print $reply = `$gnokii --identify`;
if (eregi("Nokia", $reply)) {
        print "success";
}
?>
