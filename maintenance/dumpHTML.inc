<?php
/**
 * @addtogroup Maintenance
 */

define( 'REPORTING_INTERVAL', 10 );

require_once( 'includes/ImagePage.php' );
require_once( 'includes/CategoryPage.php' );
require_once( 'includes/RawPage.php' );

class DumpHTML {
	# Destination directory
	var $dest;

	# Skip existing files
	var $noOverwrite = false;

	# Show interlanguage links?
	var $interwiki = true;

	# Depth of HTML directory tree
	var $depth = 3;

	# Directory that commons images are copied into
	var $sharedStaticDirectory;

	# Directory that the images are in, after copying
	var $destUploadDirectory;

	# Relative path to image directory
	var $imageRel = 'upload';

	# Copy commons images instead of symlinking
	var $forceCopy = false;

	# Make a copy of all images encountered
	var $makeSnapshot = false;

	# Don't image description pages in doEverything()
	var $noSharedDesc = false;

	# Make links assuming the script path is in the same directory as
	# the destination
	var $alternateScriptPath = false;

	# Original values of various globals
	var $oldArticlePath = false, $oldCopyrightIcon = false;

	# Has setupGlobals been called?
	var $setupDone = false;

	# Has to compress html pages
	var $compress = false;

	# List of raw pages used in the current article
	var $rawPages;

	# Skin to use
	var $skin = 'htmldump';

	# Checkpoint stuff
	var $checkpointFile = false, $checkpoints = false;

	var $startID = 1, $endID = false;

	var $sliceNumerator = 1, $sliceDenominator = 1;

	# Max page ID, lazy initialised
	var $maxPageID = false;

	# UDP profiling
	var $udpProfile, $udpProfileCounter = 0, $udpProfileInit = false;

	function DumpHTML( $settings = array() ) {
		foreach ( $settings as $var => $value ) {
			$this->$var = $value;
		}
	}

	function loadCheckpoints() {
		if ( $this->checkpoints !== false ) {
			return true;
		} elseif ( !$this->checkpointFile ) {
			return false;
		} else {
			$lines = @file( $this->checkpointFile );
			if ( $lines === false ) {
				print "Starting new checkpoint file \"{$this->checkpointFile}\"\n";
				$this->checkpoints = array();
			} else {
				$lines = array_map( 'trim', $lines );
				$this->checkpoints = array();
				foreach ( $lines as $line ) {
					list( $name, $value ) = explode( '=', $line, 2 );
					$this->checkpoints[$name] = $value;
				}
			}
			return true;
		}
	}

	function getCheckpoint( $type, $defValue = false ) {
		if ( !$this->loadCheckpoints() ) {
			return false;
		}
		if ( !isset( $this->checkpoints[$type] ) ) {
			return false;
		} else {
			return $this->checkpoints[$type];
		}
	}

	function setCheckpoint( $type, $value ) {
		if ( !$this->checkpointFile ) {
			return;
		}
		$this->checkpoints[$type] = $value;
		$blob = '';
		foreach ( $this->checkpoints as $type => $value ) {
			$blob .= "$type=$value\n";
		}
		file_put_contents( $this->checkpointFile, $blob );
	}

	function doEverything() {
		if ( $this->getCheckpoint( 'everything' ) == 'done' ) {
			print "Checkpoint says everything is already done\n";
			return;
		}
		$this->doArticles();
		$this->doCategories();
		$this->doRedirects();
		if ( $this->sliceNumerator == 1 ) {
			$this->doSpecials();
		}
		$this->doLocalImageDescriptions();

		if ( !$this->noSharedDesc ) {
			$this->doSharedImageDescriptions();
		}

		$this->setCheckpoint( 'everything', 'done' );
	}

