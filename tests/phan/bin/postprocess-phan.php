<?php

/**
 * Phan does not natively support per-line @suppress annotations. To
 * rectify that this script post-processes the results and checks if
 * the line prior to an error has a matching @suppress annotation.
 */

$results = file( "php://stdin" );
$errors = [];
foreach ( $results as $error ) {
	if ( !preg_match( '/^(.*):(\d+) (Phan\w+) (.*)$/', $error, $matches ) ) {
		echo "Failed to parse line: $error\n";
		continue;
	}
	list( $source, $file, $lineno, $type, $message ) = $matches;
	$errors[$file][] = [
		'orig' => $error,
		// convert from 1 indexed to 0 indexed
		'lineno' => $lineno - 1,
		'type' => $type,
	];
}

foreach ( $errors as $file => $errors ) {
	$source = file( $file );
	foreach ( $errors as $error ) {
		if ( $error['lineno'] === 0 || !preg_match(
			"|/\*\* @suppress {$error["type"]} |",
			$source[$error['lineno'] - 1]
		) ) {
			echo $error['orig'];
		}
	}
}

