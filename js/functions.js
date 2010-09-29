function check_delete(){
	if(window.confirm('Do you really want to delete this record?')){
		form_risk.confirm_del.value = 1;
	}
}

function delete_fp_service(){
	if(window.confirm('You are about to delete this record. Do you really wanted to delete this record?')){
		form_fp_chart.confirm_del.value = 1;
		form_fp_chart.submit();
	}
}

function confirm_dropout(){
		var form = window.document.forms["form_methods"];
		alert(form.elements.length);
		for(var i=0;i<form.elements.length;i++){
			window.alert(form.elements[i].value);
		}
}


function show_bfeed_date(){
	if(form_mc_postpartum.breastfeeding_flag.checked){
		var str  = form_mc_postpartum.date_breastfed.value;
		if(window.confirm(str.length)){
			form_mc_postpartum.submit();		
		}
	}
}

function verify_mother_id(){		
	window.open ("../site/verify_patient.php?id="+ form_consult_ccdev.mother_px_id.value,"CHITS - Verify Patient ID","location=1,status=0,scrollbars=0,width=200,height=200,resizable=0,menubar=0,toolbars=0");
}

function edit_consult_date(){
	window.alert('testing');
}

function check_appt_info(){
	//window.alert(form_consult_appointment.hidden_cp.value);

	if(form_consult_appointment.appointment_date.value==""){
		window.alert("The appointment date cannot be empty");
		return false;
	}
	
	else{	

		if(form_consult_appointment.reminder_flag.checked==true){
			if(form_consult_appointment.patient_cp.value==""){
				window.alert("Please specify the cellphone number of the patient");
				return false;
			}
			else if(form_consult_appointment.patient_cp.value.length!=11){
				window.alert("Cellphone number should be 11 digits (i.e. 09XX1234567)");
				return false;
			}
			else{
				form_consult_appointment.valid.value = 1;
				form_consult_appointment.action_button.value = form_consult_appointment.submitsked.value;
				
				if(form_consult_appointment.patient_cp.value!=form_consult_appointment.hidden_cp.value){
					window.alert("The original contact number of the patient was changed. The new cellphone number will be saved.");
				}

				form_consult_appointment.submit();
				return true;
			}
		}
		else{
			form_consult_appointment.valid.value = 1;
			form_consult_appointment.action_button.value = form_consult_appointment.submitsked.value;
			
			if(form_consult_appointment.patient_cp.value!=form_consult_appointment.hidden_cp.value){
				window.alert("The original contact number of the patient was changed. The new cellphone number will be saved.");
			}
			
			form_consult_appointment.submit();
			return true;
		}

	}
}

function verify_patient_id(){		
	window.open ("../site/verify_patient.php?id="+ form_visit1.spouse_name.value,"CHITS - Verify Patient ID","location=1,status=0,scrollbars=0,width=200,height=200,resizable=0,menubar=0,toolbars=0");
}

function confirm_delete_fp(){
	if(window.confirm('Do you really want to unenroll this patient from this FP method?')){
		form_methods.confirm_del.value = 1;
	}
	form_methods.submit();
}

function search_patient(){
	sList = window.open("../site/search_patient.php","searchpx","width=300,height=300");
}

function pick(pxid,pxfirst,pxlast){
  if (window.opener && !window.opener.closed){
      window.opener.document.form_visit1.spouse_name.value = pxfirst+' '+pxlast;
      window.opener.document.form_visit1.spouse_id.value = pxid;
  }  
  window.close();        
}

function import_sputum(){        
        winopen = window.open("../site/import_sputum.php?id="+form_symptomatic.pxid.value,"importsputum","width=600,height=600");
}

function delete_symp(){

        if(window.confirm('Do you really want to delete this TB Symptomatic record?')){
                form_symptomatic.confirm_del.value = 1;
                form_symptomatic.submit();                
        }        
}

function delete_sputum(){
        if(window.confirm('Do you really want to remove this TB Sputum record from this table?')){        
                window.alert('The Sputum Record record was successfully been removed.');
        }
}

function set_var_dropdown(){
	window.alert('The list has been set');
	window.alert(form_alert_lib.sel_mods.value);

	switch(form_alert_lib.sel_mods.value){
		case 'ccdev':
			form_alert_lib.tbl_name.value = 'm_consult_ccdev_services,m_patient_ccdev';
			break;

		case 'epi':
			form_alert_lib.tbl_name.value = 'm_consult_ccdev_services';
			break;

		case 'mc':
			form_alert_lib.tbl_name.value = 'm_patient_mc,m_consult_mc_services';
			break;

		case 'notifiable':
			form_alert_lib.tbl_name.value = 'm_consult_notes,m_consult_notes_dxclass';
			break;

		case 'fp':
			form_alert_lib.tbl_name.value = 'm_patient_fp';
			break;	
		default:
			form_alert_lib.tbl_name.value = '';
			break;
	}
	window.alert(form_alert_lib.tbl_name.value);
	window.reload();
	
}

function autoSubmit_alert()
{
	var formObject = document.forms['form_alert_lib'];
	if(formObject!=0){
		formObject.submit();
	}
}