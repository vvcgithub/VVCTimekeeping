<?php
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	include 'includes/aside.php' ;
	include 'includes/logged_in.php' ;
	
	if (isset($_POST['view_charging_clients_parameter'])) {
		$from = $_POST['from'];
		$to = $_POST['to'];
		$date_from = date("Y-m-d", strtotime($from));
		$date_to = date("Y-m-d", strtotime($to));
		
		if (empty($from)) {
			$errors1[] = "Date from is required!";
		}
		
		if (empty($to)) {
			$errors1[] = "Date to is required!";
		}
		
		if (empty($errors1)) {
			header("Location: charging_clients.php?date_from=$date_from&date_to=$date_to");
			exit();
		}
	}
	
	if (isset($_POST['view_charging_clients_parameter_post'])) {
		$from = $_POST['from'];
		$to = $_POST['to'];
		$date_from = date("Y-m-d", strtotime($from));
		$date_to = date("Y-m-d", strtotime($to));
		
		if (empty($from)) {
			$errors1[] = "Date from is required!";
		}
		
		if (empty($to)) {
			$errors1[] = "Date to is required!";
		}
		
		if (empty($errors1)) {
			header("Location: charging_clients_post.php?date_from=$date_from&date_to=$date_to");
			exit();
		}
	}
	
	if (isset($_POST['view'])) {
		$from = $_POST['from'];
		$to = $_POST['to'];
		$date_from = date("Y-m-d", strtotime($from));
		$date_to = date("Y-m-d", strtotime($to));
		
		if (empty($from)) {
			$errors2[] = "Date from is required!";
		}
		
		if (empty($to)) {
			$errors2[] = "Date to is required!";
		}
		
		
		if (empty($errors2)) {
			header("Location: charging_employees.php?date_from=$date_from&date_to=$date_to");
			exit();
		}
	}
?>
	<h1><a href="reports_main.php" style="text-decoration:none;">&#8656;</a> Client charging</h1>
	<ul>
		<li><a href="#charging_clients_parameter" class="link_new padding10 span3" style="text-decoration:none;">Clients Charging</a></li>
		<div id="charging_clients_parameter" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="close">X</a>
				<h2>Client Charging</h2>
				<form action="" method="post">
<?php
					echo (isset($errors1) && !empty($errors1)) ? output_errors($errors1) : "";
?>
					<br />
					<label for="date_from" class="span1">Date from <span class="fg-color-red">*</span></label>
					<input type="date" id="from" name="from" maxlength="10" class="span2" value="<?php echo (isset($from)) ? $from : "" ; ?>" placeholder="mm/dd/yyyy" />
					<br /><br />
					<label for="date_to" class="span1">Date to <span class="fg-color-red">*</span></label>
					<input type="date" id="to" name="to" maxlength="10" class="span2" value="<?php echo (isset($to)) ? $to : "" ; ?>" placeholder="mm/dd/yyyy" />
					<br /><br />
					<input type="submit" name="view_charging_clients_parameter" value="View" />
				</form>
			</div>
		</div>
		<li><a href="#charging_clients_parameter_post" class="link_new padding10 span3" style="text-decoration:none;">Clients Charging Post</a></li>
		<div id="charging_clients_parameter_post" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="close">X</a>
				<h2>Client Charging Post</h2>
				<form action="" method="post">
<?php
					echo (isset($errors1) && !empty($errors1)) ? output_errors($errors1) : "";
?>
					<br />
					<label for="date_from" class="span1">Date from <span class="fg-color-red">*</span></label>
					<input type="date" id="from" name="from" maxlength="10" class="span2" value="<?php echo (isset($from)) ? $from : "" ; ?>" placeholder="mm/dd/yyyy" />
					<br /><br />
					<label for="date_to" class="span1">Date to <span class="fg-color-red">*</span></label>
					<input type="date" id="to" name="to" maxlength="10" class="span2" value="<?php echo (isset($to)) ? $to : "" ; ?>" placeholder="mm/dd/yyyy" />
					<br /><br />
					<input type="submit" name="view_charging_clients_parameter_post" value="View" />
				</form>
			</div>
		</div>
		<li><a href="#charging_employees_parameter" class="link_new padding10 span3" style="text-decoration:none;">Employees charging</a></li>
		<div id="charging_employees_parameter" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="close">X</a>
				<h2>Employees charging</h2>
				<form action="" method="post">
<?php
					echo (isset($errors2) && !empty($errors2)) ? output_errors($errors2) : "";
?>
					<br />
					<label for="date_from" class="span1">Date from <span class="fg-color-red">*</span></label>
					<input type="date" id="from" name="from" maxlength="10" class="span2" value="<?php echo (isset($from)) ? $from : "" ; ?>" placeholder="mm/dd/yyyy" />
					<br /><br />
					<label for="date_to" class="span1">Date to <span class="fg-color-red">*</span></label>
					<input type="date" id="to" name="to" maxlength="10" class="span2" value="<?php echo (isset($to)) ? $to : "" ; ?>" placeholder="mm/dd/yyyy" />
					<br /><br />
					<input type="submit" name="view" value="View" />
				</form>
			</div>
		</div>
	</ul>
<?php
	include 'includes/overall/footer.php' ; 
?>