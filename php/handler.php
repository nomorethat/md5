<?php
	require_once "auxiliary_functions.php"; 
	require_once "steps.php";
	
	if($_POST["open_message"] !== false){
		$open_message = $_POST["open_message"];
		md_5($open_message);
	}

	function md_5($open_message){
		$binnary_open_message = "";
		for($i = 0; $i < strlen($open_message); $i++){
			$binnary_open_message .= bstr2bin($open_message[$i]); 
		}
		
		$extended_binnary_open_message = adding_missing_bits($binnary_open_message); 
		
		$binnary_record_of_open_message_length = decbin(strlen($open_message));
		$extended_binnary_record_of_open_message_length = extended_binnary_record_of_open_message_length($binnary_record_of_open_message_length);
		$extended_binnary_open_message = $extended_binnary_open_message.$extended_binnary_record_of_open_message_length;
		
		initialization_of_MD_buffer();
		$T = generate_table_T_with_constants();
		
		$array_of_512_bits_blocks = break_extended_binnary_open_message_into_512_bits_blocks($extended_binnary_open_message);
		main_cycle($array_of_512_bits_blocks, $T);
	}
?>