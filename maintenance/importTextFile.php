<?php

/**
 * Maintenance script to insert an article, importing text from a file
 *
 * @package MediaWiki
 * @subpackage Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

$options = array( 'help', 'norc' ); 
$optionsWithArgs = array( 'title', 'user', 'comment' );
require_once( 'commandLine.inc' );
require_once( 'importTextFile.inc' );
echo( "Import Text File\n\n" );

if( !isset( $options['help'] ) || !$options['help'] ) {

	# Check file existence
	$filename = $args[0];
	echo( "Using file '{$filename}'..." );
	if( file_exists( $filename ) ) {
		echo( "found.\n" );
	
		# Work out the title for the page	
		if( isset( $option['title'] ) || trim( $options['title'] ) != '' ) {
			$titleText = $options['title'];
			# Use the supplied title
			echo( "Using title '{$titleText}'..." );
			$title = Title::newFromText( $options['title'] );
		} else {
			# Attempt to make a title out of the filename
			echo( "Using title from filename..." );
			$title = titleFromFilename( $filename );
		}
		
		# Check the title's valid
		if( !is_null( $title ) && is_object( $title ) ) {
			echo( "ok.\n" );
		
			# Read in the text
			$text = file_get_contents( $filename );
			
			# Check the supplied user and fall back to a default if needed
			if( isset( $options['user'] ) && trim( $options['user'] ) != '' ) {
				$username = $options['user'];
			} else {
				$username = 'MediaWiki default';
			}
			echo( "Using user '{$username}'..." );
			$user = User::newFromName( $username );
			
			# Check the user's valid
			if( !is_null( $user ) && is_object( $user ) ) {
				echo( "ok.\n" );
				$wgUser =& $user;
			
				# If a comment was supplied, use it (replace _ with spaces ) else use a default
				if( isset( $options['comment'] ) || trim( $options['comment'] != '' ) ) {
					$comment = str_replace( '_', ' ', $options['comment'] );
				} else {
					$comment = 'Importing text file';
				}
				echo( "Using edit summary '{$comment}'.\n" );
			
				# Do we need to update recent changes?
				if( isset( $options['norc'] ) && $options['norc'] ) {
					$rc = false;
				} else {
					$rc = true;
				}
			
				# Attempt the insertion
				echo( "Attempting to insert page..." );
				$success = insertNewArticle( $title, $text, $user, $comment, $rc );
				if( $success ) {
					echo( "done.\n" );
				} else {
					echo( "failed. Title exists.\n" );
				}
			
			} else {
				# Dud user
				echo( "invalid username.\n" );
			}
			
		} else {
			# Dud title
			echo( "invalid title.\n" );
		}
		
	} else {
		# File not found
		echo( "not found.\n" );
	}

} else {
	# Show help
	echo( "Imports the contents of a text file into a wiki page.\n\n" );
	echo( "USAGE: php importTextFile.php [--help|--title <title>|--user <user>|--comment <comment>|--norc] <filename>\n\n" );
	echo( "              --help: Show this help information\n" );
	echo( "    --title <title> : Title for the new page; if not supplied, the filename is used as a base for the title\n" );
	echo( "      --user <user> : User to be associated with the edit; if not supplied, a default is used\n" );
	echo( "--comment <comment> : Edit summary to be associated with the edit; underscores are transformed into spaces; if not supplied, a default is used\n" );
	echo( "         <filename> : Path to the file containing the wikitext to import\n" );
	echo( "             --norc : Do not add a page creation event to recent changes\n" );

}
echo( "\n" );	

?>