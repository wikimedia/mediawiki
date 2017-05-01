<?php

abstract class Suppressor {
	/**
	 * @param string $input
	 * @return bool do errors remain
	 */
	abstract public function suppress( $input );

	/**
	 * @param string[] $source
	 * @param string $type
	 * @param int $lineno
	 * @return bool
	 */
	protected function isSuppressed( array $source, $type, $lineno ) {
		return $lineno > 0 && preg_match(
			"|/\*\* @suppress {$type} |",
			$source[$lineno - 1]
		);
	}
}

class TextSuppressor extends Suppressor {
	/**
	 * @param string $input
	 * @return bool do errors remain
	 */
	public function suppress( $input ) {
		$hasErrors = false;
		$errors = [];
		foreach ( explode( "\n", $input ) as $error ) {
			if ( empty( $error ) ) {
				continue;
			}
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
				if ( !$this->isSuppressed( $source, $error['type'], $error['lineno'] ) ) {
					echo $error['orig'], "\n";
					$hasErrors = true;
				}
			}
		}

		return $hasErrors;
	}
}

class CheckStyleSuppressor extends Suppressor {
	/**
	 * @param string $input
	 * @return bool True do errors remain
	 */
	public function suppress( $input ) {
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
				if ( $this->isSuppressed( $source, $type, $lineno ) ) {
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
}

class NoopSuppressor extends Suppressor {
	private $mode;

	public function __construct( $mode ) {
		$this->mode = $mode;
	}
	public function suppress( $input ) {
		echo "Unsupported output mode: {$this->mode}\n$input";
		return true;
	}
}

$opt = getopt( "m:", [ "output-mode:" ] );
// if provided multiple times getopt returns an array
if ( isset( $opt['m'] ) ) {
	$mode = $opt['m'];
} elseif ( isset( $mode['output-mode'] ) ) {
	$mode = $opt['output-mode'];
} else {
	$mode = 'text';
}
if ( is_array( $mode ) ) {
	// If an option is passed multiple times getopt returns an
	// array. Just take the last one.
	$mode = end( $mode );
}

switch ( $mode ) {
case 'text':
	$suppressor = new TextSuppressor();
	break;
case 'checkstyle':
	$suppressor = new CheckStyleSuppressor();
	break;
default:
	$suppressor = new NoopSuppressor( $mode );
}

$input = file_get_contents( 'php://stdin' );
$hasErrors = $suppressor->suppress( $input );

if ( $hasErrors ) {
	exit( 1 );
}
