<div class = "widget">
	<h2>Employees</h2>
	<div class = "inner">
		<?php
			$employee_count = employee_count();
			$suffix = ($employee_count != 1) ? 's' : '';
		?>
		
		We currently have <?php echo $employee_count ?> active employee<?php echo $suffix ?>.
	</div>
</div>