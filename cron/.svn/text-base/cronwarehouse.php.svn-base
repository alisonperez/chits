#!/usr/bin/php
<?php
//      DATA WAREHOUSE ETL
//
//      Author: Herman Tolentino MD
//      July 2003
//      Copyright 2003 All rights reserved
//
//      This module runs as a cron job and performs the following:
//
//      1. gathers data for individual compliance, cost, performance
//      2. saves data in warehouse tables
//
$cqipath = "/home/herman/public_html/cqi";
include "$cqipath/conn.php";
include "$cqipath/functions.php";
$sqltoday = date("Y-m-d");
$asoftoday = date("h:mA, M j, Y D");
$timefilename = date("Ymd").".".date("His");
$span_days = 30;
$reminder_threshold = 4; // 4 days of no submission

$sql_days = "select to_days('$sqltoday')";
if ($result_days = mysql_query($sql_days, $mysql_conn)) {
    if (mysql_num_rows($result_days)) {
        list($date_to_days) = mysql_fetch_array($result_days);
    }
}
//
// COMPLIANCE
//

// delete last $span_days days
$sql_delete = "delete from usercompliance where to_days(reportdate) > $date_to_days-$span_days";
$result_delete = mysql_query($sql_delete, $mysql_conn);

// loop for last $span_days days
for ($i=$date_to_days - $span_days; $i<=$date_to_days; $i++) {
    //
    // checking is by the day for the past 30 days
    //
    $sql_reverse = "select from_days($i)";
    if ($result_reverse = mysql_query($sql_reverse, $mysql_conn)) {
        if (mysql_num_rows($result_reverse)) {
            list($fromdays) = mysql_fetch_array($result_reverse);
        }
    }
    print "\nDAY $i: $fromdays\n";
    $sql_users = "select firstname, lastname, userid, cellphone, email, batch from login where userid <> 999 and isactive = 'Y'";
    if ($result_users = mysql_query($sql_users, $mysql_conn)) {
        if (mysql_num_rows($result_users)){
            while (list($firstname, $lastname, $userid, $cellphone, $email, $batch) = mysql_fetch_array($result_users)) {

                print trim(ucwords("$firstname $lastname"));
                // store compliance information
                // 1. check for each user daily record from rawdata
                // 2. if no record check with nocase
                // 3. insert appropriate entry in warehouse table: usercompliance
                $sql_rawdata = "select reportdate, to_days(entrydate), to_days(reportdate), filename from rawdata where to_days(reportdate) = $i and cellnumber = '$cellphone'";
                if ($result_rawdata = mysql_query($sql_rawdata, $mysql_conn)) {
                    if (mysql_num_rows($result_rawdata)) {
                        // there is rawdata = there is case, compliant
                        $iscompliant = true;
                        $isnocase = false;
                        unset($cases);
                        unset($nocase);
                        unset($ontime);
                        unset($onedaylate);
                        unset($twodayslate);
                        unset($moredayslate);
                        unset($smsmessages);
                        $cases = 0;
                        $nocase = 0;
                        $ontime = 0;
                        $onedaylate = 0;
                        $twodayslate = 0;
                        $moredayslate = 0;
                        $smsmessages = 0;
                        while (list($reportdate, $daysentry, $daysreport, $filename) = mysql_fetch_array($result_rawdata)) {
                            $cases++;
                            $dayslate = $daysentry-$daysreport;
                            if ($dayslate==0) {
                                $ontime++;
                            } elseif ($dayslate==1) {
                                $onedaylate++;
                            } elseif ($dayslate==2) {
                                $twodayslate++;
                            } else {
                                $moredayslate++;
                            }
                            if ($filename<>'web') {
                                $smsmessages++;
                            }
                        }
                        print " $cases submissions ontime=$ontime, one day late = $onedaylate, twodayslate = $twodayslate, more than two days=$moredayslate\n";
                        $sql_insert = "insert into usercompliance (userid, batch, submissions, reportdate, weekid, yearid, nocase, ontime, onedaylate, twodayslate, moredayslate) ".
                                      "values ($userid, '$batch', $cases, from_days($i), week(from_days($i)), year(from_days($i)), 'N', $ontime, $onedaylate, $twodayslate, $moredayslate)";
                        $result_insert = mysql_query($sql_insert, $mysql_conn);
                    } else {
                        // there is no rawdata, look for nocase submissions
                        $sql_nocase = "select count(casedate) from nocase where cellphone = '$cellphone' and to_days(casedate) = $i";
                        if ($result_nocase = mysql_query($sql_nocase, $mysql_conn)) {
                            if (mysql_num_rows($result_nocase)) {
                                // there is nocase submission, compliant
                                list($nocase) = mysql_fetch_array($result_nocase);
                                if ($nocase==0) {
                                    $iscompliant = false;
                                    $isnocase = false;
                                    print " 0 submission-1\n";
                                    $sql_insert = "insert into usercompliance (userid, batch, submissions, reportdate, weekid, yearid) ".
                                                  "values ($userid, '$batch', 0, from_days($i), week(from_days($i)), year(from_days($i)))";
                                    $result_insert = mysql_query($sql_insert, $mysql_conn);
                                } else {
                                    $iscompliant = true;
                                    $isnocase = false;
                                    print " $nocase submissions nocase\n";
                                    $sql_insert = "insert into usercompliance (userid, batch, submissions, reportdate, weekid, yearid, nocase) ".
                                                  "values ($userid, '$batch', $nocase, from_days($i), week(from_days($i)), year(from_days($i)), 'Y')";
                                    $result_insert = mysql_query($sql_insert, $mysql_conn);
                                }
                            } else {
                                $iscompliant = false;
                                $isnocase = false;
                                $nocase = 0;
                                print " 0 submission-2\n";
                                $sql_insert = "insert into usercompliance (userid, batch, submissions, reportdate, weekid, yearid) ".
                                              "values ($userid, '$batch', 0, from_days($i), week(from_days($i)), year(from_days($i)))";
                                $result_insert = mysql_query($sql_insert, $mysql_conn);
                            }
                        }
                    }
                }
            }
        }
    } // $sql_users

} // for loop

