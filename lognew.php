<?php
$_SESSION['username'] = "";
if (isset($_POST['username'])) {
	$_SESSION['username'] = $_POST['username'];
}
?>

<script language="javascript">
	function required() {
		alert("Testing");
		return false;
	}
</script>

<div class="widget">  
    <h2>Log in</h2>
    <div class="inner">
		<form action="login.php" method="post">
		<ul id="login">
			<li>
				Username:<br>
				<input type="text" name="username"  value="<?php echo $_SESSION['username'] ?>">
			</li>
			<li>
				Password:<br>	
				<input type="password" name="password">
			</li>
			<li>
				<input type="submit" value="Log in" >
			</li>
		</ul>
	</form>
    </div>
</div>

