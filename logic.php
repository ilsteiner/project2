<?php
/*
	I'd like for this function to take in an enum to limit the 
	possible file names, but for now I just trust the caller.
*/
function get_word($word_type, $used = array()) {
	$words = file($word_type . "s.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$word;
	$tries = 0;
	do {
		$word = $words[array_rand($words, 1)];

		foreach ($used as $taken) {
			if($word != $taken) {
				return $word;
			}
		}

		$tries++;
	} while ($tries <= $GLOBALS["MAX_TRIES"]);

	//Couldn't get an unused word, so returning a duplicate
	return $words[array_rand($words, 1)];
}

// Wrapper functions for get_word(), just to make things easier
function get_noun($used = array()) {
	return get_word("noun", $used);
}

function get_verb($used = array()) {
	return get_word("verb", $used);
}

function get_adjective($used = array()) {
	return get_word("adjective", $used);
}

function random_case($str) {
	$char_array = str_split(strtolower($str));

	foreach ($char_array as &$char) {
		if(rand(0,1) % 2) {
			$char = strtoupper($char);
		}
	}

	return implode($char_array);
}

function fix_case($str,$case) {
	switch ($case) {
		case 'lower':
			return strtolower($str);
		case 'upper':
			return strtoupper($str);
		case 'title':
			return ucwords($str,"-");
		case 'random':
			return random_case($str);
		default:
			return $str;
	}
}

function process_form() {
	$num_words = htmlspecialchars($_GET["num_words"]);
	$with_number = (isset($_GET["with_number"]) ? htmlspecialchars($_GET["with_number"]) : "No");
	$special_chars = htmlspecialchars($_GET["special_chars"]);
	$case_type = htmlspecialchars($_GET["case_type"]);

	$errors = validate_input($num_words, $with_number, $special_chars, $case_type);

	if(count($errors) >= 1) {
		echo '<script>var errors = ' . json_encode($errors) . '</script>';
		return json_encode($errors);
	}
	else {
		return make_password($num_words, $with_number, $special_chars, $case_type);
	}
}

function validate_input($num_words,$with_number,$special_chars,$case_type) {
	$errors = array();

	$MIN = $GLOBALS['MIN'];
	$MAX = $GLOBALS['MAX'];

	if($num_words < $MIN["words"] || $num_words > $MAX["words"]) {
		$errors['num_words'] = 
			"Number of words should be between " 
			. $MIN["words"] 
			. " and " 
			. $MAX["words"];
	}

	if($special_chars < $MIN["special_chars"] || $special_chars > $MAX["special_chars"]) {
		$errors['special_chars'] = 
			"Number of special characters should be between " 
			. $MIN["special_chars"] 
			. " and " 
			. $MAX["special_chars"];
	}

	if($with_number != "Yes" && $with_number != "No") {
		$errors['with_number'] = "With number checkbox must be either 'Yes' or 'No'.";
	}

	if(!in_array($case_type, $GLOBALS['CASE_TYPES'])){
		$errors['case_type'] = "Not a valid case type.";
	}

	return $errors;
}

function add_numbers($password,$count=1){
	/*Add a number to either the 
	  beginning or the end of the password
	*/
	$rand_num = rand(0,9);
	if($rand_num % 2) {
		$password = $password . $GLOBALS['SEPARATOR'] . $rand_num;
	}
	else {
		$password = $rand_num . $GLOBALS['SEPARATOR'] . $password;
	}

	return $password;
}

function add_special_chars($password,$count=1) {
	$chars = $GLOBALS["CHARS"];

	/*Add the specified number of special characters
	  Alternate between the beginning and the end of the password
	*/
	for($i = 0; $i<$count; $i++){
		if($i % 2){
			$password = $chars[rand(0, strlen($chars)-1)] . $password;
		}
		else{
			$password = $password . $chars[rand(0, strlen($chars)-1)];
		}
	}

	return $password;
}

function make_password($num_words,$with_number,$special_chars,$case_type) {
	$sep = $GLOBALS["SEPARATOR"];

	$password = '';

	//If we are making a minimum size password, just use a noun and an adjective
	if($num_words < 3) {
		$password = get_adjective() . $sep . get_noun();
	}

	//If it is larger, then use noun and verb, filled with adjectives as needed
	else {
		$password = get_noun() . $sep . get_verb();
		$used_adjectives = array();

		//Add the adjectives
		for($i=0;$i<$num_words-2;$i++){
			$tries = 0;
			$adjective = get_adjective($used_adjectives);
			$password = $adjective . $sep . $password;
			array_push($used_adjectives, $adjective);
		}
	}

	$password = fix_case($password,$case_type);

	if($special_chars > 0) {
		$password = add_special_chars($password,$special_chars);
	}

	if($with_number == "Yes") {
		$password = add_numbers($password);
	}

	echo $password;
}

//To make the form processing function run when we submit the form
if(count($_GET) > 0) {
	process_form();
}
//Make a default password when the form isn't filled
else {
	make_password(4,"No",0,"lower");
}