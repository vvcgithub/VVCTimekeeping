<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ; 
	include 'includes/aside.php' ; 

	if(isset($_GET['page'])) {
		$current_page = check_input($_GET['page']);
	} else {
		$current_page = 1;
		header("Location: periods_view.php?page=1&text_search=");
		exit();
	}
	
	if (isset($_GET['search'])) {
		$search = $_GET['text_search'];
		header("Location: periods_view.php?page=1&text_search=$search");
		exit();
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_POST['refresh'])) {
		$_GET['text_search'] = "";
		header("Location: periods_view.php?page=1&text_search=");
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
	
	$filter = (empty($text_search)) ? "" : "WHERE period_id LIKE '%$text_search%' OR period_code LIKE '%$text_search%' OR period_from LIKE '%$text_search%' OR period_to LIKE '$text_search'";	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT period_id, period_code, period_from, period_to, active FROM periods $filter");
	$rows = mysqli_affected_rows($mysqli);
	include 'core/database/close.php' ;
	$per_page = 10;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1) * $per_page;
?>
	<h1><a href="index.php" style="text-decoration:none;" title="Back">&#8656;</a> Template Reference</h1>
	<p><a href="clients_view_start.php">CLIENTS</a> | PERIODS</p>
	<form action="" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />
		<input type="text" name="text_search" class="span3" value="<?php echo (isset($text_search)) ? $text_search : '' ; ?>" placeholder="Search..." />
		<button type="submit" name="search" title="Search">Search</button><p>					
	</form>
	<form action="" method="post">
		<table class="table_list">
			<thead>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="4" class="left">
						<button type="submit" name="refresh" title="Refresh">Refresh</button>
					</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
					<th>Code</th>
					<th>From</th>
					<th>To</th>
					<th>Active</th>
				</tr>
			</thead>
			<tbody>
<?php
				include 'core/database/connect.php' ;
				$query = $mysqli->query("SELECT period_id, period_code, period_from, period_to, active FROM periods $filter ORDER BY period_id DESC LIMIT $limit ,$per_page");
				while($row = mysqli_fetch_array($query)) {
					$period_id = $row['period_id'];
					$period_code = $row['period_code'];
					$period_from = $row['period_from'];
					$period_to = $row['period_to'];
					$active = $row['active'];
?>
				<tr class="alternate">
					<td class = "bg-color-dimgrey fg-color-white"><input type="text" id ="<?php echo $period_id; ?>" value= "<?php echo $period_code; ?>" readonly /><input type="button" value="Select" onclick="CopyField(<?php echo $period_id; ?>);"></td>
					<td><?php echo $period_from; ?></td>
					<td><?php echo $period_to; ?></td>
					<td class="center"><?php echo ($active === "0") ? "<span style='color:red; font-weight:bold;'>N</span>" : "<span style='color:green'>Y</span>" ; ?></td>
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