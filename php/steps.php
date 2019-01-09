<?php
	
	function adding_missing_bits($binnary_open_message){
		$extended_binnary_open_message = $binnary_open_message;
		$extended_binnary_open_message .= "1";
		if((strlen($extended_binnary_open_message))%512 == 448)
			return $extended_binnary_open_message;
		else{
			while(1){
				$extended_binnary_open_message .= "0";
				if((strlen($extended_binnary_open_message))%512 == 448)
					break;
			}
		}
		return $extended_binnary_open_message;
	}
	
	function extended_binnary_record_of_open_message_length($binnary_record_of_open_message_length){
		if(strlen($binnary_record_of_open_message_length) < 64){
			while(1){
				if(strlen($binnary_record_of_open_message_length) == 64)
					break;
				$binnary_record_of_open_message_length = "0".$binnary_record_of_open_message_length;	
			}
		}
		
		$binnary_record_of_open_message_length = substr($binnary_record_of_open_message_length, 56).substr($binnary_record_of_open_message_length, 48, 8).substr($binnary_record_of_open_message_length, 40, 8).substr($binnary_record_of_open_message_length, 32, 8).substr($binnary_record_of_open_message_length, 24, 8).substr($binnary_record_of_open_message_length, 16, 8).substr($binnary_record_of_open_message_length, 8, 8).substr($binnary_record_of_open_message_length, 0, 8);
		return $binnary_record_of_open_message_length;
	}

	function initialization_of_MD_buffer(){
		$a = extend_binnary_record_for_32_bits(base_convert("67452301", 16, 2)); 
		$b = extend_binnary_record_for_32_bits(base_convert("efcdab89", 16, 2));
		$c = extend_binnary_record_for_32_bits(base_convert("98badcfe", 16, 2));
		$d = extend_binnary_record_for_32_bits(base_convert("10325476", 16, 2));
		return $MD_buffer = Array("a" => $a, "b" => $b, "c" => $c, "d" => $d);
	}
	
	function F($x, $y, $z){ 
		$not_x = bitwise_not($x);
		$f = (($x & $y) | ($not_x & $z));
		return $f;
	}
	
	function G($x, $y, $z){ 
		$not_z = bitwise_not($z);
		$f = (($x & $y) | ($not_z & $y));
		return $f;
	}
	
	function H($x, $y, $z){ 
		$f = $x ^ $y ^ $z;
		return $f;
	}
	
	function I($x, $y, $z){ 
		$not_z = bitwise_not($z);
		$f = $y ^ ($not_z | $x);
		return $f;
	}
	
	function generate_table_T_with_constants(){
		//$T = new Array();
		for($n = 0; $n < 64; $n++){
			$T[$n] = (int)(pow(2, 32) * abs(sin($n)));
		}
		return $T;
	}
	
	function break_extended_binnary_open_message_into_512_bits_blocks($extended_binnary_open_message){
		$j = 0;
		$step = 0;
		while(1){
			if($step === strlen($extended_binnary_open_message))
				break;
			for($i = $step; $i < ($step + 512); $i++){
				$block_512 .= $extended_binnary_open_message[$i];
			}
			$array_of_512_bits_blocks[$j] = $block_512;
			$block_512 = "";
			$j++;
			$step += 512;
		}
		return $array_of_512_bits_blocks;
	}

	
	function main_cycle($array_of_512_bits_blocks, $T){ 
		$MD_buffer = initialization_of_MD_buffer(); 
		
		$a = $MD_buffer["a"];
		$b = $MD_buffer["b"];
		$c = $MD_buffer["c"];
		$d = $MD_buffer["d"];
		
		
		for($i = 0; $i < count($array_of_512_bits_blocks); $i++){
			$X = break_into_32_bits_words($array_of_512_bits_blocks[$i]);
			
			$aa = $a;
			$bb = $b;
			$cc = $c;
			$dd = $d;
			
			$a = round_1($a, $b, $c, $d, $X[0], 7, $T[0]);
			$d = round_1($d, $a, $b, $c, $X[1], 12, $T[1]);
			$c = round_1($c, $d, $a, $b, $X[2], 17, $T[2]);
			$b = round_1($b, $c, $d, $a, $X[3], 22, $T[3]);
			
			$a = round_1($a, $b, $c, $d, $X[4], 7, $T[4]);
			$d = round_1($d, $a, $b, $c, $X[5], 12, $T[5]);
			$c = round_1($c, $d, $a, $b, $X[6], 17, $T[6]);
			$b = round_1($b, $c, $d, $a, $X[7], 22, $T[7]);
			
			$a = round_1($a, $b, $c, $d, $X[8], 7, $T[8]);
			$d = round_1($d, $a, $b, $c, $X[9], 12, $T[9]);
			$c = round_1($c, $d, $a, $b, $X[10], 17, $T[10]);
			$b = round_1($b, $c, $d, $a, $X[11], 22, $T[11]);
			
			$a = round_1($a, $b, $c, $d, $X[12], 7, $T[12]);
			$d = round_1($d, $a, $b, $c, $X[13], 12, $T[13]);
			$c = round_1($c, $d, $a, $b, $X[14], 17, $T[14]);
			$b = round_1($b, $c, $d, $a, $X[15], 22, $T[15]);
			
			
			$a = round_2($a, $b, $c, $d, $X[1], 5, $T[16]);
			$d = round_2($d, $a, $b, $c, $X[6], 9, $T[17]);
			$c = round_2($c, $d, $a, $b, $X[11], 14, $T[18]);
			$b = round_2($b, $c, $d, $a, $X[0], 20, $T[19]);
			
			$a = round_2($a, $b, $c, $d, $X[5], 5, $T[20]);
			$d = round_2($d, $a, $b, $c, $X[10], 9, $T[21]);
			$c = round_2($c, $d, $a, $b, $X[15], 14, $T[22]);
			$b = round_2($b, $c, $d, $a, $X[4], 20, $T[23]);
			
			$a = round_2($a, $b, $c, $d, $X[9], 5, $T[24]);
			$d = round_2($d, $a, $b, $c, $X[14], 9, $T[25]);
			$c = round_2($c, $d, $a, $b, $X[3], 14, $T[26]);
			$b = round_2($b, $c, $d, $a, $X[8], 20, $T[27]);
			
			$a = round_2($a, $b, $c, $d, $X[13], 5, $T[28]);
			$d = round_2($d, $a, $b, $c, $X[2], 9, $T[29]);
			$c = round_2($c, $d, $a, $b, $X[7], 14, $T[30]);
			$b = round_2($b, $c, $d, $a, $X[12], 20, $T[31]);
			
			
			$a = round_3($a, $b, $c, $d, $X[5], 4, $T[32]);
			$d = round_3($d, $a, $b, $c, $X[8], 11, $T[33]);
			$c = round_3($c, $d, $a, $b, $X[11], 16, $T[34]);
			$b = round_3($b, $c, $d, $a, $X[14], 23, $T[35]);
			
			$a = round_3($a, $b, $c, $d, $X[1], 4, $T[36]);
			$d = round_3($d, $a, $b, $c, $X[4], 11, $T[37]);
			$c = round_3($c, $d, $a, $b, $X[7], 16, $T[38]);
			$b = round_3($b, $c, $d, $a, $X[10], 23, $T[39]);
			
			$a = round_3($a, $b, $c, $d, $X[13], 4, $T[40]);
			$d = round_3($d, $a, $b, $c, $X[0], 11, $T[41]);
			$c = round_3($c, $d, $a, $b, $X[3], 16, $T[42]);
			$b = round_3($b, $c, $d, $a, $X[6], 23, $T[43]);
			
			$a = round_3($a, $b, $c, $d, $X[9], 4, $T[44]);
			$d = round_3($d, $a, $b, $c, $X[12], 11, $T[45]);
			$c = round_3($c, $d, $a, $b, $X[15], 16, $T[46]);
			$b = round_3($b, $c, $d, $a, $X[2], 23, $T[47]);
			
			
			$a = round_4($a, $b, $c, $d, $X[0], 6, $T[48]);
			$d = round_4($d, $a, $b, $c, $X[7], 10, $T[49]);
			$c = round_4($c, $d, $a, $b, $X[14], 15, $T[50]);
			$b = round_4($b, $c, $d, $a, $X[5], 21, $T[51]);
			
			$a = round_4($a, $b, $c, $d, $X[12], 6, $T[52]);
			$d = round_4($d, $a, $b, $c, $X[3], 10, $T[53]);
			$c = round_4($c, $d, $a, $b, $X[10], 15, $T[54]);
			$b = round_4($b, $c, $d, $a, $X[1], 21, $T[55]);
			
			$a = round_4($a, $b, $c, $d, $X[8], 6, $T[56]);
			$d = round_4($d, $a, $b, $c, $X[15], 10, $T[57]);
			$c = round_4($c, $d, $a, $b, $X[6], 15, $T[58]);
			$b = round_4($b, $c, $d, $a, $X[13], 21, $T[59]);
			
			$a = round_4($a, $b, $c, $d, $X[4], 6, $T[60]);
			$d = round_4($d, $a, $b, $c, $X[11], 10, $T[61]);
			$c = round_4($c, $d, $a, $b, $X[2], 15, $T[62]);
			$b = round_4($b, $c, $d, $a, $X[9], 21, $T[63]);
			
			
			$a = extend_binnary_record_for_32_bits(decbin(bindec($a) + bindec($aa)));
			$b = extend_binnary_record_for_32_bits(decbin(bindec($b) + bindec($bb)));
			$c = extend_binnary_record_for_32_bits(decbin(bindec($c) + bindec($cc)));
			$d = extend_binnary_record_for_32_bits(decbin(bindec($d) + bindec($dd)));
		}
		
		$MD_5_digest = base_convert($a, 2, 16).base_convert($b, 2, 16).base_convert($c, 2, 16).base_convert($d, 2, 16);
		echo var_dump($MD_5_digest);
	}
	
	function round_1($a, $b, $c, $d, $k, $s, $t){ 
		$a = bindec($b) + (bindec($a) + bindec(F(decbin($b), decbin($c), decbin($d))) + bindec($k) + bindec($t));
		$a = extend_binnary_record_for_32_bits(decbin($a));
		$a = cyclic_shift_to_the_left($a, $s);
		return $a;
	}
	
	function round_2($a, $b, $c, $d, $k, $s, $t){ 
		$a = bindec($b) + (bindec($a) + bindec(G(decbin($b), decbin($c), decbin($d))) + bindec($k) + bindec($t));
		$a = extend_binnary_record_for_32_bits(decbin($a));
		$a = cyclic_shift_to_the_left($a, $s);
		return $a;
	}
	
	function round_3($a, $b, $c, $d, $k, $s, $t){ 
		$a = bindec($b) + (bindec($a) + bindec(H(decbin($b), decbin($c), decbin($d))) + bindec($k) + bindec($t));
		$a = extend_binnary_record_for_32_bits(decbin($a));
		$a = cyclic_shift_to_the_left($a, $s);
		return $a;
	}
	
	function round_4($a, $b, $c, $d, $k, $s, $t){ 
		$a = bindec($b) + (bindec($a) + bindec(I(decbin($b), decbin($c), decbin($d))) + bindec($k) + bindec($t));
		$a = extend_binnary_record_for_32_bits(decbin($a));
		$a = cyclic_shift_to_the_left($a, $s);
		return $a;
	}

	
	function break_into_32_bits_words($block_of_512_bits){
		$j = 0;
		$step = 0;
		while(1){
			if($step === strlen($block_of_512_bits))
				break;
			for($i = $step; $i < ($step + 32); $i++){
				$machine_word .= $block_of_512_bits[$i];
			}
			$array_of_32_bits_words[$j] = $machine_word;
			$machine_word = "";
			$j++;
			$step += 32;
		}
		return $array_of_32_bits_words;
	}
?>