<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 
	
	if (employee_account_type($employee_data['employee_id']) <> 'Administrator' && privilege_employees($employee_data['employee_id']) == 7) {
		header('Location: index.php');
		exit();
	}

	if(isset($_GET['page'])) {
		$current_page = check_input($_GET['page']);
	} else {
		$current_page = 1;
		header("Location: employees_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_GET['search'])) {
		$search = $_GET['text_search'];
		header("Location: employees_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_POST['search'])) {
		$search = $_POST['text_search'];
		header("Location: employees_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_POST['refresh'])) {
		header("Location: employees_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_POST['add'])) {
		header("Location: employees_insert.php");
		exit();
	}
	
	if (isset($_POST['download'])) {
		header("Location: backup_tables_to_csv_final.php?employees");
		exit();
	}
	
	if (isset($_POST['back'])) {
		header("Location: maintain.php");
		exit();
	}
	
	if(isset($_GET['delete'])) {
		$delete = $_GET['delete'];
		include 'core/database/connect.php' ;
		$query = $mysqli->query("DELETE FROM employees WHERE employee_id='$delete'");
		include 'core/database/close.php' ;
		header("Location: employees_list.php?page=$current_page&text_search=$text_search");
		exit();
	}
	
	$filter = (empty($text_search)) ? "" : "WHERE employee_id LIKE '%$text_search%' OR employee_id_no LIKE '%$text_search%' OR employee_username LIKE '%$text_search%' OR employee_last_name LIKE '%$text_search%' OR employee_first_name LIKE '%$text_search%' OR employee_email LIKE '%$text_search%' OR employee_account_type LIKE '%$text_search%' OR active LIKE '$text_search'";	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM employees $filter");
	$rows = mysqli_affected_rows($mysqli);
	include 'core/database/close.php' ;
	$per_page = 20;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1)* $per_page;
?>
	<h1><a href="maintain.php" style="text-decoration:none;" title="Back">&#8656;</a> Employees List</h1>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>?page=1&text_search=" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />	
	</form>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>?page=1&text_search=" method="post">
		<table class="table_list">
			<thead>
				<tr style="padding:0px;">
					<th colspan="9" class="left" style="border:none;">
						<input type="submit" style="float:right;margin-left:5px;" name="search" title="Search" value="Search" ><input type="text" style="float:right;" name="text_search" class="span3" value="<?php echo (isset($text_search)) ? $text_search : '' ; ?>" placeholder="Search..." />
<?php
					if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_employees($employee_data['employee_id']) == 2 || privilege_employees($employee_data['employee_id']) == 3) {
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
					if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_employees($employee_data['employee_id']) == 2 || privilege_employees($employee_data['employee_id']) == 4) {
?>
						<th></th>
<?php
					}
?>
<?php
					if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_employees($employee_data['employee_id']) == 2 || privilege_employees($employee_data['employee_id']) == 5) {
?>
						<th></th>
<?php
					}
?>
					<th>ID</th>
					<th>ID#</th>
					<th>Username</th>
					<th>Name</th>
					<th>Email</th>
					<th>Type</th>
					<th>Active</th>
				</tr>
			</thead>
			<tbody>
<?php	
				include 'core/database/connect.php' ;
				$query = $mysqli->query("SELECT * FROM employees $filter ORDER BY employee_id DESC LIMIT $limit ,$per_page");
				while($rows = mysqli_fetch_array($query)) {
					$employee_id = $rows['employee_id'];
					$employee_id_no = $rows['employee_id_no'];
					$employee_username = $rows['employee_username'];
					$employee_last_name = $rows['employee_last_name'];
					$employee_first_name = $rows['employee_first_name'];
					$employee_middle_name = $rows['employee_middle_name'];
					$employee_whole_name = complete_name_from_id_no($employee_id_no);
					$employee_email = $rows['employee_email'];
					$employee_email2 = $rows['employee_email2'];
					$employee_address = $rows['employee_address'];
					$employee_account_type = $rows['employee_account_type'];
					$active = $rows['active'];
?>
					<tr class="alternate">
<?php
						if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_employees($employee_data['employee_id']) == 2 || privilege_employees($employee_data['employee_id']) == 4) {
?>
							<td class="center">
								<a href="employees_update.php?employee_id=<?php echo $employee_id ; ?>&page=<?php echo $current_page ; ?>&text_search=<?php echo $text_search; ?>">Edit</a>
							</td>
<?php
						}
?>
<?php
						if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_employees($employee_data['employee_id']) == 2 || privilege_employees($employee_data['employee_id']) == 5) {
?>
							<td class="center">
								<a href="#" onclick="delete_confirm_for_employees('<?php echo $current_page; ?>', '<?php echo $employee_id; ?>', '<?php echo $employee_whole_name; ?>', '<?php echo $text_search; ?>')">Delete</a>
							</td>
<?php
						}
?>
						<td class="center"><?php echo $employee_id; ?></td>
						<td class="center"><?php echo $employee_id_no; ?></td>
						<td><?php echo $employee_username; ?></td>
						<td><?php echo complete_name_from_id_no($employee_id_no); ?></td>
						<td><a href="mailto:<?php echo $employee_email; ?>"><?php echo $rows['employee_email']; ?></a></td>
						<td class="center"><?php echo $employee_account_type; ?></td>
						<td class="text-center" style="padding:2px; text-align:center;"><?php echo ($active === "0") ? "<span style='color:red; font-weight:bold;'>N</span>" : "<span style='color:green'>Y</span>" ; ?></td>
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