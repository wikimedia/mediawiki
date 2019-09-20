<?php
/**
 * Import one or more images from the local file system into the wiki without
 * using the web-based interface.
 *
 * "Smart import" additions:
 * - aim: preserve the essential metadata (user, description) when importing media
 *   files from an existing wiki.
 * - process:
 *      - interface with the source wiki, don't use bare files only (see --source-wiki-url).
 *      - fetch metadata from source wiki for each file to import.
 *      - commit the fetched metadata to the destination wiki while submitting.
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
 * @author Mij <mij@bitchx.it>
 */

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;

class ImportImages extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Imports images and other media files into the wiki' );
		$this->addArg( 'dir', 'Path to the directory containing images to be imported' );

		$this->addOption( 'extensions',
			'Comma-separated list of allowable extensions, defaults to $wgFileExtensions',
			false,
			true
		);
		$this->addOption( 'overwrite',
			'Overwrite existing images with the same name (default is to skip them)' );
		$this->addOption( 'limit',
			'Limit the number of images to process. Ignored or skipped images are not counted',
			false,
			true
		);
		$this->addOption( 'from',
			"Ignore all files until the one with the given name. Useful for resuming aborted "
				. "imports. The name should be the file's canonical database form.",
			false,
			true
		);
		$this->addOption( 'skip-dupes',
			'Skip images that were already uploaded under a different name (check SHA1)' );
		$this->addOption( 'search-recursively', 'Search recursively for files in subdirectories' );
		$this->addOption( 'sleep',
			'Sleep between files. Useful mostly for debugging',
			false,
			true
		);
		$this->addOption( 'user',
			"Set username of uploader, default 'Maintenance script'",
			false,
			true
		);
		// This parameter can optionally have an argument. If none specified, getOption()
		// returns 1 which is precisely what we need.
		$this->addOption( 'check-userblock', 'Check if the user got blocked during import' );
		$this->addOption( 'comment',
			"Set file description, default 'Importing file'",
			false,
			true
		);
		$this->addOption( 'comment-file',
			'Set description to the content of this file',
			false,
			true
		);
		$this->addOption( 'comment-ext',
			'Causes the description for each file to be loaded from a file with the same name, but '
				. 'the extension provided. If a global description is also given, it is appended.',
			false,
			true
		);
		$this->addOption( 'summary',
			'Upload summary, description will be used if not provided',
			false,
			true
		);
		$this->addOption( 'license',
			'Use an optional license template',
			false,
			true
		);
		$this->addOption( 'timestamp',
			'Override upload time/date, all MediaWiki timestamp formats are accepted',
			false,
			true
		);
		$this->addOption( 'protect',
			'Specify the protect value (autoconfirmed,sysop)',
			false,
			true
		);
		$this->addOption( 'unprotect', 'Unprotects all uploaded images' );
		$this->addOption( 'source-wiki-url',
			'If specified, take User and Comment data for each imported file from this URL. '
				. 'For example, --source-wiki-url="http://en.wikipedia.org/',
			false,
			true
		);
		$this->addOption( 'dry', "Dry run, don't import anything" );
	}

	public function execute() {
		global $wgFileExtensions, $wgUser, $wgRestrictionLevels;

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$processed = $added = $ignored = $skipped = $overwritten = $failed = 0;

		$this->output( "Importing Files\n\n" );

		$dir = $this->getArg( 0 );

		# Check Protection
		if ( $this->hasOption( 'protect' ) && $this->hasOption( 'unprotect' ) ) {
			$this->fatalError( "Cannot specify both protect and unprotect.  Only 1 is allowed.\n" );
		}

		if ( $this->hasOption( 'protect' ) && trim( $this->getOption( 'protect' ) ) ) {
			$this->fatalError( "You must specify a protection option.\n" );
		}

		# Prepare the list of allowed extensions
		$extensions = $this->hasOption( 'extensions' )
			? explode( ',', strtolower( $this->getOption( 'extensions' ) ) )
			: $wgFileExtensions;

		# Search the path provided for candidates for import
		$files = $this->findFiles( $dir, $extensions, $this->hasOption( 'search-recursively' ) );

		# Initialise the user for this operation
		$user = $this->hasOption( 'user' )
			? User::newFromName( $this->getOption( 'user' ) )
			: User::newSystemUser( 'Maintenance script', [ 'steal' => true ] );
		if ( !$user instanceof User ) {
			$user = User::newSystemUser( 'Maintenance script', [ 'steal' => true ] );
		}
		$wgUser = $user;

		# Get block check. If a value is given, this specified how often the check is performed
		$checkUserBlock = (int)$this->getOption( 'check-userblock' );

		$from = $this->getOption( 'from' );
		$sleep = (int)$this->getOption( 'sleep' );
		$limit = (int)$this->getOption( 'limit' );
		$timestamp = $this->getOption( 'timestamp', false );

		# Get the upload comment. Provide a default one in case there's no comment given.
		$commentFile = $this->getOption( 'comment-file' );
		if ( $commentFile !== null ) {
			$comment = file_get_contents( $commentFile );
			if ( $comment === false || $comment === null ) {
				$this->fatalError( "failed to read comment file: {$commentFile}\n" );
			}
		} else {
			$comment = $this->getOption( 'comment', 'Importing file' );
		}
		$commentExt = $this->getOption( 'comment-ext' );
		$summary = $this->getOption( 'summary', '' );

		$license = $this->getOption( 'license', '' );

		$sourceWikiUrl = $this->getOption( 'source-wiki-url' );

		# Batch "upload" operation
		$count = count( $files );
		if ( $count > 0 ) {
			foreach ( $files as $file ) {
				if ( $sleep && ( $processed > 0 ) ) {
					sleep( $sleep );
				}

				$base = UtfNormal\Validator::cleanUp( wfBaseName( $file ) );

				# Validate a title
				$title = Title::makeTitleSafe( NS_FILE, $base );
				if ( !is_object( $title ) ) {
					$this->output(
						"{$base} could not be imported; a valid title cannot be produced\n"
					);
					continue;
				}

				if ( $from ) {
					if ( $from == $title->getDBkey() ) {
						$from = null;
					} else {
						$ignored++;
						continue;
					}
				}

				if ( $checkUserBlock && ( ( $processed % $checkUserBlock ) == 0 ) ) {
					$user->clearInstanceCache( 'name' ); // reload from DB!
					if ( $permissionManager->isBlockedFrom( $user, $title ) ) {
						$this->output(
							"{$user->getName()} is blocked from {$title->getPrefixedText()}! skipping.\n"
						);
						$skipped++;
						continue;
					}
				}

				# Check existence
				$image = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
					->newFile( $title );
				if ( $image->exists() ) {
					if ( $this->hasOption( 'overwrite' ) ) {
						$this->output( "{$base} exists, overwriting..." );
						$svar = 'overwritten';
					} else {
						$this->output( "{$base} exists, skipping\n" );
						$skipped++;
						continue;
					}
				} else {
					if ( $this->hasOption( 'skip-dupes' ) ) {
						$repo = $image->getRepo();
						# XXX: we end up calculating this again when actually uploading. that sucks.
						$sha1 = FSFile::getSha1Base36FromPath( $file );

						$dupes = $repo->findBySha1( $sha1 );

						if ( $dupes ) {
							$this->output(
								"{$base} already exists as {$dupes[0]->getName()}, skipping\n"
							);
							$skipped++;
							continue;
						}
					}

					$this->output( "Importing {$base}..." );
					$svar = 'added';
				}

				if ( $sourceWikiUrl ) {
					/* find comment text directly from source wiki, through MW's API */
					$real_comment = $this->getFileCommentFromSourceWiki( $sourceWikiUrl, $base );
					if ( $real_comment === false ) {
						$commentText = $comment;
					} else {
						$commentText = $real_comment;
					}

					/* find user directly from source wiki, through MW's API */
					$real_user = $this->getFileUserFromSourceWiki( $sourceWikiUrl, $base );
					if ( $real_user === false ) {
						$wgUser = $user;
					} else {
						$wgUser = User::newFromName( $real_user );
						if ( $wgUser === false ) {
							# user does not exist in target wiki
							$this->output(
								"failed: user '$real_user' does not exist in target wiki."
							);
							continue;
						}
					}
				} else {
					# Find comment text
					$commentText = false;

					if ( $commentExt ) {
						$f = $this->findAuxFile( $file, $commentExt );
						if ( !$f ) {
							$this->output( " No comment file with extension {$commentExt} found "
								 . "for {$file}, using default comment. " );
						} else {
							$commentText = file_get_contents( $f );
							if ( !$commentText ) {
								$this->output(
									" Failed to load comment file {$f}, using default comment. "
								);
							}
						}
					}

					if ( !$commentText ) {
						$commentText = $comment;
					}
				}

				# Import the file
				if ( $this->hasOption( 'dry' ) ) {
					$this->output(
						" publishing {$file} by '{$wgUser->getName()}', comment '$commentText'... "
					);
				} else {
					$mwProps = new MWFileProps( MediaWiki\MediaWikiServices::getInstance()->getMimeAnalyzer() );
					$props = $mwProps->getPropsFromPath( $file, true );
					$flags = 0;
					$publishOptions = [];
					$handler = MediaHandler::getHandler( $props['mime'] );
					if ( $handler ) {
						$metadata = \Wikimedia\AtEase\AtEase::quietCall( 'unserialize', $props['metadata'] );

						$publishOptions['headers'] = $handler->getContentHeaders( $metadata );
					} else {
						$publishOptions['headers'] = [];
					}
					$archive = $image->publish( $file, $flags, $publishOptions );
					if ( !$archive->isGood() ) {
						$this->output( "failed. (" .
							 $archive->getMessage( false, false, 'en' )->text() .
							 ")\n" );
						$failed++;
						continue;
					}
				}

				$commentText = SpecialUpload::getInitialPageText( $commentText, $license );
				if ( !$this->hasOption( 'summary' ) ) {
					$summary = $commentText;
				}

				if ( $this->hasOption( 'dry' ) ) {
					$this->output( "done.\n" );
				} elseif ( $image->recordUpload2(
					$archive->value,
					$summary,
					$commentText,
					$props,
					$timestamp
				)->isOK() ) {
					$this->output( "done.\n" );

					$doProtect = false;

					$protectLevel = $this->getOption( 'protect' );

					if ( $protectLevel && in_array( $protectLevel, $wgRestrictionLevels ) ) {
						$doProtect = true;
					}
					if ( $this->hasOption( 'unprotect' ) ) {
						$protectLevel = '';
						$doProtect = true;
					}

					if ( $doProtect ) {
						# Protect the file
						$this->output( "\nWaiting for replica DBs...\n" );
						// Wait for replica DBs.
						sleep( 2 ); # Why this sleep?
						wfWaitForSlaves();

						$this->output( "\nSetting image restrictions ... " );

						$cascade = false;
						$restrictions = [];
						foreach ( $title->getRestrictionTypes() as $type ) {
							$restrictions[$type] = $protectLevel;
						}

						$page = WikiPage::factory( $title );
						$status = $page->doUpdateRestrictions( $restrictions, [], $cascade, '', $user );
						$this->output( ( $status->isOK() ? 'done' : 'failed' ) . "\n" );
					}
				} else {
					$this->output( "failed. (at recordUpload stage)\n" );
					$svar = 'failed';
				}

				$$svar++;
				$processed++;

				if ( $limit && $processed >= $limit ) {
					break;
				}
			}

			# Print out some statistics
			$this->output( "\n" );
			foreach (
				[
					'count' => 'Found',
					'limit' => 'Limit',
					'ignored' => 'Ignored',
					'added' => 'Added',
					'skipped' => 'Skipped',
					'overwritten' => 'Overwritten',
					'failed' => 'Failed'
				] as $var => $desc
			) {
				if ( $$var > 0 ) {
					$this->output( "{$desc}: {$$var}\n" );
				}
			}
		} else {
			$this->output( "No suitable files could be found for import.\n" );
		}
	}

	/**
	 * Search a directory for files with one of a set of extensions
	 *
	 * @param string $dir Path to directory to search
	 * @param array $exts Array of extensions to search for
	 * @param bool $recurse Search subdirectories recursively
	 * @return array|bool Array of filenames on success, or false on failure
	 */
	private function findFiles( $dir, $exts, $recurse = false ) {
		if ( is_dir( $dir ) ) {
			$dhl = opendir( $dir );
			if ( $dhl ) {
				$files = [];
				while ( ( $file = readdir( $dhl ) ) !== false ) {
					if ( is_file( $dir . '/' . $file ) ) {
						$ext = pathinfo( $file, PATHINFO_EXTENSION );
						if ( array_search( strtolower( $ext ), $exts ) !== false ) {
							$files[] = $dir . '/' . $file;
						}
					} elseif ( $recurse && is_dir( $dir . '/' . $file ) && $file !== '..' && $file !== '.' ) {
						$files = array_merge( $files, $this->findFiles( $dir . '/' . $file, $exts, true ) );
					}
				}

				return $files;
			} else {
				return [];
			}
		} else {
			return [];
		}
	}

	/**
	 * Find an auxilliary file with the given extension, matching
	 * the give base file path. $maxStrip determines how many extensions
	 * may be stripped from the original file name before appending the
	 * new extension. For example, with $maxStrip = 1 (the default),
	 * file files acme.foo.bar.txt and acme.foo.txt would be auxilliary
	 * files for acme.foo.bar and the extension ".txt". With $maxStrip = 2,
	 * acme.txt would also be acceptable.
	 *
	 * @param string $file Base path
	 * @param string $auxExtension The extension to be appended to the base path
	 * @param int $maxStrip The maximum number of extensions to strip from the base path (default: 1)
	 * @return string|bool
	 */
	private function findAuxFile( $file, $auxExtension, $maxStrip = 1 ) {
		if ( strpos( $auxExtension, '.' ) !== 0 ) {
			$auxExtension = '.' . $auxExtension;
		}

		$d = dirname( $file );
		$n = basename( $file );

		while ( $maxStrip >= 0 ) {
			$f = $d . '/' . $n . $auxExtension;

			if ( file_exists( $f ) ) {
				return $f;
			}

			$idx = strrpos( $n, '.' );
			if ( !$idx ) {
				break;
			}

			$n = substr( $n, 0, $idx );
			$maxStrip -= 1;
		}

		return false;
	}

	# @todo FIXME: Access the api in a saner way and performing just one query
	# (preferably batching files too).
	private function getFileCommentFromSourceWiki( $wiki_host, $file ) {
		$url = $wiki_host . '/api.php?action=query&format=xml&titles=File:'
			. rawurlencode( $file ) . '&prop=imageinfo&&iiprop=comment';
		$body = Http::get( $url, [], __METHOD__ );
		if ( preg_match( '#<ii comment="([^"]*)" />#', $body, $matches ) == 0 ) {
			return false;
		}

		return html_entity_decode( $matches[1] );
	}

	private function getFileUserFromSourceWiki( $wiki_host, $file ) {
		$url = $wiki_host . '/api.php?action=query&format=xml&titles=File:'
			. rawurlencode( $file ) . '&prop=imageinfo&&iiprop=user';
		$body = Http::get( $url, [], __METHOD__ );
		if ( preg_match( '#<ii user="([^"]*)" />#', $body, $matches ) == 0 ) {
			return false;
		}

		return html_entity_decode( $matches[1] );
	}

}

$maintClass = ImportImages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
