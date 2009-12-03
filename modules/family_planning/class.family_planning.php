<?php
class family_planning extends module{

	function family_planning(){ // class constructor
		$this->author = "darth_ali";
		$this->version = "0.15-".date("Y-m-d");
		$this->module = "family_planning";
		$this->description = "CHITS Module - Family Planning";

		//0.1 - created data entry forms for the family service record
		/* mechanics for family planning:
			1. Each FP patient has to fill out the Family Planning service record (Medical HX, Physical Examination, Obstetrical History,
			   Pelvic Examination. One FP patient = 1 family planning service record regardless of how many methods he/she has enrolled
			2. FP patient will be enrolled for a particular program. Female - 15 to 49, Male - Vasectomy or Condom
			3. If a patient is new to the method classify him or her to as NEW ACCEPTOR and CURRENT USER
			4. A patient is considered as dropout if: 1). conditions for being dropout based on the methods are applied, 2). the patient decided to be drop out on purpose based on the conditions
		        5. If a patient re-applies again:
		            a. same method before drop out - RESTART , CURRENT USER (i.e. pills-drop out-pills)
		            b. different method before the drop out
		               i. if patient is already a previous user - CURRENT USER, CHANGE METHOD (i.e. dmpa-drop out-pills-drop out-dmpa)
		               ii. if patient chooses a new method - CURRENT USER, CHANGE METHOD, NEW ACCEPTOR (i.e. pills-drop out-dmpa)
*/
	}

	//standard module functions

	function init_deps(){
	    module::set_dep($this->module, "module");
	    module::set_dep($this->module, "healthcenter");
	    module::set_dep($this->module, "patient");
	}

	function init_lang(){
		module::set_lang("THEAD_FP_HEADER", "english", "FAMILY PLANNING SERVICE RECORD", "Y");
	}

	function init_stats(){

	}

	function init_help(){

	}

	function init_menu(){
		if(func_num_args()>0){
			$arg_list = func_get_args();
		}
	        module::set_detail($this->description, $this->version, $this->author, $this->module);

	}

