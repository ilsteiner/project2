<?php
	$MAX = array(
		'words' => 10,
		'chars' => 120,
		'special_chars' => 60
		 );

	$MIN = array(
		'words' => 2,
		'chars' => 0,
		'special_chars' => 0
		 );

	$SEPARATOR = '-';

	$CHARS = htmlspecialchars(" !\"#$%&'()*+,-./:;<=>?@[\]^_`{|}~");

	$MAX_TRIES = 20;

	$CASE_TYPES = array(
		'lower',
		'upper',
		'title',
		'random');
?>