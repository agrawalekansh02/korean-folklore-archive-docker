CREATE TABLE report_history (
  id int(11) NOT NULL AUTO_INCREMENT,
  quarter_id int(11) NOT NULL,
  active_collectors int(11) NOT NULL,
  new_consultants int(11) NOT NULL,
  new_contexts int(11) NOT NULL ,
  new_data int(11) NOT NULL,
  total_data_size int(11) NOT NULL,
  report_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

ALTER TABLE consultant
ADD COLUMN consultant_quarter_created int(11) default null,
ADD COLUMN consultant_date_created TIMESTAMP NULL default CURRENT_TIMESTAMP;

ALTER TABLE context
ADD COLUMN context_quarter_created int(11) default null,
ADD COLUMN context_date_created TIMESTAMP NULL default CURRENT_TIMESTAMP;

ALTER TABLE data
ADD COLUMN data_quarter_created int(11) default null,
ADD COLUMN data_date_created TIMESTAMP NULL default CURRENT_TIMESTAMP;

