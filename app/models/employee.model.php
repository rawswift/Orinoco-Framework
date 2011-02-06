<?php
/*
+------------+----------------------------------------+------+-----+---------------------+-----------------------------+
| Field      | Type                                   | Null | Key | Default             | Extra                       |
+------------+----------------------------------------+------+-----+---------------------+-----------------------------+
| id         | bigint(20)                             | NO   | PRI | NULL                | auto_increment              |
| first_name | varchar(255)                           | YES  |     | NULL                |                             |
| last_name  | varchar(255)                           | YES  |     | NULL                |                             |
| phone      | varchar(255)                           | YES  |     | NULL                |                             |
| job_id     | bigint(20)                             | YES  |     | NULL                |                             |
| created_on | timestamp                              | NO   |     | 0000-00-00 00:00:00 |                             |
| updated_on | timestamp                              | NO   |     | CURRENT_TIMESTAMP   | on update CURRENT_TIMESTAMP |
| access     | enum('Administrator','Normal','Guest') | NO   |     | Guest               |                             |
+------------+----------------------------------------+------+-----+---------------------+-----------------------------+
 */
class employee extends Record {
	public $id = array('primary' => true);
	public $first_name;
	public $last_name;
	public $phone;
	public $job_id = array('model' => 'job', 'join' => 'right');
	public $created_on;
	public $updated_on;
	public $access;
}