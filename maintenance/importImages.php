<?php

/**
 * Maintenance script to import one or more images from the local file system into
 * the wiki without using the web-based interface.
 *
 * "Smart import" additions:
 * - aim: preserve the essential metadata (user, description) when importing medias from an existing wiki
 * - process:
 *      - interface with the source wiki, don't use bare files only (see --source-wiki-url).
 *      - fetch metadata from source wiki for each file to import.
 *      - commit the fetched metadata to the destination wiki while submitting.
 *
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 * @author Mij <mij@bitchx.it>
 */

$optionsWithArgs = array( 'extensions', 'comment', 'comment-file', 'comment-ext', 'user', 'license', 'sleep', 'limit', 'from', 'source-wiki-url' );
require_once( dirname(__FILE__) . '/commandLine.inc' );
require_once( dirname(__FILE__) . '/importImages.inc' );
$processed = $added = $ignored = $skipped = $overwritten = $failed = 0;

echo( "Import Images\n\n" );

# Need a path
if( count( $args ) > 0 ) {

	$dir = $args[0];

	# Check Protection
	if (isset($options['protect']) && isset($options['unprotect']))
			die("Cannot specify both protect and unprotect.  Only 1 is allowed.\n");

if (isset($options['protect']) && $options['protect'] == 1)
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

	# Get block check. If a value is given, this specified how often the check is performed
	if ( isset( $options['check-userblock'] ) ) {
		if ( !$options['check-userblock'] ) $checkUserBlock = 1;
		else $checkUserBlock = (int)$options['check-userblock']; 
	} else {
		$checkUserBlock = false;
	}

	# Get --from 
	$from = @$options['from'];

	# Get sleep time. 
	$sleep = @$options['sleep'];
	if ( $sleep ) $sleep = (int)$sleep; 

	# Get limit number
	$limit = @$options['limit'];
	if ( $limit ) $limit = (int)$limit; 

	# Get the upload comment
	$comment = NULL;

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
	
			if ( $from ) {
				if ( $from == $title->getDBkey() ) {
					$from = NULL;
				} else {
					$ignored++;
					continue;
				}
			}

			if ( $checkUserBlock && ( ( $processed % $checkUserBlock ) == 0 ) ) {
				$user->clearInstanceCache( 'name' ); //reload from DB!
				if ( $user->isBlocked() ) {
					echo( $user->getName() . " was blocked! Aborting.\n" );
					break;
				}
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
				if ( isset( $options['skip-dupes'] ) ) {
					$repo = $image->getRepo();
					$sha1 = File::sha1Base36( $file ); #XXX: we end up calculating this again when actually uploading. that sucks.

					$dupes = $repo->findBySha1( $sha1 );

					if ( $dupes ) {
						echo( "{$base} already exists as " . $dupes[0]->getName() . ", skipping\n" );
						$skipped++;
						continue;
					}
				}

				echo( "Importing {$base}..." );
				$svar = 'added';
			}

            if (isset( $options['source-wiki-url'])) {
                /* find comment text directly from source wiki, through MW's API */
                $real_comment = getFileCommentFromSourceWiki($options['source-wiki-url'], $base);
                if ($real_comment === false)
                    $commentText = $comment;
                else
                    $commentText = $real_comment;

                /* find user directly from source wiki, through MW's API */
                $real_user = getFileUserFromSourceWiki($options['source-wiki-url'], $base);
                if ($real_user === false) {
                    $wgUser = $user;
                } else {
                    $wgUser = User::newFromName($real_user);
                    if ($wgUser === false) {
                        # user does not exist in target wiki
                        echo ("failed: user '$real_user' does not exist in target wiki.");
                        continue;
                    }
                }
            } else {
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
            }


			# Import the file	
			if ( isset( $options['dry'] ) ) {
				echo( " publishing {$file} by '" . $wgUser->getName() . "', comment '$commentText'... " );
			} else {
				$archive = $image->publish( $file );
				if( WikiError::isError( $archive ) || !$archive->isGood() ) {
					echo( "failed.\n" );
					$failed++;
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
				$svar = 'failed';
			}
			
			$$svar++;
			$processed++;

			if ( $limit && $processed >= $limit )
				break;

			if ( $sleep )
				sleep( $sleep );
		}
		
		# Print out some statistics
		echo( "\n" );
		foreach( array( 'count' => 'Found', 'limit' => 'Limit', 'ignored' => 'Ignored', 
			'added' => 'Added', 'skipped' => 'Skipped', 'overwritten' => 'Overwritten', 
			'failed' => 'Failed' ) as $var => $desc ) {
			if( $$var > 0 )
				echo( "{$desc}: {$$var}\n" );
		}
		
	} else {
		echo( "No suitable files could be found for import.\n" );
	}

} else {
	showUsage();
}

exit(0);

function showUsage( $reason = false ) {
	if( $reason ) {
		echo( $reason . "\n" );
	}

	echo <<<TEXT
Imports images and other media files into the wiki
USAGE: php importImages.php [options] <dir>

<dir> : Path to the directory containing images to be imported

Options:
--extensions=<exts>	Comma-separated list of allowable extensions, defaults to \$wgFileExtensions
--overwrite		Overwrite existing images with the same name (default is to skip them)
--limit=<num>		Limit the number of images to process. Ignored or skipped images are not counted.
--from=<name>		Ignore all files until the one with the given name. Useful for resuming
                        aborted imports. <name> should be the file's canonical database form.
--skip-dupes		Skip images that were already uploaded under a different name (check SHA1)
--sleep=<sec> 		Sleep between files. Useful mostly for debugging.
--user=<username> 	Set username of uploader, default 'Maintenance script'
--check-userblock 	Check if the user got blocked during import.
--comment=<text>  	Set upload summary comment, default 'Importing image file'.
--comment-file=<file>  	Set upload summary comment the the content of <file>.
--comment-ext=<ext>  	Causes the comment for each file to be loaded from a file with the same name
			but the extension <ext>. If a global comment is also given, it is appended.
--license=<code>  	Use an optional license template
--dry			Dry run, don't import anything
--protect=<protect>     Specify the protect value (autoconfirmed,sysop)
--unprotect             Unprotects all uploaded images
--source-wiki-url   if specified, take User and Comment data for each imported file from this URL.
                    For example, --source-wiki-url="http://en.wikipedia.org/"

TEXT;
	exit(1);
}
