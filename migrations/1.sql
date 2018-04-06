-- Original Table structures
--
-- Host: localhost    Database: kfl
--
-- Table structure for table `collector`
--

CREATE TABLE collector (
  collector_id int(11) NOT NULL AUTO_INCREMENT,
  collector_last_name varchar(20) COLLATE utf8_bin DEFAULT NULL,
  collector_first_name varchar(20) COLLATE utf8_bin DEFAULT NULL,
  collector_initial varchar(5) COLLATE utf8_bin DEFAULT NULL,
  collector_sid varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT 'UCLA ID',
  collector_email varchar(50) COLLATE utf8_bin DEFAULT NULL,
  collector_street varchar(50) COLLATE utf8_bin DEFAULT NULL,
  collector_city varchar(30) COLLATE utf8_bin DEFAULT NULL,
  collector_state varchar(20) COLLATE utf8_bin DEFAULT NULL,
  collector_zipcode varchar(20) COLLATE utf8_bin DEFAULT NULL,
  collector_country varchar(20) COLLATE utf8_bin DEFAULT NULL,
  collector_age smallint(6) DEFAULT NULL,
  collector_dob date DEFAULT NULL,
  collector_gender varchar(1) COLLATE utf8_bin DEFAULT NULL,
  collector_marital_status varchar(10) COLLATE utf8_bin DEFAULT NULL,
  collector_occupation varchar(30) COLLATE utf8_bin DEFAULT NULL,
  collector_edu_level varchar(20) COLLATE utf8_bin DEFAULT NULL,
  collector_heritage varchar(600) COLLATE utf8_bin DEFAULT NULL,
  collector_language varchar(100) COLLATE utf8_bin DEFAULT NULL,
  collector_status tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=past,1=current,2=admin',
  PRIMARY KEY (collector_id),
  UNIQUE KEY collector_sid (collector_sid)
);

--
-- Table structure for table `consultant`
--

CREATE TABLE consultant (
  consultant_id int(11) NOT NULL AUTO_INCREMENT,
  collector_id int(11) DEFAULT NULL,
  consultant_last_name varchar(20) COLLATE utf8_bin DEFAULT NULL,
  consultant_first_name varchar(20) COLLATE utf8_bin DEFAULT NULL,
  consultant_initial varchar(5) COLLATE utf8_bin DEFAULT NULL,
  consultant_show_level varchar(20) COLLATE utf8_bin DEFAULT NULL,
  consultant_street varchar(50) COLLATE utf8_bin DEFAULT NULL,
  consultant_city varchar(30) COLLATE utf8_bin DEFAULT NULL,
  consultant_state varchar(20) COLLATE utf8_bin DEFAULT NULL,
  consultant_zipcode varchar(20) COLLATE utf8_bin DEFAULT NULL,
  consultant_country varchar(20) COLLATE utf8_bin DEFAULT NULL,
  consultant_age smallint(6) DEFAULT NULL,
  consultant_dob date DEFAULT NULL,
  consultant_birth_city varchar(30) COLLATE utf8_bin DEFAULT NULL,
  consultant_birth_state varchar(20) COLLATE utf8_bin DEFAULT NULL,
  consultant_birth_country varchar(20) COLLATE utf8_bin DEFAULT NULL,
  consultant_gender varchar(1) COLLATE utf8_bin DEFAULT NULL,
  consultant_marital_status varchar(30) COLLATE utf8_bin DEFAULT NULL,
  consultant_occupation varchar(30) COLLATE utf8_bin DEFAULT NULL,
  consultant_edu_level varchar(20) COLLATE utf8_bin DEFAULT NULL,
  consultant_income_level int(11) DEFAULT NULL,
  consultant_heritage varchar(600) COLLATE utf8_bin DEFAULT NULL,
  consultant_language varchar(100) COLLATE utf8_bin DEFAULT NULL,
  consultant_immigration_status tinyint(1) DEFAULT NULL,
  consultant_immigration_date date DEFAULT NULL,
  consultant_file_name varchar(50) COLLATE utf8_bin DEFAULT NULL,
  consultant_file_type varchar(50) COLLATE utf8_bin DEFAULT NULL,
  consultant_file_size int(11) DEFAULT NULL,
  consultant_consent_form int(11) DEFAULT NULL,
  PRIMARY KEY (consultant_id)
);

--
-- Table structure for table `context`
--

CREATE TABLE context (
  context_id int(11) NOT NULL AUTO_INCREMENT,
  collector_id int(11) DEFAULT NULL,
  context_street varchar(50) COLLATE utf8_bin DEFAULT NULL,
  context_city varchar(30) COLLATE utf8_bin DEFAULT NULL,
  context_state varchar(20) COLLATE utf8_bin DEFAULT NULL,
  context_zipcode varchar(20) COLLATE utf8_bin DEFAULT NULL,
  context_country varchar(20) COLLATE utf8_bin DEFAULT NULL,
  context_place varchar(15) COLLATE utf8_bin DEFAULT NULL,
  context_latitude_direction varchar(1) COLLATE utf8_bin DEFAULT NULL,
  context_latitude_degree smallint(6) DEFAULT NULL,
  context_latitude_minsec double DEFAULT NULL,
  context_longitude_direction varchar(1) COLLATE utf8_bin DEFAULT NULL,
  context_longitude_degree smallint(6) DEFAULT NULL,
  context_longitude_minsec double DEFAULT NULL,
  context_time time DEFAULT NULL,
  context_date date DEFAULT NULL,
  context_weather varchar(10) COLLATE utf8_bin DEFAULT NULL,
  context_language varchar(100) COLLATE utf8_bin DEFAULT NULL,
  context_other_language varchar(50) COLLATE utf8_bin DEFAULT NULL,
  context_media varchar(50) COLLATE utf8_bin DEFAULT NULL,
  context_event_type varchar(800) COLLATE utf8_bin DEFAULT NULL,
  context_event_name varchar(20) COLLATE utf8_bin DEFAULT NULL,
  context_otherpresent_num smallint(6) DEFAULT NULL,
  context_otherpresent_age varchar(50) COLLATE utf8_bin DEFAULT NULL,
  context_description text COLLATE utf8_bin,
  context_consultants varchar(250) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (context_id)
);

--
-- Table structure for table `data`
--

CREATE TABLE data (
  data_id int(11) NOT NULL AUTO_INCREMENT,
  collector_id int(11) DEFAULT NULL,
  consultant_id int(11) DEFAULT NULL,
  context_id int(11) DEFAULT NULL,
  data_project_title varchar(50) COLLATE utf8_bin DEFAULT NULL,
  data_type varchar(20) COLLATE utf8_bin DEFAULT NULL,
  data_description text COLLATE utf8_bin,
  data_file_name varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  data_file_type varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  data_file_size int(11) DEFAULT NULL,
  data_file int(11) DEFAULT NULL,
  PRIMARY KEY (data_id)
);

--
-- Table structure for table `collector_quarter`
--

CREATE TABLE collector_quarter (
  collector_id int(11) NOT NULL,
  quarter_id smallint(3) NOT NULL
);

--
-- Table structure for table `quarter`
--

CREATE TABLE quarter (
  quarter_id int(5) NOT NULL AUTO_INCREMENT,
  quarter_short_name varchar(5) DEFAULT NULL,
  is_current_quarter tinyint(1) DEFAULT 0,
  PRIMARY KEY (quarter_id)
);
