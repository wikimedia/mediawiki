<?php

/**
 * Maintenance script allows creating or editing pages using
 * the contents of a text file
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */
 
$options = array( 'help', 'nooverwrite' ); 
$optionsWithArgs = array( 'title', 'user', 'comment' );
require_once( 'commandLine.inc' );
echo( "Import Text File\n\n" );

if( isset( $options['help'] ) ) {
	showHelp();
} else {

	$filename = $args[0];
	echo( "Using {$filename}..." );
	if( is_file( $filename ) ) {
		
		$title = isset( $options['title'] ) ? $options['title'] : titleFromFilename( $filename );
		$title = Title::newFromUrl( $title );
		echo( "\nUsing title '" . $title->getPrefixedText() . "'..." );
		
		if( is_object( $title ) ) {
			
			if( !$title->exists() || !isset( $options['nooverwrite'] ) ) {
			
				$text = file_get_contents( $filename );
				$user = isset( $options['user'] ) ? $options['user'] : 'MediaWiki default';
				$user = User::newFromName( $user );
				echo( "\nUsing username '" . $user->getName() . "'..." );
				
				if( is_object( $user ) ) {
				
					$wgUser =& $user;
					$comment = isset( $options['comment'] ) ? $options['comment'] : 'Importing text file';
					$comment = str_replace( '_', ' ', $comment );
					
					echo( "\nPerforming edit..." );
					$article = new Article( $title );
					$article->doEdit( $text, $comment );
					echo( "done.\n" );
				
				} else {
					echo( "invalid username.\n" );
				}
			
			} else {
				echo( "page exists.\n" );
			}
			
		} else {
			echo( "invalid title.\n" );
		}
		
	} else {
		echo( "does not exist.\n" );
	}

}

function titleFromFilename( $filename ) {
	$parts = explode( '/', $filename );
	$parts = explode( '.', $parts[ count( $parts ) - 1 ] );
	return $parts[0];
}

function showHelp() {
	echo( "Import the contents of a text file into a wiki page.\n\n" );
	echo( "USAGE: php importTextFile.php [--help|--title <title>|--user <user>|--comment <comment>|--nooverwrite] <filename>\n\n" );
	echo( "              --help: Show this help information\n" );
	echo( "    --title <title> : Title for the new page; if not supplied, the filename is used as a base for the title\n" );
	echo( "      --user <user> : User to be associated with the edit; if not supplied, a default is used\n" );
	echo( "--comment <comment> : Edit summary to be associated with the edit; underscores are transformed into spaces; if not supplied, a default is used\n" );
	echo( "      --nooverwrite : Don't overwrite existing page content\n" );
	echo( "         <filename> : Path to the file containing the wikitext to import\n\n" );
}

?>