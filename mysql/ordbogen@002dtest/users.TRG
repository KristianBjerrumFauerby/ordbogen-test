TYPE=TRIGGERS
triggers='CREATE DEFINER=`admin`@`%` TRIGGER `bi_createTimestamp` BEFORE INSERT ON `users` FOR EACH ROW SET new.created_at = UNIX_TIMESTAMP()'
sql_modes=524288
definers='admin@%'
client_cs_names='utf8mb4'
connection_cl_names='utf8mb4_general_ci'
db_cl_names='latin1_swedish_ci'
created=159296150275
