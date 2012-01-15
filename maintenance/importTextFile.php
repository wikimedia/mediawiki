<?php

/**
 * Maintenance script allows creating or editing pages using
 * the contents of a text file
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

$options = array( 'help', 'nooverwrite', 'norc' );
$optionsWithArgs = array( 'title', 'user', 'comment' );
require_once( dirname( __FILE__ ) . '/commandLine.inc' );
echo( "Import Text File\n\n" );

if ( count( $args ) < 1 || isset( $options['help'] ) ) {
	showHelp();
} else {

	$filename = $args[0];
	echo( "Using {$filename}..." );
	if ( is_file( $filename ) ) {

		$title = isset( $options['title'] ) ? $options['title'] : titleFromFilename( $filename );
		$title = Title::newFromURL( $title );

		if ( is_object( $title ) ) {

			echo( "\nUsing title '" . $title->getPrefixedText() . "'..." );
			if ( !$title->exists() || !isset( $options['nooverwrite'] ) ) {

				$text = file_get_contents( $filename );
				$user = isset( $options['user'] ) ? $options['user'] : 'Maintenance script';
				$user = User::newFromName( $user );

				if ( is_object( $user ) ) {

					echo( "\nUsing username '" . $user->getName() . "'..." );
					$wgUser =& $user;
					$comment = isset( $options['comment'] ) ? $options['comment'] : 'Importing text file';
					$flags = 0 | ( isset( $options['norc'] ) ? EDIT_SUPPRESS_RC : 0 );

					echo( "\nPerforming edit..." );
					$page = WikiPage::factory( $title );
					$page->doEdit( $text, $comment, $flags, false, $user );
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
