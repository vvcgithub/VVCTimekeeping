<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ; 
	include 'includes/aside.php' ;
	
	
	if (isset($_GET['bug_id'])) {
		$bug_id = $_GET['bug_id'];
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT * FROM bugs WHERE bug_id=$bug_id"); 
		$rows = $query->fetch_assoc();
		if($rows) { 
			$bug_id = $rows['bug_id'];
			$bug_employee_id_no = $rows['bug_employee_id_no'];
			$bug_description = $rows['bug_description'];
			$bug_status = $rows['bug_status'];
			$bug_solution = $rows['bug_solution'];
		}
		include 'core/database/close.php' ;
	} else {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = $_GET['text_search'];
	}
  
	if (isset($_POST['fix'])) {
		$bug_status = check_input($_POST['bug_status']);
		$bug_solution = check_input($_POST['bug_solution']);
		include 'core/database/connect.php' ;
		$mysqli->query("UPDATE bugs SET bug_status='$bug_status'
		, bug_solution='$bug_solution'
		WHERE bug_id='$bug_id'"); 
		include 'core/database/close.php' ;
		header ("Location: bugs_list.php?page=$page&text_search=$text_search");
		exit();
	}
?>
	<h1><a href="bugs_list.php" style="text-decoration:none;" title="Back">&#8656;</a> Fix Bug</h1>
	<form action="" method="post">
		<label for="bug_status" class="span2">Employee <span class="fg-color-red"></span></label>
		<span><?php echo isset($bug_employee_id_no) ? complete_name_from_id_no($bug_employee_id_no) : ""; ?></span>
		<br /><br />
		<label for="bug_status" class="span2">Description <span class="fg-color-red"></span></label>
		<span><?php echo isset($bug_description) ? $bug_description : ""; ?></span>
		<br /><br />
		<label for="bug_status" class="span2">Status <span class="fg-color-red"></span></label>
		<select name="bug_status" id="bug_status">		
			<option value="">- Select account status -</option>
			<option value="0" <?php if (isset($bug_status) && (int)$bug_status===0) {  echo "selected"; } ?>>Bug</option>
			<option value="1" <?php if (isset($bug_status) && (int)$bug_status===1) {  echo "selected"; } ?>>Fix</option>
			<option value="2" <?php if (isset($bug_status) && (int)$bug_status===2) {  echo "selected"; } ?>>Not applicable</option>
		</select><br /><br />
		<label for="bug_solution" class="span2">Solution <span class="fg-color-red"></span></label>
		<textarea name="bug_solution" id="bug_solution" class="span8" maxlength="300" ><?php echo isset($bug_solution) ? $bug_solution : ""; ?></textarea>
		<br /><br />
		<input type="submit" name="fix" value="Fix" />
	</form>

<?php
	include 'includes/overall/footer.php' ; 
?>