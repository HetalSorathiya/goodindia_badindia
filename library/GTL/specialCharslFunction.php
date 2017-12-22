<?php
function ReplaceLangSplChar($subject)
{
	$charRepl = array(
	' ' => '-',
	'à' => 'a',
	'À' => 'A',
	'â' => 'a',
	'Â' => 'A',
	'ä' => 'ae',
	'Ä' => 'AE',
	'á' => 'a',
	'Á' => 'A',
	'ã' => 'a',
	'Ã' => 'A',
	'å' => 'a',
	'Å' => 'A',
	'æ' => 'ae',
	'Æ' => 'AE',
	'ç' => 'c',
	'Ç' => 'C',
	'é' => 'e',
	'É' => 'E',
	'è' => 'e',
	'È' => 'E',
	'ê' => 'e',
	'Ê' => 'E',
	'ë' => 'e',
	'Ë' => 'E',
	'ð' => 'eth',
	'Ð' => 'ETH',
	'g' => 'g',
	'G' => 'G',
	'ì' => 'i',
	'Ì' => 'I',
	'î' => 'i',
	'Î' => 'I',
	'ï' => 'i',
	'Ï' => 'I',
	'i' => 'i',
	'I' => 'I',
	'ñ' => 'n',
	'ò' => 'o',
	'Ò' => 'O',
	'ô' => 'o',
	'Ô' => 'O',
	'ó' => 'o',
	'Ó' => 'O',
	'õ' => 'o',
	'Õ' => 'O',
	'ø' => 'oe',
	'Ø' => 'OE',
	'ö' => 'oe',
	'Ö' => 'OE',
	'oe' => 'oe',
	'OE' => 'OE',
	's' => 's',
	'S' => 'S',
	'ß' => 'ss',
	'Þ' => 'th',
	'þ' => 'TH',
	'ù' => 'u',
	'Ù' => 'U',
	'û' => 'u',
	'Û' => 'U',
	'ú' => 'u',
	'Ú' => 'U',
	'ü' => 'ue',
	'Ü' => 'UE',
	'ý' => 'y',
	'Ý' => 'Y',
	'ÿ' => 'y'
	);
	
	
	$charReplKey = array_keys($charRepl);
	$charReplVal = array_values($charRepl);
	
	
	$subject = str_replace($charReplKey , $charReplVal , $subject);
	
	return  $subject;
}