<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 
	include 'core/database/connect.php' ;
	
	if (employee_account_type($employee_data['employee_id']) <> 'Administrator' && privilege_positions($employee_data['employee_id']) == 7) {
		header('Location: index.php');
		exit();
	}

	if(isset($_GET['page'])) {
		$current_page = check_input($_GET['page']);
	} else {
		$current_page = 1;
		header("Location: positions_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_GET['search'])) {
		$search = $_GET['text_search'];
		header("Location: positions_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_POST['search'])) {
		$search = $_POST['text_search'];
		header("Location: positions_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_POST['refresh'])) {
		header("Location: positions_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_POST['add'])) {
		header("Location: positions_insert.php");
		echo "add";
		exit();
	}
	
	if (isset($_GET['export'])) {
		header("Location: backup_tables_to_csv_final.php?positions");
		exit();
	}
	
	if (isset($_POST['back'])) {
		header("Location: c");
		exit();
	}
	
	if(isset($_GET['delete'])) {
		$delete = $_GET['delete'];
		$mysqli->query("DELETE FROM positions WHERE position_id='$delete'");
		header("Location: positions_list.php?page=$current_page&text_search=$text_search");
		exit();
	}
	
	$filter = (empty($text_search)) ? "" : "WHERE position_id LIKE '". position_id($text_search) . "' OR position_code LIKE '%text_search%' OR position_name LIKE '%$text_search%' OR active LIKE '$text_search'";	
	$query = $mysqli->query("SELECT * FROM positions $filter");
	$rows = mysqli_affected_rows($mysqli);
	$per_page = 20;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1) * $per_page;
?>
	<h1><a href="maintain.php" style="text-decoration:none;" >&#8656;</a> Positions List</h1>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>?page=1&text_search=" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />			
	</form>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>?page=1&text_search=" method="post">
		<table class="table_list">
			<thead>
				<tr style="padding:0px;">
					<th colspan="6" class="left" style="border:none;">
						<input type="submit" style="float:right;margin-left:5px;" name="search" title="Search" value="Search" ><input type="text" style="float:right;" name="text_search" class="span3" value="<?php echo (isset($text_search)) ? $text_search : '' ; ?>" placeholder="Search..." />
<?php
					if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_positions($employee_data['employee_id']) == 2 || privilege_positions($employee_data['employee_id']) == 3) {
?>
						<input type="submit" name="add" value="Add" class="button_text" />
<?php
					}
?>
						<input type="submit" name="refresh" value="Refresh" class="button_text" />
					</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
<?php
				if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_positions($employee_data['employee_id']) == 2 || privilege_positions($employee_data['employee_id']) == 4) {
?>
					<th></th>
<?php
				}
?>
<?php
				if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_positions($employee_data['employee_id']) == 2 || privilege_positions($employee_data['employee_id']) == 5) {
?>
					<th></th>
<?php
				}
?>
					<th style="min-width:150px;">Code</th>
					<th style="min-width:300px;">Name</th>
					<th style="min-width:300;">Rate</th>
					<th>Active</th>
				</tr>
			</thead>
		<tbody>
<?php	
			$query = $mysqli->query("SELECT * FROM positions $filter ORDER BY position_id DESC LIMIT $limit ,$per_page");
			while($rows = mysqli_fetch_array($query)) {
				$position_id = $rows['position_id'];
				$position_code = $rows['position_code'];
				$position_name = $rows['position_name'];
				$position_rate = $rows['position_rate'];
				$active = $rows['active'];
?>
			<tr id="<?php echo $position_id; ?>" name="row_id[]" class="alternate">
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_positions($employee_data['employee_id']) == 2 || privilege_positions($employee_data['employee_id']) == 4) {
?>
				<td class="center">
					<a href="positions_update.php?position_id=<?php echo $position_id ; ?>&page=<?php echo $current_page; ?>&text_search=<?php echo $text_search; ?>">Edit</a>
				</td>
<?php
			}
?>
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_positions($employee_data['employee_id']) == 2 || privilege_positions($employee_data['employee_id']) == 5) {
?>
				<td class="center">
					<a href="#" onclick="delete_confirm_for_positions('<?php echo $current_page; ?>', '<?php echo $position_id; ?>', '<?php echo $position_code; ?>', '<?php echo $text_search; ?>')">Delete</a>
				</td>
<?php
			}
?>
				<td <?php echo ($active == 0) ? "style='opacity:.4; text-decoration:line-through;'" : "style='text-align:left;'" ; ?>><?php echo $position_code; ?></td>
				<td <?php echo ($active == 0) ? "style='opacity:.4; text-decoration:line-through;'" : "style='text-align:left;'" ; ?>><?php echo $position_name; ?></td>
				<td <?php echo ($active == 0) ? "style='opacity:.4; text-decoration:line-through;'" : "style='text-align:right;'" ; ?>><?php echo $position_rate; ?></td>
				<td class="center" style="padding:2px; text-align:center;"><?php echo ($active === "0") ? "<span style='color:red; font-weight:bold;'>N</span>" : "<span style='color:green'>Y</span>" ; ?></td>
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
	</form>
	<br />
<?php
	include 'includes/pagination_search.php' ; 
	include 'core/database/close.php' ;
	include 'includes/overall/footer.php' ; 
?>