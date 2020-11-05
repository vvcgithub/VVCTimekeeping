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
						<h1>Department<small>insert</small></h1>
					</div>
				</div>
				<div class="page-region">
					<div class="page-region-content">
						<ul class="unstyled">
							<li>Department was successfully inserted!</li>
							<br />
							<li class="as-inline-block">
								<form action="departments_insert.php" method="post">
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
  
	if (isset($_POST['insert'])) {
		$department_code = mysql_real_escape_string($_POST['department_code']);
		$department_name = mysql_real_escape_string($_POST['department_name']);


		$department_code_empty = (empty($department_code) === true) ? "Code is required!" : "";
		$department_name_empty = (empty($department_name) === true) ? "Name is required!" : "";
		$department_code_exist = (department_code_exists($department_code, "") === true) ? "Code already exist!" : "";
		
		if (
			empty($department_code_empty) &&
			empty($department_name_empty) &&
			empty($department_code_exist)
			) {
				mysql_query("INSERT INTO departments (department_code, department_name) 
				VALUES (
				'$department_code', 
				'$department_name'
				)"); 
			
				header('Location: departments_list.php');
				exit();
			} 
	}

?>
	<form action="" method="post">
		<div class="page secondary" style="min-height:700px;">
			<div style="padding:5px; margin:5px;">
				<div class="page-header">
					<div class="page-header-content">
						<h1>Department<small>insert</small></h1>
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
							<li><strong>Code</strong></li>
							<li class="input-control text span2">
								<input type="text" name="department_code" value="<?php echo (isset($department_code)) ? $department_code : ""; ?>" class="with-helper "/><button class="helper"></button>
							</li>
							<li><strong>Name</strong></li>
							<li class="input-control text span5">
								<input type="text" name="department_name" value="<?php echo (isset($department_name)) ? $department_name : ""; ?>" class="with-helper "/><button class="helper"></button>
							</li>
						</ul>
						<br />
						<li class="as-inline-block">
							<input type="submit" name="insert" value="Insert" <?php echo "class='shortcut fg-color-$fgcolor bg-color-$bgcolor'" ?> />
						</li>
					</div>
				</div>
			</div>
		</div>
	</form>

	
<?php include("footer.php"); ?>