	/**
	 * Write a set of articles specified by start and end page_id
	 * Skip categories and images, they will be done separately
	 */
	function doArticles() {
		if ( $this->endID === false ) {
			$end = $this->getMaxPageID();
		} else {
			$end = $this->endID;
		}
		$start = $this->startID;
		
		# Start from the checkpoint
		$cp = $this->getCheckpoint( 'article' );
		if ( $cp == 'done' ) {
			print "Articles already done\n";
			return;
		} elseif ( $cp !== false ) {
			$start = $cp;
			print "Resuming article dump from checkpoint at page_id $start of $end\n";
		} else {
			print "Starting from page_id $start of $end\n";
		}

		# Move the start point to the correct slice if it isn't there already
		$start = $this->modSliceStart( $start );

		$this->setupGlobals();

		$mainPageObj = Title::newMainPage();
		$mainPage = $mainPageObj->getPrefixedDBkey();

		for ( $id = $start, $i = 0; $id <= $end; $id += $this->sliceDenominator, $i++ ) {
			wfWaitForSlaves( 20 );
			if ( !( $i % REPORTING_INTERVAL) ) {
				print "Processing ID: $id\r";
				$this->setCheckpoint( 'article', $id );
			}
			if ( !($i % (REPORTING_INTERVAL*10) ) ) {
				print "\n";
			}
			$title = Title::newFromID( $id );
			if ( $title ) {
				$ns = $title->getNamespace() ;
				if ( $ns != NS_CATEGORY && $ns != NS_MEDIAWIKI && 
				  $title->getPrefixedDBkey() != $mainPage ) {
					$this->doArticle( $title );
				}
			}
		}
		$this->setCheckpoint( 'article', 'done' );
		print "\n";
	}

	function doSpecials() {
		$this->doMainPage();

		$this->setupGlobals();
		print "Special:Categories...";
		$this->doArticle( SpecialPage::getTitleFor( 'Categories' ) );
		print "\n";
	}

	/** Write the main page as index.html */
	function doMainPage() {

		print "Making index.html  ";

		// Set up globals with no ../../.. in the link URLs
		$this->setupGlobals( 0 );

		$title = Title::newMainPage();
		$text = $this->getArticleHTML( $title );
		
		# Parse the XHTML to find the images
		$images = $this->findImages( $text );
		$this->copyImages( $images );
		
		$file = fopen( "{$this->dest}/index.html", "w" );
		if ( !$file ) {
			print "\nCan't open index.html for writing\n";
			return false;
		}
		fwrite( $file, $text );
		fclose( $file );
		print "\n";
	}

	function doImageDescriptions() {
		$this->doLocalImageDescriptions();
		if ( !$this->noSharedDesc ) {
			$this->doSharedImageDescriptions();
		}
	}

	/**
	 * Dump image description pages that don't have an associated article, but do
	 * have a local image
	 */
	function doLocalImageDescriptions() {
		$chunkSize = 1000;

		$dbr =& wfGetDB( DB_SLAVE );
		
		$cp = $this->getCheckpoint( 'local image' );
		if ( $cp == 'done' ) {
			print "Local image descriptions already done\n";
			return;
		} elseif ( $cp !== false ) {
			print "Writing image description pages starting from $cp\n";
			$conds = array(	'img_name >= ' . $dbr->addQuotes( $cp ) );
		} else {
			print "Writing image description pages for local images\n";		
			$conds = false;
		}

		$this->setupGlobals();
		$i = 0;

		do {
			$res = $dbr->select( 'image', array( 'img_name' ), $conds, __METHOD__, 
				array( 'ORDER BY' => 'img_name', 'LIMIT' => $chunkSize ) );
			$numRows = $dbr->numRows( $res );

			while ( $row = $dbr->fetchObject( $res ) ) {
				# Update conds for the next chunk query
				$conds = array( 'img_name > ' . $dbr->addQuotes( $row->img_name ) );
				
				// Slice the result set with a filter
				if ( !$this->sliceFilter( $row->img_name ) ) {
					continue;
				}

				wfWaitForSlaves( 10 );
				if ( !( ++$i % REPORTING_INTERVAL ) ) {
					print "{$row->img_name}\n";
					if ( $row->img_name !== 'done' ) {
						$this->setCheckpoint( 'local image', $row->img_name );
					}
				}
				$title = Title::makeTitle( NS_IMAGE, $row->img_name );
				if ( $title->getArticleID() ) {
					// Already done by dumpHTML
					continue;
				}
				$this->doArticle( $title );
			}
			$dbr->freeResult( $res );
		} while ( $numRows );
		
		$this->setCheckpoint( 'local image', 'done' );
		print "\n";
	}

