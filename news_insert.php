<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	
	if (isset($_POST['close'])) {
		header ("Location: news_list.php");
		exit();
	}
  
	if (isset($_POST['insert'])) {
		$news_date = check_input($_POST['news_date']);
		$news_title = check_input($_POST['news_title']);
		$news_description = check_input($_POST['news_description']);

		if (empty($news_date) === true) {
			$errors[] = "Date is required!";
		}
		
		if (empty($news_title) === true) {
			$errors[] = "Title is required!";
		}
		
		if (empty($news_description) === true) {
			$errors[] = "Description is required!";
		}

		if (empty($errors)) {
				include 'core/database/connect.php' ;
				$mysqli->query("INSERT INTO news (news_date, news_title, news_description) 
				VALUES (
				'". date("Y-m-d", strtotime($news_date)) . "', 
				'$news_title',
				'$news_description'
				)"); 
				include 'core/database/close.php' ;
			
				header('Location: news_list.php');
				exit();
			} 
	}

?>
<form action="" method="post">
	<h1><a href="news_list.php" style="text-decoration:none;" title="Back">&#8656;</a> Insert News</h1>
<?php
		echo (isset($errors) && !empty($errors)) ? output_errors($errors) : "";
?>
	<ul>
		<li>Date *<br />
			<input type="date" name="news_date" class="span2" maxlength="15" value="<?php echo (isset($news_date)) ? $news_date : "" ; ?>" placeholder="mm/dd/yyyy" />
		</li>
		<li>Title *<br />
			<input type="text" name="news_title" class="span5" maxlength="80" value="<?php echo (isset($news_title)) ? $news_title : ""; ?>" />
		</li>
		<li>Description *<br />
			<textarea name="news_description" class="span8" maxlength="100"><?php if (isset($news_description)) { echo $news_description; } ?></textarea>
		</li>
		<li><i>Fields with arterisk (*) sign are required fields</i></li>
		<br />
		<li>
			<input type="submit" name="insert" value="Insert" />
		</li>
	</ul>
</form>

<?php
	include 'includes/overall/footer.php' ; 
?>