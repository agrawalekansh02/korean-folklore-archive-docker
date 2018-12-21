/* Add new REPORT_HISTORY table */
CREATE TABLE report_history (
  id int(11) NOT NULL AUTO_INCREMENT,
  quarter_id int(11) NOT NULL,
  active_collectors int(11) NOT NULL,
  new_consultants int(11) NOT NULL,
  new_contexts int(11) NOT NULL,
  new_data int(11) NOT NULL,
  total_data_size int(11) NOT NULL,
  report_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Update COLLECTOR table in order to set all 0000 dates to NULL and back to date */
ALTER TABLE collector
MODIFY collector_dob varchar(50) DEFAULT NULL;

UPDATE collector SET collector_dob = NULL WHERE collector_dob = '0000-00-00';
UPDATE collector SET collector_dob = CONCAT(YEAR(collector_dob), '-01-', '01')  WHERE MONTH(collector_dob) = '00' AND DAY(collector_dob) = '00';
UPDATE collector SET collector_dob = CONCAT(YEAR(collector_dob), '-', MONTH(collector_dob),'-01')  WHERE DAY(collector_dob) = '00';
UPDATE collector SET collector_dob = CONCAT(YEAR(collector_dob), '-01-', DAY(collector_dob))  WHERE MONTH(collector_dob) = '00';

ALTER TABLE collector
MODIFY collector_dob date DEFAULT NULL;

/* Update CONSULTANT table:
    - add new columns
    - modify consultant_consent_form from int to varchar to save actual filename
    - modify consultant_dob and consultant_immigration_date in order to set all 0000 dates to NULL and back to date 
*/
ALTER TABLE consultant
ADD COLUMN consultant_box_file_id bigint(20) DEFAULT NULL,
ADD COLUMN consultant_quarter_created int(11) DEFAULT NULL,
ADD COLUMN consultant_date_created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
MODIFY consultant_consent_form varchar(100) DEFAULT NULL,
MODIFY consultant_dob varchar(50) DEFAULT NULL,
MODIFY consultant_immigration_date varchar(50) DEFAULT NULL,
MODIFY consultant_file_type varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL;

UPDATE consultant SET consultant_dob = NULL WHERE consultant_dob = '0000-00-00';
UPDATE consultant SET consultant_dob = CONCAT(YEAR(consultant_dob), '-01-', '01')  WHERE MONTH(consultant_dob) = '00' AND DAY(consultant_dob) = '00';
UPDATE consultant SET consultant_dob = CONCAT(YEAR(consultant_dob), '-', MONTH(consultant_dob),'-01')  WHERE DAY(consultant_dob) = '00';
UPDATE consultant SET consultant_dob = CONCAT(YEAR(consultant_dob), '-01-', DAY(consultant_dob))  WHERE MONTH(consultant_dob) = '00';

UPDATE consultant SET consultant_immigration_date = NULL WHERE consultant_immigration_date = '0000-00-00';
UPDATE consultant SET consultant_immigration_date = CONCAT(YEAR(consultant_immigration_date), '-01-', '01')  WHERE MONTH(consultant_immigration_date) = '00' AND DAY(consultant_immigration_date) = '00';
UPDATE consultant SET consultant_immigration_date = CONCAT(YEAR(consultant_immigration_date), '-', MONTH(consultant_immigration_date),'-01')  WHERE DAY(consultant_immigration_date) = '00';
UPDATE consultant SET consultant_immigration_date = CONCAT(YEAR(consultant_immigration_date), '-01-', DAY(consultant_immigration_date))  WHERE MONTH(consultant_immigration_date) = '00';

ALTER TABLE consultant
MODIFY consultant_dob date DEFAULT NULL,
MODIFY consultant_immigration_date date DEFAULT NULL;

/* Update CONTEXT table:
    - add new columns
    - modify context_date in order to set all 0000 dates to NULL and back to date 
*/
ALTER TABLE context 
ADD COLUMN context_quarter_created int(11) DEFAULT NULL,
ADD COLUMN context_date_created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
MODIFY context_date varchar(50) DEFAULT NULL;

UPDATE context SET context_date = NULL WHERE context_date = '0000-00-00';
UPDATE context SET context_date = CONCAT(YEAR(context_date), '-01-', '01')  WHERE MONTH(context_date) = '00' AND DAY(context_date) = '00';
UPDATE context SET context_date = CONCAT(YEAR(context_date), '-', MONTH(context_date),'-01')  WHERE DAY(context_date) = '00';
UPDATE context SET context_date = CONCAT(YEAR(context_date), '-01-', DAY(context_date))  WHERE MONTH(context_date) = '00';

ALTER TABLE context
MODIFY context_date date DEFAULT NULL;

/* Update DATA table
    - add new columns
    - modify data_file from int to varchar to save actual filename
*/
ALTER TABLE data
ADD COLUMN data_box_file_id bigint(20) DEFAULT NULL,
ADD COLUMN data_quarter_created int(11) DEFAULT NULL,
ADD COLUMN data_date_created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
MODIFY data_file_type varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
MODIFY data_file varchar(100) DEFAULT NULL;

/* Update table engines to allow for rollback, foreign keys, constraints, etc... */
ALTER TABLE collector ENGINE=InnoDB;
ALTER TABLE collector_quarter ENGINE=InnoDB;
ALTER TABLE consultant ENGINE=InnoDB;
ALTER TABLE context ENGINE=InnoDB;
ALTER TABLE consultant ENGINE=InnoDB;
ALTER TABLE quarter ENGINE=InnoDB;
ALTER TABLE report_history ENGINE=InnoDB;

