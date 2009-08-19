<?php
//      SMS DATA EXTRACTION MODULE
//
//      Author: Herman Tolentino MD
//      February 2003
//      Copyright 2003 All rights reserved
//      Version 0.50
//
//      This module runs as a cron job and performs the following:
//      1. reads the SMS inbox for text files
//      2. validates each entry
//      3. saves validated records in the database
//      4. moves saved records in the in.handled directory
//      5. receives SMS user registration
//      6. receives auth code requests and sends auth code
//
// path where scripts are
$cqipath = "/home/herman/public_html/cqi";
$sqltoday = date("Y-m-d");
$asoftoday = date("H:m A, M j, Y D");
$timefilename = date("Ymd").".".date("His");

include "$cqipath/conn.php";
include "$cqipath/functions.php";

// read everything in IN directory
$handle = opendir("$cqipath/gsm/in");

while (false !== ($file = readdir($handle))) {
    if ($file!="." && $file!="..") {

        // read each file as array
        $lines = file("$cqipath/gsm/in/$file");

        //      Each file has the following format:
        //
        //      LINE 1          1. Inbox Message (unread)
        //      LINE 2          Date/time: 27/02/2003 10:53:53 +0800
        //      LINE 3          Sender: +639189214108 Msg Center: +639180000125
        //      LINE 4          Text:
        //      LINE 5          1234*20030227*nocase

        // remember array index starts with 0
        // skip LINE 1
        foreach($lines as $key => $value) {
            switch ($key) {
            case 1: // date/time

                //$datestring = str_replace("Date/time: ", "", $lines[$key]);
                //$datestring = str_replace(" +0800", "", $datestring);
                list($xx, $datestring, $timestring) = explode(" ", $lines[$key]);
                print "$datestring $timestring\n";
                break;

            case 2: //sender

                list($xx, $senderstring, ) = explode(" ", $lines[$key]);
                print "$senderstring\n";
                break;

            case 4: // sms data

                //
                //      Process SMS message here
                //      rtrim needed because of newline !
                //      break up SMS message by asterisk
                //
                list($pin, $reportdate, $location, $slot, $eventlist) = explode("*", rtrim($lines[$key]," \n\t"));

                //
                //        SMS Server communication section
                //        Establish client-server communications
                //        Look at first string to find out what  text is about
                //

                switch ($pin) {
                case "HELPREG":
                    //      SMS help for mobile registration
                    $message = "CQI Server: Registration format REGUSER*userid*lastname, firstname*PIN*email*login*password*authcode, obtain authcode from CQI committee member";
                    $tmpfname = tempnam ("$cqipath/gsm/out/", "HELPREG-$timefilename-");
                    $fp = fopen($tmpfname, "w");
                    $sms = "NUMBER: $senderstring\n$message";
                    if (fwrite($fp, $sms)) {
                        fclose($fp);
                        $cost = 0;
                        $prefix = substr($senderstring, 0, 6);
                        print $sql_cost = "select cost from gsmnetwork where prefix = '$prefix'\n";
                        if ($result_cost = mysql_query($sql_cost)) {
                            if (mysql_num_rows($result_cost)) {
                                list($message_cost) = mysql_fetch_array($result_cost);
                                $sql_insert = "insert into systemaccount (senddate, cellnumber, messagetype, messagecost) ".
                                              "values (sysdate(), '$senderstring', 'helpreg', $message_cost)";
                                $result_insert = mysql_query($sql_insert, $mysql_conn);
                            }
                        }
                    }
                    break;

                case "HELPSUBMIT":
                    //      SMS help for submission format
                    $sql_auth = "select cellphone from login where cellphone = '$senderstring' and isactive = 'Y'";
                    if ($result_auth = mysql_query($sql_auth)) {
                        if (mysql_num_rows($result_auth)) {
                            // message below is 45 characters
                            print $message = "CQI SMS format PIN*YYYYMMDD*location*caseno*eventlist, caseno=case numerical order, eventlist=list of event codes. For no case submit NOCASE*PIN.";
                            $tmpfname = tempnam ("$cqipath/gsm/out/", "HELPSUBMIT-$timefilename-");
                            $fp = fopen($tmpfname, "w");
                            $sms = "NUMBER: $senderstring\n$message";
                            if (fwrite($fp, $sms)) {
                                $cost = 0;
                                $prefix = substr($senderstring, 0, 6);
                                print $sql_cost = "select cost from gsmnetwork where prefix = '$prefix'\n";
                                if ($result_cost = mysql_query($sql_cost)) {
                                    if (mysql_num_rows($result_cost)) {
                                        list($message_cost) = mysql_fetch_array($result_cost);
                                        $sql_insert = "insert into systemaccount (senddate, cellnumber, messagetype, messagecost) ".
                                                      "values (sysdate(), '$senderstring', 'helpsubmit', $message_cost)";
                                        $result_insert = mysql_query($sql_insert, $mysql_conn);
                                    }
                                }
                                fclose($fp);
                            }

                            print $sql_upd = "update smsusage set requests = requests+1 where reportdate = '$sqltoday' and cellphone = '$senderstring'";
                            $result_upd = mysql_query($sql_upd);
                            print $sql_ins = "insert into smsusage (reportdate, cellphone, requests) values ('$sqltoday', '$senderstring', 1)";
                            $result_ins = mysql_query($sql_ins);


                        }
                    }
                    break;

                case "REQAUTH":
                    //      SMS authentication code request
                    list($xx, $pin) = explode("*", rtrim($lines[$key]," \n\t"));
                    $sql_auth = "select cellphone, pin from  login where isadmin='Y' and isactive = 'Y' and cellphone = '$senderstring' and pin = '$pin'";
                    if ($result_auth = mysql_query($sql_auth)) {
                        if (mysql_num_rows($result_auth)) {
                            $authcode = substr(md5(date("Y-m-d")),0,4);
                            print $message = "CQI Server: AUTH CODE today -- $authcode.";
                            $tmpfname = tempnam ("$cqipath/gsm/out/", "REQAUTH-$timefilename-");
                            $fp = fopen($tmpfname, "w");
                            $sms = "NUMBER: $senderstring\n$message";
                            if (fwrite($fp, $sms)) {
                                $cost = 0;
                                $prefix = substr($senderstring, 0, 6);
                                print $sql_cost = "select cost from gsmnetwork where prefix = '$prefix'\n";
                                if ($result_cost = mysql_query($sql_cost)) {
                                    if (mysql_num_rows($result_cost)) {
                                        list($message_cost) = mysql_fetch_array($result_cost);
                                        $sql_insert = "insert into systemaccount (senddate, cellnumber, messagetype, messagecost) ".
                                                      "values (sysdate(), '$senderstring', 'reqauth', $message_cost)";
                                        $result_insert = mysql_query($sql_insert, $mysql_conn);
                                    }
                                }
                                fclose($fp);
                            }

                            print $sql_upd = "update smsusage set requests = requests+1 where reportdate = '$sqltoday' and cellphone = '$senderstring'";
                            $result_upd = mysql_query($sql_upd);
                            print $sql_ins = "insert into smsusage (reportdate, cellphone, requests) values ('$sqltoday', '$senderstring', 1)";
                            $result_ins = mysql_query($sql_ins);

                        }
                    }
                    break;
                case "NOCASE":
                    list($xx, $pin) = explode("*", rtrim($lines[$key]," \n\t"));
                    $sql_auth = "select cellphone, pin, firstname from login where isadmin='Y' and isactive = 'Y' and cellphone = '$senderstring' and pin = '$pin'";
                    if ($result_auth = mysql_query($sql_auth)) {
                        if (mysql_num_rows($result_auth)) {
                            // proceed with database action
                            $sql_insert = "insert into nocase (cellphone, casedate) values ('$senderstring', '$sqltoday')";
                            if ($result_insert = mysql_query($sql_insert)) {

                                print $sql_upd = "update smsusage set accepted = accepted+1 where reportdate = '$sqltoday' and cellphone = '$senderstring'";
                                $result_upd = mysql_query($sql_upd);
                                print $sql_ins = "insert into smsusage (reportdate, cellphone, accepted) values ('$sqltoday', '$senderstring', 1)";
                                $result_ins = mysql_query($sql_ins);

                            }

                        }
                    }
                    break;

                case "REQSTATS":
                    //      SMS stats request
                    list($xx, $pin) = explode("*", rtrim($lines[$key]," \n\t"));
                    $sql_auth = "select cellphone, pin, firstname from login where isadmin='Y' and isactive = 'Y' and cellphone = '$senderstring' and pin = '$pin'";
                    if ($result_auth = mysql_query($sql_auth, $mysql_conn)) {
                        if (mysql_num_rows($result_auth)) {
                            list($userphone, $userpin, $userfname) = mysql_fetch_array($result_auth);
                            //
                            // this section is the same as in cronsmsfeedback.php
                            // different only in $greeting and iteration through CQI Committee users
                            //
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
                                $greeting = "CQI Server -- Good Day ";
                                if (mysql_num_rows($result_summary)) {
                                    list($cases, $events) = mysql_fetch_array($result_summary);
                                    $message = "! As of $asoftoday the dept reported $events ".($events==1?"event":"events")." in $cases ".($cases==1?"case":"cases").". Total cases = $totalcases. Compliance = $compliancerate.";
                                } else {
                                    $message = "! As of $asoftoday there are no reported cases in the dept.";
                                }
                                $sendmessage = $greeting.$userfname.$message;
                                $tmpfname = tempnam ("$cqipath/gsm/out/", "REQSTATS-$timefilename-");
                                $fp = fopen($tmpfname, "w");
                                $sms = "NUMBER: $senderstring\n$sendmessage";
                                if (fwrite($fp, $sms)) {
                                    $cost = 0;
                                    $prefix = substr($senderstring, 0, 6);
                                    print $sql_cost = "select cost from gsmnetwork where prefix = '$prefix'\n";
                                    if ($result_cost = mysql_query($sql_cost)) {
                                        if (mysql_num_rows($result_cost)) {
                                            list($message_cost) = mysql_fetch_array($result_cost);
                                            $sql_insert = "insert into systemaccount (senddate, cellnumber, messagetype, messagecost) ".
                                                           "values (sysdate(), '$senderstring', 'helpsubmit', $message_cost)";
                                            $result_insert = mysql_query($sql_insert, $mysql_conn);
                                        }
                                    }
                                    fclose($fp);
                                }

                                $sms_usage = "select reportdate from smsusage where reportdate = '$sqltoday' and cellphone='$senderstring'";
                                if ($result_usage = mysql_query($sql_usage)) {
                                    if (mysql_num_rows($result_usage)) {
                                        $sql_upd = "update smsusage set requests = requests+1 where reportdate = '$sqltoday' and cellphone = '$senderstring'";
                                        $result_upd = mysql_query($sql_upd);
                                    } else {
                                        $sql_ins = "insert into smsusage (reportdate, cellphone, requests) values ('$sqltoday', '$senderstring', 1)";
                                        $result_ins = mysql_query($sql_ins);
                                    }
                                }

                            }
                        }
                    }
                    break;

                case "REGUSER":

                    //
                    //      SMS registration module
                    //      SMS format:
                    //              REGUSER*userid*lastname, firstname*pin*email*login*password*authcode
                    //                      userid = department supplied
                    //                      pin = user supplied 4-digit PIN
                    //                      authcode = look for this under user admin, changes per day
                    //                                              OR send REQAUTH sms (see above)
                    //      Example:
                    //              REGUSER*411*dela Cruz, Juan*1245*juan@yahoo.com*juan*juan*b582
                    //

                    // initialize error variable
                    unset($errmsg);

                    // break up SMS message string by asterisk
                    list($xx, $uid, $name, $pin, $email, $login, $password, $authcode) = explode("*", rtrim($lines[$key]," \n\t"));

                    // validate entries
                    include "$cqipath/cron/validateSMSreg.php";

                    if (!$errmsg) {
                        if (strlen(trim($email))==0) {
                            $email = 'none';
                        }
                        print $sql_insert = "insert into login (userid, login, password, firstname, lastname, email, pin, cellphone, reminder) ".
                                "values ($validuid, '".trim($login)."', password('".trim($password)."'), '".ucfirst(strtolower(trim($firstname)))."', '".ucfirst(strtolower(trim($lastname)))."', '".strtolower(trim($email))."', '$validpin', '$senderstring', 'Y')";
                        if ($result_insert = mysql_query($sql_insert)) {
                            // send confirmation SMS
                            print $message = "CQI SERVER: Welcome and good ".date("A")." $firstname! Your PIN is $validpin.";
                        } else {
                            print $message = "CQI SERVER: Registration error: duplicate PIN or cellnumber.";
                        }
                    } else {
                        print $message = "CQI SERVER -- Registration errors: ".$errmsg;
                    }
                    $tmpfname = tempnam ("$cqipath/gsm/out/", "REGUSER-$timefilename-");
                    $fp = fopen($tmpfname, "w");
                    $sms = "NUMBER: $senderstring\n$message";
                    if (fwrite($fp, $sms)) {
                        $cost = 0;
                        $prefix = substr($senderstring, 0, 6);
                        print $sql_cost = "select cost from gsmnetwork where prefix = '$prefix'\n";
                        if ($result_cost = mysql_query($sql_cost)) {
                            if (mysql_num_rows($result_cost)) {
                                list($message_cost) = mysql_fetch_array($result_cost);
                                $sql_insert = "insert into systemaccount (senddate, cellnumber, messagetype, messagecost) ".
                                              "values (sysdate(), '$senderstring', 'helpsubmit', $message_cost)";
                                $result_insert = mysql_query($sql_insert, $mysql_conn);
                            }
                        }
                        fclose($fp);
                    }

                    print $sql_upd = "update smsusage set requests = requests+1 where reportdate = '$sqltoday' and cellphone = '$senderstring'";
                    $result_upd = mysql_query($sql_upd);
                    print $sql_ins = "insert into smsusage (reportdate, cellphone, requests) values ('$sqltoday', '$senderstring', 1)";
                    $result_ins = mysql_query($sql_ins);

                    break;

                default:

                    // initialize error variable
                    unset($errmsg);

                    // validate everything here against database
                    include "$cqipath/validateSMS.php";


                    //
                    //      if valid, save in table rawdata
                    //      save at this stage because file is read line by line and this is the last line!
                    //      VARIABLES:
                    //      $location = location code
                    //      $reportdate = date when case was done
                    //      $pin = user PIN
                    //      $demog = demographics
                    //      $eventlist = CQI event list
                    //      $senderstring = cellular number of sender
                    //      $file = filename being read
                    //

                    if (!$errmsg) {
                        // check location mispelling
                        // seen in SMS entries only!!!
                        if ($location=='0t') {
                            $location = 'ot';
                        } elseif ($location=='0r') {
                            $location = 'or';
                        }
                        if ($location && $reportdate && $pin && $slot && $eventlist && $senderstring) {
                            // all records without error are saved in the rawdata table
                            // including zero reporting to get complete number of cases
                            $sql_insert = "insert into rawdata (entrydate, locid, reportdate, pin, slot, events, cellnumber, filename) ".
                                    "values (sysdate(), '".strtolower($location)."', '$reportdate', '$pin', '$slot', '".strtolower($eventlist)."', '$senderstring', '$file')";
                            mysql_transaction();
                            if ($result_insert = mysql_query($sql_insert)) {

                                print $sql_upd = "update smsusage set accepted = accepted+1 where reportdate = '$sqltoday' and cellphone = '$senderstring'";
                                $result_upd = mysql_query($sql_upd);
                                print $sql_ins = "insert into smsusage (reportdate, cellphone, accepted) values ('$sqltoday', '$senderstring', 1)";
                                $result_ins = mysql_query($sql_ins);

                                mysql_commit();
                                $evt = explode(",", $eventlist);
                                foreach ($evt as $key => $value) {
                                    print $sql_dayevt = "select cellphone from dayevent where casedate = '$sqldate' and eventid = '$value' and cellphone = '$senderstring'";
                                    if ($result_dayevt = mysql_query($sql_dayevt)) {
                                        if (mysql_num_rows($result_dayevt)) {
                                            // there is a record in dayevent table
                                            $sql_update = "update dayevent set count = count+1 where casedate = '$sqldate' and eventid = '$value' and cellphone = '$senderstring'";
                                            $result_update = mysql_query($sql_update);
                                        } else {
                                            // there is no record in dayevent table
                                            $sql_insert = "insert into dayevent (cellphone, casedate, eventid, count) values ('$senderstring', '$sqldate', '$value', 1)";
                                            $result_insert  = mysql_query($sql_insert);
                                        }
                                    }
                                }
                            }
                            unset($location);
                            unset($reportdate);
                            unset($pin);
                            unset($slot);
                            unset($eventlist);
                            unset($senderstring);
                        }
                    } else {
                        if ($senderstring) {
                            $sql_insert = "insert into baddata (entrydate, cellnumber, filename, baddata, errors) ".
                                "values (sysdate(), '$senderstring', '$file', '".$lines[4]."', '$errmsg')";
                            mysql_transaction();
                            if ($result_insert = mysql_query($sql_insert)) {
                                // send SMS message to inform sender about bad data
                                $sms = "CQI Server: CQI message you sent contained errors: ".rtrim($errmsg).". Please check and resend. Thanks!";
                                $tmpfname = tempnam ("$cqipath/gsm/out/", "ERRORMSG-$timefilename-");
                                $fp = fopen($tmpfname, "w");
                                print $sms = "NUMBER: $senderstring\n".$sms;
                                if (fwrite($fp, $sms)) {
                                    $cost = 0;
                                    $prefix = substr($senderstring, 0, 6);
                                    print $sql_cost = "select cost from gsmnetwork where prefix = '$prefix'\n";
                                    if ($result_cost = mysql_query($sql_cost)) {
                                        if (mysql_num_rows($result_cost)) {
                                            list($message_cost) = mysql_fetch_array($result_cost);
                                            $sql_insert = "insert into systemaccount (senddate, cellnumber, messagetype, messagecost) ".
                                                          "values (sysdate(), '$senderstring', 'helpsubmit', $message_cost)";
                                            $result_insert = mysql_query($sql_insert, $mysql_conn);
                                        }
                                    }
                                    fclose($fp);
                                }

                                print $sql_upd = "update smsusage set rejected = rejected+1 where reportdate = '$sqltoday' and cellphone = '$senderstring'";
                                $result_upd = mysql_query($sql_upd);
                                print $sql_ins = "insert into smsusage (reportdate, cellphone, rejected) values ('$sqltoday', '$senderstring', 1)";
                                $result_ins = mysql_query($sql_ins);

                                mysql_commit();
                            }
                            unset($senderstring);
                        }
                    }

                } // switch pin

                break;
            } // switch key
            //print "$key: $value";
        } // foreach

        //
        //         2-step move read file from IN to IN.HANDLED
        //
        $target = "$cqipath/gsm/in.handled/$file";
        if (copy("$cqipath/gsm/in/$file", "$cqipath/gsm/in.handled/$file")) {
            unlink("$cqipath/gsm/in/$file");
            `chown herman:apache $target`;
        }
    }
}
closedir($handle);
?>
