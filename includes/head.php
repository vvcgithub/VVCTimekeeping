<head>
	<title>Time Keeping</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
	<link rel="stylesheet" href="css/screen.css">
	<link rel="stylesheet" href="css/print.css">
	<link rel="shortcut icon" href="favicon.ico" />
	
	
	<meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1">
	  
	<script>
		function delete_confirm_for_logs(current_page, log_id, log_date, log_in, log_out, log_client_code) {
			var current_page = current_page;
			var log_id = log_id;
			var log_date = log_date;
			var log_in = log_in;
			var log_out = log_out;
			var log_client_code = log_client_code;
			var answer = confirm("Delete from LOGS where details = " + "\n\tDate: " + log_date + "\n\tIn: " + log_in + "\n\tOut: " + log_out + "\n\tClient: " + log_client_code + "?");
			
			if (answer) {
			window.location="logs_list.php?page="+ current_page + "&delete=" + log_id;
			return true;
			} else {
			//window.location="logs_list.php?page="+ current_page + "&sort=" + sort + "&order=" + order;
			return false;
			}
		}
		
		function delete_all_confirm_for_logs() {
			var answer = confirm("Delete all unchecked records from LOGS?");
			if (answer) {
			window.location="logs_list.php?page=1&empty_trash=";
			return true;
			} else {
			//window.location="logs_list.php?page="+ current_page + "&sort=" + sort + "&order=" + order;
			return false;
			}
		}
		
		function delete_confirm_for_logs_check(log_id, log_date, log_in, log_out, log_client_code, log_employee_id_no, log_period_code) {
			var log_id = log_id;
			var log_date = log_date;
			var log_in = log_in;
			var log_out = log_out;
			var log_client_code = log_client_code;
			var log_employee_id_no = log_employee_id_no;
			var log_period_code = log_period_code;
			var answer = confirm("Delete from LOGS where details = " + "\n\tDate: " + log_date + "\n\tIn: " + log_in + "\n\tOut: " + log_out + "\n\tClient: " + log_client_code + "?");
			
			if (answer) {
			window.location="reviews_check_report.php?delete=" + log_id + "&employee_id_no=" + log_employee_id_no + "&period_code=" + log_period_code;
			return true;
			} else {
			//window.location="logs_list.php?page="+ current_page + "&sort=" + sort + "&order=" + order;
			return false;
			}
		}
		
		function delete_confirm_for_loa(current_page, loa_id, loa_period_code) {
			var current_page = current_page;
			var loa_id = loa_id;
			var loa_period_code = loa_period_code;
			var answer = confirm("Delete from LOA where period = " + loa_period_code);
			
			if (answer) {
			window.location="loa_list.php?page="+ current_page + "&delete=" + loa_id;
			return true;
			} else {
			//window.location="logs_list.php?page="+ current_page + "&sort=" + sort + "&order=" + order;
			return false;
			}
		}
	
		function delete_confirm_for_employees(current_page, employee_id, employee_name, text_search) {
			var current_page = current_page;
			var employee_id = employee_id;
			var employee_name = employee_name;
			var text_search = text_search;
			var answer = confirm("Delete from EMPLOYEES where employee name = " + employee_name + "?");
			
			if (answer) {
			window.location="employees_list.php?page="+ current_page + "&delete=" + employee_id + "&text_search=" + text_search;
			return true;
			} else {
			//window.location="employees_list.php?page="+ current_page + "&sort=" + sort + "&order=" + order;
			return false;
			}
		}
	  
		function delete_confirm_for_clients(current_page, client_id, client_code, text_search) {
			var current_page = current_page;
			var client_id = client_id;
			var client_code = client_code;
			var text_search = text_search;
			var answer = confirm("Delete from CLIENTS where client code = " + client_code + "?");
			
			if (answer) {
			window.location="clients_list.php?page="+ current_page + "&delete=" + client_id + "&text_search=" + text_search;
			return true;
			} else {
			//window.location="clients_list.php?page="+ current_page + "&sort=" + sort + "&order=" + order;
			return false;
			}
		}
		
		function delete_confirm_for_positions(current_page, position_id, position_code, text_search) {
			var current_page = current_page;
			var position_id = position_id;
			var position_code = position_code;
			var text_search = text_search;
			var answer = confirm("Delete from POSITIONS where position code = " + position_code + "?");
			
			if (answer) {
			window.location="positions_list.php?page="+ current_page + "&delete=" + position_id + "&text_search=" + text_search;
			return true;
			} else {
			//window.location="clients_list.php?page="+ current_page + "&sort=" + sort + "&order=" + order;
			return false;
			}
		}
		
		function delete_confirm_for_privileges(current_page, privilege_id, privilege_employee, text_search) {
			var current_page = current_page;
			var privilege_id = privilege_id;
			var privilege_employee = privilege_employee;
			var text_search = text_search;
			var answer = confirm("Delete from PRIVILEGES where employee name = " + privilege_employee + "?");
			
			if (answer) {
			window.location="privileges_list.php?page="+ current_page + "&delete=" + privilege_id + "&text_search=" + text_search;
			return true;
			} else {
			//window.location="clients_list.php?page="+ current_page + "&sort=" + sort + "&order=" + order;
			return false;
			}
		}
		
		function delete_confirm_for_periods(current_page, period_id, period_code, text_search) {
			var current_page = current_page;
			var period_id = period_id;
			var period_code = period_code;
			var text_search = text_search;
			var answer = confirm("Delete from PERIODS where period code = " + period_code + "?");
			
			if (answer) {
			window.location="periods_list.php?page="+ current_page + "&delete=" + period_id + "&text_search=" + text_search;
			return true;
			} else {
			//window.location="clients_list.php?page="+ current_page + "&sort=" + sort + "&order=" + order;
			return false;
			}
		}
		
		function delete_confirm_for_news(current_page, news_id, news_title, text_search) {
			var current_page = current_page;
			var news_id = news_id;
			var news_title = news_title;
			var text_search = text_search;
			var answer = confirm("Delete from NEWS where news title = " + news_title + "?");
			
			if (answer) {
			window.location="news_list.php?page="+ current_page + "&delete=" + news_id + "&text_search=" + text_search;
			return true;
			} else {
			//window.location="clients_list.php?page="+ current_page + "&sort=" + sort + "&order=" + order;
			return false;
			}
		}
		
		function delete_confirm_for_bugs(current_page, bug_id, bug_description, text_search) {
			var current_page = current_page;
			var bug_id = bug_id;
			var bug_description = bug_description;
			var text_search = text_search;
			var answer = confirm("Delete from BUGS where description = " + bug_description + "?");
			
			if (answer) {
			window.location="bugs_list.php?page="+ current_page + "&delete=" + bug_id + "&text_search=" + text_search;
			return true;
			} else {
			//window.location="clients_list.php?page="+ current_page + "&sort=" + sort + "&order=" + order;
			return false;
			}
		}
		
		function toggle_logs_list(source) {
			row = document.getElementsByName('row_id[]');
			var color1 = 'lightskyblue';
			var color2 = '';
			checkboxes = document.getElementsByName('unique_id[]');
			for (var i = 0; i < checkboxes.length; i++) {
				checkboxes[i].checked = source.checked;
				row[i].style.background = (source.checked ? color1 : color2);
			} 
		}
		
		function highlight(row, box) {		
			rows = document.getElementById(row);
			var color1 = 'lightskyblue';
			var color2 = '';
			rows.style.background = (box.checked ? color1 : color2);
		}
		
		function CopyField(row) {
			document.getElementById(row).focus();
			document.getElementById(row).select();
			document.execCommand("Copy");
		}
	</script>
</head>