	/**
	 * Dump images which only have a real description page on commons
	 */
	function doSharedImageDescriptions() {
		list( $start, $end ) = $this->sliceRange( 0, 255 );

		$cp = $this->getCheckpoint( 'shared image' );
		if ( $cp == 'done' ) {
			print "Shared description pages already done\n";
			return;
		} elseif ( $cp !== false ) {
			print "Writing description pages for commons images starting from directory $cp/255\n";
			$start = $cp;
		} else {
			print "Writing description pages for commons images\n";
		}

		$this->setupGlobals();
		$i = 0;
		for ( $hash = $start; $hash <= $end; $hash++ ) {
			$this->setCheckpoint( 'shared image', $hash );

			$dir = sprintf( "%s/%01x/%02x", $this->sharedStaticDirectory,
		       		intval( $hash / 16 ), $hash );
			$handle = @opendir( $dir );
			while ( $handle && $file = readdir( $handle ) ) {
				if ( $file[0] == '.' ) {
					continue;
				}
				if ( !(++$i % REPORTING_INTERVAL ) ) {
					print "$i\r";
				}

				$title = Title::makeTitleSafe( NS_IMAGE, $file );
				$this->doArticle( $title );
			}
			if ( $handle ) {
				closedir( $handle );
			}
		}
		$this->setCheckpoint( 'shared image', 'done' );
		print "\n";
	}

	function doCategories() {
		$chunkSize = 1000;
		
		$this->setupGlobals();
		$dbr =& wfGetDB( DB_SLAVE );
		
		$cp = $this->getCheckpoint( 'category' );
		if ( $cp == 'done' ) {
			print "Category pages already done\n";
			return;
		} elseif ( $cp !== false ) {
			print "Resuming category page dump from $cp\n";
			$conds = array( 'cl_to >= ' . $dbr->addQuotes( $cp ) );
		} else {
			print "Starting category pages\n";
			$conds = false;
		}

		$i = 0;
		do {
			$res = $dbr->select( 'categorylinks', 'DISTINCT cl_to', $conds, __METHOD__, 
				array( 'ORDER BY' => 'cl_to', 'LIMIT' => $chunkSize ) );
			$numRows = $dbr->numRows( $res );
			
			while ( $row = $dbr->fetchObject( $res ) ) {
				// Set conditions for next chunk
				$conds = array( 'cl_to > ' . $dbr->addQuotes( $row->cl_to ) );
				
				// Filter pages from other slices
				if ( !$this->sliceFilter( $row->cl_to ) ) {
					continue;
				}

				wfWaitForSlaves( 10 );
				if ( !(++$i % REPORTING_INTERVAL ) ) {
					print "{$row->cl_to}\n";
					if ( $row->cl_to != 'done' ) {
						$this->setCheckpoint( 'category', $row->cl_to );
					}
				}
				$title = Title::makeTitle( NS_CATEGORY, $row->cl_to );
				$this->doArticle( $title );
			}
			$dbr->freeResult( $res );
		} while ( $numRows );
		
		$this->setCheckpoint( 'category', 'done' );
		print "\n";
	}

	function doRedirects() {
		print "Doing redirects...\n";

		$chunkSize = 10000;
		$end = $this->getMaxPageID();
		$cp = $this->getCheckpoint( 'redirect' );
		if ( $cp == 'done' )  {
			print "Redirects already done\n";
			return;
		} elseif ( $cp !== false ) {
			print "Resuming redirect generation from page_id $cp\n";
			$start = intval( $cp );
		} else {
			$start = 1;
		}

		$this->setupGlobals();
		$dbr =& wfGetDB( DB_SLAVE );
		$i = 0;

		for ( $chunkStart = $start; $chunkStart <= $end; $chunkStart += $chunkSize ) {
			$chunkEnd = min( $end, $chunkStart + $chunkSize - 1 );
			$conds = array( 
				'page_is_redirect' => 1,
				"page_id BETWEEN $chunkStart AND $chunkEnd"
			);
			# Modulo slicing in SQL
			if ( $this->sliceDenominator != 1 ) {
				$n = intval( $this->sliceNumerator );
				$m = intval( $this->sliceDenominator );
				$conds[] = "page_id % $m = $n";
			}
			$res = $dbr->select( 'page', array( 'page_id', 'page_namespace', 'page_title' ),
				$conds, __METHOD__ );
			
			while ( $row = $dbr->fetchObject( $res ) ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				if ( !(++$i % (REPORTING_INTERVAL*10) ) ) {
					printf( "Done %d redirects (%2.3f%%)\n", $i, $row->page_id / $end * 100 );
					$this->setCheckpoint( 'redirect', $row->page_id );
				}
				$this->doArticle( $title );
			}
			$dbr->freeResult( $res );
		}
		$this->setCheckpoint( 'redirect', 'done' );
	}

