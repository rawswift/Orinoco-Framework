<?php
/*
 * test ORM controller
 * using the updated Orinoco Framework ORM 
 */
class ormController extends Controller {
	
	function index() {
		/*
		 * do nothing, let the view engine render associated contents
		 */
		
/*
 * test model
 */
	$employee = new employee();
	$employee->find(array(
					'limit' => '0,100'
				));
	
	echo '<br />Record count (All): ' . $employee->record_count();
	
	echo '<br /><table border="1" style="border-collapse:collapse;">';
	while ($employee->next()) {
		echo '<tr>';
			echo '<td>' . $employee->id . '</td>';
			echo '<td>' . $employee->first_name . '</td>';
			echo '<td>' . $employee->last_name . '</td>';
			echo '<td>' . $employee->name . '</td>';
		echo '</tr>';
	}
	echo '</table>';

	//$employee->iterate();		
		
/*
 * get first record
 */	
	$resp = $employee->first(); // go to first record
	echo '<h1>First Record</h1>';
	echo '<br /><table border="1" style="border-collapse:collapse;">';
		echo '<tr>';
			echo '<td>' . $employee->id . '</td>';
			echo '<td>' . $employee->first_name . '</td>';
			echo '<td>' . $employee->last_name . '</td>';
			echo '<td>' . $employee->name . '</td>';
		echo '</tr>';
	/*while ($employee->iterate()) {
		echo '<tr>';
			echo '<td>' . $employee->id . '</td>';
			echo '<td>' . $employee->first_name . '</td>';
			echo '<td>' . $employee->last_name . '</td>';
			echo '<td>' . $employee->name . '</td>';
		echo '</tr>';
	}*/
	echo '</table>';	
	
/*
 * get last record
 */	
	$resp = $employee->last(5); // go to last record
	echo '<h1>Last Record</h1>';
	echo '<br /><br /><table border="1" style="border-collapse:collapse;">';
	while ($employee->next()) {
		echo '<tr>';
			echo '<td>' . $employee->id . '</td>';
			echo '<td>' . $employee->first_name . '</td>';
			echo '<td>' . $employee->last_name . '</td>';
			echo '<td>' . $employee->name . '</td>';
		echo '</tr>';
	}
	echo '</table>';	

/*
 * end test model
 */
		
	}
	
}

// -EOF-