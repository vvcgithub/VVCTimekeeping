<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'includes/logged_in.php' ;

	if(isset($_GET['page'])) {
		$current_page = $_GET['page'];
		$current_page = mysql_real_escape_string($current_page);
	} else {
		$current_page = 1;
		header("Location: clients_view.php?page=1&text_search=");
	}
	
	if (isset($_GET['search'])) {
		$current_page = 1;
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_POST['refresh'])) {
		$_GET['text_search'] = "";
		$text_search = check_input($_GET['text_search']);
		$current_page = 1;
	}
	
	
	if (isset($_GET['download'])) {
		header("Location: backup_tables_to_csv_final.php?clients");
		exit();
	}
	
	if (isset($_GET['close'])) {
		header("Location: index.php");
		exit();
	}
	
	$filter = (empty($text_search)) ? "" : "WHERE client_id LIKE '". client_id($text_search) . "' OR client_code LIKE '%text_search%' OR client_name LIKE '%$text_search%' OR active LIKE '$text_search'";	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM clients $filter");
	$rows = mysqli_affected_rows($mysqli);
	include 'core/database/close.php' ;
	$per_page = 50;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1) * $per_page;
?>
	<h1>Clients</h1>
	<form action="" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />
		<input type="hidden" name="sort" value="<?php echo (isset($sort)) ? $sort : ''; ?>" />
		<input type="hidden" name="order" value="<?php echo (isset($order)) ? $order : ''; ?>" />
		<input type="text" name="text_search" class="span3" value="<?php echo (isset($text_search)) ? $text_search : '' ; ?>" placeholder="Search..." />
		<button type="submit" name="search" title="Search">Search</button><p>					
	</form>
	<form action="" method="post">
		<table class="table_list">
			<thead>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="4" class="left white_border">
						<button type="submit" name="refresh" title="Refresh"><img src="icons/refresh.png" /></button>
					</th>	
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
					<th>Code</th>
					<th>Name</th>
					<th>Active</th>
				</tr>
			</thead>
			<tbody>
<?php	
				include 'core/database/connect.php' ;
				$query = $mysqli->query("SELECT * FROM clients $filter ORDER BY client_id DESC LIMIT $limit ,$per_page");
				while($rows = mysql_fetch_array($query)) {
					$client_id = $rows['client_id'];
					$client_code = $rows['client_code'];
					$client_name = $rows['client_name'];
					$active = $rows['active'];
?>
				<tr class="alternate">
					<td class = "bg-color-dimgrey fg-color-white"><input type="text" id = "<?php echo $client_id; ?>" value= "<?php echo $client_code; ?>" readonly /><input type="button" value="Select" onclick="CopyField('<?php echo $client_id; ?>');"></td>
					<td><?php echo $client_name; ?></td>
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
	include 'includes/pagination.php' ; 
	include 'includes/overall/footer.php' ; 
?>