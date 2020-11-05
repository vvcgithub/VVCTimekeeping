<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	include 'includes/logged_in.php' ;

	if(isset($_GET['page'])) {
		$current_page = $_GET['page'];
		$current_page = mysql_real_escape_string($current_page);
	} else {
		$current_page = 1;
		header("Location: clients_view2.php?page=1&start=$start");
	}
	
	if(isset($_GET['start'])) {
		$start = $_GET['start'];
		$start = mysql_real_escape_string($start);
	} else {
		$start = 'A';
		header("Location: clients_view2.php?page=1&start=$start");
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
	$result = mysql_query("SELECT * FROM clients WHERE client_code like '$start%'");
	$rows = mysql_num_rows($result);	
	$per_page = 50;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1) * $per_page;
	$query = mysql_query("SELECT * FROM clients WHERE client_code like '$start%'ORDER BY client_code ASC LIMIT $limit ,$per_page");
	
	if(isset($_GET['delete'])) {
		$delete = $_GET['delete'];
		$sql = "DELETE FROM clients WHERE client_id='$delete'";
		mysql_query($sql);
		header('Location: clients_view2.php?page=' . $current_page . '&sort=' . $sort . '&order=' . $order);
	}
?>
	<h1>Clients</h1>
	<form action="" method="get">
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />
		<input type="hidden" name="start" value="<?php echo (isset($start)) ? $start : ''; ?>" />
		<input type="hidden" name="order" value="<?php echo (isset($order)) ? $order : ''; ?>" />
	</form>
	<form action="" method="post">
		<table class="table_list">
			<thead>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="4" class="left white_border center">
						<a href="clients_view2.php?page=1&start=A" style="color:white;" class="padding5">A</a>
						<a href="clients_view2.php?page=1&start=B" style="color:white;" class="padding5">B</a>
						<a href="clients_view2.php?page=1&start=C" style="color:white;" class="padding5">C</a>
						<a href="clients_view2.php?page=1&start=D" style="color:white;" class="padding5">D</a>
						<a href="clients_view2.php?page=1&start=E" style="color:white;" class="padding5">E</a>
						<a href="clients_view2.php?page=1&start=F" style="color:white;" class="padding5">F</a>
						<a href="clients_view2.php?page=1&start=G" style="color:white;" class="padding5">G</a>
						<a href="clients_view2.php?page=1&start=H" style="color:white;" class="padding5">H</a>
						<a href="clients_view2.php?page=1&start=I" style="color:white;" class="padding5">I</a>
						<a href="clients_view2.php?page=1&start=J" style="color:white;" class="padding5">J</a>
						<a href="clients_view2.php?page=1&start=K" style="color:white;" class="padding5">K</a>
						<a href="clients_view2.php?page=1&start=L" style="color:white;" class="padding5">L</a>
						<a href="clients_view2.php?page=1&start=M" style="color:white;" class="padding5">M</a>
						<a href="clients_view2.php?page=1&start=N" style="color:white;" class="padding5">N</a>
						<a href="clients_view2.php?page=1&start=O" style="color:white;" class="padding5">O</a>
						<a href="clients_view2.php?page=1&start=P" style="color:white;" class="padding5">P</a>
						<a href="clients_view2.php?page=1&start=Q" style="color:white;" class="padding5">Q</a>
						<a href="clients_view2.php?page=1&start=R" style="color:white;" class="padding5">R</a>
						<a href="clients_view2.php?page=1&start=S" style="color:white;" class="padding5">S</a>
						<a href="clients_view2.php?page=1&start=T" style="color:white;" class="padding5">T</a>
						<a href="clients_view2.php?page=1&start=U" style="color:white;" class="padding5">U</a>
						<a href="clients_view2.php?page=1&start=V" style="color:white;" class="padding5">V</a>
						<a href="clients_view2.php?page=1&start=W" style="color:white;" class="padding5">W</a>
						<a href="clients_view2.php?page=1&start=X" style="color:white;" class="padding5">X</a>
						<a href="clients_view2.php?page=1&start=Y" style="color:white;" class="padding5">Y</a>
						<a href="clients_view2.php?page=1&start=Z" style="color:white;" class="padding5">Z</a>
					</th>	
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
					<th class="span3">Code</th>
					<th class="span5">Name</th>
					<th class="span0">Active</th>
				</tr>
			</thead>
			<tbody>
<?php	
				while($row = mysql_fetch_array($query)) {
					$client_id = $row['client_id'];
					$client_code = $row['client_code'];
					$client_name = $row['client_name'];
					$active = $row['active'];
?>
				<tr class="alternate">
					<td class="center bg-color-dimgrey fg-color-white"><input type="text" id = "<?php echo $client_id; ?>" class="span2" value= "<?php echo $client_code; ?>" readonly /><input type="button" value="Select" onclick="CopyField('<?php echo $client_id; ?>');"></td>
					<td><?php echo $client_name; ?></td>
					<td class="center"><?php echo ($active === "0") ? "<span style='color:red; font-weight:bold;'>N</span>" : "<span style='color:green'>Y</span>" ; ?></td>
				</tr>
<?php
					}
?>
			</tbody>
			<tfoot>
			</tfoot>
		</table>
	</form>
	<br />

<?php
	include 'includes/overall/footer.php' ; 
?>