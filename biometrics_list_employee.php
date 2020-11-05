<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ; 
	
	$employee_employee_id_no = $employee_data['employee_id_no'];
	
	if(isset($_GET['page'])) {
		$current_page = check_input($_GET['page']);
	} else {
		$current_page = 1;
		header('Location: biometrics_list_employee.php?page=1&from=&to=');
		exit();
	}
	
	if (isset($_GET['from']) && isset($_GET['to'])) {
		$from = $_GET['from'];
		$to = $_GET['to'];
		
		if (!empty($from) && !empty($to)) {
			$date_time =  " AND biometrics_date BETWEEN '" . date("Y-m-d", strtotime($from)) . "' and '" .  date("Y-m-d", strtotime($to)) . "'";
		} else {
			$date_time = "";
		}
	} else {
		$from = "";
		$to = "";
	}
	
	if (isset($_GET['search'])) {
		header("Location: biometrics_list_employee.php?page=1&from=$from&to=$to");
		echo "adsf";
	}

	if (isset($_POST['back'])) {
		header("Location: biometrics_main.php");
		exit();
	}
	
	if (isset($_POST['refresh'])) {
		header("Location: biometrics_list_employee.php?page=1&from=&to=");
		exit();
	}
	
	if (isset($_POST['all'])) {
		header("Location: biometrics_list_all.php?page=1&employee_id_no=$employee_employee_id_no&from=&to=");
		exit();
	}
	
	if (isset($_GET['download'])) {
		header("Location: biometrics_to_csv_final.php?employee_id=$employee_id&date_from_final=$from&date_to_final=$to");
	}
	
	$filter = (empty($from) || empty($to)) ? "" : "$date_time";	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM biometrics WHERE biometrics_employee_id_no = '$employee_employee_id_no' $filter");
	$rows = mysqli_affected_rows($mysqli);
	include 'core/database/close.php' ;	
	$per_page = 50;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1) * $per_page;
?>

	<h1><a href="biometrics_main.php" style="text-decoration:none;" title="Back">&#8656;</a> Biometrics</h1>
	<form action="" method="get">
		
		<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />
		From <input type="date" name="from" id="from" class="span2" value="<?php echo (!empty($from)) ? $from : '' ; ?>" placeholder="yyyy-mm-dd" />
		to <input type="date" name="to" id="to" class="span2" value="<?php echo (!empty($to)) ? $to : '' ; ?>" placeholder="yyyy-mm-dd" />
		<input type="submit" name="search" title="Search" value="Search"><p>			
			
	</form>
	<form action="" method="post">
		<table class="table_list">
			<thead>
				<tr class="bg-color-dimgrey fg-color-white">
					<th colspan="4" class="left">
						<button type="submit" name="refresh" class="button_text" title="Refresh">Refresh</button>
						<button type="submit" name="all" class="button_text" title="All">All</button>
					</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
					<th>Date</th>
					<th>Time</th>
					<th>Employee</th>
					<th>ID</th>
				</tr>
			</thead>
			<tbody>
<?php	
				include 'core/database/connect.php' ;
				$query = $mysqli->query("SELECT * FROM biometrics WHERE biometrics_employee_id_no = '$employee_employee_id_no' $filter ORDER BY biometrics_id DESC LIMIT $limit ,$per_page");
				while($row = mysqli_fetch_array($query)) {
					$biometrics_id = $row['biometrics_id'];
					$employee_employee_id_no = $row['biometrics_employee_id_no'];
					$biometrics_date = date("Y-m-d", strtotime($row['biometrics_date']));
					$day = date("D", strtotime($row['biometrics_date']));
					$biometrics_time = $row['biometrics_time'];
?>
					<tr class="alternate">
						<td class="left"><?php echo $biometrics_date . " <small>$day</small>"; ?></td>
						<td class="center"><?php echo $biometrics_time; ?></td>
						<td class="center"><?php echo complete_name_from_id_no($employee_employee_id_no); ?></td>
						<td class="center"><?php echo $biometrics_id; ?></td>
					</tr>
<?php
				}
				include 'core/database/close.php' ;
?>
			</tbody>
			<tfoot>
			<tr class="bg-color-dimgrey fg-color-white">
				<th colspan="4" class="right">
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
	include 'includes/pagination_biometrics.php' ; 
	include 'includes/overall/footer.php' ; 
?>