	function init_sql(){

		if(func_num_args()>0){
			$arg_list = func_get_args();
		}

		//m_patient_fp -- create
		module::execsql("CREATE TABLE IF NOT EXISTS `m_patient_fp` (
				  `fp_id` float NOT NULL auto_increment,
				  `patient_id` float NOT NULL default '0',
				  `date_enrolled` date NOT NULL,
				  `date_encoded` date NOT NULL,
				  `consult_id` float NOT NULL,
				  `last_edited` date NOT NULL,
				  `plan_more_children` char(1) NOT NULL default '',
				  `no_of_living_children_desired` tinyint(2) NOT NULL default '0',
				  `no_of_living_children_actual` tinyint(2) NOT NULL,
				  `birth_interval_desired` tinyint(2) NOT NULL,
				  `educ_id` varchar(10) NOT NULL default '',
				  `occup_id` varchar(10) NOT NULL default '',
				  `spouse_name` varchar(100) NOT NULL default '',
				  `spouse_educ_id` varchar(10) NOT NULL default '',
				  `spouse_occup_id` varchar(10) NOT NULL default '',
				  `ave_monthly_income` float NOT NULL default '0',
				  `user_id` int(11) NOT NULL default '0',
				  `user_id_edited` int(11) NOT NULL,
				  `uterine_mass_iud` int(3) NOT NULL,
				  `pe_others` text NOT NULL,
				  PRIMARY KEY  (`fp_id`),
				  KEY `key_patient` (`patient_id`),
				  KEY `key_educ` (`educ_id`),
				  KEY `key_occup` (`occup_id`),
				  KEY `key_spouse_educ` (`spouse_educ_id`),
				  KEY `key_spouse_occup` (`spouse_occup_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1");

		//m_patient_fp_hx -- create
		module::execsql("CREATE TABLE IF NOT EXISTS `m_patient_fp_hx` (
			  `fp_id` float NOT NULL,
			  `patient_id` float NOT NULL,
			  `consult_id` float NOT NULL,
			  `history_id` varchar(100) NOT NULL,
			  `date_encoded` date NOT NULL,
			  `user_id` int(11) NOT NULL,
			  `last_edited` date NOT NULL,
			  `user_id_edited` int(11) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");


		//m_patient_fp_pe -- create
		module::execsql("CREATE TABLE IF NOT EXISTS `m_patient_fp_pe` (
			  `fp_id` float NOT NULL,
			  `patient_id` float NOT NULL,
			  `pe_id` int(5) NOT NULL,
			  `consult_id` float NOT NULL,
			  `date_encoded` date NOT NULL,
			  `user_id` int(3) NOT NULL,
			  `last_edited` date NOT NULL,
			  `user_id_edited` int(3) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;");

		//m_patient_fp_pelvic --create
		module::execsql("CREATE TABLE IF NOT EXISTS `m_patient_fp_pelvic` (
					  `fp_id` float NOT NULL,
					  `patient_id` float NOT NULL,
					  `consult_id` float NOT NULL,
					  `pelvic_id` int(5) NOT NULL,
					  `date_encoded` date NOT NULL,
					  `user_id` int(3) NOT NULL,
					  `last_edited` date NOT NULL,
					  `user_id_edited` int(3) NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;");

		//m_patient_fp_obgyn -- create
		module::execsql("CREATE TABLE IF NOT EXISTS `m_patient_fp_obgyn` (
				  `fp_id` int(11) NOT NULL,
				  `patient_id` int(11) NOT NULL,
				  `obshx_id` int(5) NOT NULL,
				  `date_encoded` date NOT NULL,
				  `user_id` int(3) NOT NULL,
				  `last_edited` date NOT NULL,
				  `user_id_edited` int(3) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		//m_patient_fp_obgyn_details -- create
		module::execsql("CREATE TABLE IF NOT EXISTS `m_patient_fp_obgyn_details` (
  				`fp_id` float NOT NULL,
				  `patient_id` float NOT NULL,
				  `no_pregnancies` int(2) NOT NULL,
				  `fpal` varchar(10) NOT NULL,
				  `no_living_children` int(2) NOT NULL,
				  `date_last_delivery` date NOT NULL,
				  `type_last_delivery` varchar(50) NOT NULL,
				  `age_menarch` int(11) NOT NULL,
				  `past_menstrual_date` date NOT NULL,
				  `date_encoded` date NOT NULL,
				  `user_id` int(11) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		//m_patient_fp_method -- create
		module::execsql("CREATE TABLE IF NOT EXISTS `m_patient_fp_method` (
                  `fp_px_id` float NOT NULL AUTO_INCREMENT,
                  `fp_id` float NOT NULL,
                  `patient_id` float NOT NULL,
                  `consult_id` float NOT NULL,
                  `date_registered` date NOT NULL,
                  `date_encoded` date NOT NULL,
                  `method_id` varchar(10) NOT NULL,
                  `client_code` varchar(6) NOT NULL,
                  `treatment_partner` varchar(200) NOT NULL,
                  `permanent_method` set('Y','N') NOT NULL DEFAULT 'N',
                  `permanent_reason` varchar(200) NOT NULL,
                  `drop_out` set('Y','N') NOT NULL DEFAULT 'N',
                  `date_dropout` date NOT NULL,
                  `dropout_reason` text NOT NULL,
                  `dropout_remarks` text NOT NULL,
                  `user_id` float NOT NULL,
                  `last_edited` date NOT NULL,
                  `user_id_edited` float NOT NULL,
                  PRIMARY KEY (`fp_px_id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");


		//m_patient_fp_method_service -- create
		module::execsql("CREATE TABLE IF NOT EXISTS `m_patient_fp_method_service` (
			  `fp_service_id` float NOT NULL auto_increment,
			  `fp_id` float NOT NULL,
			  `fp_px_id` float NOT NULL,
			  `patient_id` float NOT NULL,
			  `consult_id` float NOT NULL,
			  `date_service` date NOT NULL,
			  `source_id` int(5) NOT NULL,
			  `remarks` text NOT NULL,
			  `date_encoded` date NOT NULL,
			  `user_id` float NOT NULL,
			  `next_service_date` date NOT NULL,
			  PRIMARY KEY  (`fp_service_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

		//m_patient_fp_dropout -- create
		module::execsql("CREATE TABLE IF NOT EXISTS `m_patient_fp_dropout` (
				  `dropout_id` float NOT NULL auto_increment,
				  `fp_id` float NOT NULL,
				  `patient_id` float NOT NULL,
				  `fp_px_id` float NOT NULL,
				  `reason_id` int(11) NOT NULL,
				  PRIMARY KEY  (`dropout_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");


		//m_lib_fp_dropoutreasons
		module::execsql("DROP TABLE IF EXISTS `m_lib_fp_dropoutreason`");

		module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_fp_dropoutreason` (
				  `reason_id` int(5) NOT NULL auto_increment,
				  `reason_label` varchar(200) NOT NULL,
				  `fhsis_code` char(1) NOT NULL,
				  PRIMARY KEY  (`reason_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");


		module::execsql("INSERT INTO `m_lib_fp_dropoutreason` (`reason_id`, `reason_label`, `fhsis_code`) VALUES
				(1, 'Pregnant', 'A'),(2, 'Desire to become pregnant', 'B'),(3, 'Medical complications', 'C'),
				(4, 'Fear of side effects', 'D'),(5, 'Changed clinic', 'E'),(6, 'Husband disapproves', 'F'),
				(7, 'Menopause', 'G'),(8, 'Lost or moved out of the area or residence', 'H'),(9, 'Failed to get supply', 'I'),
				(10, 'IUD expelled', 'J'),(11, 'Unknown', 'K');");


		//m_lib_fp_obsgyn -- create
		module::execsql("DROP TABLE IF EXISTS `m_lib_fp_obgyn`");
		module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_fp_obgyn` (
			  	`obshx_id` int(2) NOT NULL auto_increment,
			  	`obshx_name` varchar(200) NOT NULL,
			  	`obshx_cat` varchar(200) NOT NULL,
			   	PRIMARY KEY  (`obshx_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");


		//m_lib_fp_obsgyn -- insert
		module::execsql("INSERT INTO `m_lib_fp_obgyn` (`obshx_id`, `obshx_name`, `obshx_cat`) VALUES
				(1, 'Scanty', 'MENSES'),(2, 'Painful', 'MENSES'),(3, 'Moderate', 'MENSES'),(4, 'Regular', 'MENSES'),
				(5, 'Heavy', 'MENSES'),(6, 'Hydaditiform Mole', 'OTHERS'),(7, 'Ecplopic Pregnancy', 'OTHERS'),
				(8, 'No abnormal history', 'OTHERS');");


		//m_lib_fp_methods -- create
		module::execsql("DROP TABLE IF EXISTS `m_lib_fp_methods`");

		module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_fp_methods` (
			  	`method_id` varchar(10) NOT NULL DEFAULT '',
  				`method_name` varchar(100) NOT NULL DEFAULT '',
				`method_gender` set('M','F') NOT NULL DEFAULT '',
  				`fhsis_code` varchar(20) NOT NULL DEFAULT '',
  				`report_order` int(11) NOT NULL,
  				PRIMARY KEY (`method_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;");

		//m_lib_fp_methods -- populate contents
		
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, `method_name`, `method_gender`, `fhsis_code`, `report_order`) VALUES
			('PILLS', 'Pills', 'F', 'PILLS', 3),('CONDOM', 'Condom', 'M', 'CON', 11),('IUD', 'IUD', 'F', 'IUD', 4),('NFPLAM', 'NFP	Lactational amenorrhea', 'F', 'NFP-LAM', 8),('DMPA', 'Depo-Lactational Amenorrhea ', 'F', 'DMPA', 5),('NFPBBT', 'NFP Basal Body Temperature', 'F', 'NFP-BBT', 7),('NFPCM', 'NFP Cervical Mucus Method', 'F', 'NFP-CM', 6),('NFPSTM', 'NFP Sympothermal Method', 'F', 'NFP-STM', 10),
('NFPSDM', 'NFP Standard Days Method', 'F', 'NFP-SDM', 9),('FSTRBTL', 'Female Sterilization /Bilateral Tubal Ligation', 'F', 'FSTR/BTL', 1),
('MSV', 'Male Sterilization /Vasectomy', 'M', 'MSTR/Vasec', 2)");

		/*module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`,`method_name`,`method_gender`,`fhsis_code`) VALUES ('PILLS', 'Pills','F','PILLS')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`,`method_name`,`method_gender`,`fhsis_code`) VALUES ('CONDOM', 'Condom','M','CON')");
	        module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`,`method_name`,`method_gender`,`fhsis_code`) VALUES ('IUD', 'IUD','F','IUD')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`,`method_name`,`method_gender`,`fhsis_code`) VALUES ('NFPLAM', 'NFP Lactational amenorrhea','F','NFP-LAM')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`,`method_name`,`method_gender`,`fhsis_code`) VALUES ('DMPA', 'Depo-Lactational Amenorrhea ','F','DMPA')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`,`method_name`,`method_gender`,`fhsis_code`) VALUES ('NFPBBT', 'NFP Basal Body Temperature','F','NFP-BBT')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`,`method_name`,`method_gender`,`fhsis_code`) VALUES ('NFPCM', 'NFP Cervical Mucus Method','F','NFP-CM')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`,`method_name`,`method_gender`,`fhsis_code`) VALUES ('NFPSTM', 'NFP Sympothermal Method','F','NFP-STM')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`,`method_name`,`method_gender`,`fhsis_code`) VALUES ('NFPSDM', 'NFP Standard Days Method','F','NFP-SDM')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`,`method_name`,`method_gender`,`fhsis_code`) VALUES ('FSTRBTL', 'Female Sterilization /Bilateral Tubal Ligation','F','FSTR/BTL')");
		module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`,`method_name`,`method_gender`,`fhsis_code`) VALUES ('MSV', 'Male Sterilization /Vasectomy','M','MSTR/Vasec')"); */


		//create library for medical history category of family planning
		module::execsql("DROP TABLE IF EXISTS `m_lib_fp_history_cat`");

		module::execsql("CREATE TABLE `m_lib_fp_history_cat` (".
				"`cat_id` varchar(10) NOT NULL default '',".
				"`cat_name` varchar(50) NOT NULL default '',".
				"PRIMARY KEY (`cat_id`)".
				") TYPE=MyISAM; ");


		module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('HEENT', 'HEENT')");
	    	module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('CXHRT', 'CHEST/HEART')");
    		module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('ABD', 'ABDOMEN')");
    		module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('GEN', 'GENITAL')");
    		module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('EXT', 'EXTREMITIES')");
    		module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('SKIN', 'SKIN')");
    		module::execsql("INSERT INTO `m_lib_fp_history_cat` (`cat_id`, `cat_name`) VALUES ('ANY', 'HISTORY OF ANY OF THE FOLLOWING')");

    //create table for fp library of clients

        module::execsql("DROP TABLE IF EXISTS `m_lib_fp_client`");

        module::execsql("CREATE TABLE IF NOT EXISTS `m_lib_fp_methods` (
		  `method_id` varchar(10) NOT NULL DEFAULT '',
		  `method_name` varchar(100) NOT NULL DEFAULT '',
		  `method_gender` set('M','F') NOT NULL DEFAULT '',
		  `fhsis_code` varchar(20) NOT NULL DEFAULT '',
		  `report_order` int(11) NOT NULL,
		  PRIMARY KEY (`method_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;");

        module::execsql("INSERT INTO `m_lib_fp_methods` (`method_id`, `method_name`, `method_gender`, `fhsis_code`, `report_order`) VALUES
		('PILLS', 'Pills', 'F', 'PILLS', 3),
		('CONDOM', 'Condom', 'M', 'CON', 11),
		('IUD', 'IUD', 'F', 'IUD', 4),
		('NFPLAM', 'NFP Lactational amenorrhea', 'F', 'NFP-LAM', 8),
		('DMPA', 'Depo-Lactational Amenorrhea ', 'F', 'DMPA', 5),
		('NFPBBT', 'NFP Basal Body Temperature', 'F', 'NFP-BBT', 7),
		('NFPCM', 'NFP Cervical Mucus Method', 'F', 'NFP-CM', 6),
		('NFPSTM', 'NFP Sympothermal Method', 'F', 'NFP-STM', 10),
		('NFPSDM', 'NFP Standard Days Method', 'F', 'NFP-SDM', 9),
		('FSTRBTL', 'Female Sterilization /Bilateral Tubal Ligation', 'F', 'FSTR/BTL', 1),
		('MSV', 'Male Sterilization /Vasectomy', 'M', 'MSTR/Vasec', 2),
		('LAM', 'LAM', 'F', 'LAM', 12);");

	//create table for fp medical history items
		module::execsql("DROP TABLE IF EXISTS `m_lib_fp_history`");

		module::execsql("CREATE TABLE `m_lib_fp_history` (".
		      "`history_id` varchar(10) NOT NULL default '',".
		      "`history_text` varchar(100) NOT NULL default '',".
		      "`history_cat` varchar(15) NOT NULL default '',".
		      "PRIMARY KEY (`history_id`)".
		      ") TYPE=MyISAM; ");

	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('EPILEPSY', 'Epilepsy/Convulsion/Seizure', 'HEENT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('HEADACHE', 'Severe headache/dizziness', 'HEENT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('VISION', 'Visual disturbance/blurring of vision', 'HEENT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('YCONJ', 'Yellowish conjunctive', 'HEENT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('ETHY', 'Enlarged thyroid', 'HEENT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('CXPAIN', 'Severe chest pain', 'CXHRT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('FATIGUE', 'Shortness of breath and easy fatiguability', 'CXHRT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('BRSTMASS', 'Breast/axillary masses', 'CXHRT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('NIPBLOOD', 'Nipple discharges (blood)', 'CXHRT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('NIPPUS', 'Nipple discharges (pus)', 'CXHRT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('SYS140', 'Systolic of 140 & above', 'CXHRT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('DIAS90', 'Diastolic of 90 & above', 'CXHRT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('CVAHARHD', 'Family history of CVA (strokes), hypertension, asthma, rheumatic heart disease', 'CXHRT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('MASSABD', 'Mass in the abdomen', 'ABD')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('GALL', 'History of gallbladder disease', 'ABD')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('LIVER', 'History of liver disease', 'ABD')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('UTERUS', 'Mass in the uterus', 'GEN')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('VAGDISCH', 'Vaginal discharge', 'GEN')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('INTERBLEED', 'Intermenstrual bleeding', 'GEN')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('POSTBLEED', 'Postcoital bleeding', 'GEN')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('VARICOSE', 'Severe varicosities', 'EXT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('LEGPAIN', 'Swelling or severe pain in the legs not related to injuries', 'EXT')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('YELLOWSKIN', 'Yellowish skin', 'SKIN')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('SMOKING', 'Smoking', 'ANY')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('ALLERGY', 'Allergies', 'ANY')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('DRUGINTAKE', 'Drug intake (anti-TB, anti-diabetic, anticonvulsant)', 'ANY')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('STD', 'STD', 'ANY')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('MPARTNERS', 'Multiple partners', 'ANY')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('BLEEDING', 'Bleeding tendencies (nose, gums, etc.)', 'ANY')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('ANEMIA', 'Anemia', 'ANY')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('DIABETES', 'Diabetes', 'ANY')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('HMOLE', 'Hydatidiform mole (w/in the last 12 mos.)', 'ANY')");
	    module::execsql("INSERT INTO `m_lib_fp_history` (`history_id`, `history_text`, `history_cat`) VALUES ('ECTPREG', 'Ectopic pregnancy', 'ANY')");

		//table for fp PE category
		module::execsql("DROP TABLE IF EXISTS `m_lib_fp_pe_cat`");

		module::execsql("CREATE TABLE `m_lib_fp_pe_cat` (`pe_cat_id` VARCHAR( 20 ) NOT NULL ,`pe_cat_name` VARCHAR( 50 ) NOT NULL , PRIMARY KEY ( `pe_cat_id` )
) ENGINE = MYISAM ");

		module::execsql("INSERT INTO `m_lib_fp_pe_cat` (`pe_cat_id`,`pe_cat_name`) VALUES ('CONJUNCTIVA','CONJUNCTIVA')");
		module::execsql("INSERT INTO `m_lib_fp_pe_cat` (`pe_cat_id`,`pe_cat_name`) VALUES ('NECK','NECK')");
		module::execsql("INSERT INTO `m_lib_fp_pe_cat` (`pe_cat_id`,`pe_cat_name`) VALUES ('BREAST','BREAST')");
		module::execsql("INSERT INTO `m_lib_fp_pe_cat` (`pe_cat_id`,`pe_cat_name`) VALUES ('THORAX','THORAX')");
		module::execsql("INSERT INTO `m_lib_fp_pe_cat` (`pe_cat_id`,`pe_cat_name`) VALUES ('ABDOMEN','ABDOMEN')");
		module::execsql("INSERT INTO `m_lib_fp_pe_cat` (`pe_cat_id`,`pe_cat_name`) VALUES ('EXTREMITIES','EXTREMITIES')");

		//table for fp PE items
		module::execsql("DROP TABLE IF EXISTS `m_lib_fp_pe`");

		module::execsql(" CREATE TABLE `m_lib_fp_pe` (
				`pe_id` INT( 5 ) NOT NULL AUTO_INCREMENT ,
				`pe_name` VARCHAR( 100 ) NOT NULL ,
				`pe_cat` VARCHAR( 20 ) NOT NULL ,PRIMARY KEY ( `pe_id` )
				) ENGINE = MYISAM ");

		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Pale',`pe_cat`='CONJUNCTIVA'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Yellowish',`pe_cat`='CONJUNCTIVA'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Enlarged Thyroid',`pe_cat`='NECK'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Enlarged Lymph Nodes',`pe_cat`='NECK'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Mass',`pe_cat`='BREAST'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Nipple Discharge',`pe_cat`='BREAST'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Skin-orange-peel or dimpling',`pe_cat`='BREAST'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Enlarged Axillary Lymph Nodes',`pe_cat`='BREAST'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Abnormal Heart Sounds/Cardiac Rate',`pe_cat`='THORAX'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Abnormal Breath Sounds/Respiratory Rate',`pe_cat`='THORAX'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Enlarge Liver',`pe_cat`='ABDOMEN'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Mass',`pe_cat`='ABDOMEN'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Tenderness',`pe_cat`='ABDOMEN'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Edema',`pe_cat`='EXTREMITIES'");
		module::execsql("INSERT INTO `m_lib_fp_pe` SET `pe_name`='Varicosities',`pe_cat`='EXTREMITIES'");


		//table for pelvic PE exam categories
		module::execsql("DROP TABLE IF EXISTS `m_lib_fp_pelvic_cat`");

		module::execsql(" CREATE TABLE `m_lib_fp_pelvic_cat` (
				`pelvic_cat_id` VARCHAR( 20 ) NOT NULL ,
				`pelvic_cat_name` VARCHAR( 50 ) NOT NULL ,PRIMARY KEY ( `pelvic_cat_id` )) ENGINE = MYISAM ");


		module::execsql("INSERT INTO `m_lib_fp_pelvic_cat` (`pelvic_cat_id`,`pelvic_cat_name`) VALUES ('PERENIUM','PERENIUM')");
		module::execsql("INSERT INTO `m_lib_fp_pelvic_cat` (`pelvic_cat_id`,`pelvic_cat_name`) VALUES ('VAGINA','VAGINA')");
		module::execsql("INSERT INTO `m_lib_fp_pelvic_cat` (`pelvic_cat_id`,`pelvic_cat_name`) VALUES ('CERVIX','CERVIX')");
		module::execsql("INSERT INTO `m_lib_fp_pelvic_cat` (`pelvic_cat_id`,`pelvic_cat_name`) VALUES ('CERVIXCOLOR','Color')");
		module::execsql("INSERT INTO `m_lib_fp_pelvic_cat` (`pelvic_cat_id`,`pelvic_cat_name`) VALUES ('CERVIXCONSISTENCY','Consistency')");
		module::execsql("INSERT INTO `m_lib_fp_pelvic_cat` (`pelvic_cat_id`,`pelvic_cat_name`) VALUES ('UTERUSPOS','UTERUS POSITION')");
		module::execsql("INSERT INTO `m_lib_fp_pelvic_cat` (`pelvic_cat_id`,`pelvic_cat_name`) VALUES ('UTERUSSIZE','UTERUS SIZE')");
		module::execsql("INSERT INTO `m_lib_fp_pelvic_cat` (`pelvic_cat_id`,`pelvic_cat_name`) VALUES ('UTERUSMASS','UTERUS MASS')");
		module::execsql("INSERT INTO `m_lib_fp_pelvic_cat` (`pelvic_cat_id`,`pelvic_cat_name`) VALUES ('ADNEXA','ADNEXA')");

		//table for FP pelvic PE exam items
		module::execsql("DROP TABLE IF EXISTS `m_lib_fp_pelvic`");

		module::execsql(" CREATE TABLE `m_lib_fp_pelvic` (
				`pelvic_id` INT( 5 ) NOT NULL AUTO_INCREMENT ,
				`pelvic_name` VARCHAR( 50 ) NOT NULL ,
				`pelvic_cat` VARCHAR( 50 ) NOT NULL ,PRIMARY KEY ( `pelvic_id` )) ENGINE = MYISAM ");

		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Scars',`pelvic_cat`='PERENIUM'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Warts',`pelvic_cat`='PERENIUM'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Reddish',`pelvic_cat`='PERENIUM'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Laceration',`pelvic_cat`='PERENIUM'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Congested',`pelvic_cat`='VAGINA'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Bartholin's cyst',`pelvic_cat`='VAGINA'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Warts',`pelvic_cat`='VAGINA'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Skene's Gland Discharge',`pelvic_cat`='VAGINA'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Rectocele',`pelvic_cat`='VAGINA'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Cytocele',`pelvic_cat`='VAGINA'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Congested',`pelvic_cat`='CERVIX'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Erosion',`pelvic_cat`='CERVIX'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Discharge',`pelvic_cat`='CERVIX'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Polyps/Cyst',`pelvic_cat`='CERVIX'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Laceration',`pelvic_cat`='CERVIX'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Pinkish',`pelvic_cat`='CERVIXCOLOR'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Bluish',`pelvic_cat`='CERVIXCOLOR'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Bartholin's cyst',`pelvic_cat`='VAGINA'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Firm',`pelvic_cat`='CERVIXCONSISTENCY'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Soft',`pelvic_cat`='CERVIXCONSISTENCY'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Mid',`pelvic_cat`='UTERUSPOS'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Anteflexed',`pelvic_cat`='UTERUSPOS'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Retroflexed',`pelvic_cat`='UTERUSPOS'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Normal',`pelvic_cat`='UTERUSSIZE'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Small',`pelvic_cat`='UTERUSSIZE'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Large',`pelvic_cat`='UTERUSSIZE'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Normal',`pelvic_cat`='ADNEXA'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Mass',`pelvic_cat`='ADNEXA'");
		module::execsql("INSERT INTO `m_lib_fp_pelvic` SET `pelvic_name`='Tenderness',`pelvic_cat`='ADNEXA'");
	}



	function drop_tables(){
		module::execsql("DROP table m_patient_fp");
		module::execsql("DROP table m_lib_fp_methods");
		module::execsql("DROP table m_lib_fp_history_cat");
		module::execsql("DROP table m_lib_fp_history");
		module::execsql("DROP table m_lib_fp_pe_cat");
		module::execsql("DROP table m_lib_fp_pe");
		module::execsql("DROP table m_lib_fp_pelvic_cat");
		module::execsql("DROP table m_lib_fp_pelvic");
	}


	//custom-built functions
	//all function starts here

	function _consult_family_planning(){

		if($exitinfo = $this->missing_dependencies('family_planning')){
			return print($exitinfo);
		}

		if(func_num_args()>0){
		      $menu_id = $arg_list[0];	   //from $_GET
		      $post_vars = $arg_list[1];      //from form submissions
		      $get_vars = $arg_list[2];       //from $_GET
		      $validuser = $arg_list[3];       //from $_SESSION
		      $isadmin = $arg_list[4];	       //from $_SESSION
		}

		$fp = new family_planning;
		$fp->fp_menu($_GET["menu_id"],$_POST,$_GET,$_SESSION["validuser"],$_SESSION["isadmin"]);
		//$fp->form_fp($menu_id,$post_vars,$get_vars,$isadmin);

		if($_POST["submit_fp"]):
			print_r($_POST);
			switch($_POST["submit_fp"]){
				case "Save Family Planning Method":
					$fp->submit_method_visit();
					break;

				case "Update Family Planning Method":
					$fp->update_method_visit();
					break;

				case "Save Family Planning First Visit":
					$fp->submit_first_visit();
					break;

				case "Update Family Planning First Visit":
					$fp->submit_first_visit();
					break;

				case "Save FP History":
					$fp->submit_fp_history();
					break;

				case "Save Physical Examination":
					$fp->submit_fp_pe();
					break;

				case "Save Pelvic Examination":
					$fp->submit_fp_pelvic();
					break;

				case "Save FP Service Chart":
					$fp->submit_fp_service();
					break;

				case "Update FP Service Chart":
					$fp->submit_fp_service();
					break;

				case "Delete FP Service Record":
						$fp->submit_fp_service();
						break;
				default:
					break;
			}
		endif;
		$fp->form_fp();
	}


	function form_fp(){
		echo "<table>";
		echo "<tr><td>".THEAD_FP_HEADER."</td></tr>";
		echo "<tr><td>";

		switch($_GET["fp"]){

		case "METHODS":
			$this->form_fp_methods();
			break;

		case "VISIT1":
			$this->form_fp_visit1();
			break;
		case "HX":
			$this->form_fp_history();
			break;
		case "PE":
			$this->form_fp_pe();
			break;

		case "PELVIC":
			$this->form_fp_pelvicpe();
			break;

		case "CHART":
			$this->form_fp_chart();
			break;

		case "OBS":
			$this->form_fp_obs();
			break;
		case "SVC":

			break;
		default:
			//print_r($_GET);
		    $this->form_fp_visit1();  //redirect the visitor to form_fp_visit1();
			break;
		}

		echo "</td></tr>";
		echo "</table>";
	}

	function fp_menu(){   			 /* displays main menus for FP */
		echo "<table>";
		echo "<tr><td>";

		echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=METHODS#methods' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'METHODS','METHODS')."</a>";

		echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=CHART#chart' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'CHART','FP CHART')."</a>";

	        echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=VISIT1#visit1' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'VISIT1','VISIT1')."</a>";

	        echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=HX#hx' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'HX','FP HX')."</a>";

	        echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=OBS#obs' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'OBS','OSTETRICAL HX')."</a>";

	        echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=PE#pe' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'PE','FP PE')."</a>";

	        echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=PELVIC#pelvic' class='groupmenu'>".$this->menu_highlight($_GET["fp"],'PELVIC','PELVIC EXAM')."</a>";

		echo "</td></tr>";
		echo "<table>";
	}

	function form_fp_methods(){
		/*three scenarios:
			1. new patient w/o previous FP method (METHOD (dropdown), PREVIOUS METHOD - None (label)), record visit 1 information
			2. old patient w/ previous FP method but presently dropout and in FP method (METHOD (dropdown),PREVIOUS - label, show history)
			3. patient with existing FP method also with previous FP methods (METHOD (label),PREVIOUS - label, show history)
		*/
		
	    $q_fp = $this->check_fprec();

		if(mysql_num_rows($q_fp)!=0):

		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);

		$q_fp_methods = mysql_query("SELECT a.fp_id,b.fp_px_id,b.method_id,c.method_name,b.drop_out,b.date_registered,b.treatment_partner,b.dropout_reason,FLOOR((unix_timestamp(b.date_dropout)-unix_timestamp(b.date_registered))/(3600*24)) as duration,b.date_dropout,b.dropout_reason,b.client_code,b.permanent_reason FROM m_patient_fp a, m_patient_fp_method b, m_lib_fp_methods c WHERE a.patient_id='$pxid' AND a.fp_id=b.fp_id AND b.method_id=c.method_id ORDER by b.drop_out DESC,b.date_registered DESC") or die("Cannot query: 534");
		
		if(isset($_SESSION["dropout_info"]) && $_GET["action"]=="drop"):   //indicates that the end-user pressed YES for dropping out patient
			print_r($_SESSION["dropout_info"]);
			
			list($mreg,$dreg,$yreg) = explode('/',$_SESSION["dropout_info"]["txt_date_reg"]);
			list($mdrop,$ddrop,$ydrop) = explode('/',$_SESSION["dropout_info"]["txt_date_dropout"]);

			$reg = $yreg.'-'.$mreg.'-'.$dreg;
			$drop = $ydrop.'-'.$mdrop.'-'.$ddrop;
			$drop_reason = $_SESSION["dropout_info"]["sel_dropout"];
			$drop_remarks = $_SESSION["dropout_info"]["dropout_remarks"];
			$tx_partner = $_SESSION["dropout_info"]["txt_treatment_partner"];
			$fp_px_id = $_SESSION["dropout_info"]["fp_px_id"];
			
			$update_fp = mysql_query("UPDATE m_patient_fp_method SET date_registered='$reg',treatment_partner='$tx_partner',drop_out='Y',dropout_reason='$drop_reason',date_dropout='$drop',dropout_remarks='$drop_remarks' WHERE fp_px_id='$fp_px_id'") or die("Cannot query: 593");

			if($update_fp):
                            unset($_SESSION["dropout_info"]);
                            echo "<script language='javascript'>";
                            echo "alert('The patient was successfully been dropped from this method');";
                            echo "window.location.reload();";
                            echo "</script>";
			endif;
		endif;

		echo "<form name='form_methods' action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=METHODS' method='POST'>";


		echo "<table>";
		echo "<a name='methods'></a>";

			if(mysql_num_rows($q_fp_methods)==0): //scenario 1
			    
				$this->show_method_list('form_methods','sel_methods');
				$this->show_previous_method("None");

				echo "<tr><td>TREATMENT PARTNER</td><td><input type='text' name='txt_tx_partner' size='20'></input></td></tr>";				

				echo "<tr><td>REASON FOR PERMANENT METHOD</td>";
				echo "<td><textarea name='txt_reason' cols='30' row='10'>";
				echo "</textarea></td></tr>";

				$this->show_fp_clients();

				echo "<tr><td colspan='2' align='center'><input type='submit' name='submit_fp' value='Save Family Planning Method'></input></td></tr>";

			else: //scenario 2-3
				$arr_current = $this->show_current_method($q_fp_methods); //return the most current FP method used		                
				$reason_drop = $arr_current[1]["dropout_reason"];
				$q_dropreason = mysql_query("SELECT reason_label FROM m_lib_fp_dropoutreason WHERE reason_id='$reason_drop'") or die("Cannot query: 635");
				list($dropout_reason) = mysql_fetch_array($q_dropreason);				
                            
				$fp_px_id = $arr_current[0]["fp_px_id"];
				$method_id = $arr_current[0]["method_id"];

				//print_r($arr_current);

				switch($arr_current[0]["drop_out"]){
				
					case "Y":     //previous user of FP method, not present user
						/*echo "<tr><td>SELECT METHOD:</td><td>";
						echo $this->get_methods("sel_methods");
						echo "</td></tr>"; */
                                                            
						$this->show_method_list('form_methods','sel_methods');
						echo "<tr><td>TREATMENT PARTNER</td><td><input type='text' name='txt_tx_partner' size='20'></input></td></tr>";				
                                                $this->show_fp_clients();
                                                
                                                echo "<tr><td>REASON FOR PERMANENT METHOD</td>";
                                                echo "<td><textarea name='txt_reason' cols='30' row='10'>";
                                                echo "</textarea></td></tr>";
                                                
                                                
			    			echo "<tr><td>PREVIOUS METHOD:</td><td>";
						echo (isset($arr_current[0]["method_name"]))?$arr_current[0]["method_name"]:'None';
						echo "</td></tr>";

						echo "<tr><td>DATE OF DROPOUT:</td><td>";
						echo (isset($arr_current[0]["method_name"]))?$arr_current[0]["date_dropout"]:'None';
						echo "</td></tr>";

						echo "<tr><td>DURATION OF USE:</td><td>".$arr_current[0]["duration"]." days </td></tr>";

						echo "<tr><td>REASON FOR DROPOUT</td><td>".$dropout_reason."</td></tr>";

						echo "<tr><td colspan='2' align='center'><input type='submit' name='submit_fp' value='Save Family Planning Method'></input></td></tr>";

						break;

					case "N":		//current users of FP method
					        
						echo "<input type='hidden' name='fp_px_id' value='$fp_px_id'></input>";
						echo "<input type='hidden' name='method_id' value='$method_id'></input>";

						echo "<tr><td>CURRENT METHOD:</td><td>".$arr_current[0]["method_name"]."</td></tr>";
						list($y,$m,$d) = explode('-',$arr_current[0]["date_registered"]);
						$datereg = $m.'/'.$d.'/'.$y;
																		
						$this->show_fp_clients($arr_current[0]["client_code"]);						
												
						echo "<tr><td>DATE OF REGISTRATION:</td><td>";
						echo "<input type='text' name='txt_date_reg' size='8' maxlength='10' value='$datereg'>&nbsp;";
						echo "<a href=\"javascript:show_calendar4('document.form_methods.txt_date_reg', document.form_methods.txt_date_reg.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click here to pick up date'></a>";
						echo "</td></tr>";

						echo "<tr><td>TREATMENT PARTNER:</td><td>";
						$txpartner = $arr_current[0][treatment_partner];
						echo "<input type='text' size='20' value='$txpartner' name='txt_treatment_partner'></input>";
						echo "</td></tr>";
						                                            
						
						echo "<tr><td>REASON FOR PERMANENT METHOD</td>";
                                                echo "<td><textarea name='txt_reason' cols='30' row='10'>".$arr_current[0][permanent_reason];
                				echo "</textarea></td></tr>";
				
						echo "<tr><td>PREVIOUS METHOD:</td><td>";
						echo (isset($arr_current[1]["method_name"]))?$arr_current[1]["method_name"]:'None';
						echo "</td></tr>";

						echo "<tr><td>DURATION OF USE:</td><td>";
						echo (isset($arr_current[1]["method_name"]))?$arr_current[1]["duration"]:'NA';
						echo " days</td></tr>";

						echo "<tr><td>REASON FOR DISCONTINUATION:</td><td>";
						
						//echo (isset($arr_current[1]["method_name"]))?$arr_current[1]["dropout_reason"]:'NA';
						
						echo (isset($arr_current[1]["method_name"]))?$dropout_reason:'NA';
						echo "</td></tr>";

						echo "<tr><td>REASON FOR DROP OUT</td>";
						$q_dropout = mysql_query("SELECT reason_id, reason_label FROM m_lib_fp_dropoutreason ORDER by reason_label ASC") or die("Cannot query: 659");

						if(mysql_num_rows($q_dropout)!=0):
								echo "<td><select name='sel_dropout' size='1'>";
								echo "<option value='0' DEFAULT>----- Patient not drop out -----</option>";
								while($r_dropout = mysql_fetch_array($q_dropout)){
									echo "<option value='$r_dropout[reason_id]'>$r_dropout[reason_label]</option>";
								}
								echo "</select></td>";
						else:
								echo "<font color='red'>FP Library dropout missing.</font>";
						endif;

						echo "<tr><td>DATE OF DROP OUT</td><td>";
						echo "<input type='text' name='txt_date_dropout' size='8' maxlength='10'>&nbsp;";
						echo "<a href=\"javascript:show_calendar4('document.form_methods.txt_date_dropout', document.form_methods.txt_date_dropout.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click here to pick up date'></a>";
						echo "</input>";

						echo "</td></tr>";

						echo "<tr><td valign='top'>REMARKS / ACTION TAKEN</td>";
						echo "<td><textarea name='dropout_remarks' cols='20' rows='4'></textarea></td></tr>";

						echo "<tr><td  align='center' colspan='2'><input type='submit' name='submit_fp' value='Update Family Planning Method'></input></td>";

						//echo "</form>";
						break;

					default:
						break;
				}

			endif;
		echo "</table>";
		echo "</form>";

		$this->history_methods();

		else:
				$this->no_fp_msg();
		endif;

	}

	function form_fp_visit1(){
		$pxid = healthcenter::get_patient_id($_GET[consult_id]);
		$q_fp = $this->check_fprec();

		if(mysql_num_rows($q_fp)==0):
			$this->no_fp_msg();
			$actual_child = $desired_child = $ave_monthly_income = $birth_interval = 0;
		else:
			list($fp_id,$more_child,$actual_child,$desired_child,$birth_interval,$educ_id,$occup_id,$spouse_name,$spouse_educ_id,$spouse_occup_id,$ave_monthly_income) = mysql_fetch_array($q_fp);
		endif;

		echo "<form name='form_visit1' action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=VISIT1' method='POST'>";
		echo "<input type='hidden' name='pxid' value='$pxid'>";
		echo "<a name='visit1'></a>";

		echo "<table>";

		echo "<tr><td colspan='2'>FAMILY PLANNING DATA</td></tr>";

		/*
		echo "<tr><td>DATE OF REGISTRATION</td><td><input type='text' name='txt_date_reg' size='8' maxlength='10'>";

		print "<a href=\"javascript:show_calendar4('document.form_visit1.txt_date_reg', document.form_visit1.txt_date_reg.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click here to pick up date'></a>";

		echo "</td></tr>";

		echo "<tr><td>TYPE OF METHOD</td><td>";
		$this->get_methods("sel_method");
		echo "</td></tr>";
		*/
		$ans = array('Y'=>'Yes','N'=>'No');

		echo "<tr><td>PLANNING FOR MORE CHILDREN?</td>";
		echo "<td>";
		echo "<select name='sel_plan_children' size='1'>";

		foreach($ans as $key => $value){
			if($key==$more_child):
				echo "<option value='$key' selected>$value</option>";
			else:
				echo "<option value='$key'>$value</option>";
			endif;
		}
		echo "</select>";
		echo "</td></tr>";

		echo "<tr><td>NO. OF LIVING CHILDREN (ACTUAL)</td><td>";
		echo "<input name='num_child_actual' type='text' size='3' maxlength='2' value='$actual_child'></input>";
		echo "</td></tr>";

		echo "<tr><td>NO. OF LIVING CHILDREN (DESIRED)</td><td>";
		echo "<input name='num_child_desired' type='text' size='3' maxlength='2' value='$desired_child'></input>";
		echo "</td></tr>";

		echo "<tr><td>BIRTH INTERVAL DESIRED</td><td>";
		echo "<input name='birth_interval' type='text' size='3' maxlength='2' value='$birth_interval'></input>";
		echo "</td></tr>";
		echo "<tr><td>HIGHEST EDUCATIONAL ATTAINMENT</td><td>";
		$this->get_education("mother_educ",$educ_id);
		echo "</td></tr>";

		echo "<tr><td>OCCUPATION</td><td>";
		$this->get_occupation("mother_occupation",$occup_id);
		echo "</td></tr>";

		echo "<tr><td>PATIENT ID OF SPOUSE IN CHITS</td>";
		echo "<td><input name='spouse_name' type='text' size='20' value='$spouse_name'></input>&nbsp;<input type='button' name='btn_search_spouse' value='Search' onclick='verify_patient_id();'></input>";

		echo "</td></tr>";
		echo "<tr><td>HIGHEST EDUCATIONAL ATTAINMENT</td><td>";
		$this->get_education("spouse_educ",$spouse_educ_id);
		echo "</td></tr>";

		echo "<tr><td>OCCUPATION</td><td>";
		$this->get_occupation("spouse_occupation",$spouse_occup_id);
		echo "</td></tr>";

		echo "<tr><td>AVERAGE MONTHLY FAMILY INCOME</td>";
		echo "<td>";
		echo "<input name='ave_income' type='text' size='5' value='$ave_monthly_income'></input>";
		echo "</td></tr>";

		if(!isset($fp_id)):
			echo "<tr><td colspan='2' align='center'><input type='submit' name='submit_fp' value='Save Family Planning First Visit'></td></tr>";
		else:
			echo "<tr><td colspan='2' align='center'><input type='submit' name='submit_fp' value='Update Family Planning First Visit'></td></tr>";
		endif;

		echo "</table>";

		echo "</form>";
	}

	function form_fp_history(){
		$q_fp = $this->check_fprec();
		$pxid = healthcenter::get_patient_id($_GET[consult_id]);

		if(mysql_num_rows($q_fp)!=0):

		$q_hx_cat = mysql_query("SELECT cat_id, cat_name FROM m_lib_fp_history_cat") or die("Cannot query: 280");

		if(mysql_num_rows($q_hx_cat)!=0):
			$fp_arr = mysql_fetch_array($q_fp);

			echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=HX#hx' name='form_fp_hx' method='POST'>";

			echo "<input type='hidden' value='$pxid' name='pxid'></input>";
			echo "<input type='hidden' value='$fp_arr[fp_id]' name='fpid'>";
			echo "<a name='hx'></a>";
			echo "<table>";
			echo "<thead><td>MEDICAL HISTORY</td></thead>";

			while($res_hx_cat = mysql_fetch_array($q_hx_cat)){
				$q_hx = mysql_query("SELECT history_id,history_text FROM m_lib_fp_history WHERE history_cat='$res_hx_cat[cat_id]'") or die("Cannot query: 287");

				echo "<tr><td>$res_hx_cat[cat_name]</td></tr>";

				echo "<tr><td>";

				while($res_hx = mysql_fetch_array($q_hx)){
					$q_hx_patient = mysql_query("SELECT history_id FROM m_patient_fp_hx WHERE consult_id='$_GET[consult_id]' AND patient_id='$pxid' AND history_id='$res_hx[history_id]'") or die("Cannot query: 765");

					list($hxid) = mysql_fetch_array($q_hx_patient);

					if($hxid == $res_hx[history_id]):
						echo "<input type='checkbox' name='sel_hx[]' value='$res_hx[history_id]' checked><b><font color='red'>".$res_hx["history_text"]."</font></b></input><br>";
					else:
						echo "<input type='checkbox' name='sel_hx[]' value='$res_hx[history_id]'>".$res_hx["history_text"]."</input><br>";
					endif;
				}

				echo "</td></tr>";
			}
			echo "<tr><td><input type='submit' name='submit_fp' value='Save FP History'></td></tr>";
			echo "</table>";
			echo "</form>";
		else:
			echo "<font color='red'>FP History Library not found.</font>";
		endif;

		else:
				$this->no_fp_msg();
		endif;
	}

	function form_fp_pe(){
		$q_fp = $this->check_fprec();

		$pxid = healthcenter::get_patient_id($_GET[consult_id]);

		if(mysql_num_rows($q_fp)!=0):

		list($fpid) = mysql_fetch_array($q_fp);

		$q_pe_cat = mysql_query("SELECT pe_cat_id, pe_cat_name FROM m_lib_fp_pe_cat") or die("Cannot query: 350");
		echo "<a name='pe'></a>";

		if(mysql_num_rows($q_pe_cat)!=0):

		$q_consult_vitals = mysql_query("SELECT vitals_systolic, vitals_diastolic, vitals_weight, vitals_pulse FROM m_consult_vitals WHERE consult_id='$_GET[consult_id]'") or die("Cannot query: 805");

		echo "<form method='post' name='form_fp_pe' action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=PE#pe'>";

		echo "<input type='hidden' name='pxid' value='$pxid'></input>";
		echo "<input type='hidden' name='fpid' value='$fpid'></input>";
		echo "<table border='1'>";
		echo "<thead><td colspan='2' align='center'>PHYSICAL EXAMINATION</td></thead>";

		echo "<tr><td colspan='2'>";

		echo "<table border='1'>";
		if(mysql_num_rows($q_consult_vitals)==0):
				echo "<font color='red'><b>Please fill out the Vital Signs section for this consult. Click <a href='$_SERVER[PHP_SELF]?page=CONSULTS&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=VITALS'>here</a></font></b>";

		else:
				list($systolic,$diastolic,$weight,$pulse) = mysql_fetch_array($q_consult_vitals);
				echo "<tr><td>Blood Pressure: &nbsp;$systolic / $diastolic</td>";
				echo "<td>Weight&nbsp; $weight kgs</td>";
				echo "<td>Pulse Rate:&nbsp;$pulse per minute</td></tr>";
		endif;

		echo "</table>";

		echo "</td></tr>";

		echo "<tr><td><table>";

		while($r_pe_cat = mysql_fetch_array($q_pe_cat)){
			$q_pe = mysql_query("SELECT pe_id, pe_name FROM m_lib_fp_pe WHERE pe_cat='$r_pe_cat[pe_cat_id]'") or die("Cannot query: 356");
			//echo "<tr><td>".$r_pe_cat["pe_cat_name"]."</td></tr>";
			echo "<tr><td valign='top'>".$r_pe_cat["pe_cat_name"]."</td>";
			echo "<td>";
			while($r_pe = mysql_fetch_array($q_pe)){

				$q_fp_pe = mysql_query("SELECT pe_id FROM m_patient_fp_pe WHERE patient_id='$pxid' AND consult_id='$_GET[consult_id]' AND pe_id='$r_pe[pe_id]'") or die("Cannot query : 831");
				$q_pe_others = mysql_query("SELECT pe_others FROM m_patient_fp WHERE patient_id='$pxid' AND fp_id='$fpid'") or die("cannot query: 849");

				list($peid) = mysql_fetch_array($q_fp_pe);
				list($pe_others) = mysql_fetch_array($q_pe_others);

				if($r_pe[pe_id]==$peid):
					echo "<input type='checkbox' name='sel_pe[]' value='$r_pe[pe_id]' checked><font color='red'><b>".$r_pe["pe_name"]."</b></font></input><br>";
				else:
					echo "<input type='checkbox' name='sel_pe[]' value='$r_pe[pe_id]'>".$r_pe["pe_name"]."</input><br>";
				endif;
			}
			echo "</td></tr>";
		}

		echo "<tr><td>OTHERS&nbsp;</td><td><textarea name='txt_pe_others' rows='5' cols='40'>$pe_others</textarea></td></tr>";

		echo "</table></td>";

		echo "</tr>";

		echo "<tr align='center'><td colspan='2'><input type='submit' name='submit_fp' value='Save Physical Examination'></input></td></tr>";

		echo "</table>";
		echo "</form>";

		else:
			echo "<font color='red'>FP Physical Exam library is not found.</font>";
		endif;

		else:
			$this->no_fp_msg();
		endif;
	}


	function form_fp_pelvicpe(){
		$q_fp = $this->check_fprec();
		$pxid = healthcenter::get_patient_id($_GET[consult_id]);
		$px_gender = patient::get_gender($pxid);

		if($px_gender=='F'):

		if(mysql_num_rows($q_fp)!=0):

		list($fpid) = mysql_fetch_array($q_fp);

		$q_pelvic_exam = mysql_query("SELECT pelvic_cat_id,pelvic_cat_name FROM m_lib_fp_pelvic_cat") or die(mysql_error());

		if(mysql_num_rows($q_pelvic_exam)!=0):
			echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=PELVIC#pelvic' method='POST' name='form_pelvic'>";

			echo "<input type='hidden' name='pxid' value='$pxid'></input>";
			echo "<input type='hidden' name='fpid' value='$fpid'></input>";

			echo "<a name='pelvic'></a>";
			echo "<table border='1'>";
			echo "<thead><td align='center' colspan='2'>PELVIC EXAMINATION</td></thead>";

			while($r_pelvic_exam = mysql_fetch_array($q_pelvic_exam)){
				$cat = $r_pelvic_exam[pelvic_cat_id];

				echo "<tr><td>$r_pelvic_exam[pelvic_cat_name]</td>";

				$q_pelvic_cat = mysql_query("SELECT pelvic_id,pelvic_name,pelvic_cat FROM m_lib_fp_pelvic WHERE pelvic_cat='$r_pelvic_exam[pelvic_cat_id]'") or die("Cannot query: 464");

				echo "<td>";

				if($r_pelvic_exam[pelvic_cat_id]=="UTERUSMASS"):
					$q_uterine = mysql_query("SELECT uterine_mass_iud FROM m_patient_fp WHERE fp_id='$fpid' AND patient_id='$pxid'") or die("Cannot query: 915");
					list($uterine_mass) = mysql_fetch_array($q_uterine);

					echo "<font size='1'><b>Uterine Depth (for Intended IUD Users) <input type='text' name='txt_uterine_depth' size='5' maxlength='4' value='$uterine_mass'></input> cms</b></font>";
				else:
					while($r_pelvic_cat=mysql_fetch_array($q_pelvic_cat)){
						$q_pelvic = mysql_query("SELECT pelvic_id FROM m_patient_fp_pelvic WHERE consult_id='$_GET[consult_id]' AND patient_id='$pxid' AND pelvic_id='$r_pelvic_cat[pelvic_id]'") or die("Cannot query: 921");
						list($pelvic_id) = mysql_fetch_array($q_pelvic);

						if($pelvic_id==$r_pelvic_cat[pelvic_id]):
							echo "<input type='checkbox' name='sel_pecat[]' value='$r_pelvic_cat[pelvic_id]' checked><font color='red'><b>$r_pelvic_cat[pelvic_name]</b></font></input>";
						else:
							echo "<input type='checkbox' name='sel_pecat[]' value='$r_pelvic_cat[pelvic_id]'>$r_pelvic_cat[pelvic_name]</input>";
						endif;
					}
				endif;
				echo "</td>";
				echo "</tr>";
			}

			echo "<tr><td colspan='2' align='center'><input type='submit' name='submit_fp' value='Save Pelvic Examination'></input></td></tr>";

			echo "</table>";
			echo "</form>";
		else:
				echo "<font color='red'>FP Pelvic Exam Library not found</font>";
		endif;

		else:
				$this->no_fp_msg();
		endif;

		else:
			echo "<br><br><font color='red'>Pelvic Examination is only for female patients</font><br><br>";
		endif;
	}

	function menu_highlight(){  //this function highlights the active fp submenu
		if(func_num_args()>0){
			$val = func_get_args();
			$get_val = $val[0];
			$str = $val[1];
			$disp_str = $val[2];
		}

		if(strtoupper($get_val)==$str):
			return "<b>".$disp_str."</b>";
		else:
			return $disp_str;
		endif;
	}


	function _details_family_planning(){
		if(func_num_args()>0){
			$menu_id = $arg_list[0];
			$post_vars = $arg_list[1];
			$get_vars = $arg_list[2];
			$validuser = $arg_list[3];
			$isadmin = $arg_list[4];
		}
	}
	

	function get_education($form_name,$educ_id){

		$q_educ = mysql_query("select * from m_lib_education order by educ_name") or die("cannot query 187");

		if(mysql_num_rows($q_educ)!=0):
			echo "<select name='$form_name' size='1'>";
			while($r_educ = mysql_fetch_array($q_educ)){

				if($educ_id == $r_educ[educ_id]):
					echo "<option value='$r_educ[educ_id]' SELECTED>$r_educ[educ_name]</option>";
				else:
					echo "<option value='$r_educ[educ_id]'>$r_educ[educ_name]</option>";
				endif;
			}
			echo "</select>";
		else:
			echo "<font color='red'>Education library not found.</font>";
		endif;
	}

	function get_occupation($form_name, $occup_id){

		$q_job = mysql_query("SELECT occup_id, occup_name FROM m_lib_occupation ORDER by occup_name") or die("Cannot query: 187");

		if(mysql_num_rows($q_job)!=0):
			echo "<select name='$form_name' size='1'>";
			while($r_job = mysql_fetch_array($q_job)){
				if($occup_id == $r_job[occup_id]):
					echo"<option value='$r_job[occup_id]' selected>$r_job[occup_name]</option>";
				else:
					echo"<option value='$r_job[occup_id]'>$r_job[occup_name]</option>";
				endif;
			}
			echo "</select>";
		else:
			echo "<font color='red'>Occupation library not found.</font>";
		endif;
	}

	function get_methods($form_name){
		$pxid = healthcenter::get_patient_id($_GET[consult_id]);

		$q_gender =  mysql_query("SELECT patient_gender FROM m_patient WHERE patient_id='$pxid'") or die("Cannot query: 158");
		list($gender) = mysql_fetch_array($q_gender);

		$q_methods = mysql_query("SELECT method_id,method_name FROM m_lib_fp_methods WHERE method_gender='$gender' ORDER by method_name ASC") or die("Cannot query: 268");

		if(mysql_num_rows($q_methods)!=0):
			echo "<select name='$form_name'>";
			while($r_methods = mysql_fetch_array($q_methods)){
				echo "<option value='$r_methods[method_id]'>$r_methods[method_name]</option>";
			}
			echo "</select>";
		else:
			echo "<font color='red'>FP Method library not found.</font>";
		endif;
	}

	function form_fp_chart(){
		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);

		// check first if there is an active FP method the user is presently using
		$q_fp = mysql_query("SELECT fp_px_id, date_format(date_registered,'%m/%d/%Y'),fp_id,method_id FROM m_patient_fp_method WHERE patient_id=$pxid AND drop_out='N'") or die(mysql_error());

		$q_supplier = mysql_query("SELECT source_id,source_name,source_cat FROM m_lib_supply_source") or die("Cannot query: 790");

		if(mysql_num_rows($q_fp)!=0):
					list($fp_px_id, $date_reg,$fp_id,$method_id) = mysql_fetch_array($q_fp);
					if(isset($_GET["service_id"])):
							$q_service = mysql_query("SELECT date_format(date_service,'%m/%d/%Y'), source_id, remarks, date_format(next_service_date,'%m/%d/%Y') FROM m_patient_fp_method_service WHERE fp_service_id='$_GET[service_id]'") or die(mysql_error());
							list($date_service,$source,$remarks,$next_service) = mysql_fetch_array($q_service);
					endif;

					if($_POST["confirm_del"]==1):
							$this->delete_service_record();
					endif;

					echo "<table>";
					echo "<tr><td valign='top'>";

					echo "<form action='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&fp=CHART#chart' method='POST' name='form_fp_chart'>";
					echo "<input type='hidden' value='$fp_px_id' name='fp_px_id'></input>";
					echo "<input type='hidden' value='$date_reg' name='date_reg'></input>";
					echo "<input type='hidden' value='$fp_id' name='fp_id'></input>";
					echo "<input type='hidden' name='confirm_del'></input>";


					echo "<a name='chart'></a>";

					echo "<table>";
					//echo "<thead><td>FP CHART</td></thead>";
					echo "<tr><td>ACTIVE FP METHOD</td>";
					echo "<td><font color='blue'><b>$method_id</td></b></font></tr>";


					echo "<tr><td>DATE SERVICE GIVEN</td><td><input type='text' name='txt_date_service' size='7' maxlength='11' value='$date_service'>";
					echo "<a href=\"javascript:show_calendar4('document.form_fp_chart.txt_date_service', document.form_fp_chart.txt_date_service.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click here to pick up date'></a>";
					echo "</input></td></tr>";

					echo "<tr><td>SOURCE OF SUPPLY</td><td>";
					if(mysql_num_rows($q_supplier)!=0):
						echo "<select name='sel_supply' size='1'>";
						while($r_supplier = mysql_fetch_array($q_supplier)){
							if($source == $r_supplier[source_id]):
								echo "<option value='$r_supplier[source_id]' SELECTED>$r_supplier[source_name]</option>";
							else:
								echo "<option value='$r_supplier[source_id]'>$r_supplier[source_name]</option>";
							endif;
						}
						echo "</select>";
					else:
						echo "<input type='hidden' name='sel_supply' value='5'></input>";
					endif;
					
					echo "</td>";
					
					echo "<tr><td>REMARKS</td><td><textarea cols='27' rows='5' name='txt_remarks'>$remarks</textarea></td></tr>";
					echo "<tr><td>NEXT SERVICE DATE</td><td><input type='text' name='txt_next_service_date' size='7' maxlength='11' value='$next_service'>";
					echo "<a href=\"javascript:show_calendar4('document.form_fp_chart.txt_next_service_date', document.form_fp_chart.txt_next_service_date.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click here to pick up date'></a>";
					echo "</input></td></tr>";

					echo "<tr>";
					if(isset($_GET["service_id"])):  //this indicates that a service record was chosen/clicked from the table
						echo "<input type='hidden' name='service_id' value='$_GET[service_id]'></input>";
						echo "<td colspan='2' align='center'>";
					   if($_SESSION["priv_update"]==1):
									echo "<input type='submit' name='submit_fp' value='Update FP Service Chart'></input>";
					   endif;

						if($_SESSION["priv_delete"]==1):
									echo "<input type='button' name='submit_fp' value='Delete FP Service Record' onclick='delete_fp_service()'></input>";
					   endif;

					   echo "<input type='button' name='submit_fp' value='Cancel Transaction' onclick='history.go(-1)'></input>";   //returns to the previous cleared form

					   echo "</td>";
					else:
						echo "<td colspan='2' align='center'><input type='submit' name='submit_fp' value='Save FP Service Chart'></input></td>";
					endif;

					echo "</tr>";
					echo "</table>";
					echo "</form>";

					echo "</td><td valign='top'>";

					$q_service = mysql_query("SELECT fp_service_id, date_service, a.source_id, source_name, next_service_date FROM m_patient_fp_method_service a, m_lib_supply_source b WHERE a.fp_id='$fp_id' AND a.fp_px_id='$fp_px_id' AND a.patient_id='$pxid' AND  a.source_id=b.source_id ORDER by date_service DESC") or die(mysql_error());

					if(mysql_num_rows($q_service)!=0):
						echo "<table>";
						echo "<tr valign='top'><td>Date Service Given</td><td>Source</td><td>Next Service Date</td></tr>";

							while($r_service = mysql_fetch_array($q_service)){
								echo "<tr><td><a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&service_id=$r_service[fp_service_id]&fp=CHART#chart'>$r_service[date_service]</a></td>";
								echo "<td>$r_service[source_name]</td>";
								echo "<td>$r_service[next_service_date]</td>";
								echo "</tr>";
							}

						echo "</table>";

					endif;

					echo "</td></tr></table>";
		else:
					echo "<br>";
					echo "<font color='red'><b>No active family planning method for this patient.</b></font>";
					echo "<br><br>";
		endif;
	}

	function form_fp_obs(){
		$pxid = healthcenter::get_patient_id($_GET[consult_id]);
		$q_fp = $this->check_fprec();
		$px_gender = patient::get_gender($pxid);

			if($px_gender=='F'):

						if(mysql_num_rows($q_fp)!=0):

						echo "<form action='$_SERVER[PHP_SELF]' method='POST' name='form_fp_obs'>";
						echo "<a name='obs'></a>";
						echo "<table>";
						echo "<thead><td colspan='2'>OBSTETRICAL HISTORY</td></thead>";

						echo "<tr><td>Number of Pregnancies (FPAL)</td>";
						echo "<td><input type='text' name='txt_fp_fpal' size='3' maxlength='4'></td></tr>";

						echo "<tr><td>Date of Last Delivery</td><td><input type='text' name='txt_last_delivery' size='7' maxlength='11'>";

						echo "<a href=\"javascript:show_calendar4('document.form_fp_obs.txt_last_delivery', document.form_fp_obs.txt_last_delivery.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click here to pick up date'></a>";
						echo "</input></td></tr>";

						echo "<tr><td>TYPE OF LAST DELIVERY</td><td><input type='text' name='txt_type_delivery' size='10'></td></tr>";

						echo "<tr><td>PAST MENSTRUAL PERIOD</td><td><input type='text' name='txt_past_mens' size='7' maxlength='11'>";
						echo "<a href=\"javascript:show_calendar4('document.form_fp_obs.txt_past_mens', document.form_fp_obs.txt_past_mens.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click here to pick up date'></a>";
						echo "</input></td></tr>";

						echo "<tr><td>LAST MENSTRUAL PERIOD</td><td><input type='text' name='txt_last_mens' size='7' maxlength='11'>";
						echo "<a href=\"javascript:show_calendar4('document.form_fp_obs.txt_last_mens', document.form_fp_obs.txt_last_mens.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click here to pick up date'></a>";
						echo "</input></td></tr>";


						echo "<tr><td>Duration and Character of Menstrual Bleeding</td><td><input type='text' name='txt_mens_bleed' size='3'></input> days</td></tr>";

						echo "<tr><td colspan='2' align='center'><input type='submit' name='submit_fp' value='Save Obstectrical History'></td></tr>";

						echo "</table>";
						echo "</form>";

						else:
							$this->no_fp_msg();
						endif;

		else:
			echo "<br><br><font color='red'>Obstetrical history is only for female patients</font><br><br>";
		endif;
	}

	function show_method_list($form_name,$sel_dropdown){

		echo "<tr><td>DATE OF REGISTRATION</td><td><input type='text' name='txt_date_reg' size='8' maxlength='10'>";

		print "<a href=\"javascript:show_calendar4('document.$form_name.txt_date_reg', document.$form_name.txt_date_reg.value);\"><img src='../images/cal.gif' width='16' height='16' border='0' alt='Click here to pick up date'></a>";

		echo "</td></tr>";

		echo "<tr><td>TYPE OF METHOD</td><td>";
		$this->get_methods($sel_dropdown);
		echo "</td></tr>";
	}

	function show_previous_method($prev_method){
		echo "<tr><td>Previous Method Used</td>";
		echo "<td>".$prev_method."</td>";
		echo "</tr>";
	}

	function submit_method_visit(){

		list($month,$date,$year) = explode('/',$_POST["txt_date_reg"]);
		$permanent = ($_POST["sel_methods"]=='FSTRBTL' || $_POST["sel_methods"]=='MSV')?'Y':'N';

		$reg_date = $year.'-'.$month.'-'.$date;

		if(empty($_POST[txt_date_reg])):
			echo "<script language='javascript'''>";
			echo "alert('Please supply date of registration')";
			echo "</script>";

		elseif($permanent=='N' && !empty($_POST[txt_reason])):
			echo "<script language='javascript'''>";
			echo "alert('The method you have choosen is not permanent. Please delete reason for using permanent method')";
			echo "</script>";
		else:
					$pxid = healthcenter::get_patient_id($_GET["consult_id"]);
					$get_fp = mysql_query("SELECT fp_id FROM m_patient_fp WHERE patient_id='$pxid'") or die("Cannot query: 1189");
					list($fpid) = mysql_fetch_array($get_fp);
					
					$insert_fp_method_service = mysql_query("INSERT INTO m_patient_fp_method SET fp_id='$fpid',patient_id='$pxid',consult_id='$_GET[consult_id]',date_registered='$reg_date',date_encoded=DATE_FORMAT(NOW(),'%y-%m-%d'),method_id='$_POST[sel_methods]',treatment_partner='$_POST[txt_tx_partner]',user_id='$_SESSION[userid]',permanent_method='$permanent',client_code='$_POST[sel_clients]',permanent_reason='$_POST[txt_reason]'") or die("Cannot query ".mysql_error());

						if($insert_fp_method_service):
							echo "<font color='red'>Patient was successfully been enrolled in $_POST[sel_methods]</font>";
						else:
							echo "<font color='red'>Patient was not enrolled in $_POST[sel_methods]</font>";
						endif;
		endif;
	}

	function show_current_method($q_fp_methods){
			$arr_current = array();
			while($r_methods = mysql_fetch_array($q_fp_methods)){
				array_push($arr_current,$r_methods);
			}

			//$arr_current = mysql_fetch_array($q_fp_methods);
			return $arr_current;
	}

	function submit_first_visit(){

			$spouse_name = trim($_POST[spouse_name]);
			if(empty($spouse_name)):
					$this->no_spouse_msg();
			else:
					//print_r($_SESSION);
					$q_fp = $this->check_fprec();
					if(mysql_num_rows($q_fp)==0): //a new FP visit 1 record

					$insert_fp = mysql_query("INSERT INTO m_patient_fp SET  user_id='$_SESSION[userid]',patient_id='$_POST[pxid]', date_enrolled=NOW(),date_encoded=NOW(),consult_id='$_GET[consult_id]',last_edited=NOW(),plan_more_children='$_POST[sel_plan_children]',no_of_living_children_actual='$_POST[num_child_actual]',no_of_living_children_desired='$_POST[num_child_desired]',birth_interval_desired='$_POST[birth_interval]',educ_id='$_POST[mother_educ]',occup_id='$_POST[mother_occupation]',spouse_name='$_POST[spouse_name]',spouse_educ_id='$_POST[spouse_educ]',spouse_occup_id='$_POST[spouse_occupation]',ave_monthly_income='$_POST[ave_income]',user_id_edited='$_SESSION[userid]'") or die(mysql_error());

								if($insert_fp):
										echo "<script language='Javascript'>";
										echo "window.alert('FP Record was saved. Please fill out the FP History, Physical Exam, Pelvic Exam and Obstetrical Exam (for females)')";
										echo "</script>";
								endif;
					else: // this is an update of an existing FP visit 1 record

							$update_fp = mysql_query("UPDATE m_patient_fp SET user_id_edited='$_SESSION[userid]',last_edited='NOW()',plan_more_children='$_POST[sel_plan_children]',no_of_living_children_actual='$_POST[num_child_actual]',no_of_living_children_desired='$_POST[num_child_desired]',birth_interval_desired='$_POST[birth_interval]',educ_id='$_POST[mother_educ]',occup_id='$_POST[mother_occupation]',spouse_name='$_POST[spouse_name]',spouse_educ_id='$_POST[spouse_educ]',spouse_occup_id='$_POST[spouse_occupation]',ave_monthly_income='$_POST[ave_income]' WHERE patient_id='$_POST[pxid]'") or die("Cannot query: 1125");

							if($update_fp):
										echo "<script language='Javascript'>";
										echo "window.alert('FP Record was successfully updated')";
										echo "</script>";
							endif;
					endif;
			endif;
	}

	function submit_fp_history(){
			$del_hx = mysql_query("DELETE FROM m_patient_fp_hx WHERE patient_id='$_POST[pxid]' AND consult_id='$_GET[consult_id]'") or die('Cannot query: 1144');

			$hx_arr = $_POST[sel_hx];

			for($i=0;$i<sizeof($hx_arr);$i++){
				$insert_hx = mysql_query("INSERT INTO m_patient_fp_hx SET fp_id='$_POST[fpid]',patient_id='$_POST[pxid]',consult_id='$_GET[consult_id]',history_id='$hx_arr[$i]',date_encoded=NOW(),user_id='$_SESSION[userid]',last_edited=NOW(),user_id_edited='$_SESSION[userid]'") or die("Cannot query: 1148");
			}

			echo "<script language='Javascript'>";
			echo "window.alert('FP History is successfully been updated.')";
			echo "</script>";
	}

	function submit_fp_pe(){
		$del_pe = mysql_query("DELETE FROM m_patient_fp_pe WHERE patient_id='$_POST[pxid]' AND consult_id='$_GET[consult_id]'") or die("Cannot query: 1182");

			$pe_arr = $_POST[sel_pe];

			for($i=0;$i<sizeof($pe_arr);$i++){
				$insert_pe = mysql_query("INSERT INTO m_patient_fp_pe SET fp_id='$_POST[fpid]',patient_id='$_POST[pxid]',pe_id='$pe_arr[$i]',consult_id='$_GET[consult_id]',date_encoded=NOW(),user_id='$_SESSION[userid]',last_edited=NOW(),user_id_edited='$_SESSION[userid]'") or die(mysql_error());
			}

			$update_pe_others = mysql_query("UPDATE m_patient_fp SET pe_others='$_POST[txt_pe_others]' WHERE fp_id='$_POST[fpid]' AND patient_id='$_POST[pxid]'") or die("Cannot query: 1235");

			echo "<script language='Javascript'>";
			echo "window.alert('FP Physical Exam was successfully been updated.')";
			echo "</script>";
	}

	function submit_fp_pelvic(){

		$del_pelvic = mysql_query("DELETE FROM m_patient_fp_pelvic WHERE patient_id='$_POST[pxid]' AND consult_id='$_GET[consult_id]'") or die("Cannot query: 1230");

		$pelvic_arr = $_POST[sel_pecat];

		for($i=0;$i<sizeof($pelvic_arr);$i++){
			$insert_pelvic = mysql_query("INSERT INTO m_patient_fp_pelvic SET fp_id='$_POST[fpid]',patient_id='$_POST[pxid]',consult_id='$_GET[consult_id]',pelvic_id='$pelvic_arr[$i]',date_encoded=NOW(),user_id='$_SESSION[userid]',last_edited=NOW(),user_id_edited='$_SESSION[userid]'") or die("Cannot query 1235");
		}

		$update_uterine_mass = mysql_query("UPDATE m_patient_fp SET uterine_mass_iud='$_POST[txt_uterine_depth]' WHERE fp_id='$_POST[fpid]' AND patient_id='$_POST[pxid]'") or die("Cannot query: 1238");

		echo "<script language='Javascript'>";
		echo "window.alert('FP Pelvic Exam was successfully been updated.')";
		echo "</script>";
	}

	function submit_fp_service(){

			$diff_service = empty($_POST[txt_date_service])?'':mc::get_day_diff($_POST[txt_date_service],$_POST[date_reg]);
			$diff_next = empty($_POST[txt_next_service_date])?'':mc::get_day_diff($_POST[txt_next_service_date],$_POST[txt_date_service]);

			$pxid = healthcenter::get_patient_id($_GET["consult_id"]);


			if(empty($_POST[txt_date_service])):
					echo "<script language='javascript'>";
					echo "alert('Date that this service was given cannot be empty')";
					echo "</script>";

			elseif(!empty($_POST[txt_next_service_date]) && $diff_next <= 0):
					echo "<script language='javascript'>";
					echo "window.alert('Next service date should be after the date this service was given.')";
					echo "</script>";

			elseif($diff_service < 0):

					echo "<script language='javascript'>";
					echo "window.alert('Date that this service was given should be on or after the date of registration for this method.')";
					echo "</script>";

			else:
				list($month,$date,$year) = explode('/',$_POST["txt_date_service"]);
				list($m2,$d2,$y2) = explode('/',$_POST["txt_next_service_date"]);

				$date_service  =  $year.'-'.$month.'-'.$date;
				$next_service_date = $y2.'-'.$m2.'-'.$d2;

				// if no other error was found, start inserting entries to the m_patient_fp_method_service

				if(isset($_POST["service_id"])): //this signifies an update has been done
					$update_service = mysql_query("UPDATE m_patient_fp_method_service SET date_service='$date_service',source_id='$_POST[sel_supply]',remarks='$_POST[txt_remarks]',next_service_date='$next_service_date' WHERE fp_service_id='$_POST[service_id]'") or die(mysql_error());

				else:
					$insert_service = mysql_query("INSERT into m_patient_fp_method_service SET fp_id='$_POST[fp_id]',fp_px_id='$_POST[fp_px_id]',patient_id='$pxid',consult_id='$_GET[consult_id]',date_service='$date_service',remarks='$_POST[txt_remarks]',date_encoded=NOW(),user_id='$_SESSION[userid]',next_service_date='$next_service_date',source_id='$_POST[sel_supply]'") or die(mysql_error());
				endif;

				if($insert_service):
					echo "<script language='javascript'>";
					echo "alert('FP service was successfully been saved.')";
					echo "</script>";
				elseif($update_service):
					echo "<script language='javascript'>";
					echo "alert('FP service was successfully been updated.')";
					echo "</script>";
				else:
				endif;

			endif;
	}

	function delete_service_record(){
			$delete_service = mysql_query("DELETE FROM m_patient_fp_method_service WHERE fp_service_id='$_POST[service_id]'") or die("Cannot query: 1418");

			if($delete_service):
					echo "<script language='javascript'>";
					echo "alert('FP service was successfully been deleted.')";
					echo "</script>";
			endif;
	}

	function update_method_visit(){
	                
			if(empty($_POST["txt_date_reg"])):
                                echo "<script language='javascript'>";
                                echo "alert('Date of registration cannot be empty.')";
                                echo "</script>";

			elseif($_POST["sel_dropout"]!=0 && empty($_POST["txt_date_dropout"])):  // make a check that when dropping a patient, there should be a date of drop out
                                echo "<script language='javascript'>";
                                echo "alert('Cannot drop patient from this method. Indicate a date of drop out.')";
                                echo "</script>";
			else:

				$q_date = mysql_query("SELECT date_service FROM m_patient_fp_method_service WHERE fp_px_id='$_POST[fp_px_id]'") or die("Cannot query: 1469");
				$greater = 0;
				$greater_drop = 0;
				while($r_date  = mysql_fetch_array($q_date)){
					list($y,$m,$d) = explode('-',$r_date["date_service"]);
					$date_r = $m.'/'.$d.'/'.$y;

					$diff = mc::get_day_diff($date_r,$_POST["txt_date_reg"]);
					$greater = ($diff < 0)?$greater+1:$greater;

					if(!empty($_POST["txt_date_dropout"])):
							$diff_drop = mc::get_day_diff($_POST["txt_date_dropout"],$date_r);
							$greater_drop = ($diff_drop < 0)?$greater_drop+1:$greater_drop;
					endif;
				}

				if($greater !=0):
					echo "<script language='javascript'>";
					echo "alert('There is a conflict between the date of treatment and one of the date of services. Date of treatment should be on or before any date of service.')";
					echo "</script>";

				elseif($greater_drop!=0):
					echo "<script language='javascript'>";
					echo "alert('There is a conflict between the date of drop out and one of the date of services. Date of drop out should be on or after any date of service.')";
					echo "</script>";

				else:
						if($_POST["sel_dropout"]!=0 && !empty($_POST["txt_date_dropout"])):    // this indicates that a drop out is being made
							$diff_drop_reg = mc::get_day_diff($_POST["txt_date_dropout"],$_POST["txt_date_reg"]);

							if($diff_drop_reg < 0):
									echo "<script language='javascript'>";
									echo "alert('Date of drop out should be after the date of registration!')";
									echo "</script>";
							else:
									$_SESSION["dropout_info"] = $_POST;
                                                                        
									echo "<font color='red'><b>Are you sure you wanted to drop this patient?</b></font>&nbsp;";
									echo "<a href='$_SERVER[PHP_SELF]?page=$_GET[page]&menu_id=$_GET[menu_id]&consult_id=$_GET[consult_id]&ptmenu=$_GET[ptmenu]&module=$_GET[module]&action=drop&fp=METHODS#methods'>Yes</a>&nbsp;&nbsp;&nbsp;";
									echo "<a href='' onclick='history.go(-1)'>No</a>";
																		
							endif;

						else: //   a simple edit of date of registration, type of client and treatment partner
								list($m,$d,$y) = explode('/',$_POST["txt_date_reg"]);
								$date_reg = $y.'-'.$m.'-'.$d;
                                                                
								$update_fp_method = mysql_query("UPDATE m_patient_fp_method SET date_registered='$date_reg', treatment_partner='$_POST[txt_treatment_partner]',client_code='$_POST[sel_clients]',permanent_reason='$_POST[txt_reason]' WHERE fp_px_id='$_POST[fp_px_id]'") or die("Cannot query: 1546");

								if($update_fp_method):
									echo "<script language='javascript'>";
									echo "alert('FP method was successfully been edited')";
									echo "</script>";
								endif;

						endif;

				endif;
			endif;
	}


	function history_methods(){
		$pxid = healthcenter::get_patient_id($_GET["consult_id"]);

		$q_history = mysql_query("SELECT fp_px_id, fp_id,date_format(date_registered,'%m/%d/%Y') as date_reg,date_format(date_dropout,'%m/%d/%Y') as date_dropout , method_id, dropout_reason FROM m_patient_fp_method WHERE patient_id='$pxid' AND drop_out='Y' ORDER by date_reg ASC") or die("Cannot query: 1565");

	if(mysql_num_rows($q_history)!=0):
		echo "<table><tr>";
		echo "<td>Method</td>";
		echo "<td>Date Registered</td>";
		echo "<td>Date of Drop Out</td>";
		echo "<td>Dropout Reason</td>";
		echo "<td>Services</td></tr>";

		while(list($fp_px_id, $fp_id, $date_reg, $date_drop, $method_id,$dropout_reason)=mysql_fetch_array($q_history)){
					$q_method = mysql_query("SELECT method_name FROM m_lib_fp_methods WHERE method_id='$method_id'") or die("Cannot query: 1577");
					list($method_name) = mysql_fetch_array($q_method);

					$q_dropout_reason = mysql_query("SELECT reason_label FROM m_lib_fp_dropoutreason WHERE reason_id='$dropout_reason'") or die("Cannot query: 1577");
					list($reason_dropout) = mysql_fetch_array($q_dropout_reason);

					echo "<tr>";
					echo "<td>".$method_name."</td>";
					echo "<td>".$date_reg."</td>";
					echo "<td>".$date_drop."</td>";
					echo "<td>".$reason_dropout."</td>";
					echo "<td><a href='../site/view_fp_services.php?id=$fp_px_id&method_id=$method_id&px=$pxid' target='new'>View</a></td>";
					echo "</tr>";

		}
		echo "</table>";
	endif;

	}

	function check_fprec(){
		//function shall check if there exists an FP Service Record

		$pxid = healthcenter::get_patient_id($_GET[consult_id]);

		$q_fp = mysql_query("SELECT  fp_id, plan_more_children,  no_of_living_children_actual, no_of_living_children_desired, birth_interval_desired,educ_id, occup_id, spouse_name, spouse_educ_id, spouse_occup_id, ave_monthly_income FROM m_patient_fp WHERE patient_id='$pxid'") or die("Cannot query in form_fp_visit1:  line 625");

		return $q_fp;     //returns a resource identifier
	}

	function no_fp_msg(){
		echo "<font color='red'>NOTE: Patient does not have a previous FP record. Please fill out this FP Data (Visit 1) form first before enrolling the patient to any method.</font>";
	}

	function no_spouse_msg(){
		echo "<script languauge='Javascript'>";
		echo "window.alert('Please indicate the name of the spouse. Patients enrolled in FP should have partners.')";
		echo "</script>";
	}

	function show_fp_clients(){
	
	if(func_num_args()>0):
	    $arr = func_get_args();
	    $client_code_passed = $arr[0];	    
	endif;
	
        $q_clients = mysql_query("SELECT client_id, client_code, client_text FROM m_lib_fp_client ORDER by client_text ASC") or die("CAnnot query: 1637");

        if(mysql_num_rows($q_clients)!=0){
            echo "<tr><td>TYPE OF CLIENT</td>";
            echo "<td>";
            echo "<select name='sel_clients' size='1'>";

            while(list($client_id,$client_code,$client_text)=mysql_fetch_array($q_clients)){
                if($client_code_passed==$client_code):
                    echo "<option value='$client_code' SELECTED>$client_text</option>";
                else:
                    echo "<option value='$client_code'>$client_text</option>";
                endif;
            }

            echo "</select></td></tr>";
        }
	}

}
?>