// send compliance reminders
$sql_reminders = "select l.lastname, l.firstname, l.cellphone, count(c.reportdate) from usercompliance c, login l where l.userid = c.userid and week(c.reportdate) = week(sysdate()) and c.submissions=0 and l.reminder='Y' group by l.userid";
if ($result_reminders = mysql_query($sql_reminders, $mysql_conn)) {
    if (mysql_num_rows($result_reminders)) {
        while (list($last, $first, $cell, $nosubmission) = mysql_fetch_array($result_reminders)) {
            if ($nosubmission > $reminder_threshold) {
                $greeting = "CQI Server DO NOT REPLY. Good PM $first! ";
                // if no submissions for this week > 3x send reminder
                print $message = $greeting."Beginning Sunday this week, have you forgotten to submit CQI data? A friendly reminder Boots Health Care, our CQI sponsor.\n";
                $tmpfname = tempnam ("$cqipath/gsm/out/", "COMPLIANCE-$timefilename-");
                if ($fp = fopen($tmpfname, "w")) {
                    $sms = "NUMBER: $cell\n$message";
                    if (fwrite($fp, $sms)) {
                        $cost = 0;
                        $prefix = substr($cell, 0, 6);
                        print $sql_cost = "select cost from gsmnetwork where prefix = '$prefix'\n";
                        if ($result_cost = mysql_query($sql_cost)) {
                            if (mysql_num_rows($result_cost)) {
                                list($message_cost) = mysql_fetch_array($result_cost);
                                $sql_insert = "insert into systemaccount (senddate, cellnumber, messagetype, messagecost) ".
                                              "values (sysdate(), '$cell', 'compliance', $message_cost)";
                                $result_insert = mysql_query($sql_insert, $mysql_conn);
                            }
                        }
                        fclose($fp);
                    }
                } else {
                    print "SMS failed.";
                }
            }
        }
    }
}
//
// ACCOUNTING
//

// delete last $span_days days
$sql_delete = "delete from useraccount where to_days(reportdate) > $date_to_days-$span_days";
$result_delete = mysql_query($sql_delete, $mysql_conn);

