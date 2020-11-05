<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ; 

	if(isset($_GET['page'])) {
		$current_page = check_input($_GET['page']);
	} else {
		$current_page = 1;
		header("Location: news_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_GET['search'])) {
		$search = $_GET['text_search'];
		header("Location: news_list.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_POST['refresh'])) {
		header("Location: news_list.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_POST['add'])) {
		header("Location: news_insert.php");
		exit();
	}
	
	if (isset($_GET['export'])) {
		header("Location: backup_tables_to_csv_final.php?news");
		exit();
	}
	
	if (isset($_POST['back'])) {
		header("Location: maintain.php");
		exit();
	}
		
	if(isset($_GET['delete'])) {
		$delete = $_GET['delete'];
		include 'core/database/connect.php' ;
		$mysqli->query("DELETE FROM news WHERE news_id='$delete'");
		include 'core/database/close.php' ;
		header("Location: news_list.php?page=$current_page&text_search=$text_search");
		exit();
	}
	
	$filter = (empty($text_search)) ? "" : "WHERE news_id LIKE '". news_id($text_search) . "' OR news_date LIKE '%text_search%' OR news_title LIKE '%$text_search%' OR news_description LIKE '$text_search'";	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM news $filter");
	$rows = mysqli_affected_rows($mysqli);
	$per_page = 20;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1) * $per_page;
	include 'core/database/close.php' ;
?>
	<h1><a href="maintain.php" style="text-decoration:none;" title="Back">&#8656;</a> News List</h1>
	<form action="" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />
		<input type="text" name="text_search" class="span3" value="<?php echo (isset($text_search)) ? $text_search : '' ; ?>" placeholder="Search..." />
		<button type="submit" name="search" title="Search">Search</button><p>					
	</form>
	<form action="" method="post">
		<table class="table_list">
			<thead>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="6" class="left">
					<?php
					if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_news($employee_data['employee_id']) == 2 || privilege_news($employee_data['employee_id']) == 3) {
?>
						<button type="submit" name="add" class="bg-color-dimgrey no-border cursor-pointer" title="Add">Add</button>
<?php
					}
?>
						<button type="submit" name="refresh" class="bg-color-dimgrey no-border cursor-pointer" title="Refresh">Refresh</button>
					</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
<?php
				if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_news($employee_data['employee_id']) == 2 || privilege_news($employee_data['employee_id']) == 4) {
?>
					<th></th>
<?php
				}
?>
<?php
				if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_news($employee_data['employee_id']) == 2 || privilege_news($employee_data['employee_id']) == 5) {
?>
					<th></th>
<?php
				}
?>
					<th>Date</th>
					<th>Title</th>
					<th>Description</th>
				</tr>
			</thead>
		<tbody>
<?php	
			include 'core/database/connect.php' ;
			$query = $mysqli->query("SELECT * FROM news $filter ORDER BY news_id DESC LIMIT $limit ,$per_page");
			while($rows = mysqli_fetch_array($query)) {
				$news_id = $rows['news_id'];
				$news_date = $rows['news_date'];
				$news_title = $rows['news_title'];
				$news_description = $rows['news_description'];
?>
			<tr id="<?php echo $news_id; ?>" name="row_id[]" class="alternate">
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_news($employee_data['employee_id']) == 2 || privilege_news($employee_data['employee_id']) == 4) {
?>
				<td class="center">
					<a href="news_update.php?news_id=<?php echo $news_id ; ?>&page=<?php echo $current_page; ?>&text_search=<?php echo $text_search; ?>">Edit</a>
				</td>
<?php
			}
?>
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_news($employee_data['employee_id']) == 2 || privilege_news($employee_data['employee_id']) == 5) {
?>
				<td class="center">
					<a href="#" onclick="delete_confirm_for_news('<?php echo $current_page; ?>', '<?php echo $news_id; ?>', '<?php echo $news_title; ?>', '<?php echo $text_search; ?>')">Delete</a>
				</td>
<?php
			}
?>
				<td><?php echo $news_date; ?></td>
				<td><?php echo $news_title; ?></td>
				<td><?php echo $news_description; ?></td>
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