	/** Write an article specified by title */
	function doArticle( $title ) {
		if ( $this->noOverwrite ) {
			$fileName = $this->dest.'/'.$this->getHashedFilename( $title );
			if ( file_exists( $fileName ) ) {
				return;
			}
		}

		$this->profile();

		$this->rawPages = array();
		$text = $this->getArticleHTML( $title );

		if ( $text === false ) {
			return;
		}

		# Parse the XHTML to find the images
		$images = $this->findImages( $text );
		$this->copyImages( $images );

		# Write to file
		$this->writeArticle( $title, $text );

		# Do raw pages
		wfMkdirParents( "{$this->dest}/raw", 0755 );
		foreach( $this->rawPages as $record ) {
			list( $file, $title, $params ) = $record;

			$path = "{$this->dest}/raw/$file";
			if ( !file_exists( $path ) ) {
				$article = new Article( $title );
				$request = new FauxRequest( $params );
				$rp = new RawPage( $article, $request );
				$text = $rp->getRawText();

				print "Writing $file\n";
				$file = fopen( $path, 'w' );
				if ( !$file ) {
					print("Can't open file $path for writing\n");
					continue;
				}
				fwrite( $file, $text );
				fclose( $file );
			}
		}

		wfIncrStats( 'dumphtml_article' );
	}

	/** Write the given text to the file identified by the given title object */
	function writeArticle( $title, $text ) {
		$filename = $this->getHashedFilename( $title );

		# Temporary hack for current dump, this should be moved to 
		# getFriendlyName() at the earliest opportunity.
		#
		# Limit filename length to 255 characters, so it works on ext3.
		# Titles are in fact limited to 255 characters, but dumpHTML 
		# adds a suffix which may put them over the limit.
		$length = strlen( $filename );
		if ( $length > 255 ) {
			print "Warning: Filename too long ($length bytes). Skipping.\n";
			return;
		}
			
		$fullName = "{$this->dest}/$filename";
		$fullDir = dirname( $fullName );

		if ( $this->compress ) {
			$fullName .= ".gz";
			$text = gzencode( $text, 9 );				
		}

		wfMkdirParents( $fullDir, 0755 );

		wfSuppressWarnings();
		$file = fopen( $fullName, 'w' );
		wfRestoreWarnings();

		if ( !$file ) {
			die("Can't open file '$fullName' for writing.\nCheck permissions or use another destination (-d).\n");
			return;
		}

		fwrite( $file, $text );
		fclose( $file );
	}

