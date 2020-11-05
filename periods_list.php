<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 

	if(isset($_GET['page'])) {
		$current_page = check_input($_GET['page']);
	} else {
		$current_page = 1;
		header("Location: periods_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_GET['search'])) {
		$search = $_GET['text_search'];
		header("Location: periods_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_POST['search'])) {
		$search = $_POST['text_search'];
		header("Location: periods_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_POST['refresh'])) {
		$_GET['text_search'] = "";
		header("Location: periods_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_POST['add'])) {
		header("Location: periods_insert.php");
		exit();
	}
	
	if (isset($_GET['export'])) {
		header("Location: backup_tables_to_csv_final.php?periods");
		exit();
	}
	
	if (isset($_POST['back'])) {
		header("Location: maintain.php");
		exit();
	}
	
	if(isset($_GET['delete'])) {
		$delete = $_GET['delete'];
		include 'core/database/connect.php' ;
		$mysqli->query("DELETE FROM periods WHERE period_id='$delete'");
		include 'core/database/close.php' ;
		header("Location: periods_list.php?page=$current_page&text_search=$text_search");
	}
	
	$filter = (empty($text_search)) ? "" : "WHERE period_id LIKE '%$text_search%' OR period_code LIKE '%$text_search%' OR period_from LIKE '$text_search' OR period_to LIKE '$text_search' OR period_interval LIKE '$text_search'";	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM periods $filter");
	$rows = mysqli_affected_rows($mysqli);
	include 'core/database/close.php' ;
	$per_page = 10;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1) * $per_page;
?>
	<h1><a href="maintain.php" style="text-decoration:none;">&#8656;</a> Periods List</h1>
	<form action="" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />
	</form>
	<form action="" method="post">
		<table class="table_list">
			<thead>
				<tr style="padding:0px;">
					<th colspan="9" class="left" style="border:none;">
						<input type="submit" style="float:right;" name="search" title="Search" value="Search" ><input type="text" style="float:right;" name="text_search" class="span2" value="<?php echo (isset($text_search)) ? $text_search : '' ; ?>" placeholder="Search..." />
<?php
						if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_periods($employee_data['employee_id']) == 2 || privilege_periods($employee_data['employee_id']) == 3) {
?>
						<button type="submit" name="add" class="button_text" title="Add">Add</button>
<?php
						}
?>
						<button type="submit" name="refresh" class="button_text" title="Refresh">Refresh</button>
					</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
<?php
				if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_periods($employee_data['employee_id']) == 2 || privilege_periods($employee_data['employee_id']) == 4) {
?>
					<th></th>
<?php
				}
?>
<?php
				if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_periods($employee_data['employee_id']) == 2 || privilege_periods($employee_data['employee_id']) == 5) {
?>
					<th></th>
<?php
				}
?>
					<th>Code</th>
					<th>From</th>
					<th>To</th>
					<th>Interval</th>
					<th>Active</th>
				</tr>
			</thead>
			<tbody>
<?php	
				include 'core/database/connect.php' ;
				$query = $mysqli->query("SELECT * FROM periods $filter ORDER BY period_id DESC LIMIT $limit ,$per_page");
				while($rows = mysqli_fetch_array($query)) {
					$period_id = $rows['period_id'];
					$period_code = $rows['period_code'];
					$period_from = $rows['period_from'];
					$period_to = $rows['period_to'];
					$period_interval = $rows['period_interval'];
					$active = $rows['active'];
?>
				<tr class="alternate">
<?php
				if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_periods($employee_data['employee_id']) == 2 || privilege_periods($employee_data['employee_id']) == 4) {
?>
					<td class="center">
						<a href="periods_update.php?period_id=<?php echo $period_id ; ?>&page=<?php echo $current_page; ?>&text_search=<?php echo $text_search; ?>">Edit</a>
					</td>
<?php
				}
?>
<?php
				if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_periods($employee_data['employee_id']) == 2 || privilege_periods($employee_data['employee_id']) == 5) {
?>
					<td class="center">
						<a href="#" onclick="delete_confirm_for_periods('<?php echo $current_page; ?>', '<?php echo $period_id; ?>', '<?php echo $period_code; ?>', '<?php echo $text_search; ?>')">Delete</a>
					</td>
<?php
				}
?>
					<td <?php echo ($active == 0) ? "style='opacity:.4; text-decoration:line-through;'" : "style='text-align:left;'" ; ?>><?php echo $period_code; ?></td>
					<td <?php echo ($active == 0) ? "style='opacity:.4; text-decoration:line-through;'" : "style='text-align:left;'" ; ?>><?php echo $period_from; ?></td>
					<td <?php echo ($active == 0) ? "style='opacity:.4; text-decoration:line-through;'" : "style='text-align:left;'" ; ?>><?php echo $period_to; ?></td>
					<td <?php echo ($active == 0) ? "style='opacity:.4; text-decoration:line-through;'" : "style='text-align:center;'" ; ?>><?php echo $period_interval; ?></td>
					<td class="center" style="padding:2px; text-align:center;"><?php echo ($active === "0") ? "<span style='color:red; font-weight:bold;'>N</span>" : "<span style='color:green'>Y</span>" ; ?></td>
				</tr>
<?php
				}
				include 'core/database/close.php' ;
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
	include 'includes/overall/footer.php' ; 
?>