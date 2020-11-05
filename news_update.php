<?php 
	include 'core/init.php' ; 
	include 'includes/overall/header.php' ; 
	
	if (isset($_GET['news_id'])) {
		// query db
		$news_id = $_GET['news_id'];
		include 'core/database/connect.php' ;
		$query = $mysqli->query("SELECT * FROM news WHERE news_id=$news_id"); 
		$rows = $query->fetch_assoc();

		// check that the 'id' matches up with a rows in the databse
		if($rows) { // get data from db
			$news_date = $rows['news_date'];
			$news_title = $rows['news_title'];
			$news_description = $rows['news_description'];
		}
		include 'core/database/close.php' ;
	}
	
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	}
	
	if (isset($_GET['text_search'])) {
		$text_search = $_GET['text_search'];
	}
  
	if (isset($_POST['update'])) {
		//$news_id = check_input($_POST['news_id']);
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
			$mysqli->query("UPDATE news SET news_date='" . date("Y-m-d", strtotime($news_date)) . "'
			, news_title='$news_title'
			, news_description='$news_description'
			WHERE news_id='$news_id'"); 
			include 'core/database/close.php' ;

			// once saved, redirect back to the view page
			header ("Location: news_list.php?page=$page&text_search=$text_search");
			exit();
		}
	}
	
	if (isset($_POST['close'])) {
		header ("Location: news_list.php?page=$page&text_search=$text_search");
		exit();
	}
?>
<form action="" method="post">
	<h1><a href="news_list.php" style="text-decoration:none;" title="Back">&#8656;</a> Update News</h1>
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
			<input type="submit" name="update" value="Update" />
		</li>
	</ul>
</form>

<?php
	include 'includes/overall/footer.php' ; 
?>