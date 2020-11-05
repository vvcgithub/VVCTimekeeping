<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ;
	
	if (logged_in() == false) {
		header('Location: index.php');
		exit();
	}

	if(isset($_GET['page'])) {
		$current_page = $_GET['page'];
		$current_page = mysql_real_escape_string($current_page);
	} else {
		$current_page = 1;
		header("Location: departments_list.php?page=1&sort=department_id&order=ASC&text_search=");
	}
	
	if (isset($_GET['search'])) {
		$current_page = 1;
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = check_input($_GET['text_search']);
	} else {
		$text_search = "";
	}
	
	if (isset($_GET['refresh'])) {
		$_GET['text_search'] = "";
		$text_search = check_input($_GET['text_search']);
		$current_page = 1;
	}
	
	if (isset($_GET['add'])) {
		header("Location: departments_insert.php");
		exit();
	}
	
	if (isset($_GET['download'])) {
		header("Location: backup_tables_to_csv_final.php?departments");
		exit();
	}
	
	if (isset($_GET['close'])) {
		header("Location: index.php");
		exit();
	}
	
	$sortDefault = 'department_id';
	$sortColumns = array('department_id', 'department_code', 'department_name', 'active');
	$sort = (isset($_GET['sort']) && in_array($_GET['sort'], $sortColumns)) ? $_GET['sort'] : $sortDefault;
	$order = (isset($_GET['order']) && strcasecmp($_GET['order'], 'DESC') == 0) ? 'DESC' : 'ASC'; 	
	
	$filter = (empty($text_search)) ? "" : "WHERE department_id LIKE '%$text_search%' OR department_code LIKE '%$text_search%' OR department_name LIKE '%$text_search%' OR active LIKE '$text_search'";	
	$result = mysql_query("SELECT * FROM departments $filter");
	$rows = mysql_num_rows($result);	
	$per_page = 10;
	$numpages = ceil($rows/$per_page);
	$limit = ($current_page-1) * $per_page;
	$query = mysql_query("SELECT * FROM departments $filter ORDER BY $sort $order LIMIT $limit ,$per_page");
	
	if(isset($_GET['delete'])) {
		$delete = $_GET['delete'];
		$sql = "DELETE FROM departments WHERE department_id='$delete'";
		mysql_query($sql);
		header('Location: departments_list.php?page=' . $current_page . '&sort=' . $sort . '&order=' . $order);
	}
?>
<h1>Departments List</h1>
<?php
	if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_clients($employee_data['employee_id']) == 2 || privilege_clients($employee_data['employee_id']) == 3) {
?>
		<a href="logs_insert.php">Add record</a> |
<?php
	}
?>
<a href="departments_list.php?page=1&sort=department_id_id&order=ASC&text_search=&refresh">Refresh</a> |
<a href="departments_list.php?page=1&sort=department_id_id&order=ASC&text_search=&download">Download</a><p>
<form action="" method="get">
	<input type="hidden" name="page" value="<?php echo (isset($current_page)) ? $current_page : ''; ?>" />
	<input type="hidden" name="sort" value="<?php echo (isset($sort)) ? $sort : ''; ?>" />
	<input type="hidden" name="order" value="<?php echo (isset($order)) ? $order : ''; ?>" />
	<input type="text" name="text_search" class="span3" value="<?php echo (isset($text_search)) ? $text_search : '' ; ?>" placeholder="Search..." />
	<button type="submit" name="search" title="Search">Search</button><p>					
</form>
<table class="table_list">
	<thead>
		<tr>
			<th colspan="5" class="right white_border">
<?php
				include 'includes/page_number.php' ; 
?>
			</th>
		</tr>
		<tr class="bg-color-dimgrey fg-color-white">
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_departments($employee_data['employee_id']) == 2 || privilege_departments($employee_data['employee_id']) == 4) {
?>
			<th></th>
<?php
			}
