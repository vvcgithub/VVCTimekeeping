<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 

	if(isset($_GET['page'])) {
		$current_page = check_input($_GET['page']);
	} else {
		$current_page = 1;
		header("Location: bugs_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_GET['search'])) {
		$search = $_GET['text_search'];
		header("Location: bugs_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_POST['search'])) {
		$search = $_POST['text_search'];
		header("Location: bugs_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_POST['refresh'])) {
		header("Location: bugs_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_POST['report'])) {
		header("Location: bugs_report.php");
		exit();
	}
	
	if (isset($_GET['export'])) {
		header("Location: backup_tables_to_csv_final.php?bugs");
		exit();
	}
	
	if (isset($_POST['back'])) {
		header("Location: c");
		exit();
	}
	
	if(isset($_GET['delete'])) {
		$delete = $_GET['delete'];
		include 'core/database/connect.php' ;
		$mysqli->query("DELETE FROM bugs WHERE bug_id='$delete'");
		include 'core/database/close.php' ;
		header("Location: bugs_list.php?page=$current_page&text_search=$text_search");
		exit();
	}
	
	$filter = (empty($text_search)) ? "" : "WHERE bug_id LIKE '%$text_search%' OR bug_description LIKE '%$text_search%' OR bug_status LIKE '%$text_search%' OR bug_solution LIKE '$text_search'";	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM bugs $filter");
	$rows = mysqli_affected_rows($mysqli);
	$per_page = 20;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1) * $per_page;
	include 'core/database/close.php' ;
?>
	<h1><a href="index.php" style="text-decoration:none;" >&#8656;</a> Bugs List</h1>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>?page=1&text_search=" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />
	</form>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>?page=1&text_search=" method="post">
		<table class="table_list">
			<thead>
				<tr style="padding:0px;">
					<th colspan="6" class="left" style="border:none;">
						<input type="submit" style="float:right;margin-left:5px;" name="search" title="Search" value="Search" ><input type="text" style="float:right;" name="text_search" class="span3" value="<?php echo (isset($text_search)) ? $text_search : '' ; ?>" placeholder="Search..." />
						<input type="submit" name="report" value="Report"/>
						<input type="submit" name="refresh" value="Refresh"/>
					</th>
				<tr class="bg-color-dimgrey fg-color-white">
<?php
					if (employee_account_type($employee_data['employee_id']) === 'Administrator') {
?>
					<th></th>
					<th></th>
					<th widt>Employee</th>
<?php
					}
?>
					<th>Bug description</th>
					<th>Status</th>
					<th>Solution</th>
				</tr>
			</thead>
		<tbody>
<?php	
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM bugs $filter ORDER BY bug_id DESC LIMIT $limit ,$per_page");
			while($rows = mysqli_fetch_array($query)) {
				$bug_id = $rows['bug_id'];
				$bug_employee_id_no = $rows['bug_employee_id_no'];
				$bug_description = $rows['bug_description'];
				$bug_status = $rows['bug_status'];
				$bug_solution = $rows['bug_solution'];
?>
			<tr id="<?php echo $bug_id; ?>" name="row_id[]" class="alternate">
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator') {
?>
				<td class="center">
					<a href="bugs_fix.php?bug_id=<?php echo $bug_id ; ?>&page=<?php echo $current_page; ?>&text_search=<?php echo $text_search; ?>">Fix</a>
				</td>
<?php
			}
?>
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator') {
?>
				<td class="center">
					<a href="#" onclick="delete_confirm_for_bugs('<?php echo $current_page; ?>', '<?php echo $bug_id; ?>', '<?php echo $bug_description; ?>', '<?php echo $text_search; ?>')">Delete</a>
				</td>
				<td><?php echo complete_name_from_id_no($bug_employee_id_no); ?></td>
<?php
			}
?>
				<td><?php echo $bug_description; ?></td>
				<td class="center"><?php if((int)$bug_status === 0) { echo "Bug"; } else if((int)$bug_status === 1) { echo "Fix"; } else if((int)$bug_status === 2) { echo "N/A"; } ?></td>	
				<td><?php echo $bug_solution; ?></td>					
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