// loop for last $span_days days
for ($i=$date_to_days-$span_days; $i<=$date_to_days; $i++) {
    //
    // checking is by the day for the past 30 days
    //
    $sql_reverse = "select from_days($i)";
    if ($result_reverse = mysql_query($sql_reverse, $mysql_conn)) {
        if (mysql_num_rows($result_reverse)) {
            list($fromdays) = mysql_fetch_array($result_reverse);
        }
    }
    print "\nDAY $i: $fromdays\n";
    $sql_users = "select firstname, lastname, userid, cellphone, email, batch from login where userid <> 999";
    if ($result_users = mysql_query($sql_users, $mysql_conn)) {
        if (mysql_num_rows($result_users)){
            while (list($firstname, $lastname, $userid, $cellphone, $email, $batch) = mysql_fetch_array($result_users)) {

                print trim(ucwords("$firstname $lastname"))."\n";
                // store accounting information
                // 1. check for each user daily record from rawdata
                // 2. if no record check with nocase
                // 3. insert appropriate entry in warehouse table: useraccount
                $sql_rawdata = "select cellnumber from rawdata where to_days(reportdate) = $i and cellnumber = '$cellphone' and filename <> 'web'";
                if ($result_rawdata = mysql_query($sql_rawdata, $mysql_conn)) {
                    if (mysql_num_rows($result_rawdata)) {
                        while (list($cellnumber) = mysql_fetch_array($result_rawdata)) {
                            $cost = 0;
                            $prefix = substr($cellnumber, 0, 6);
                            print $sql_cost = "select cost, networkname from gsmnetwork where prefix = '$prefix'\n";
                            if ($result_cost = mysql_query($sql_cost)) {
                                if (mysql_num_rows($result_cost)) {
                                    list($message_cost, $networkname) = mysql_fetch_array($result_cost);
                                    $sql_insert = "insert into useraccount (userid, reportdate, weekid, yearid, quarter, cost, cellnumber, gsmnetwork, batch) ".
                                                  "values ($userid, from_days($i), week(from_days($i)), year(from_days($i)), quarter(from_days($i)), $message_cost, '$cellnumber', '$networkname', '$batch')";
                                    $result_insert = mysql_query($sql_insert, $mysql_conn);
                                }
                            }
                        }
                    } else {
                        // there is no rawdata, look for nocase submissions
                        $sql_nocase = "select cellphone from nocase where cellphone = '$cellphone' and to_days(casedate) = $i";
                        if ($result_nocase = mysql_query($sql_nocase, $mysql_conn)) {
                            if (mysql_num_rows($result_nocase)) {
                                // there is nocase submission, compliant
                                while (list($cellnumber) = mysql_fetch_array($result_nocase)) {
                                    $cost = 0;
                                    $prefix = substr($cellnumber, 0, 6);
                                    print $sql_cost = "select cost, networkname from gsmnetwork where prefix = '$prefix'\n";
                                    if ($result_cost = mysql_query($sql_cost)) {
                                        if (mysql_num_rows($result_cost)) {
                                            list($message_cost, $networkname) = mysql_fetch_array($result_cost);
                                            $sql_insert = "insert into useraccount (userid, reportdate, weekid, yearid, quarter, cost, cellnumber, nocase, gsmnetwork, batch) ".
                                                          "values ($userid, from_days($i), week(from_days($i)), year(from_days($i)), quarter(from_days($i)), $message_cost, '$cellnumber', 'Y', '$networkname', '$batch')";
                                            $result_insert = mysql_query($sql_insert, $mysql_conn);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    } // $sql_users

} // for loop

//
// CRITICAL EVENTS
//
// delete last $span_days days
$sql_delete = "delete from userevent where to_days(reportdate) > $date_to_days-$span_days";
$result_delete = mysql_query($sql_delete, $mysql_conn);

// loop for last $span_days days
for ($i=$date_to_days-$span_days; $i<=$date_to_days; $i++) {
    //
    // checking is by the day for the past 30 days
    //
    $sql_reverse = "select from_days($i)";
    if ($result_reverse = mysql_query($sql_reverse, $mysql_conn)) {
        if (mysql_num_rows($result_reverse)) {
            list($fromdays) = mysql_fetch_array($result_reverse);
        }
    }
    print "\nDAY $i: $fromdays\n";
    $sql_users = "select firstname, lastname, userid, cellphone, email, batch from login where userid <> 999";
    if ($result_users = mysql_query($sql_users, $mysql_conn)) {
        if (mysql_num_rows($result_users)){
            while (list($firstname, $lastname, $userid, $cellphone, $email, $batch) = mysql_fetch_array($result_users)) {
                print trim(ucwords("$firstname $lastname"));
                // store critical event information
                // 1. check for each user daily record from rawdata
                // 3. insert appropriate entry in warehouse table: userevent
                $sql_rawdata = "select reportdate, entrydate, locid, slot, events from rawdata where to_days(reportdate) = $i and cellnumber = '$cellphone'\n";
                if ($result_rawdata = mysql_query($sql_rawdata, $mysql_conn)) {
                    if (mysql_num_rows($result_rawdata)) {
                        while (list($reportdate, $entrydate, $location, $slot, $events) = mysql_fetch_array($result_rawdata)) {
                            $eventlist = explode(",", $events);
                            //print_r($eventlist);
                            print "count: ".count($eventlist)."\n";
                            for($x=0; $x < count($eventlist); $x++) {
                                $eventitem = trim($eventlist[$x]);
                                $sql_insert = "insert into userevent (userid, batch, reportdate, entrydate, eventid, slot, location, weekid, yearid, quarter) ".
                                              "values ($userid, '$batch', from_days($i), '$entrydate', '$eventitem', $slot, '$location', week(from_days($i)), year(from_days($i)), quarter(from_days($i)))";
                                $result_insert = mysql_query($sql_insert, $mysql_conn);
                            }
                        }
                    }
                }
            }
        }
    } // $sql_users

} // for loop

?>
