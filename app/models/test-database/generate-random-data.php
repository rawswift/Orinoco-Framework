<?php
/* Debug: measure execution time: start time */
$time_start = microtime(true);

/*
 * employee table reference:
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

$link = mysql_connect('localhost', 'your_username', 'your_password'); // connect to mysql
mysql_select_db('your_database_name', $link); // select database

$date_created = date("Y-m-d H:i:s", time());		

$access = array();
$access[1] = "Administrator";
$access[2] = "Normal";
$access[3] = "Guest";

for ($i = 0; $i <= 1000; $i++) { // generate 1,000 random data

	$rand_access = rand(1, 3);
	
	// insert data
	mysql_query('INSERT INTO employee (
					first_name,
					last_name,
					phone,
					job_id,
					created_on,
					updated_on,
					access
				) VALUES (
					"' . genRandomString(8) . '",
					"' . genRandomString(10) . '",
					"' . rand(1111111, 9999999) . '",
					' . rand(1, 5) . ',
					"' . $date_created . '",
					"' . $date_created . '",
					"' . $access[$rand_access] . '"
				)');
}

mysql_close($link); // close mysql connection

echo '<pre>Done!</pre>'; // fin

/* Debug: measure execution time: end time then print */
$time_end = microtime(true);
$time = $time_end - $time_start;
echo  "<pre>Query time: " . round($time,5) . " s</pre>";

/*
 * from: http://www.lost-in-code.com/programming/php-code/php-random-string-with-numbers-and-letters/
 */
function genRandomString($length = 10) {
    $string = "x";
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    return $string;
}
	
// -EOF-