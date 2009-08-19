<?php
//      SMS FEEDBACK MODULE
//
//      Author: Herman Tolentino MD
//      February 2003
//      Copyright 2003 All rights reserved
//      Version 0.50
//
//      This module runs as a cron job and performs the following:
//
//      1. reads the MySQL database for all submissions on current day
//      2. forms SMS feedback message per user
//      3. sends the SMS messages as a batch
//
$cqipath = "/home/herman/public_html/cqi";
include "$cqipath/conn.php";
include "$cqipath/functions.php";
$sqltoday = date("Y-m-d");
$asoftoday = date("h:mA, M j, Y D");
$timefilename = date("Ymd").".".date("His");

// DAILY PERSONAL SUMMARY
// sent to all users

$sql_summary = "select l.firstname, e.casedate, l.cellphone, sum(e.count) from login l inner join dayevent e on e.cellphone = l.cellphone where l.isactive = 'Y' and e.casedate = '$sqltoday' group by e.cellphone";
if ($result_summary = mysql_query($sql_summary, $mysql_conn)) {
    if (mysql_num_rows($result_summary)) {
        while (list($first, $casedate, $cellnumber, $events) = mysql_fetch_array($result_summary)) {
            print $message = "CQI Server -- Good PM $first! As of $asoftoday you reported $events ".($events==1?"event":"events").". Thanks and keep it up!";
            $tmpfname = tempnam ("$cqipath/gsm/out/", "PERSONAL-$timefilename-");
            $fp = fopen($tmpfname, "w");
            $sms = "NUMBER: $cellnumber\n$message";
            fwrite($fp, $sms);
            fclose($fp);
        }
    }
}

//  DAILY ADMINISTRATIVE SUMMARY
//  sent to department chairman
//  currently hard coded
//
//  CQI COMMITTEE BRIEF


// compliance message
$sql_compliance = "select reportdate, (actualreporting/totalreporting) rate from compliance where reportdate = '$sqltoday'";
if ($result_compliance = mysql_query($sql_compliance, $mysql_conn)) {
    if (mysql_num_rows($result_compliance)) {
        list($casedate, $compliancerate) = mysql_fetch_array($result_compliance);
    }
}

// summary message
$sql_total = "select count(reportdate) from rawdata where reportdate = '$sqltoday'";
if ($result_total = mysql_query($sql_total, $mysql_conn)) {
    if (mysql_num_rows($result_total)) {
        list($totalcases) = mysql_fetch_array($result_total);
    }
}
$sql_summary = "select count(casedate) cases, sum(count) events from dayevent where casedate = '$sqltoday' group by casedate";
if ($result_summary = mysql_query($sql_summary, $mysql_conn)) {
    $greeting = "CQI Server -- Good PM ";
    if (mysql_num_rows($result_summary)) {
        list($cases, $events) = mysql_fetch_array($result_summary);
        $message = "! As of $asoftoday the dept reported $events ".($events==1?"event":"events")." in $cases ".($cases==1?"case":"cases").". Total cases = $totalcases. Compliance = $compliancerate.";
    } else {
        $message = "! As of $asoftoday there are no reported cases in the dept.";
    }
    $sql_notify = "select l.cellphone, l.firstname from notify n, login l where n.userid = l.userid and l.isactive = 'Y'";
    if ($result_notify = mysql_query($sql_notify, $mysql_conn)) {
        if (mysql_num_rows($result_notify)) {
            while (list($cellphone, $firstname) = mysql_fetch_array($result_notify)) {
                $sendmessage = $greeting.$firstname.$message;
                $tmpfname = tempnam ("$cqipath/gsm/out/", "COMMITTEE-$timefilename-");
                if ($fp = fopen($tmpfname, "w")) {
                    $sms = "NUMBER: $cellphone\n$sendmessage";
                    fwrite($fp, $sms);
                    fclose($fp);
                } else {
                    print "SMS failed.";
                }
            }
        } else {
            print "no rows.";
        }
    }
}



?>
