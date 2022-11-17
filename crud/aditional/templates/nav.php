
<nav>
	<div class="float-left">
		<a href="/crud/index.php?task=report">All Student</a>
<?php 
	if (hasPrevilage()) {?>
		|
		<a href="/crud/index.php?task=add">Add Student</a>
<?php }
	if (isAdmin()) { ?>
		|
		<a href="/crud/index.php?task=seed">Seeds</a>
	<?php 
} ?>
		
	</div>
	<div class="float-right">
		<?php 
		if (!$_SESSION['loggedin']):
		?>
		<a href="/crud/login.php">Log in</a>
	    <?php 
		else:
	    ?>
	    <a href="/crud/login.php?logout=false">Log Out (<?php echo $_SESSION['role']; ?>)</a>
	    <?php 
	    endif;	
	    ?>
	</div>
</nav>