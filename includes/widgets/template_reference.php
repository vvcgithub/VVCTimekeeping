<?php
	
	if(isset($_GET['start'])) {
		$start = $_GET['start'];
		$start = check_input($start);
	} else {
		$start = 'A';
		//header("Location: #clients_view_start?start=$start");
	}
	
	if (isset($_POST['refresh'])) {
		$_GET['text_search'] = "";
		header("Location: ?page=1&text_search=");
		exit();
	}
	
		
	//$filter = (empty($text_search)) ? "" : "WHERE period_id LIKE '%$text_search%' OR period_code LIKE '%$text_search%' OR period_from LIKE '%$text_search%' OR period_to LIKE '$text_search'";	
	//include 'core/database/connect.php' ;
	//$query = $mysqli->query("SELECT period_id, period_code, period_from, period_to, active FROM periods $filter");
	//$rows = mysqli_affected_rows($mysqli);
	//include 'core/database/close.php' ;
	
?>
	<div class = "widget">
		<h2>Template's Reference</h2>
		<div class = "inner">
			<ul>
				<li><a href="#clients_view_start" class="link_new_light padding10" style="text-decoration:none; width:90%;">View Clients</a></li>
				<li><a href="#positions_view" class="link_new_light padding10" style="text-decoration:none; width:90%;">View Positions</a></li>
				<li><a href="#periods_view"class="link_new_light padding10" style="text-decoration:none; width:90%;" >View Periods <sup><span class="fg-color-red"><?php echo period_active_last(); ?></span><sup></a> </li>
			</ul>
		</div>
	</div>
	
	<div id="clients_view_start" class="modalDialog">
		<div style="width:750px;height:380px;">
			<h2>View Clients</h2>
				<table class="table_list" >
					<thead>
						<tr class="bg-color-dimgrey fg-color-white">
							<th class="center">
								<a href="?start=A#clients_view_start" style="color:white;" class="padding5">A</a>
								<a href="?start=B#clients_view_start" style="color:white;" class="padding5">B</a>
								<a href="?start=C#clients_view_start" style="color:white;" class="padding5">C</a>
								<a href="?start=D#clients_view_start" style="color:white;" class="padding5">D</a>
								<a href="?start=E#clients_view_start" style="color:white;" class="padding5">E</a>
								<a href="?start=F#clients_view_start" style="color:white;" class="padding5">F</a>
								<a href="?start=G#clients_view_start" style="color:white;" class="padding5">G</a>
								<a href="?start=H#clients_view_start" style="color:white;" class="padding5">H</a>
								<a href="?start=I#clients_view_start" style="color:white;" class="padding5">I</a>
								<a href="?start=J#clients_view_start" style="color:white;" class="padding5">J</a>
								<a href="?start=K#clients_view_start" style="color:white;" class="padding5">K</a>
								<a href="?start=L#clients_view_start" style="color:white;" class="padding5">L</a>
								<a href="?start=M#clients_view_start" style="color:white;" class="padding5">M</a>
								<a href="?start=N#clients_view_start" style="color:white;" class="padding5">N</a>
								<a href="?start=O#clients_view_start" style="color:white;" class="padding5">O</a>
								<a href="?start=P#clients_view_start" style="color:white;" class="padding5">P</a>
								<a href="?start=Q#clients_view_start" style="color:white;" class="padding5">Q</a>
								<a href="?start=R#clients_view_start" style="color:white;" class="padding5">R</a>
								<a href="?start=S#clients_view_start" style="color:white;" class="padding5">S</a>
								<a href="?start=T#clients_view_start" style="color:white;" class="padding5">T</a>
								<a href="?start=U#clients_view_start" style="color:white;" class="padding5">U</a>
								<a href="?start=V#clients_view_start" style="color:white;" class="padding5">V</a>
								<a href="?start=W#clients_view_start" style="color:white;" class="padding5">W</a>
								<a href="?start=X#clients_view_start" style="color:white;" class="padding5">X</a>
								<a href="?start=Y#clients_view_start" style="color:white;" class="padding5">Y</a>
								<a href="?start=Z#clients_view_start" style="color:white;" class="padding5">Z</a>
							</th>	
						</tr>
					</thead>
				</table>
			
				<br />
			
				<a href="#close" title="Close" class="close">X</a>
				<form action="" method="post">
					<div style="height:260px;overflow:auto;">
						<table class="table_list">
							<thead>
								<tr class="bg-color-dimgrey fg-color-white">
									<th class="span3 center">Code</th>
									<th class="span5 center">Name</th>
									<th class="span0 center">Active</th>
								</tr>
							</thead>
							<tbody style="overflow: auto; height:50%;">
