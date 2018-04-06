DROP TABLE report_history;

ALTER TABLE consultant
DROP COLUMN consultant_quarter_created,
DROP COLUMN consultant_date_created;

ALTER TABLE context
DROP COLUMN context_quarter_created,
DROP COLUMN context_date_created;

ALTER TABLE data
DROP COLUMN data_quarter_created,
DROP COLUMN data_date_created;

