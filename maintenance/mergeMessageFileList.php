<?php

require_once( dirname( __FILE__ ).'/Maintenance.php' );
$maintClass = 'MergeMessageFileList';

class MergeMessageFileList extends Maintenance {

	function __construct() {
		$this->addOption( 'list-file', 'A file containing a list of wikis, one per line.', false, true );
		$this->addOption( 'get', 'Get the message files for a single wiki (for internal use).' );
		$this->addOption( 'output', 'Send output to this file (omit for stdout)', false, true );
		$this->mDescription = 'Merge $wgExtensionMessagesFiles from various wikis to produce a ' . 
			'single array containing all message files.';
	}

	public function execute() {
		if ( $this->hasOption( 'get' ) ) {
			var_export( $GLOBALS['wgExtensionMessagesFiles'] );
			return;
		}

		if ( !$this->hasOption( 'list-file' ) ) {
			$this->error( 'The --list-file option must be specified.' );
			return;
		}

		$lines = file( $this->getOption( 'list-file' ) );
		if ( $lines === false ) {
			$this->error( 'Unable to open list file.' );
		}
		$wikis = array_map( 'trim', $lines );

		# Find PHP
		$php = readlink( '/proc/' . posix_getpid() . '/exe' );
		$allFiles = array();

		foreach ( $wikis as $wiki ) {
			$s = wfShellExec( wfEscapeShellArg( $php, __FILE__, '--get',
				'--wiki', $wiki ) );
			if ( !$s ) {
				$this->error( 'Unable to get messages for wiki '. $wiki );
				continue;
			}
			$files = eval( "return $s;" );
			$allFiles += $files;
		}
		$s = '$wgExtensionMessagesFiles = ' . 
			var_export( $allFiles, true ) .
			";\n";
		if ( $this->hasOption( 'output' ) ) {
			file_put_contents( $this->getOption( 'output' ), $s );
		} else {
			echo $s;
		}
	}
}

require_once( DO_MAINTENANCE );