<?php
								include 'core/database/connect.php' ;
								$query = $mysqli->query("SELECT * FROM clients WHERE client_code like '$start%' ORDER BY client_code ASC");
								while($row = mysqli_fetch_array($query)) {
									$client_id = $row['client_id'];
									$client_code = $row['client_code'];
									$client_name = $row['client_name'];
									$active = $row['active'];
?>
								<tr class="alternate" >
									<td class="center bg-color-dimgrey fg-color-white"><input type="text" id = "<?php echo $client_id; ?>" value= "<?php echo $client_code; ?>" readonly /><input type="button" value="Select" onclick="CopyField('<?php echo $client_id; ?>');"></td>
									<td><?php echo $client_name; ?></td>
									<td class="center"><?php echo ($active === "0") ? "<span style='color:red; font-weight:bold;'>N</span>" : "<span style='color:green'>Y</span>" ; ?></td>
								</tr>
<?php
								}
								include 'core/database/close.php' ;
?>
							</tbody>
							<tfoot>
							</tfoot>
						</table>
					</div>
				</form>
			
		</div>	
	</div>
	
	<div id="positions_view" class="modalDialog">
		<div style="width:470px;height:280px;">
			<a href="#close" title="Close" class="close">X</a>
			<h2>View Positions</h2>
			<div style="height:223px;overflow:auto;">
				<form action="" method="post">
					<table class="table_list" style="overflow:auto;">
						<thead>
							<tr class="bg-color-dimgrey fg-color-white">
								<th>Code</th>
								<th>Title</th>
							</tr>
						</thead>
						<tbody>
<?php
							include 'core/database/connect.php' ;
							$query = $mysqli->query("SELECT position_id, position_code, position_name FROM positions ORDER BY position_id DESC");
							while($row = mysqli_fetch_array($query)) {
								$position_id = $row['position_id'];
								$position_code = $row['position_code'];
								$position_name = $row['position_name'];
?>
							<tr class="alternate">
								<td class = "bg-color-dimgrey fg-color-white"><input type="text" id ="<?php echo $position_id; ?>" value= "<?php echo $position_code; ?>" readonly /><input type="button" value="Select" onclick="CopyField(<?php echo $position_id; ?>);"></td>
								<td><?php echo $position_name; ?></td>
							</tr>
<?php
							}
							include 'core/database/close.php' ;
?>
						</tbody>
						<tfoot>
							
						</tfoot>
					</table>
				</form>
			</div>
		</div>
	</div>
	
	<div id="periods_view" class="modalDialog">
		<div style="width:470px;height:280px;">
			<a href="#close" title="Close" class="close">X</a>
			<h2>View Periods</h2>
			<div style="width:450px;max-height:223px;overflow:auto;">
				<form action="" method="post">
					<table class="table_list" style="overflow:auto;">
						<thead>
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
							$query = $mysqli->query("SELECT period_id, period_code, period_from, period_to, active FROM periods ORDER BY period_id DESC");
							while($row = mysqli_fetch_array($query)) {
								$period_id_ = $row['period_id'];
								$period_code_ = $row['period_code'];
								$period_from_ = $row['period_from'];
								$period_to_ = $row['period_to'];
								$active_ = $row['active'];
?>
							<tr class="alternate">
								<td class = "bg-color-dimgrey fg-color-white"><input type="text" id ="<?php echo $period_id_; ?>" value= "<?php echo $period_code_; ?>" readonly /><input type="button" value="Select" onclick="CopyField(<?php echo $period_id_; ?>);"></td>
								<td><?php echo $period_from_; ?></td>
								<td><?php echo $period_to_; ?></td>
								<td class="center"><?php echo ($active_ === "0") ? "<span style='color:red; font-weight:bold;'>N</span>" : "<span style='color:green'>Y</span>" ; ?></td>
							</tr>
<?php
							}
							include 'core/database/close.php' ;
?>
						</tbody>
						<tfoot>
							
						</tfoot>
					</table>
				</form>
			</div>
		</div>
	</div>
	
	