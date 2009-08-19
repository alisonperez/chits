-- MySQL dump 9.10
--
-- Host: localhost    Database: game
-- ------------------------------------------------------
-- Server version	4.0.18-Max

--
-- Table structure for table `m_lib_region`
--

CREATE TABLE m_lib_region (
  region_id varchar(10) NOT NULL default '',
  region_provinces varchar(255) NOT NULL default '',
  region_name varchar(50) NOT NULL default '',
  PRIMARY KEY  (region_id)
) TYPE=InnoDB;

--
-- Dumping data for table `m_lib_region`
--

INSERT INTO m_lib_region VALUES ('1','Ilocos Norte, Ilocos Sur, La Union, Pangasinan','Ilocos Region');
INSERT INTO m_lib_region VALUES ('10','Bukidnon, Camiguin, Misamis Occidental, Misamis Oriental, Lanao del Norte','Northern Mindanao');
INSERT INTO m_lib_region VALUES ('11','Davao City, Davao del Norte, Davao del Sur, Davao Oriental, Compostela Valley','Davao');
INSERT INTO m_lib_region VALUES ('12','North Cotabato, Sultan Kudarat, South Cotabato, Saranggani','SOCCSKSARGEN');
INSERT INTO m_lib_region VALUES ('13','Agusan del Norte, Agusan del Sur, Surigao del Norte, Surigao del Sur','CARAGA');
INSERT INTO m_lib_region VALUES ('2','Batanes, Cagayan, Isabela, Nueva Vizcaya, Quirino','Cagayan Valley');
INSERT INTO m_lib_region VALUES ('3','Aurora, Bataan, Bulacan, Pampanga, Nueva Ecija, Tarlac, Zambales','Central Luzon');
INSERT INTO m_lib_region VALUES ('4A','Batangas, Cavite, Laguna, Quezon, Rizal','CALABARZON');
INSERT INTO m_lib_region VALUES ('4B','Marinduque, Occidental Mindoro, Oriental Mindoro, Palawan, Romblon','MIMAROPA');
INSERT INTO m_lib_region VALUES ('5','Albay, Camarines Sur, Camarines Norte, Catanduanes, Sorsogon, Masbate','Bicol');
INSERT INTO m_lib_region VALUES ('6','Aklan, Antique, Capiz, Iloilo, Guimaras, Negros Occidental','Western Visayas');
INSERT INTO m_lib_region VALUES ('7','Bohol, Cebu, Negros Oriental, Siquijor','Central Visayas');
INSERT INTO m_lib_region VALUES ('8','Biliran, Eastern Samar, Leyte, Northern Samar, Southerm Leyte','Easttern Visayas');
INSERT INTO m_lib_region VALUES ('9','Zamboanga Sibugay, Zamboanga del Sur, Zamboanga del Norte, Zamboanga City, Isabela City','Zamboanga Peninsula');
INSERT INTO m_lib_region VALUES ('ARMM','Basilan, Sulu, Tawi-tawi, Lanao del Sur, Maguindanao','Autonomous Region of Muslim Mindanao');
INSERT INTO m_lib_region VALUES ('CAR','Abra, Benguet, Ifugao, Kalinga, Apayao, Mountain Province','Cordillera Administrative Region');
INSERT INTO m_lib_region VALUES ('NCR','Caloocan, Las Piñas, Quezon City, Makati, Manila, Muntinlupa,    Parañaque, Pasig, Pasay, Malabon, Mandaluyong,    Marikina and Valenzuela and the municipalities    of Navotas, Pateros, San Juan and Taguig','National Capital Region');

