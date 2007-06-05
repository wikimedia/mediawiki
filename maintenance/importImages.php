<?php

/**
 * Maintenance script to import one or more images from the local file system into
 * the wiki without using the web-based interface
 *
 * @addtogroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

require_once( 'commandLine.inc' );
require_once( 'importImages.inc.php' );
echo( "Import Images\n\n" );

# Need a directory and at least one extension
if( count( $args ) > 1 ) {

	$dir = array_shift( $args );

	# Check the allowed extensions
	while( $ext = array_shift( $args ) ) {
		$exts[] = ltrim( $ext, '.' );
	}

	# Search the directory given and pull out suitable candidates
	$files = findFiles( $dir, $exts );

	# Initialise the user for this operation
	$user = isset( $options['user'] )
		? User::newFromName( $options['user'] )
		: User::newFromName( 'Maintenance script' );
	if( !$user instanceof User )
		$user = User::newFromName( 'Maintenance script' );
	$wgUser = $user;

	# Get the upload comment
	$comment = isset( $options['comment'] )
		? $options['comment']
		: 'Importing image file';

	# Get the license specifier
	$license = isset( $options['license'] ) ? $options['license'] : '';

	# Batch "upload" operation
	global $wgUploadDirectory;
	if( count( $files ) > 0 ) {
		foreach( $files as $file ) {
			$base = wfBaseName( $file );
	
			# Validate a title
			$title = Title::makeTitleSafe( NS_IMAGE, $base );
			if( !is_object( $title ) ) {
				echo( "{$base} could not be imported; a valid title cannot be produced\n" );
				continue;
			}
	
			# Check existence
			$image = wfLocalFile( $title );
			if( $image->exists() ) {
				echo( "{$base} could not be imported; a file with this name exists in the wiki\n" );
				continue;
			}
	
			# Stash the file
			echo( "Saving {$base}..." );
	
			$archive = $image->publish( $file );
			if ( WikiError::isError( $archive ) ) {
				echo( "failed.\n" );
				continue;
			}
			echo( "importing..." );
	
			if ( $image->recordUpload( $archive, $comment, $license ) ) {
				# We're done!
				echo( "done.\n" );
			} else {
				echo( "failed.\n" );
			}
		}
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
USAGE: php importImages.php [options] <dir> <ext1> ...

<dir> : Path to the directory containing images to be imported
<ext1+> File extensions to import

Options:
--user=<username> Set username of uploader, default 'Image import script'
--comment=<text>  Set upload summary comment, default 'Importing image file'
--license=<code>  Use an optional license template

END;
	exit();
}

?>
