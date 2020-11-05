<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;
	
	if (employee_account_type($employee_data['employee_id']) <> 'Administrator' && privilege_biometrics($employee_data['employee_id']) == 7) {
		header('Location: index.php');
		exit();
	}
	
	if(isset($_GET['page'])) {
		$current_page = $_GET['page'];
		$current_page = check_input($current_page);
	} else {
		$current_page = 1;
		header("Location: biometrics_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_GET['search'])) {
		$current_page = 1;
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = $_GET['text_search'];
	} else {
		$text_search = "";
	}
	
	if (isset($_GET['search'])) {
		header("Location: biometrics_list.php?page=1&text_search=$text_search");
		exit();
	}
	
	if (isset($_POST['search'])) {
		$search = $_POST['text_search'];
		header("Location: biometrics_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_POST['refresh'])) {
		header("Location: biometrics_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_POST['template'])) {
		header('Location: biometrics_template.php');
		exit();
	}

	if (isset($_POST['import'])) {
		header("Location: biometrics_import.php");
		exit();
	}
	
	if (isset($_POST['empty'])) {
		header("Location: biometrics_empty.php");
		exit();
	}
	
	if (isset($_POST['back'])) {
		header("Location: maintain.php");
		exit();
	}
		
	$filter = (empty($text_search)) ? "" : "WHERE biometrics_id LIKE '%$text_search%' OR " . biometrics_employees($text_search) . " biometrics_date LIKE '%$text_search%' OR biometrics_time LIKE '$text_search'";	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM biometrics $filter");
	$rows = mysqli_affected_rows($mysqli);
	include 'core/database/close.php' ;
	$per_page = 30;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1) * $per_page;
?>
	<h1><a href="maintain.php" style="text-decoration:none;" title="Back">&#8656;</a> Biometrics List</h1>
	<form action="" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />			
	</form>
	<form action="" method="post">
		<table class="table_list">
			<thead>
				<tr style="padding:0px;">
					<th colspan="4" class="left" style="border:none;">
						<input type="submit" style="float:right;margin-left:5px;" name="search" title="Search" value="Search" ><input type="text" style="float:right;" name="text_search" class="span2" value="<?php echo (isset($text_search)) ? $text_search : '' ; ?>" placeholder="Search..." />
<?php
	if ($employee_data['employee_account_type'] == 'Administrator' || privilege_biometrics($employee_data['employee_id']) == 2 || privilege_biometrics($employee_data['employee_id']) == 3) {
?>
						<input type="submit" name="import" value="Import" class="button_text" />
<?php
	}
?>
<?php
	if ($employee_data['employee_account_type'] == 'Administrator' || privilege_biometrics($employee_data['employee_id']) == 2 || privilege_biometrics($employee_data['employee_id']) == 5) {
?>
						<input type="submit" name="empty" value="Empty" class="button_text" />
<?php
	}
?>						
						<input type="submit" name="refresh" value="Refresh" class="button_text" />
					</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
					<th>ID</th>
					<th>Employee</th>
					<th>Date</th>
					<th>Time</th>
				</tr>
			</thead>
			<tbody>
<?php	
					include 'core/database/connect.php' ;
					$query = $mysqli->query("SELECT * FROM biometrics $filter ORDER BY biometrics_id DESC LIMIT $limit ,$per_page");
					while($rows = mysqli_fetch_array($query)) {
						$biometrics_id = $rows['biometrics_id'];
						$biometrics_employee_id_no = $rows['biometrics_employee_id_no'];
						$biometrics_date = $rows['biometrics_date'];
						$biometrics_time = date("H:i", strtotime($rows['biometrics_time']));
?>
						<tr class="alternate">
							<td class="center"><?php echo $biometrics_id; ?></td>
							<td class="center"><?php echo complete_name_from_id_no($biometrics_employee_id_no); ?></td>
							<td class="center"><?php echo $biometrics_date; ?></td>
							<td class="center"><?php echo $biometrics_time; ?></td>
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