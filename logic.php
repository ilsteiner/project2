<?php
/*
	I'd like for this function to take in an enum to limit the 
	possible file names, but for now I just trust the caller.
*/
function get_word($word_type, $used = array()) {
	$words = file($word_type . ".txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$word;
	$tries = 0;
	do{
		$word = array_rand($words, 1);

		foreach ($used as $taken) {
			if($word != $taken) {
				return $word;
			}
		}

		$tries++
	} while $tries <= $GLOBALS["MAX_TRIES"];

	//Couldn't get an unused word, so returning a duplicate
	return array_rand($words, 1)
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

function process_form() {
	$num_words = htmlspecialchars($_GET["num_words"]);
	$max_length = htmlspecialchars($_GET["max_length"]);
	$with_number = (isset($_GET["with_number"]) ? htmlspecialchars($_GET["with_number"]) : "No");
	$special_chars = htmlspecialchars($_GET["special_chars"]);

	$errors = validate_input($num_words, $max_length, $with_number, $special_chars);

	if(count($errors) >= 1) {
		return json_encode($errors);
	}
	else {
		return make_password($num_words, $max_length, $with_number, $special_chars);
	}
}

function validate_input($num_words,$max_length = 0,$with_number,$special_chars) {
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

	if($max_length < $MIN["chars"] || $max_length > $MAX["chars"]) {
		$errors['max_length'] = 
			"Password length should be between " 
			. $MIN["chars"] 
			. " and " 
			. $MAX["chars"];
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

	return $errors;
}

//We pass by reference just to make things easier
function add_numbers(&$password,$count=1){
	/*Add a number to either the 
	  beginning or the end of the password
	*/
	$rand_num = rand(0,9);
	if($rand_num % 2) {
		$password . $rand_num;
	}
	else {
		$rand_num . $password;
	}
}

//We pass by reference just to make things easier
function add_special_chars(&$password,$count=1){
	$chars = $GLOBALS["CHARS"];

	/*Add the specified number of special characters
	  Alternate between the beginning and the end of the password
	*/
	for($i = 0; i<$count; i++){
		if($i % 2){
			$chars[rand(0, strlen($chars)-1)] . $password;
		}
		else{
			$password . $chars[rand(0, strlen($chars)-1)];
		}
	}
}

function make_password($num_words,$max_length = 0,$with_number,$special_chars) {
	$sep = $GLOBALS["SEPARATOR"];

	/*
		Our wordlist is relatively small and our search
		algorithm is VERY naive. As such the "max length"
		parameter is just a "best try" parameter and is
		not strictly adhered to.
	*/

	$MAX_TRIES = 10;
	$tries = 0;

	$az_max = $max_length - $special_chars - ($with_number == 'Yes' ? 1 : 0);

	//If we are making a minimum size password, just use a noun and an adjective
	if($num_words < 3) {
		do {
			$password = get_adjective() . $sep . get_noun();
		} while (strlen($password) > $az_max && $tries <= $MAX_TRIES)
	}

	//If it is larger, then use noun and verb, filled with adjectives as needed
	else {

	}

	if($tries == $MAX_TRIES) {
		echo "Password may be longer than expected."
	}
}

//To make the form processing function run when we submit the form
if(count($_GET) > 0) {
	process_form();
}