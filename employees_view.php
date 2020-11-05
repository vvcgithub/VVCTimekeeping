<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'includes/logged_in.php' ;

	if(isset($_GET['page'])) {
		$current_page = $_GET['page'];
		$current_page = check_input($current_page);
	} else {
		$current_page = 1;
		header("Location: employees_view.php?page=1&text_search=$text_search");
	}
	
	if (isset($_GET['search'])) {
		$current_page = 1;
	}
	
	if (isset($_GET['search'])) {
		$current_page = 1;
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_GET['clear'])) {
		$_GET['text_search'] = "";
		$text_search = check_input($_GET['text_search']);
	}
	
	if (isset($_GET['search'])) {
		header("Location: employees_view.php?page=1&text_search=$text_search");
	}
	
	if (isset($_GET['refresh'])) {
		$_GET['text_search'] = "";
		$text_search = check_input($_GET['text_search']);
		$current_page = 1;
	}
	
	if (isset($_GET['add'])) {
		header("Location: employees_insert.php");
		exit();
	}
	
	if (isset($_GET['download'])) {
		header("Location: backup_tables_to_csv_final.php?employees");
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
	
	
	if(isset($_GET['delete'])) {
		$delete = $_GET['delete'];
		$sql = "DELETE FROM employees WHERE employee_id='$delete'";
		mysqli_query($mysqli, $sql);
		header('Location: employees_view.php?page=' . $current_page . '&sort=' . $sort . '&order=' . $order);
	}
?>
	<h1>Template Reference</h1>
	<h2>Employees <a href="clients_view_start.php">Clients</a> <a href="periods_view.php">Periods</a></h2>
	<form action="" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />
		<input type="text" name="text_search" class="span3" value="<?php echo (isset($text_search)) ? $text_search : '' ; ?>" placeholder="Search..." />
		<button type="submit" name="search" title="Search">Search</button><p>					
	</form>
	<form action="" method="post">
		<table class="table_list">
			<thead>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="10" class="left white_border">
						<button type="submit" name="refresh" title="Refresh"><img src="icons/refresh.png" /></button>
					</th>	
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
					<th>ID #</th>
					<th>Username</th>
					<th>Name</th>
					<th>Email</th>
					<th>Active</th>
				</tr>
			</thead>
			<tbody>
<?php	
				include 'core/database/connect.php' ;
				$query = $mysqli->query("SELECT * FROM employees $filter ORDER BY employee_id DESC LIMIT $limit ,$per_page");
				while($rows = $query->fetch_assoc()) {
					$employee_id = $rows['employee_id'];
					$employee_id_no = $rows['employee_id_no'];
					$employee_username = $rows['employee_username'];
					$employee_last_name = $rows['employee_last_name'];
					$employee_first_name = $rows['employee_first_name'];
					$employee_middle_name = $rows['employee_middle_name'];
					$employee_email = $rows['employee_email'];
					$employee_email2 = $rows['employee_email2'];
					$employee_address = $rows['employee_address'];
					$employee_account_type = $rows['employee_account_type'];
					$active = $rows['active'];
?>
					<tr class="alternate">		
						<td class = "bg-color-dimgrey fg-color-white"><input type="text" id = "<?php echo $employee_id; ?>" value= "<?php echo $employee_id_no; ?>" readonly /><input type="button" value="Select" onclick="CopyField('<?php echo $employee_id; ?>');"></td>
						<td><?php echo $employee_username; ?></td>
						<td><?php echo complete_name_from_id($employee_id); ?></td>
						<td><a href="mailto:<?php echo $employee_email; ?>"><?php echo $rows['employee_email']; ?></a></td>
						<td class="center"><?php echo ($active === "0") ? "<span style='color:red; font-weight:bold;'>N</span>" : "<span style='color:green'>Y</span>" ; ?></td>
					</tr>
							
<?php
				}
				include 'core/database/close.php' ;
?>
			</tbody>
			<tfoot>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="21" class="right white_border">
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