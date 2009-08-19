<?php
//      SMS THRESHOLD MODULE
//
//      Author: Herman Tolentino MD
//      July 2003
//      Copyright 2003 All rights reserved
//      Version 0.50
//
//      This module runs as a cron job and performs the following:
//
//      1. reads dayevent and rawdata table for threshold breaches
//      2. forms SMS feedback message
//      3. sends the SMS messages for CQI committee
//
$cqipath = "/home/herman/public_html/cqi";
include "$cqipath/conn.php";
include "$cqipath/functions.php";
$sqltoday = date("Y-m-d");
$asoftoday = date("h:mA, M j, Y D");
$timefilename = date("Ymd").".".date("His");

//  THRESHOLD BREACH NOTIFICATION
//

// threshold monitor
// monitors thresholds every 15 minutes
$sql_threshold = "select d.eventid, sum(d.count) from dayevent d, cqievents e where e.eventid = d.eventid and d.count >= e.threshold and d.eventid <> 'z1' and d.eventid <> 'Z1' and d.casedate = '$sqltoday' group by d.eventid";
if ($result_threshold = mysql_query($sql_threshold, $mysql_conn)) {
    if (mysql_num_rows($result_threshold)) {
        while (list($eventid, $eventcount) = mysql_fetch_array($result_threshold)) {
            $sql_check = "select * from thresholdnotify where reportdate = '$sqltoday' and eventid = '$eventid' and count = $eventcount";
            if ($result_check = mysql_query($sql_check, $mysql_conn)) {
                if (!mysql_num_rows($result_check)) {
                    // sent record already so create new one
                    $sql_insert = "insert into thresholdnotify (reportdate, eventid, count) values ('$sqltoday', '$eventid', $eventcount)";
                    $result = mysql_query($sql_insert, $mysql_conn);
                }
            }
        }
    }
}

$sql_notify = "select reportdate, eventid, count from thresholdnotify where notifysent='N' and reportdate = '$sqltoday'";
if ($result_notify = mysql_query($sql_notify)) {
    if (mysql_num_rows($result_notify)) {
        while (list($reportdate, $eventid, $eventcount) = mysql_fetch_array($result_notify)) {
            // compose SMS message
            $message = $message."$eventid=$eventcount ";
        }
        if ($message) {
            $message = "CQI Server -- URGENT: $asoftoday, ".$message;
        }
    }
    if ($message) {
        // if there is a message send it
        $sql_send = "select l.cellphone, l.firstname from notify n, login l where n.userid = l.userid and l.isactive = 'Y'";
        if ($result_send = mysql_query($sql_send)) {
            if (mysql_num_rows($result_send)) {
                while (list($cellphone, $firstname) = mysql_fetch_array($result_send)) {
                    $tmpfname = tempnam ("$cqipath/gsm/out/", "THRESHOLD-$timefilename-");
                    $fp = fopen($tmpfname, "w");
                    $sms = "NUMBER: $cellphone\n$message";
                    fwrite($fp, $sms);
                    fclose($fp);
                }
                $sql_update = "update thresholdnotify set notifysent='Y' where notifysent = 'N' and reportdate = '$sqltoday'";
                $result_update = mysql_query($sql_update, $mysql_conn);
            }
        }
    }
}

?>
