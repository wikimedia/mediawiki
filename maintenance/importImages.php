<?php

/**
 * Maintenance script to import one or more images from the local file system into
 * the wiki without using the web-based interface
 *
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

$optionsWithArgs = array( 'extensions', 'comment', 'comment-file', 'comment-ext', 'user', 'license' );
require_once( 'commandLine.inc' );
require_once( 'importImages.inc.php' );
$added = $skipped = $overwritten = 0;

echo( "Import Images\n\n" );

# Need a path
if( count( $args ) > 0 ) {

	$dir = $args[0];

	# Check Protection
	if (isset($options['protect']) && isset($options['unprotect']))
			die("Cannot specify both protect and unprotect.  Only 1 is allowed.\n");

	if ($options['protect'] == 1)
			die("You must specify a protection option.\n");

	# Prepare the list of allowed extensions
	global $wgFileExtensions;
	$extensions = isset( $options['extensions'] )
		? explode( ',', strtolower( $options['extensions'] ) )
		: $wgFileExtensions;

	# Search the path provided for candidates for import
	$files = findFiles( $dir, $extensions );

	# Initialise the user for this operation
	$user = isset( $options['user'] )
		? User::newFromName( $options['user'] )
		: User::newFromName( 'Maintenance script' );
	if( !$user instanceof User )
		$user = User::newFromName( 'Maintenance script' );
	$wgUser = $user;

	# Get the upload comment
	$comment = 'Importing image file';

	if ( isset( $options['comment-file'] ) ) {
		$comment =  file_get_contents( $options['comment-file'] );
		if ( $comment === false || $comment === NULL ) {
			die( "failed to read comment file: {$options['comment-file']}\n" );
		}
	}
	else if ( isset( $options['comment'] ) ) {
		$comment =  $options['comment'];
	}

	$commentExt = isset( $options['comment-ext'] ) ? $options['comment-ext'] : false;

	# Get the license specifier
	$license = isset( $options['license'] ) ? $options['license'] : '';

	# Batch "upload" operation
	if( ( $count = count( $files ) ) > 0 ) {
	
		foreach( $files as $file ) {
			$base = wfBaseName( $file );
	
			# Validate a title
			$title = Title::makeTitleSafe( NS_FILE, $base );
			if( !is_object( $title ) ) {
				echo( "{$base} could not be imported; a valid title cannot be produced\n" );
				continue;
			}
	
			# Check existence
			$image = wfLocalFile( $title );
			if( $image->exists() ) {
				if( isset( $options['overwrite'] ) ) {
					echo( "{$base} exists, overwriting..." );
					$svar = 'overwritten';
				} else {
					echo( "{$base} exists, skipping\n" );
					$skipped++;
					continue;
				}
			} else {
				echo( "Importing {$base}..." );
				$svar = 'added';
			}

			# Find comment text
			$commentText = false;

			if ( $commentExt ) {
				$f = findAuxFile( $file, $commentExt );
				if ( !$f ) {
					echo( " No comment file with extension {$commentExt} found for {$file}, using default comment. " );
				} else {
					$commentText = file_get_contents( $f );
					if ( !$f ) {
						echo( " Failed to load comment file {$f}, using default comment. " );
					}
				}
			}

			if ( !$commentText ) {
				$commentText = $comment;
			}

			# Import the file	
			if ( isset( $options['dry'] ) ) {
				echo( " publishing {$file}... " );
			} else {
				$archive = $image->publish( $file );
				if( WikiError::isError( $archive ) || !$archive->isGood() ) {
					echo( "failed.\n" );
					continue;
				}
			}
			
			$doProtect = false;
			$restrictions = array();
			
			global $wgRestrictionLevels;
			
			$protectLevel = isset($options['protect']) ? $options['protect'] : null;
			
			if ( $protectLevel && in_array( $protectLevel, $wgRestrictionLevels ) ) {
					$restrictions['move'] = $protectLevel;
					$restrictions['edit'] = $protectLevel;
					$doProtect = true;
			}
			if (isset($options['unprotect'])) {
					$restrictions['move'] = '';
					$restrictions['edit'] = '';
					$doProtect = true;
			}


			$$svar++;
			if ( isset( $options['dry'] ) ) {
				echo( "done.\n" );
			} else if ( $image->recordUpload( $archive->value, $commentText, $license ) ) {
				# We're done!
				echo( "done.\n" );
				if ($doProtect) {
						# Protect the file
						$article = new Article( $title );
						echo "\nWaiting for slaves...\n";
						// Wait for slaves.
						sleep(2.0);
						wfWaitForSlaves( 1.0 );
						
						echo( "\nSetting image restrictions ... " );
						if ( $article->updateRestrictions($restrictions) )
								echo( "done.\n" );
						else
								echo( "failed.\n" );
				}

			} else {
				echo( "failed.\n" );
			}
			
		}
		
		# Print out some statistics
		echo( "\n" );
		foreach( array( 'count' => 'Found', 'added' => 'Added',
			'skipped' => 'Skipped', 'overwritten' => 'Overwritten' ) as $var => $desc ) {
			if( $$var > 0 )
				echo( "{$desc}: {$$var}\n" );
		}
		
	} else {
		echo( "No suitable files could be found for import.\n" );
	}

} else {
	showUsage();
}

exit();

function showUsage( $reason = false ) {
	if( $reason ) {
		echo( $reason . "\n" );
	}

	echo <<<END
Imports images and other media files into the wiki
USAGE: php importImages.php [options] <dir>

<dir> : Path to the directory containing images to be imported

Options:
--extensions=<exts>	Comma-separated list of allowable extensions, defaults to \$wgFileExtensions
--overwrite		Overwrite existing images if a conflicting-named image is found
--user=<username> 	Set username of uploader, default 'Maintenance script'
--comment=<text>  	Set upload summary comment, default 'Importing image file'
--comment-file=<file>  	Set upload summary comment the the content of <file>.
--comment-ext=<ext>  	Causes the comment for each file to be loaded from a file with the same name
			but the extension <ext>.
--license=<code>  	Use an optional license template
--dry			Dry run, don't import anything
--protect=<protect>     Specify the protect value (autoconfirmed,sysop)
--unprotect             Unprotects all uploaded images

END;
	exit();
}