	/** Set up globals required for parsing */
	function setupGlobals( $currentDepth = NULL ) {
		global $wgUser, $wgStylePath, $wgArticlePath, $wgMathPath;
		global $wgUploadPath, $wgLogo, $wgMaxCredits, $wgSharedUploadPath;
		global $wgHideInterlanguageLinks, $wgUploadDirectory, $wgThumbnailScriptPath;
		global $wgSharedThumbnailScriptPath, $wgEnableParserCache, $wgHooks, $wgServer;
		global $wgRightsUrl, $wgRightsText, $wgCopyrightIcon, $wgEnableSidebarCache;
		global $wgGenerateThumbnailOnParse;

		static $oldLogo = NULL;

		if ( !$this->setupDone ) {
			$wgHooks['GetLocalURL'][] =& $this;
			$wgHooks['GetFullURL'][] =& $this;
			$wgHooks['SiteNoticeBefore'][] =& $this;
			$wgHooks['SiteNoticeAfter'][] =& $this;
			$this->oldArticlePath = $wgServer . $wgArticlePath;
		}

		if ( is_null( $currentDepth ) ) {
			$currentDepth = $this->depth;
		}

		if ( $this->alternateScriptPath ) {
			if ( $currentDepth == 0 ) {
				$wgScriptPath = '.';
			} else {
				$wgScriptPath = '..' . str_repeat( '/..', $currentDepth - 1 );
			}
		} else {
			$wgScriptPath = '..' . str_repeat( '/..', $currentDepth );
		}

		$wgArticlePath = str_repeat( '../', $currentDepth ) . '$1';

		# Logo image
		# Allow for repeated setup
		if ( !is_null( $oldLogo ) ) {
			$wgLogo = $oldLogo;
		} else {
			$oldLogo = $wgLogo;
		}

		if ( strpos( $wgLogo, $wgUploadPath ) === 0 ) {
			# If it's in the upload directory, rewrite it to the new upload directory
			$wgLogo = "$wgScriptPath/{$this->imageRel}/" . substr( $wgLogo, strlen( $wgUploadPath ) + 1 );
		} elseif ( $wgLogo{0} == '/' ) {
			# This is basically heuristic
			# Rewrite an absolute logo path to one relative to the the script path
			$wgLogo = $wgScriptPath . $wgLogo;
		}

		# Another ugly hack
		if ( !$this->setupDone ) {
			$this->oldCopyrightIcon = $wgCopyrightIcon;
		}
		$wgCopyrightIcon = str_replace( 'src="/images',
			'src="' . htmlspecialchars( $wgScriptPath ) . '/images', $this->oldCopyrightIcon );

		$wgStylePath = "$wgScriptPath/skins";
		$wgUploadPath = "$wgScriptPath/{$this->imageRel}";
		$wgSharedUploadPath = "$wgUploadPath/shared";
		$wgMaxCredits = -1;
		$wgHideInterlanguageLinks = !$this->interwiki;
		$wgThumbnailScriptPath = $wgSharedThumbnailScriptPath = false;
		$wgEnableParserCache = false;
		$wgMathPath = "$wgScriptPath/math";
		$wgEnableSidebarCache = false;
		$wgGenerateThumbnailOnParse = true;

		if ( !empty( $wgRightsText ) ) {
			$wgRightsUrl = "$wgScriptPath/COPYING.html";
		}

		$wgUser = new User;
		$wgUser->setOption( 'skin', $this->skin );
		$wgUser->setOption( 'editsection', 0 );

		$this->destUploadDirectory = "{$this->dest}/{$this->imageRel}";
		if ( realpath( $this->destUploadDirectory ) == realpath( $wgUploadDirectory ) ) {
			print "Disabling image snapshot because the destination is the same as the source\n";
			$this->makeSnapshot = false;
		}
		$this->sharedStaticDirectory = "{$this->destUploadDirectory}/shared";

		$this->setupDone = true;
	}

	/** Reads the content of a title object, executes the skin and captures the result */
	function getArticleHTML( $title ) {
		global $wgOut, $wgTitle, $wgArticle, $wgUser;

		$linkCache =& LinkCache::singleton();
		$linkCache->clear();
		$wgTitle = $title;
		if ( is_null( $wgTitle ) ) {
			return false;
		}

		$ns = $wgTitle->getNamespace();
		if ( $ns == NS_SPECIAL ) {
			$wgOut = new OutputPage;
			$wgOut->setParserOptions( new ParserOptions );
			SpecialPage::executePath( $wgTitle );
		} else {
			/** @todo merge with Wiki.php code */
			if ( $ns == NS_IMAGE ) {
				$wgArticle = new ImagePage( $wgTitle );
			} elseif ( $ns == NS_CATEGORY ) {
				$wgArticle = new CategoryPage( $wgTitle );
			} else {
				$wgArticle = new Article( $wgTitle );
			}
			$rt = Title::newFromRedirect( $wgArticle->fetchContent() );
			if ( $rt != NULL ) {
				return $this->getRedirect( $rt );
			} else {
				$wgOut = new OutputPage;
				$wgOut->setParserOptions( new ParserOptions );

				$wgArticle->view();
			}
		}

	
		$sk =& $wgUser->getSkin();
		ob_start();
		$sk->outputPage( $wgOut );
		$text = ob_get_contents();
		ob_end_clean();

		return $text;
	}

