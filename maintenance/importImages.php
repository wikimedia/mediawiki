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
	while( $ext = array_shift( $args ) )
		$exts[] = ltrim( $ext, '.' );
		
	# Search the directory given and pull out suitable candidates
	$files = findFiles( $dir, $exts );

	# Set up a fake user for this operation
	if( isset( $options['user'] ) ) {
		$wgUser = User::newFromName( $options['user'] );
	} else {
		$wgUser = User::newFromName( 'Image import script' );
	}
	if ( $wgUser->isAnon() ) {
		$wgUser->addToDatabase();
	}
	
	# Get the upload comment
	$comment = isset( $options['comment'] )
		? $options['comment']
		: 'Importing image file';
	
	# Get the license specifier
	$license = isset( $options['license'] ) ? $options['license'] : '';
	
	# Batch "upload" operation
	foreach( $files as $file ) {
	
		$base = wfBaseName( $file );
		
		# Validate a title
		$title = Title::makeTitleSafe( NS_IMAGE, $base );
		if( is_object( $title ) ) {
			
			# Check existence
			$image = new Image( $title );
			if( !$image->exists() ) {
			
				global $wgUploadDirectory;
				
				# copy() doesn't create paths so if the hash path doesn't exist, we
				# have to create it
				makeHashPath( wfGetHashPath( $image->name ) );
				
				# Stash the file
				echo( "Saving {$base}..." );
				
				if( copy( $file, $image->getFullPath() ) ) {
				
					echo( "importing..." );
				
					# Grab the metadata
					$image->loadFromFile();
					
					# Record the upload
					if( $image->recordUpload( '', $comment, $license ) ) {
					
						# We're done!
						echo( "done.\n" );
						
					} else {
						echo( "failed.\n" );
					}
				
				} else {
					echo( "failed.\n" );
				}
			
			} else {
				echo( "{$base} could not be imported; a file with this name exists in the wiki\n" );
			}
		
		} else {
			echo( "{$base} could not be imported; a valid title cannot be produced\n" );
		}
		
	}
	

} else {
	showUsage();
}

exit();

function showUsage( $reason = false ) {
	if( $reason )
		echo( $reason . "\n" );
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
