<?php 
	include("core/init.php");
	include("redirect.php"); 
	include("header.php"); 
	
	if (isset($_POST['ok'])) {
		header('Location: departments_list.php');
	}
	if (isset($_GET['success'])) {
?>
		<div class="page secondary" style="min-height:700px;">
			<div style="padding:5px; margin:5px;">
				<div class="page-header">
					<div class="page-header-content">
						<h1>Department<small>update</small></h1>
					</div>
				</div>
				<div class="page-region">
					<div class="page-region-content">
						<ul class="unstyled">
							<li>Department was successfully updated!</li>
							<br />
							<li class="as-inline-block">
								<form action="departments_update.php" method="post">
								<input type="submit" name="ok" value="Ok" <?php echo "class='shortcut fg-color-$fgcolor bg-color-$bgcolor'" ?> />
								</form>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
<?php
		include("footer.php");
		exit();
	}
	
	if (isset($_POST['department_id'])) {
		// query db
		$department_id = $_POST['department_id'];
		$result = mysql_query("SELECT * FROM departments WHERE department_id=$department_id")
		or die(mysql_error()); 
		$row = mysql_fetch_array($result);

		// check that the 'id' matches up with a row in the databse
		if($row) { // get data from db
		$department_code = $row['department_code'];
		$department_name = $row['department_name'];
		$active = $row['active'];
		}
	}
  
	if (isset($_POST['cancel'])) {
		header ("Location: departments_list.php");
		exit();
	}
  
	if (isset($_POST['update'])) {
		$department_id = mysql_real_escape_string($_POST['department_id']);
		$department_code = mysql_real_escape_string($_POST['department_code']);
		$department_name = mysql_real_escape_string($_POST['department_name']);
		$active = (empty($_POST['active'])) ? "0" : "1";

		$department_code_empty = (empty($department_code) === true) ? "Code is required!" : "";
		$department_name_empty = (empty($department_name) === true) ? "Name is required!" : "";
		$department_code_exist = (department_code_exists($department_code, $department_id) === true) ? "Code already exist!" : "";

		if (
		empty($department_code_empty) &&
		empty($department_name_empty) &&
		empty($department_code_exist)
		) {
			mysql_query("UPDATE departments SET department_code='$department_code'
			, department_name='$department_name'
			, active='$active'
			WHERE department_id='$department_id'"); 

			// once saved, redirect back to the view page
			header('Location: departments_list.php');
		}
	}
?>
	<form action="" method="post">
		<div class="page secondary" style="min-height:700px;">
			<div style="padding:5px; margin:5px;">
				<div class="page-header">
					<div class="page-header-content">
						<h1>Department<small>update</small></h1>
						<a href="departments_list.php" class="back-button big page-back"></a>
					</div>
				</div>
				<div class="page-region">
					<div class="page-region-content">
						<ul>
							<?php echo (isset($department_code_empty) && !empty($department_code_empty)) ? "<li class='fg-color-red'>$department_code_empty</li>" : ""; ?>
							<?php echo (isset($department_code_exist) && !empty($department_code_exist)) ? "<li class='fg-color-red'>$department_code_exist</li>" : ""; ?>
							<?php echo (isset($department_name_empty) && !empty($department_name_empty)) ? "<li class='fg-color-red'>$department_name_empty</li>" : ""; ?>
						</ul>
						<ul class="unstyled">
							<input type="hidden" name="department_id" class="text" value="<?php echo $department_id; ?>" />
							<li><strong>Code</strong></li>
							<li class="input-control text span2">
								<input type="text" name="department_code" value="<?php echo (isset($department_code)) ? $department_code : ""; ?>" class="with-helper "/><button class="helper"></button>
							</li>
							<li><strong>Name</strong></li>
							<li class="input-control text span5">
								<input type="text" name="department_name" value="<?php echo (isset($department_name)) ? $department_name : ""; ?>" class="with-helper "/><button class="helper"></button>
							</li>
							<li>
								<label class="input-control checkbox" onclick="">
									<input type="checkbox" name="active" id="active" <?php echo ($active==="1") ? "checked" : ""; ?> />
									<span class="helper">Active</span>
								</label>
							</li>
						</ul>
						<br />
						<li class="as-inline-block">
							<input type="submit" name="update" value="Update" <?php echo "class='shortcut fg-color-$fgcolor bg-color-$bgcolor'" ?> />
						</li>
					</div>
				</div>
			</div>
		</div>
	</form>

	
<?php include("footer.php"); ?>