	function getRedirect( $rt ) {
		$url = $rt->escapeLocalURL();
		$text = $rt->getPrefixedText();
		return <<<ENDTEXT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="Refresh" content="0;url=$url" />
</head>
<body>
  <p>Redirecting to <a href="$url">$text</a></p>
</body>
</html>
ENDTEXT;
	}

	/** Returns image paths used in an XHTML document */
	function findImages( $text ) {
		global $wgOutputEncoding, $wgDumpImages;
		$parser = xml_parser_create( $wgOutputEncoding );
		xml_set_element_handler( $parser, 'wfDumpStartTagHandler', 'wfDumpEndTagHandler' );

		$wgDumpImages = array();
		xml_parse( $parser, $text );
		xml_parser_free( $parser );

		return $wgDumpImages;
	}

	/**
	 * Copy a file specified by a URL to a given directory
	 * 
	 * @param string $srcPath The source URL
	 * @param string $srcPathBase The base directory of the source URL
	 * @param string $srcDirBase The base filesystem directory of the source URL
	 * @param string $destDirBase The base filesystem directory of the destination URL
	 */
	function relativeCopy( $srcPath, $srcPathBase, $srcDirBase, $destDirBase ) {
		$rel = substr( $srcPath, strlen( $srcPathBase ) + 1 ); // +1 for slash
		$sourceLoc = "$srcDirBase/$rel";
		$destLoc = "$destDirBase/$rel";
		#print "Copying $sourceLoc to $destLoc\n";
		if ( !file_exists( $destLoc ) ) {
			wfMkdirParents( dirname( $destLoc ), 0755 );
			if ( function_exists( 'symlink' ) && !$this->forceCopy ) {
				if ( !symlink( $sourceLoc, $destLoc ) ) {
					print "Warning: unable to create symlink at $destLoc\n";
				}
			} else {
				if ( !copy( $sourceLoc, $destLoc ) ) {
					print "Warning: unable to copy $sourceLoc to $destLoc\n";
				}
			}
		}
	}

	/**
	 * Copy an image, and if it is a thumbnail, copy its parent image too
	 */
	function copyImage( $srcPath, $srcPathBase, $srcDirBase, $destDirBase ) {
		global $wgUploadPath, $wgUploadDirectory, $wgSharedUploadPath;
		$this->relativeCopy( $srcPath, $srcPathBase, $srcDirBase, $destDirBase );
		if ( substr( $srcPath, strlen( $srcPathBase ) + 1, 6 ) == 'thumb/' ) {
			# The image was a thumbnail
			# Copy the source image as well
			$rel = substr( $srcPath, strlen( $srcPathBase ) + 1 );
			$parts = explode( '/', $rel );
			$rel = "{$parts[1]}/{$parts[2]}/{$parts[3]}";
			$newSrc = "$srcPathBase/$rel";
			$this->relativeCopy( $newSrc, $srcPathBase, $srcDirBase, $destDirBase );
		}
	}
	
	/**
	 * Copy images (or create symlinks) from commons to a static directory.
	 * This is necessary even if you intend to distribute all of commons, because
	 * the directory contents is used to work out which image description pages
	 * are needed.
	 *
	 * Also copies math images, and full-sized images if the makeSnapshot option 
	 * is specified.
	 *
	 */
	function copyImages( $images ) {
		global $wgUploadPath, $wgUploadDirectory, $wgSharedUploadPath, $wgSharedUploadDirectory, 
			$wgMathPath, $wgMathDirectory;
		# Find shared uploads and copy them into the static directory
		$sharedPathLength = strlen( $wgSharedUploadPath );
		$mathPathLength = strlen( $wgMathPath );
		$uploadPathLength = strlen( $wgUploadPath );
		foreach ( $images as $escapedImage => $dummy ) {
			$image = urldecode( $escapedImage );

			if ( substr( $image, 0, $sharedPathLength ) == $wgSharedUploadPath ) {
				$this->copyImage( $image, $wgSharedUploadPath, $wgSharedUploadDirectory, $this->sharedStaticDirectory );
			} elseif ( substr( $image, 0, $mathPathLength ) == $wgMathPath ) {
				$this->relativeCopy( $image, $wgMathPath, $wgMathDirectory, "{$this->dest}/math" );
			} elseif ( $this->makeSnapshot && substr( $image, 0, $uploadPathLength ) == $wgUploadPath ) {
				$this->copyImage( $image, $wgUploadPath, $wgUploadDirectory, $this->destUploadDirectory );
			}
		}
	}

