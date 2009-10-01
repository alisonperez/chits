<?
// BEGIN SERVER CODE: DO NOT EDIT
// Server generated code
// Generated 2009-10-01 10:14:06
// Module: _module.php
// Author: Herman Tolentino MD
//
if (file_exists('../modules/appointment/class.appointment.php')) {
	include '../modules/appointment/class.appointment.php';
	$appointment = new appointment;
	if (!$module->activated('appointment') && $initmod) {
		$appointment->init_sql();
		$appointment->init_menu();
		$appointment->init_deps();
		$appointment->init_lang();
		$appointment->init_help();
	}
}
if (file_exists('../modules/barangay/class.barangay.php')) {
	include '../modules/barangay/class.barangay.php';
	$barangay = new barangay;
	if (!$module->activated('barangay') && $initmod) {
		$barangay->init_sql();
		$barangay->init_menu();
		$barangay->init_deps();
		$barangay->init_lang();
		$barangay->init_help();
	}
}
if (file_exists('../modules/birthday/class.birthday.php')) {
	include '../modules/birthday/class.birthday.php';
	$birthday = new birthday;
	if (!$module->activated('birthday') && $initmod) {
		$birthday->init_sql();
		$birthday->init_menu();
		$birthday->init_deps();
		$birthday->init_lang();
		$birthday->init_help();
	}
}
if (file_exists('../modules/calendar/class.calendar.php')) {
	include '../modules/calendar/class.calendar.php';
	$calendar = new calendar;
	if (!$module->activated('calendar') && $initmod) {
		$calendar->init_sql();
		$calendar->init_menu();
		$calendar->init_deps();
		$calendar->init_lang();
		$calendar->init_help();
	}
}
if (file_exists('../modules/ccdev/class.ccdev.php')) {
	include '../modules/ccdev/class.ccdev.php';
	$ccdev = new ccdev;
	if (!$module->activated('ccdev') && $initmod) {
		$ccdev->init_sql();
		$ccdev->init_menu();
		$ccdev->init_deps();
		$ccdev->init_lang();
		$ccdev->init_help();
	}
}
if (file_exists('../modules/ccdev_report/class.ccdev_report.php')) {
	include '../modules/ccdev_report/class.ccdev_report.php';
	$ccdev_report = new ccdev_report;
	if (!$module->activated('ccdev_report') && $initmod) {
		$ccdev_report->init_sql();
		$ccdev_report->init_menu();
		$ccdev_report->init_deps();
		$ccdev_report->init_lang();
		$ccdev_report->init_help();
	}
}
if (file_exists('../modules/complaint/class.complaint.php')) {
	include '../modules/complaint/class.complaint.php';
	$complaint = new complaint;
	if (!$module->activated('complaint') && $initmod) {
		$complaint->init_sql();
		$complaint->init_menu();
		$complaint->init_deps();
		$complaint->init_lang();
		$complaint->init_help();
	}
}
if (file_exists('../modules/consult_report/class.consult_report.php')) {
	include '../modules/consult_report/class.consult_report.php';
	$consult_report = new consult_report;
	if (!$module->activated('consult_report') && $initmod) {
		$consult_report->init_sql();
		$consult_report->init_menu();
		$consult_report->init_deps();
		$consult_report->init_lang();
		$consult_report->init_help();
	}
}
if (file_exists('../modules/database/class.database.php')) {
	include '../modules/database/class.database.php';
	$database = new database;
	if (!$module->activated('database') && $initmod) {
		$database->init_sql();
		$database->init_menu();
		$database->init_deps();
		$database->init_lang();
		$database->init_help();
	}
}
if (file_exists('../modules/drug/class.drug.php')) {
	include '../modules/drug/class.drug.php';
	$drug = new drug;
	if (!$module->activated('drug') && $initmod) {
		$drug->init_sql();
		$drug->init_menu();
		$drug->init_deps();
		$drug->init_lang();
		$drug->init_help();
	}
}
if (file_exists('../modules/education/class.education.php')) {
	include '../modules/education/class.education.php';
	$education = new education;
	if (!$module->activated('education') && $initmod) {
		$education->init_sql();
		$education->init_menu();
		$education->init_deps();
		$education->init_lang();
		$education->init_help();
	}
}
if (file_exists('../modules/epi_report/class.epi_report.php')) {
	include '../modules/epi_report/class.epi_report.php';
	$epi_report = new epi_report;
	if (!$module->activated('epi_report') && $initmod) {
		$epi_report->init_sql();
		$epi_report->init_menu();
		$epi_report->init_deps();
		$epi_report->init_lang();
		$epi_report->init_help();
	}
}
if (file_exists('../modules/family/class.family.php')) {
	include '../modules/family/class.family.php';
	$family = new family;
	if (!$module->activated('family') && $initmod) {
		$family->init_sql();
		$family->init_menu();
		$family->init_deps();
		$family->init_lang();
		$family->init_help();
	}
}
if (file_exists('../modules/family_planning/class.family_planning.php')) {
	include '../modules/family_planning/class.family_planning.php';
	$family_planning = new family_planning;
	if (!$module->activated('family_planning') && $initmod) {
		$family_planning->init_sql();
		$family_planning->init_menu();
		$family_planning->init_deps();
		$family_planning->init_lang();
		$family_planning->init_help();
	}
}
if (file_exists('../modules/graph/class.graph.php')) {
	include '../modules/graph/class.graph.php';
	$graph = new graph;
	if (!$module->activated('graph') && $initmod) {
		$graph->init_sql();
		$graph->init_menu();
		$graph->init_deps();
		$graph->init_lang();
		$graph->init_help();
	}
}
if (file_exists('../modules/healthcenter/class.healthcenter.php')) {
	include '../modules/healthcenter/class.healthcenter.php';
	$healthcenter = new healthcenter;
	if (!$module->activated('healthcenter') && $initmod) {
		$healthcenter->init_sql();
		$healthcenter->init_menu();
		$healthcenter->init_deps();
		$healthcenter->init_lang();
		$healthcenter->init_help();
	}
}
if (file_exists('../modules/icd10/class.icd10.php')) {
	include '../modules/icd10/class.icd10.php';
	$icd10 = new icd10;
	if (!$module->activated('icd10') && $initmod) {
		$icd10->init_sql();
		$icd10->init_menu();
		$icd10->init_deps();
		$icd10->init_lang();
		$icd10->init_help();
	}
}
if (file_exists('../modules/imci/class.imci.php')) {
	include '../modules/imci/class.imci.php';
	$imci = new imci;
	if (!$module->activated('imci') && $initmod) {
		$imci->init_sql();
		$imci->init_menu();
		$imci->init_deps();
		$imci->init_lang();
		$imci->init_help();
	}
}
if (file_exists('../modules/injury/class.injury.php')) {
	include '../modules/injury/class.injury.php';
	$injury = new injury;
	if (!$module->activated('injury') && $initmod) {
		$injury->init_sql();
		$injury->init_menu();
		$injury->init_deps();
		$injury->init_lang();
		$injury->init_help();
	}
}
if (file_exists('../modules/injury_report/class.injury_report.php')) {
	include '../modules/injury_report/class.injury_report.php';
	$injury_report = new injury_report;
	if (!$module->activated('injury_report') && $initmod) {
		$injury_report->init_sql();
		$injury_report->init_menu();
		$injury_report->init_deps();
		$injury_report->init_lang();
		$injury_report->init_help();
	}
}
if (file_exists('../modules/lab/class.lab.php')) {
	include '../modules/lab/class.lab.php';
	$lab = new lab;
	if (!$module->activated('lab') && $initmod) {
		$lab->init_sql();
		$lab->init_menu();
		$lab->init_deps();
		$lab->init_lang();
		$lab->init_help();
	}
}
if (file_exists('../modules/language/class.language.php')) {
	include '../modules/language/class.language.php';
	$language = new language;
	if (!$module->activated('language') && $initmod) {
		$language->init_sql();
		$language->init_menu();
		$language->init_deps();
		$language->init_lang();
		$language->init_help();
	}
}
if (file_exists('../modules/mc/class.mc.php')) {
	include '../modules/mc/class.mc.php';
	$mc = new mc;
	if (!$module->activated('mc') && $initmod) {
		$mc->init_sql();
		$mc->init_menu();
		$mc->init_deps();
		$mc->init_lang();
		$mc->init_help();
	}
}
if (file_exists('../modules/mc_report/class.mc_report.php')) {
	include '../modules/mc_report/class.mc_report.php';
	$mc_report = new mc_report;
	if (!$module->activated('mc_report') && $initmod) {
		$mc_report->init_sql();
		$mc_report->init_menu();
		$mc_report->init_deps();
		$mc_report->init_lang();
		$mc_report->init_help();
	}
}
if (file_exists('../modules/news/class.news.php')) {
	include '../modules/news/class.news.php';
	$news = new news;
	if (!$module->activated('news') && $initmod) {
		$news->init_sql();
		$news->init_menu();
		$news->init_deps();
		$news->init_lang();
		$news->init_help();
	}
}
if (file_exists('../modules/notes/class.notes.php')) {
	include '../modules/notes/class.notes.php';
	$notes = new notes;
	if (!$module->activated('notes') && $initmod) {
		$notes->init_sql();
		$notes->init_menu();
		$notes->init_deps();
		$notes->init_lang();
		$notes->init_help();
	}
}
if (file_exists('../modules/notifiable/class.notifiable.php')) {
	include '../modules/notifiable/class.notifiable.php';
	$notifiable = new notifiable;
	if (!$module->activated('notifiable') && $initmod) {
		$notifiable->init_sql();
		$notifiable->init_menu();
		$notifiable->init_deps();
		$notifiable->init_lang();
		$notifiable->init_help();
	}
}
if (file_exists('../modules/notifiable_report/class.notifiable_report.php')) {
	include '../modules/notifiable_report/class.notifiable_report.php';
	$notifiable_report = new notifiable_report;
	if (!$module->activated('notifiable_report') && $initmod) {
		$notifiable_report->init_sql();
		$notifiable_report->init_menu();
		$notifiable_report->init_deps();
		$notifiable_report->init_lang();
		$notifiable_report->init_help();
	}
}
if (file_exists('../modules/ntp/class.ntp.php')) {
	include '../modules/ntp/class.ntp.php';
	$ntp = new ntp;
	if (!$module->activated('ntp') && $initmod) {
		$ntp->init_sql();
		$ntp->init_menu();
		$ntp->init_deps();
		$ntp->init_lang();
		$ntp->init_help();
	}
}
if (file_exists('../modules/ntp_report/class.ntp_report.php')) {
	include '../modules/ntp_report/class.ntp_report.php';
	$ntp_report = new ntp_report;
	if (!$module->activated('ntp_report') && $initmod) {
		$ntp_report->init_sql();
		$ntp_report->init_menu();
		$ntp_report->init_deps();
		$ntp_report->init_lang();
		$ntp_report->init_help();
	}
}
if (file_exists('../modules/occupation/class.occupation.php')) {
	include '../modules/occupation/class.occupation.php';
	$occupation = new occupation;
	if (!$module->activated('occupation') && $initmod) {
		$occupation->init_sql();
		$occupation->init_menu();
		$occupation->init_deps();
		$occupation->init_lang();
		$occupation->init_help();
	}
}
if (file_exists('../modules/patient/class.patient.php')) {
	include '../modules/patient/class.patient.php';
	$patient = new patient;
	if (!$module->activated('patient') && $initmod) {
		$patient->init_sql();
		$patient->init_menu();
		$patient->init_deps();
		$patient->init_lang();
		$patient->init_help();
	}
}
if (file_exists('../modules/philhealth/class.philhealth.php')) {
	include '../modules/philhealth/class.philhealth.php';
	$philhealth = new philhealth;
	if (!$module->activated('philhealth') && $initmod) {
		$philhealth->init_sql();
		$philhealth->init_menu();
		$philhealth->init_deps();
		$philhealth->init_lang();
		$philhealth->init_help();
	}
}
if (file_exists('../modules/population/class.population.php')) {
	include '../modules/population/class.population.php';
	$population = new population;
	if (!$module->activated('population') && $initmod) {
		$population->init_sql();
		$population->init_menu();
		$population->init_deps();
		$population->init_lang();
		$population->init_help();
	}
}
if (file_exists('../modules/ptgroup/class.ptgroup.php')) {
	include '../modules/ptgroup/class.ptgroup.php';
	$ptgroup = new ptgroup;
	if (!$module->activated('ptgroup') && $initmod) {
		$ptgroup->init_sql();
		$ptgroup->init_menu();
		$ptgroup->init_deps();
		$ptgroup->init_lang();
		$ptgroup->init_help();
	}
}
if (file_exists('../modules/region/class.region.php')) {
	include '../modules/region/class.region.php';
	$region = new region;
	if (!$module->activated('region') && $initmod) {
		$region->init_sql();
		$region->init_menu();
		$region->init_deps();
		$region->init_lang();
		$region->init_help();
	}
}
if (file_exists('../modules/reminder/class.reminder.php')) {
	include '../modules/reminder/class.reminder.php';
	$reminder = new reminder;
	if (!$module->activated('reminder') && $initmod) {
		$reminder->init_sql();
		$reminder->init_menu();
		$reminder->init_deps();
		$reminder->init_lang();
		$reminder->init_help();
	}
}
if (file_exists('../modules/smoking/class.smoking.php')) {
	include '../modules/smoking/class.smoking.php';
	$smoking = new smoking;
	if (!$module->activated('smoking') && $initmod) {
		$smoking->init_sql();
		$smoking->init_menu();
		$smoking->init_deps();
		$smoking->init_lang();
		$smoking->init_help();
	}
}
if (file_exists('../modules/sputum/class.sputum.php')) {
	include '../modules/sputum/class.sputum.php';
	$sputum = new sputum;
	if (!$module->activated('sputum') && $initmod) {
		$sputum->init_sql();
		$sputum->init_menu();
		$sputum->init_deps();
		$sputum->init_lang();
		$sputum->init_help();
	}
}
if (file_exists('../modules/tcl/class.tcl.php')) {
	include '../modules/tcl/class.tcl.php';
	$tcl = new tcl;
	if (!$module->activated('tcl') && $initmod) {
		$tcl->init_sql();
		$tcl->init_menu();
		$tcl->init_deps();
		$tcl->init_lang();
		$tcl->init_help();
	}
}
if (file_exists('../modules/template/class.template.php')) {
	include '../modules/template/class.template.php';
	$template = new template;
	if (!$module->activated('template') && $initmod) {
		$template->init_sql();
		$template->init_menu();
		$template->init_deps();
		$template->init_lang();
		$template->init_help();
	}
}
if (file_exists('../modules/vaccine/class.vaccine.php')) {
	include '../modules/vaccine/class.vaccine.php';
	$vaccine = new vaccine;
	if (!$module->activated('vaccine') && $initmod) {
		$vaccine->init_sql();
		$vaccine->init_menu();
		$vaccine->init_deps();
		$vaccine->init_lang();
		$vaccine->init_help();
	}
}
if (file_exists('../modules/wtforage/class.wtforage.php')) {
	include '../modules/wtforage/class.wtforage.php';
	$wtforage = new wtforage;
	if (!$module->activated('wtforage') && $initmod) {
		$wtforage->init_sql();
		$wtforage->init_menu();
		$wtforage->init_deps();
		$wtforage->init_lang();
		$wtforage->init_help();
	}
}

// END SERVER CODE

?>