<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	//include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 
	include 'core/database/connect.php' ;
	
	if (employee_account_type($employee_data['employee_id']) <> 'Administrator') {
		header('Location: index.php');
		exit();
	}

	if(isset($_GET['page'])) {
		$current_page = check_input($_GET['page']);
	} else {
		$current_page = 1;
		header("Location: privileges_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_GET['search'])) {
		$search = $_GET['text_search'];
		header("Location: privileges_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_POST['search'])) {
		$search = $_POST['text_search'];
		header("Location: privileges_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_POST['refresh'])) {
		header("Location: privileges_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_POST['add'])) {
		header("Location: privileges_insert.php");
		echo "add";
		exit();
	}
	
	if (isset($_GET['export'])) {
		header("Location: backup_tables_to_csv_final.php?privileges");
		exit();
	}
	
	if (isset($_POST['back'])) {
		header("Location: c");
		exit();
	}
	
	if(isset($_GET['delete'])) {
		$delete = $_GET['delete'];
		$mysqli->query("DELETE FROM privileges WHERE privilege_id='$delete'");
		header("Location: privileges_list.php?page=$current_page&text_search=$text_search");
		exit();
	}
	
	$filter = (empty($text_search)) ? "" : "WHERE privilege_id LIKE '%$text_search%' OR privilege_employee_id LIKE '". privilege_id($text_search) . "'";	
	$query = $mysqli->query("SELECT * FROM privileges $filter");
	$rows = mysqli_affected_rows($mysqli);
	$per_page = 20;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1) * $per_page;
?>
	<h1><a href="maintain.php" style="text-decoration:none;" >&#8656;</a> Privileges List</h1>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>?page=1&text_search=" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />
	</form>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>?page=1&text_search=" method="post">
		<div style="overflow:auto;width:100%;height:100%;">
		<table class="table_list">
			<thead>
				<tr style="padding:0px;">
					<th colspan="10" class="left" style="border:none;">
						<input type="submit" style="float:right;margin-left:5px;" name="search" title="Search" value="Search" ><input type="text" style="float:right;" name="text_search" class="span3" value="<?php echo (isset($text_search)) ? $text_search : '' ; ?>" placeholder="Search..." />
<?php
					if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_clients($employee_data['employee_id']) == 2 || privilege_clients($employee_data['employee_id']) == 3) {
?>
						<input type="submit" name="add" value="Add" class="button_text" />
<?php
					}
?>
						<input type="submit" name="refresh" value="Refresh" class="button_text" />
					</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="3"></th>
					<th colspan="7" style='text-align:center;'>AREAS</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="3"></th>
					<th colspan="5" style='text-align:center;'>MAINTAIN</th>
					<th colspan="1" style='text-align:center;'>REPORTS</th>
					<th colspan="1" style='text-align:center;'>TOOLS</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
<?php
				if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_clients($employee_data['employee_id']) == 2 || privilege_clients($employee_data['employee_id']) == 4) {
?>
					<th></th>
<?php
				}
?>
<?php
				if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_clients($employee_data['employee_id']) == 2 || privilege_clients($employee_data['employee_id']) == 5) {
?>
					<th></th>
<?php
				}
?>
					<th>Employee</th>
					<th>Employees</th>
					<th>Biometrics</th>
					<th>Clients</th>
					<th>Positions</th>
					<th>Periods</th>
					<th>Costing</th>
					<th>Import logs</th>
				</tr>
			</thead>
		<tbody>
<?php	
			$query = $mysqli->query("SELECT * FROM privileges $filter ORDER BY privilege_id DESC LIMIT $limit ,$per_page");
			while($rows = mysqli_fetch_array($query)) {
				$privilege_id = $rows['privilege_id'];
				$privilege_employee_id = $rows['privilege_employee_id'];
				$employee_name = complete_name_from_id($privilege_employee_id);
				$privilege_employees = $rows['privilege_employees'];
				$privilege_biometrics = $rows['privilege_biometrics'];
				$privilege_clients = $rows['privilege_clients'];
				$privilege_positions = $rows['privilege_positions'];
				$privilege_periods = $rows['privilege_periods'];
				$privilege_costing = $rows['privilege_costing'];
				$privilege_import_logs = $rows['privilege_import_logs'];
				
?>
			<tr id="<?php echo $privilege_id; ?>" name="row_id[]" class="alternate">
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator') {
?>
				<td class="center">
					<a href="privileges_update.php?privilege_id=<?php echo $privilege_id ; ?>&page=<?php echo $current_page; ?>&text_search=<?php echo $text_search; ?>">Edit</a>
				</td>
<?php
			}
?>
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator') {
?>
				<td class="center">
					<a href="#" onclick="delete_confirm_for_privileges('<?php echo $current_page; ?>', '<?php echo $privilege_id; ?>', '<?php echo $employee_name; ?>', '<?php echo $text_search; ?>')">Delete</a>
				</td>
<?php
			
			}
?>
				<td style='text-align:left;'><?php echo $employee_name; ?></td>
				<td style='text-align:center;<?php echo "background:". background_color($privilege_employees) . ";" ;?>'><?php echo privileges_name($privilege_employees); ?></td>
				<td style='text-align:center;<?php echo "background:". background_color($privilege_biometrics) . ";" ;?>'><?php echo privileges_name($privilege_biometrics); ?></td>
				<td style='text-align:center;<?php echo "background:". background_color($privilege_clients) . ";" ;?>'><?php echo privileges_name($privilege_clients); ?></td>
				<td style='text-align:center;<?php echo "background:". background_color($privilege_positions) . ";" ;?>'><?php echo privileges_name($privilege_positions); ?></td>
				<td style='text-align:center;<?php echo "background:". background_color($privilege_periods) . ";" ;?>'><?php echo privileges_name($privilege_periods); ?></td>
				<td style='text-align:center;<?php echo "background:". background_color($privilege_costing) . ";" ;?>'><?php echo privileges_name($privilege_costing); ?></td>
				<td style='text-align:center;<?php echo "background:". background_color($privilege_import_logs) . ";" ;?>'><?php echo privileges_name($privilege_import_logs); ?></td>
			</tr>
<?php
			}
?>
		</tbody>
		<tfoot>
			<tr class="bg-color-dimgrey fg-color-white">
				<th colspan="21" class="right">
<?php
				include 'includes/page_number.php' ; 
?>
				</th>
			</tr>
		</tfoot>
		</table>
		</div>
	</form>
	<br />
<?php
	include 'includes/pagination_search.php' ; 
	include 'core/database/close.php' ;
	include 'includes/overall/footer.php' ; 
?>