	function onGetFullURL( &$title, &$url, $query ) {
		global $wgContLang, $wgArticlePath;

		$iw = $title->getInterwiki();
		if ( $title->isExternal() && $wgContLang->getLanguageName( $iw ) ) {
			if ( $title->getDBkey() == '' ) {
				$url = str_replace( '$1', "../$iw/index.html", $wgArticlePath );
			} else {
				$url = str_replace( '$1', "../$iw/" . wfUrlencode( $this->getHashedFilename( $title ) ),
					$wgArticlePath );
			}
			$url .= $this->compress ? ".gz" : "";
			return false;
		} else {
			return true;
		}
	}

	function onGetLocalURL( &$title, &$url, $query ) {
		global $wgArticlePath;

		if ( $title->isExternal() ) {
			# Default is fine for interwiki
			return true;
		}

		$url = false;
		if ( $query != '' ) {
			$params = array();
			parse_str( $query, $params );
			if ( isset($params['action']) && $params['action'] == 'raw' ) {
				if ( $params['gen'] == 'css' || $params['gen'] == 'js' ) {
					$file = 'gen.' . $params['gen'];
				} else {
					$file = $this->getFriendlyName( $title->getPrefixedDBkey() );
					// Clean up Monobook.css etc.
					$matches = array();
					if ( preg_match( '/^(.*)\.(css|js)_[0-9a-f]{4}$/', $file, $matches ) ) {
						$file = $matches[1] . '.' . $matches[2];
					}
				}
				$this->rawPages[$file] = array( $file, $title, $params );
				$url = str_replace( '$1', "raw/" . wfUrlencode( $file ), $wgArticlePath );
			}
		}
		if ( $url === false ) {
			$url = str_replace( '$1', wfUrlencode( $this->getHashedFilename( $title ) ), $wgArticlePath );
		}
		$url .= $this->compress ? ".gz" : "";
		return false;
	}

	function getHashedFilename( &$title ) {
		if ( '' != $title->mInterwiki ) {
			$dbkey = $title->getDBkey();
		} else {
			$dbkey = $title->getPrefixedDBkey();
		}

		$mainPage = Title::newMainPage();
		if ( $mainPage->getPrefixedDBkey() == $dbkey ) {
			return 'index.html';
		}

		return $this->getHashedDirectory( $title ) . '/' .
			$this->getFriendlyName( $dbkey ) . '.html';
	}

	function getFriendlyName( $name ) {
		global $wgLang;
		# Replace illegal characters for Windows paths with underscores
		$friendlyName = strtr( $name, '/\\*?"<>|~', '_________' );

		# Work out lower case form. We assume we're on a system with case-insensitive
		# filenames, so unless the case is of a special form, we have to disambiguate
		if ( function_exists( 'mb_strtolower' ) ) {
			$lowerCase = $wgLang->ucfirst( mb_strtolower( $name ) );
		} else {
			$lowerCase = ucfirst( strtolower( $name ) );
		}

		# Make it mostly unique
		if ( $lowerCase != $friendlyName  ) {
			$friendlyName .= '_' . substr(md5( $name ), 0, 4);
		}
		# Handle colon specially by replacing it with tilde
		# Thus we reduce the number of paths with hashes appended
		$friendlyName = str_replace( ':', '~', $friendlyName );

		return $friendlyName;
	}

