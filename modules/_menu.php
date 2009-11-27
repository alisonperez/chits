<?
// BEGIN SERVER CODE: DO NOT EDIT
// Server generated code
// Generated 2009-11-26 23:38:20
// Module: _menu.php
// Author: Herman Tolentino MD
//
if ($HTTP_GET_VARS["menu_id"]) {
	switch ($HTTP_GET_VARS["menu_id"]) {
	case 1329:
		$appointment->_appointments($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1330:
		$appointment->_consult_schedule($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 686:
		$barangay->_barangay($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1334:
		$ccdev->_ccdev_services($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1333:
		$ccdev->_ccdev_stats($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1173:
		$ccdev_report->_ccdev_report($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 687:
		$complaint->_complaint($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1118:
		$consult_report->_consult_report($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1335:
		$database->_database_support($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1341:
		$drug->_drugs($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1337:
		$drug->_drug_formulation($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1339:
		$drug->_drug_manufacturer($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1338:
		$drug->_drug_preparation($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1340:
		$drug->_drug_source($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 784:
		$education->_education($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 785:
		$education->_education_stats($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1344:
		$epi_report->_epi_report($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 691:
		$family->_family($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1327:
		$healthcenter->_consult($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1328:
		$healthcenter->_modules($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1055:
		$icd10->_icd10($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1095:
		$imci->_imci_drugs($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1096:
		$imci->_imci_drug_class($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1094:
		$imci->_imci_dxclass($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1097:
		$imci->_imci_signs($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1098:
		$imci->_imci_treatment($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1104:
		$injury->_injury_codes($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1105:
		$injury->_injury_locations($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1106:
		$injury->_injury_mechanisms($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1107:
		$injury_report->_injury_report($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1034:
		$lab->_laboratory($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1033:
		$lab->_lab_exams($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 319:
		$language->_language($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1273:
		$mc->_mc_attendant($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1268:
		$mc->_mc_followup($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1272:
		$mc->_mc_outcomes($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1269:
		$mc->_mc_risk_factors($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1270:
		$mc->_mc_services($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1271:
		$mc->_mc_vaccines($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1345:
		$mc_report->_mc_report($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 885:
		$news->_news($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 884:
		$news->_news_archive($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1343:
		$notes->_notes_dxclass($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1342:
		$notes->_notes_templates($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1070:
		$notifiable->_notifiable($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1116:
		$notifiable_report->_notifiable_report($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1314:
		$ntp->_consult_ntp_followup($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1320:
		$ntp->_ntp_appointments($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1318:
		$ntp->_ntp_labs($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1316:
		$ntp->_ntp_outcomes($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1317:
		$ntp->_ntp_partners($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1315:
		$ntp->_ntp_patient_type($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1319:
		$ntp->_ntp_treatment_category($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1041:
		$ntp_report->_ntp_report($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 818:
		$occupation->_occupation($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 819:
		$occupation->_occupation_cat($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 820:
		$occupation->_occupation_stats($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 657:
		$patient->_patient($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 658:
		$patient->_patient_modules($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 780:
		$philhealth->_philhealth_labs($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 779:
		$philhealth->_philhealth_report($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 781:
		$philhealth->_philhealth_services($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1349:
		$population->_population($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 764:
		$ptgroup->_ptgroup($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 869:
		$region->_region($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1336:
		$reminder->_sms_template($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 717:
		$template->_templates($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	case 1214:
		$vaccine->_vaccine($menu_id, $HTTP_POST_VARS, $HTTP_GET_VARS,$_SESSION["validuser"],$_SESSION["isadmin"]);
		break;
	}
}

// END SERVER CODE

?>