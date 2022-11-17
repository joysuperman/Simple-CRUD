<?php

define('db_name', 'E:\Web_Page\TDG\Ampps\www\crud\data\data.txt');


function seed(){
	$data = array(
		array(
			'id' => 1,
			'fname' => 'Hello',
			'lname' => 'Word',
			'roll'  => '1'
		),array(
			'id' => 2,
			'fname' => 'Rahim',
			'lname' => 'Ahmed',
			'roll'  => '2'
		),array(
			'id' => 3,
			'fname' => 'Jahid',
			'lname' => 'Khan',
			'roll'  => '3'
		),
	);

	$serialized = serialize($data);
	file_put_contents(db_name, $serialized, LOCK_EX);
} 


function generateReport(){
	$serialized = file_get_contents(db_name);
	$students = unserialize($serialized);
	?>


	<table>
	  	<thead>
		    <tr>
		      	<th>Name</th>
		      	<th>Roll</th>
		      	<?php
		      		if (hasPrevilage()) { ?>
		      			<th style="width: 30%;">Action</th>
		      		<?php }
		      	?>
		      	
		    </tr>
	  	</thead>
	  	<tbody>

	  		<?php foreach ($students as $student) { ?>
  			<tr>
		      	<td><?php printf("%s %s",$student['fname'], $student['lname']); ?></td>
		      	<td><?php printf("%d",$student['roll']); ?></td>
		      	<?php
		      	if (hasPrevilage()) {
		      		?>
		      	<td>
	      		<?php 
		      		printf('<a href="/crud/index.php?task=edit&&id=%d">Edit</a> ',$student['id']);
		      	if (isAdmin()) {
		      		printf( '| <a class="delete" href="/crud/index.php?task=delete&&id=%d">Delete</a>', $student['id'] ); ?>
		      	</td>
	      	<?php 
	      		}
		    } 
	      	?>    	
		    </tr>
			<?php } ?>
		 
	  	</tbody>
	</table>

<?php }


function addStudent($fname, $lname){
	$found = false;
	$serialized = file_get_contents(db_name);
	$students = unserialize($serialized);

	

	foreach ($students as $_student){
		if ($_student['roll'] == $roll) {
			$found = true;
			break;
		}
	}
	
	if (!$found) {
		$newId = newId($students);
		$newRoll = newRoll($roll);

		$student = array(
		'id' => $newId,
		'fname' => $fname,
		'lname' => $lname,
		'roll'  => $newRoll
		);


		array_push($students, $student);

		$serialized = serialize($students);
		file_put_contents(db_name, $serialized, LOCK_EX);
		return true;
	}
	return false;
}



function getStudent($id){
	$serialized = file_get_contents(db_name);
	$students = unserialize($serialized);


	foreach ($students as $student){
		if ($student['id'] == $id) {
			return $student;
		}
	}return false;
}


function updateStudent($id, $fname, $lname, $roll){
	$found = false;
	$serialized = file_get_contents(db_name);
	$students = unserialize($serialized);

	

	foreach ($students as $_student){
		if ($_student['roll'] == $roll) {
			$found = true;
			break;
		}
	}

	if (!$found || $students[$id-1]['roll'] == $roll) {
		$students[$id-1]['fname'] = $fname;
		$students[$id-1]['lname'] = $lname;
		$students[$id-1]['roll'] = $roll;


		$serialized = serialize($students);
		file_put_contents(db_name, $serialized, LOCK_EX);
		return true;
	}
	return false;
}



function deleteStudent($id){
	$serialized = file_get_contents(db_name);
	$students = unserialize($serialized);

	unset($students[$id-1]);

	$serialized = serialize($students);
	file_put_contents(db_name, $serialized, LOCK_EX);
}


function newId($students){
	$maxId = max(array_column($students, 'id'));
	return $maxId+1;
}

function newRoll($roll){
	$serialized = file_get_contents(db_name);
	$students = unserialize($serialized);
	$lastStudent = end($students);
	$roll = $lastStudent['roll'] + 1;
	return $roll;
}


// Role Manage Is Admin

function isAdmin(){
	return ("Admin" == $_SESSION['role']) ;
}

function isUser(){
	return ("User" == $_SESSION['role']) ;
}

function hasPrevilage(){
	return (isAdmin() || isUser());
}