<?php
	session_name('Crud');
	session_start([
	    'cookie_lifetime' => 300,
	]);
	require_once('aditional/functions.php');

	$task = $_GET['task'] ?? 'report';
	$error = $_GET['error'] ?? '0';

	$info = '';

	if ('edit' == $task) {
		if (!hasPrevilage()) {
			header('location: /crud/index.php?task=report');
		}
	}

	else if('seed'== $task){
		if (!isAdmin()) {
			header('location: /crud/index.php');
		}
		//seed();
		$info = "Restart";
	}
	else if('add'== $task){
		$info = "Add Student";
	}
	elseif ('delete'== $task) {
		if (!isAdmin()) {
			header('location: /crud/index.php?task=report');
		}

		$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING);

		if ($id>0) {
			deleteStudent($id);
			header('location: /crud/index.php?task=report');
		}
	}
	else{
		$info = "Report Is Runing";
	}

	$fname ='';
	$lname ='';
	$roll = '';

	
	if (isset($_POST['submit'])) {
		$fname = filter_input(INPUT_POST,'fname', FILTER_SANITIZE_STRING);
		$lname = filter_input(INPUT_POST,'lname', FILTER_SANITIZE_STRING);
		$roll = filter_input(INPUT_POST,'roll', FILTER_SANITIZE_STRING);
		$id = filter_input(INPUT_POST,'id', FILTER_SANITIZE_STRING);

		if ($id) {
			if ($fname != '' && $lname != '' && $roll != '') {
				$updateStudent = updateStudent($id, $fname, $lname, $roll);

				if ($updateStudent) {
					header('location: /crud/index.php?task=report');
				}
				else{
					$error = '1';
				}
			}
		}
		else{
			if ($fname != '' && $lname != '') {
				$result = addStudent($fname, $lname);

				if ($result) {
					header('location: /crud/index.php?task=add');
				}
				else {
					$info = "Error!";
					$error = '1';
				}
			}
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $info; ?></title>
	<!-- View Port -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Google Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
	<!-- CSS Reset -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
	<!-- Milligram CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

</head>
<body>

	<header>
		<div class="container" style="text-align: center; padding: 50px 0px;">
			<?php  ?>
			<?php 
				if (true == $_SESSION['loggedin']) {
                    echo "<h1>login Done</h1>";
                } 
                else{
                    echo "<h1>Login Needed</h1>";
                }
			?>
			<div class="row">
				<div class="column column-50 column-offset-25">
					<?php include_once('aditional/templates/nav.php'); ?>
				</div>
			</div>
		</div>
	</header>

	<main>

		<?php if('report' == $task){ ?>
		<div class="container">
			<div class="row">
				<div class="column column-50 column-offset-25">
					<?php generateReport();	 ?>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php 
			if ('1'== $error) { ?>

				<div class="container">
					<div class="row">
						<div class="column column-50" style="margin: 0 auto;">
							<blockquote>Duplicate Roll Number</blockquote>
						</div>
					</div>
				</div>
		<?php
			}
		?>

		<?php if('add' == $task){ ?>
		<div class="container">
			<div class="row">
				<div class="column column-50 column-offset-25">
					<form action="/crud/index.php?task=add" method="POST">
					  	<fieldset>
						    <input type="text" name="fname" placeholder="First Name" id="fname" value="<?php echo $fname; ?>">

						    <input type="text" name="lname" placeholder="Last Name" id="lname"value="<?php echo $lname; ?>">

						    <!-- <input type="number" name="roll" placeholder="Roll" id="roll" value="<?php echo $roll; ?>"> -->

						    <input class="button-primary" type="submit" name="submit" value="Save">
					  	</fieldset>
					</form>
				</div>
			</div>
		</div>
		<?php } ?>



		<?php if('edit' == $task): 
			$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING);
			$student = getStudent($id);

			if($student):
			?>
		<div class="container">
			<div class="row">
				<div class="column column-50 column-offset-25">
					<form method="POST">
					  	<fieldset>
					  		<input type="hidden" name="id" value="<?php echo $id; ?>">
						    <input type="text" name="fname" placeholder="First Name" id="fname" value="<?php echo $student['fname']; ?>">

						    <input type="text" name="lname" placeholder="Last Name" id="lname"value="<?php echo $student['lname']; ?>">

						    <input type="number" name="roll" placeholder="Roll" id="roll" value="<?php echo $student['roll']; ?>">

						    <input class="button-primary" type="submit" name="submit" value="Save">
					  	</fieldset>
					</form>
				</div>
			</div>
		</div>
	<?php 
		endif;
		endif;
	?>


	</main>


	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded',function(){
			console.log('loded');
		});
	</script>
</body>
</html>