?>
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_departments($employee_data['employee_id']) == 2 || privilege_departments($employee_data['employee_id']) == 5) {
?>
			<th></th>
<?php
			}
?>
			<th class="center">Code</th>
			<th class="center">Name</th>
			<th class="center">Active</th>
		</tr>
	</thead>
	<tbody>
<?php	
		while($row = mysql_fetch_array($query)) {
			$department_id = $row['department_id'];
			$department_code = $row['department_code'];
			$department_name = $row['department_name'];
			$active = $row['active'];
?>
		<tr class="alternate">
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_departments($employee_data['employee_id']) == 2 || privilege_departments($employee_data['employee_id']) == 4) {
?>
				<td class="center">
					<form action="departments_update.php" method="post">
						<input type="hidden" name="department_id" value="<?php echo $department_id; ?>" />
						<button type="submit" title="Edit record"><img src="icons/edit.png" /></button>
					</form>
				</td>
<?php
			}
?>
<?php
			if (employee_account_type($employee_data['employee_id']) === 'Administrator' || privilege_departments($employee_data['employee_id']) == 2 || privilege_departments($employee_data['employee_id']) == 5) {
?>
				<td class="center">
					<button type="button" name="delete" title="Delete record" onclick="delete_confirm_for_departments('<?php echo $current_page; ?>', '<?php echo $sort; ?>', '<?php echo $order; ?>', '<?php echo $department_id; ?>', '<?php echo $department_code; ?>')"><img src="icons/delete.png" /></button>
				</td>
<?php
			}
?>
			<td <?php echo ($active == 0) ? "style='opacity:.4; text-decoration:line-through;'" : "style='text-align:left;'" ; ?>><?php echo $department_code; ?></td>
			<td <?php echo ($active == 0) ? "style='opacity:.4; text-decoration:line-through;'" : "style='text-align:left;'" ; ?>><?php echo $department_name; ?></td>
			<td class="center" style="padding:2px;"><?php echo ($active === "0") ? "<span style='color:red; font-weight:bold;'>N</span>" : "<span style='color:green'>Y</span>" ; ?></td>
		</tr>
<?php
			}
?>
	</tbody>
	<tfoot></tfoot>
</table>
<br />
<div class="pagination">
	<ul>
<?php
		if($current_page!=1)
		{
			
			echo "<li><a href='?page=1&sort=$sort&order=$order&text_search=$text_search'>First</a></li>";
			$previous = $current_page-1;
			echo "<li><a href='?page=$previous&sort=$sort&order=$order&text_search=$text_search'>Previous</a></li>";
		}

		$range=5;
		$buffer = ($range % 2)? ceil($range/2)-1 : ceil($range/2);

		if($numpages != $range && $range < $numpages) {
			$t_beginno = $current_page - $buffer;
			$t_endno = ($range % 2) ? $current_page + $buffer : $current_page + ($buffer-1);
			$beginno = ($t_beginno < 1 )? 1 : (($numpages - $current_page < $buffer)? $numpages - ($range-1) : $t_beginno);
			$endno = ($t_beginno < 1 )? $range : (($numpages - $current_page < $buffer)? $numpages : $t_endno);
		}
		else { 
			$beginno = 1; 
			$endno = $numpages;
		}
		for($page=$beginno;$page<=$endno;$page++) {
			echo ($page == $current_page) ? "<li class='bold'><a href='?page=$page&sort=$sort&order=$order'>$page</a></li>" : "<li><a href='?page=$page&sort=$sort&order=$order&text_search=$text_search'>$page</a></li>";
		}
		if($current_page < $numpages) {
			$next=$current_page+1;
			echo "<li><a href='?page=$next&sort=$sort&order=$order&text_search=$text_search'>Next</a></li>";
			echo "<li><a href='?page=$numpages&sort=$sort&order=$order&text_search=$text_search'>Last</a></li>";
		}
?>
	</ul>
</div>
<?php
include 'includes/overall/footer.php' ; 
?>