	/**
	 * Get a relative directory for putting a title into
	 */
	function getHashedDirectory( &$title ) {
		if ( '' != $title->getInterwiki() ) {
			$pdbk = $title->getDBkey();
		} else {
			$pdbk = $title->getPrefixedDBkey();
		}

		# Find the first colon if there is one, use characters after it
		$p = strpos( $pdbk, ':' );
		if ( $p !== false ) {
			$dbk = substr( $pdbk, $p + 1 );
			$dbk = substr( $dbk, strspn( $dbk, '_' ) );
		} else {
			$dbk = $pdbk;
		}

		# Split into characters
		$m = array();
		preg_match_all( '/./us', $dbk, $m );

		$chars = $m[0];
		$length = count( $chars );
		$dir = '';

		for ( $i = 0; $i < $this->depth; $i++ ) {
			if ( $i ) {
				$dir .= '/';
			}
			if ( $i >= $length ) {
				$dir .= '_';
			} else {
				$c = $chars[$i];
				if ( ord( $c ) >= 128 || preg_match( '/[a-zA-Z0-9!#$%&()+,[\]^_`{}-]/', $c ) ) {
					if ( function_exists( 'mb_strtolower' ) ) {
						$dir .= mb_strtolower( $c );
					} else {
						$dir .= strtolower( $c );
					}
				} else {
					$dir .= sprintf( "%02X", ord( $c ) );
				}
			}
		}
		return $dir;
	}

	/**
	 * Calculate the start end end of a job based on the current slice
	 * @param integer $start
	 * @param integer $end
	 * @return array of integers
	 */
	function sliceRange( $start, $end ) {
		$count = $end - $start + 1;
		$each = $count / $this->sliceDenominator;
		$sliceStart = $start + intval( $each * ( $this->sliceNumerator - 1 ) );
		if ( $this->sliceNumerator == $this->sliceDenominator ) {
			$sliceEnd = $end;
		} else {
			$sliceEnd = $start + intval( $each * $this->sliceNumerator ) - 1;
		}
		return array( $sliceStart, $sliceEnd );
	}

	/**
	 * Adjust a start point so that it belongs to the current slice, where slices are defined by integer modulo
	 * @param integer $start
	 * @param integer $base The true start of the range; the minimum start
	 */
	function modSliceStart( $start, $base = 1 ) {
		return $start - ( $start % $this->sliceDenominator ) + $this->sliceNumerator - 1 + $base;
	}

	/**
	 * Determine whether a string belongs to the current slice, based on hash
	 */
	function sliceFilter( $s ) {
		return crc32( $s ) % $this->sliceDenominator == $this->sliceNumerator - 1;
	}

	/**
	 * No site notice
	 */
	function onSiteNoticeBefore( &$text ) {
		$text = '';
		return false;
	}
	function onSiteNoticeAfter( &$text ) {
		$text = '';
		return false;
	}

	function getMaxPageID() {
		if ( $this->maxPageID === false ) {
			$dbr =& wfGetDB( DB_SLAVE );
			$this->maxPageID = $dbr->selectField( 'page', 'max(page_id)', false, __METHOD__ );
		}
		return $this->maxPageID;
	}

	function profile() {
		global $wgProfiler;

		if ( !$this->udpProfile ) {
			return;
		}
		if ( !$this->udpProfileInit ) {
			$this->udpProfileInit = true;
		} elseif ( $this->udpProfileCounter == 1 % $this->udpProfile ) {
			$wgProfiler->getFunctionReport();
			$wgProfiler = new DumpHTML_ProfilerStub;
		}
		if ( $this->udpProfileCounter == 0 ) {
			$wgProfiler = new ProfilerSimpleUDP;
			$wgProfiler->setProfileID( 'dumpHTML' );
		}
		$this->udpProfileCounter = ( $this->udpProfileCounter + 1 ) % $this->udpProfile;
	}
}

class DumpHTML_ProfilerStub {
	function profileIn() {}
	function profileOut() {}
	function getOutput() {}
	function close() {}
	function getFunctionReport() {}
}

/** XML parser callback */
function wfDumpStartTagHandler( $parser, $name, $attribs ) {
	global $wgDumpImages;

	if ( $name == 'IMG' && isset( $attribs['SRC'] ) ) {
		$wgDumpImages[$attribs['SRC']] = true;
	}
}

/** XML parser callback */
function wfDumpEndTagHandler( $parser, $name ) {}

# vim: syn=php
?>
