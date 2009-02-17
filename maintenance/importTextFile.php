<?php

/**
 * Maintenance script allows creating or editing pages using
 * the contents of a text file
 *
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

$options = array( 'help', 'nooverwrite', 'norc' );
$optionsWithArgs = array( 'title', 'user', 'comment' );
require_once( 'commandLine.inc' );
echo( "Import Text File\n\n" );

if( count( $args ) < 1 || isset( $options['help'] ) ) {
	showHelp();
} else {

	$filename = $args[0];
	echo( "Using {$filename}..." );
	if( is_file( $filename ) ) {

		$title = isset( $options['title'] ) ? $options['title'] : titleFromFilename( $filename );
		$title = Title::newFromUrl( $title );

		if( is_object( $title ) ) {

			echo( "\nUsing title '" . $title->getPrefixedText() . "'..." );
			if( !$title->exists() || !isset( $options['nooverwrite'] ) ) {

				$text = file_get_contents( $filename );
				$user = isset( $options['user'] ) ? $options['user'] : 'Maintenance script';
				$user = User::newFromName( $user );

				if( is_object( $user ) ) {

					echo( "\nUsing username '" . $user->getName() . "'..." );
					$wgUser =& $user;
					$comment = isset( $options['comment'] ) ? $options['comment'] : 'Importing text file';
					$flags = 0 | ( isset( $options['norc'] ) ? EDIT_SUPPRESS_RC : 0 );

					echo( "\nPerforming edit..." );
					$article = new Article( $title );
					$article->doEdit( $text, $comment, $flags );
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
print <<<EOF
USAGE: php importTextFile.php <options> <filename>

<filename> : Path to the file containing page content to import

Options:

--title <title>
	Title for the new page; default is to use the filename as a base
--user <user>
	User to be associated with the edit
--comment <comment>
	Edit summary
--nooverwrite
	Don't overwrite existing content
--norc
	Don't update recent changes
--help
	Show this information

EOF;
}
