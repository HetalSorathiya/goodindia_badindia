<?php
function ReplaceLangSplChar($subject)
{
	$charRepl = array(
	' ' => '-',
	'�' => 'a',
	'�' => 'A',
	'�' => 'a',
	'�' => 'A',
	'�' => 'ae',
	'�' => 'AE',
	'�' => 'a',
	'�' => 'A',
	'�' => 'a',
	'�' => 'A',
	'�' => 'a',
	'�' => 'A',
	'�' => 'ae',
	'�' => 'AE',
	'�' => 'c',
	'�' => 'C',
	'�' => 'e',
	'�' => 'E',
	'�' => 'e',
	'�' => 'E',
	'�' => 'e',
	'�' => 'E',
	'�' => 'e',
	'�' => 'E',
	'�' => 'eth',
	'�' => 'ETH',
	'g' => 'g',
	'G' => 'G',
	'�' => 'i',
	'�' => 'I',
	'�' => 'i',
	'�' => 'I',
	'�' => 'i',
	'�' => 'I',
	'i' => 'i',
	'I' => 'I',
	'�' => 'n',
	'�' => 'o',
	'�' => 'O',
	'�' => 'o',
	'�' => 'O',
	'�' => 'o',
	'�' => 'O',
	'�' => 'o',
	'�' => 'O',
	'�' => 'oe',
	'�' => 'OE',
	'�' => 'oe',
	'�' => 'OE',
	'oe' => 'oe',
	'OE' => 'OE',
	's' => 's',
	'S' => 'S',
	'�' => 'ss',
	'�' => 'th',
	'�' => 'TH',
	'�' => 'u',
	'�' => 'U',
	'�' => 'u',
	'�' => 'U',
	'�' => 'u',
	'�' => 'U',
	'�' => 'ue',
	'�' => 'UE',
	'�' => 'y',
	'�' => 'Y',
	'�' => 'y'
	);
	
	
	$charReplKey = array_keys($charRepl);
	$charReplVal = array_values($charRepl);
	
	
	$subject = str_replace($charReplKey , $charReplVal , $subject);
	
	return  $subject;
}