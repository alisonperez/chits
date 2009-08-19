-- phpMyAdmin SQL Dump
-- version 2.11.3deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 09, 2008 at 09:56 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `chitsquery`
--

-- --------------------------------------------------------

--
-- Table structure for table `childcare_indicators`
--

CREATE TABLE IF NOT EXISTS `childcare_indicators` (
  `ind_id` int(2) NOT NULL auto_increment,
  `childcare_label` text NOT NULL,
  `ques_id` int(2) NOT NULL,
  `seq_id` int(2) NOT NULL,
  PRIMARY KEY  (`ind_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `childcare_indicators`
--

INSERT INTO `childcare_indicators` (`ind_id`, `childcare_label`, `ques_id`, `seq_id`) VALUES
(1, 'Fully Immunized Children (9-11 mos)', 31, 1),
(2, 'Infant given 3rd dose of Hepa B', 31, 2),
(3, 'Infants seen at 6th month', 31, 3),
(4, 'Infants exclusively breastfed up to 6th month', 31, 4),
(5, 'Diarrhea cases given ORS (0-59 mos)', 31, 5),
(6, 'Pneumonia cases seen (0-59 mos)', 31, 6),
(7, 'Pneumonia cases given treatment(0-59 mos)', 31, 7),
(8, 'Children (9-11 mos) given Vit. A capsules', 31, 8),
(9, 'Children (12-59 mos) given Vit. A capsules', 31, 9),
(10, 'Moderately underweight children (6-59 mos)', 31, 10),
(11, '- Given food supplementation', 31, 11),
(12, '- Receiving food supplementation', 31, 11),
(13, '- Rehabilitated', 31, 13),
(14, 'Severely underweight children (6-59 mos)', 31, 14),
(15, '- Given food supplementation', 31, 15),
(16, '- Receiving food supplementation', 31, 16),
(17, '- Rehabilitated', 31, 17);

-- --------------------------------------------------------

--
-- Table structure for table `crit_class`
--

CREATE TABLE IF NOT EXISTS `crit_class` (
  `critclass_id` int(3) NOT NULL auto_increment,
  `ques_id` int(2) NOT NULL,
  `crit_id` int(2) NOT NULL,
  PRIMARY KEY  (`critclass_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `crit_class`
--


-- --------------------------------------------------------

--
-- Table structure for table `maternal_indicators`
--

CREATE TABLE IF NOT EXISTS `maternal_indicators` (
  `mat_id` int(11) NOT NULL auto_increment,
  `mat_label` text NOT NULL,
  `ques_id` int(2) NOT NULL,
  `seq_id` int(2) NOT NULL,
  PRIMARY KEY  (`mat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `maternal_indicators`
--

INSERT INTO `maternal_indicators` (`mat_id`, `mat_label`, `ques_id`, `seq_id`) VALUES
(1, 'Pregnant with 4 or more AP visits', 30, 1),
(2, 'Pregnant given TT1', 30, 2),
(3, 'Pregnant given TT2', 30, 3),
(4, 'Pregnant given TT3', 30, 4),
(5, 'Pregnant given TT4', 30, 5),
(6, 'Pregnant given TT5', 30, 6),
(7, 'Pregnant given TT2+', 30, 7),
(8, 'Pregnant given Complete Iron Dosage', 30, 8),
(9, 'Pregnant given Vitamin A', 30, 9),
(10, 'Postpartum with at least 1 PP visit', 30, 10),
(11, 'Postpartum given Complete Iron Dosage', 30, 11),
(12, 'Postpartum women initiated breastfeeding', 30, 12),
(13, 'Prenatal - Target Client List (TCL)', 30, 13),
(14, 'Prenatal Form - Indicators', 30, 14);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `ques_id` int(3) NOT NULL auto_increment,
  `ques_label` varchar(200) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `sql_code` text NOT NULL,
  PRIMARY KEY  (`ques_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`ques_id`, `ques_label`, `cat_id`, `sql_code`) VALUES
(1, 'Number of Male and Female per barangay', 1, '"SELECT a.patient_lastname, a.patient_firstname, a.patient_gender, b.family_id, c.barangay_id, d.barangay_name\r\nFROM m_patient a, m_family_members b, m_family_address c, m_lib_barangay d\r\nWHERE a.patient_id = b.patient_id \r\nAND b.family_id = c.family_id AND c.barangay_id = d.barangay_id AND a.registration_date >=''$_SESSION[sdate]'' and a.registration_date<=''$_SESSION[edate]'' ORDER by d.barangay_name ASC, a.patient_gender DESC, a.patient_lastname ASC, a.patient_firstname ASC"'),
(2, 'Age and sex distribution', 1, ''),
(3, 'Top ten causes of morbidity', 7, ''),
(4, 'Number of families who have consulted', 2, ''),
(5, 'Number of families with PhilHealth', 2, ''),
(6, 'Number of families with PhilHealth that have been given services', 2, ''),
(7, 'Number of FIC''s (including "OZ''s")', 3, ''),
(8, 'Number of Antigen given', 3, ''),
(9, 'Number of Antigen not given', 3, ''),
(10, 'Number of Missed EPI appointments', 3, ''),
(11, 'Pregnant mothers with risk factors', 4, ''),
(12, 'Pregnant mothers under 18 years old', 4, ''),
(13, 'Mothers given TT, FeSO4, Vit A', 4, ''),
(14, 'Mothers consulting monthly', 4, ''),
(15, 'Mother given T1 to T5', 4, ''),
(16, 'NSD per location per attendant', 4, ''),
(17, 'Mothers who breastfed her child after birth', 4, ''),
(18, 'Ferous Sulfate given', 4, ''),
(19, 'Lactating mother given Vit. A', 4, ''),
(20, 'Patient per regimen per barangay', 5, ''),
(21, 'Positive and Negative Per Classification', 5, ''),
(22, 'NTP Patients Undergoing Treatment', 5, ''),
(23, 'Completed NTP treatment per barangay', 5, ''),
(24, 'Defaulters Per Regimen', 5, ''),
(25, 'NTP Relapsed Cases', 5, ''),
(26, 'NTP Patients by age and sex', 5, ''),
(27, 'Class of weight status according to age and status', 6, ''),
(28, 'Monthly Weight Distribution', 6, ''),
(29, 'FHSIS Notifiable Disease Report', 7, ''),
(30, 'FHSIS Maternal Care', 4, ''),
(31, 'FHSIS Child Care Report', 8, ''),
(32, 'Antigens Provided for CCDEV', 8, ''),
(33, 'List of Patients Not in Family Folder', 1, ''),
(34, 'Prenatal Form - Target Client List (TCL)', 4, ''),
(35, 'Postpartum Form - Target Client List (TCL)', 4, '');

-- --------------------------------------------------------

--
-- Table structure for table `ques_cat`
--

CREATE TABLE IF NOT EXISTS `ques_cat` (
  `cat_id` int(2) NOT NULL auto_increment,
  `cat_label` varchar(200) NOT NULL,
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `ques_cat`
--

INSERT INTO `ques_cat` (`cat_id`, `cat_label`) VALUES
(1, 'Patient Information'),
(2, 'Family Folder'),
(3, 'EPI'),
(4, 'Maternal Care'),
(5, 'NTP'),
(6, 'Nutrition'),
(7, 'Notifiable Diseases'),
(8, 'Child Care');
