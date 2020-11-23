<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/logged_in.php' ;
	include 'core/database/connect.php' ;
	
		
	if(isset($_GET['page'])) {
		$current_page = $_GET['page'];
		$current_page = mysqli_real_escape_string($mysqli, $current_page);
	} else {
		$current_page = 1;
		header('Location: loa_list.php?page=1');
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_GET['search'])) {
		$current_page = 1;
	}
	
	if (isset($_POST['refresh'])) {
		header('Location: loa_list.php?page=1');
		exit();
	}
	
	if (isset($_POST['add'])) {
		header('Location: loa_insert.php');
		exit();
	}
	
	if (isset($_GET['close'])) {
		header('Location: index.php');
		exit();
	}
	
	$employee_id = $employee_data['employee_id'];
	$employee_id_no = $employee_data['employee_id_no'];
	
	include 'core/database/connect.php' ;
	$query = $mysqli->query("SELECT * FROM loa WHERE loa_employee_id_no = $employee_id_no AND loa_check = 0");
	$rows = mysqli_affected_rows($mysqli);
	include 'core/database/close.php' ;
	$per_page = 30;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1)* $per_page;
	
	
	
	if (isset($_POST['delete_selected'])) {
		include 'core/database/connect.php' ;
		if (isset($_POST['unique_id']) && !empty($_POST['unique_id'])) {
			$unique_id = $_POST['unique_id'];
			$mysqli->autocommit(FALSE);
			foreach ($unique_id as $id => $value) {
				$mysqli->query("DELETE FROM loa WHERE loa_id = '$value' AND loa_check = 0");
			}
			header("Location: loa_list.php?page=$current_page");
			exit();
		}
		include 'core/database/close.php' ;
	}

	if(isset($_GET['delete'])) { 
		$loa_id = $_GET['delete'];
		include 'core/database/connect.php' ;
		$mysqli->query("DELETE FROM loa WHERE loa_id='$loa_id'");
		include 'core/database/close.php' ;
		header('Location: loa_list.php?page=' . $current_page);
		exit();
	}
	
?>
	<h1><a href="index.php" style="text-decoration:none;" title="Back">&#8656;</a> Leave List</h1>
	<form action="" method="post">
		<table class="table_list">
			<thead>
				<tr style="padding:0px;">
					<th colspan="6" class="left" style="border:none;">
						<input type="submit" name="add" value="Add" class="" />
						<input type="submit" name="refresh" value="Refresh" class="" />
					</th>
				</tr>
				<tr class="bg-color-dimgrey fg-color-white">
					<th></th>
					<th></th>
					<th>Period</th>
					<th>SL</th>
					<th>VL</th>
					<th>LWOP</th>
					<th>M/P-aternity</th>
				</tr>
			</thead>
			<tbody>
<?php	
				include 'core/database/connect.php' ;
				$query = $mysqli->query("SELECT loa_id, loa_period_code, loa_employee_id_no, loa_employee_id_no, loa_sl, loa_vl, loa_lwop, loa_mpaternity, loa_check FROM loa WHERE loa_employee_id_no = $employee_id_no ORDER BY loa_id DESC LIMIT $limit ,$per_page");
				//while($rows = $query->fetch_assoc()) {
				while($rows = mysqli_fetch_assoc($query)) {
					$loa_id = $rows['loa_id'];
					$loa_period_code = $rows['loa_period_code'];
					$loa_employee_id_no =$rows['loa_employee_id_no'];
					$loa_sl =$rows['loa_sl'];
					$loa_vl =$rows['loa_vl'];
					$loa_lwop =$rows['loa_lwop'];
					$loa_mpaternity =$rows['loa_mpaternity'];
					$loa_check = $rows['loa_check'];
?>				
					<tr id="<?php echo $loa_id; ?>" name="row_id[]" style="<?php echo ($loa_check == 1) ? 'background:lightgreen; color:grey;' : ''; ?>" class="alternate">
						<td class="center">
<?php
							if ($loa_check == 0) {
?>
								<a href="loa_update.php?loa_id=<?php echo $loa_id ; ?>&page=<?php echo $current_page ; ?>">Edit</a>
<?php
							} else {
?>
								Edit
<?php
							}
?>	
						</td>
						<td class="center">
<?php
							if ($loa_check == 0) {
?>
							<a href="#" onclick="delete_confirm_for_loa('<?php echo $current_page; ?>', '<?php echo $loa_id; ?>', '<?php echo $loa_period_code; ?>')">Delete</a>
<?php
							} else {
?>
								Delete
<?php
							}
?>							
						</td>
						<td class="center"><?php echo $loa_period_code; ?></td>
						<td class="center"><?php echo $loa_sl; ?></td>
						<td class="center"><?php echo $loa_vl; ?></td>
						<td class="center"><?php echo $loa_lwop; ?></td>
						<td class="center"><?php echo $loa_mpaternity; ?></td>
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
	include 'includes/pagination_no_search.php' ; 
	include 'includes/overall/footer.php' ; 
?>