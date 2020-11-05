<div class="pagination">
	<ul>
<?php
		if($current_page!=1)
		{
			
			echo "<li><a href='?page=1&text_search=$text_search'>First</a></li>";
			$previous = $current_page-1;
			echo "<li><a href='?page=$previous&text_search=$text_search'>Previous</a></li>";
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
			echo ($page == $current_page) ? "<li class='bold'><a href='?page=$page&text_search=$text_search'>$page</a></li>" : "<li><a href='?page=$page&text_search=$text_search'>$page</a></li>";
		}
		if($current_page < $numpages) {
			$next=$current_page+1;
			echo "<li><a href='?page=$next&text_search=$text_search'>Next</a></li>";
			echo "<li><a href='?page=$numpages&text_search=$text_search'>Last</a></li>";
		}
?>
	</ul>
</div>