<?php

/**
 * @param string[] $source
 * @param string $type
 * @param int $lineno
 * @return bool
 */
function is_suppressed( array $source, $type, $lineno ) {
	return $lineno > 0 && preg_match(
		"|/\*\* @suppress {$type} |",
		$source[$lineno - 1]
	);
}

/**
 * @param string $input
 * @return bool do errors remain
 */
function suppress_text( $input ) {
	$hasErrors = false;
	$errors = [];
	foreach ( explode( "\n", $input ) as $error ) {
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
	foreach ( $errors  as $file => $fileErrors ) {
		$source = file( $file );
		foreach ( $fileErrors as $error ) {
			if ( !is_suppressed( $source, $error['type'], $error['lineno'] ) ) {
				echo $error['orig'], "\n";
				$hasErrors = true;
			}
		}
	}

	return $hasErrors;
}

/**
 * @param string $input
 * @return bool True do errors remain
 */
function suppress_codeclimate( $input ) {
	$dom = new DOMDocument();
	$dom->loadXML( $input );
	$hasErrors = false;
	// DOMNodeList's are "live", convert to an array so it works as expected
	$files = [];
	foreach ( $dom->getElementsByTagName( 'file' ) as $file ) {
		$files[] = $file;
	}
	foreach ( $files as $file ) {
		$errors = [];
		foreach ( $file->getElementsByTagName( 'error' ) as $error ) {
			$errors[] = $error;
		}
		$source = file( $file->getAttribute( 'name' ) );
		$fileHasErrors = false;
		foreach ( $errors as $error ) {
			$lineno = $error->getAttribute( 'line' ) - 1;
			$type = $error->getAttribute( 'source' );
			if ( is_suppressed( $source, $type, $lineno ) ) {
				$error->parentNode->removeChild( $error );
			} else {
				$fileHasErrors = true;
				$hasErrors = true;
			}
		}
		if ( !$fileHasErrors ) {
			$file->parentNode->removeChild( $file );
		}
	}
	echo $dom->saveXML();

	return $hasErrors;
}

$opt = getopt("m:", ["output-mode:"]);
// if provided multiple times getopt returns an array
if ( isset( $opt['m'] ) ) {
	$mode = $opt['m'];
} elseif ( isset( $mode['output-mode'] ) ) {
	$mode = $opt['output-mode'];
} else {
	$mode = 'text';
}
if ( is_array( $mode ) ) {
	// If an option is passed multiple times getopt returns it as the last
	$mode = end( $mode );
}

$input = file_get_contents( 'php://stdin' );
switch( $mode ) {
case 'text':
	$hasErrors = suppress_text( $input );
	break;
case 'codeclimate':
	$hasErrors = suppress_codeclimate( $input );
	break;
default:
	echo "Unsupported output mode: $mode\n$input";
	$hasErrors = true;
}

if ( $hasErrors ) {
	exit( 1 );
}
