<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 */

define( 'REPORTING_INTERVAL', 10 );

require_once( 'includes/ImagePage.php' );
require_once( 'includes/CategoryPage.php' );

class DumpHTML {
	var $dest, $interwiki, $depth, $sharedStaticPath;

	function DumpHTML( $dest, $interwiki = true, $depth = 3 ) {
		$this->dest = $dest;
		$this->interwiki = $interwiki;
		$this->depth = $depth;
	}

	/** 
	 * Write a set of articles specified by start and end page_id 
	 * Skip categories and images, they will be done separately
	 */
	function doArticles( $start, $end = false ) {
		$fname = 'DumpHTML::doArticles';
		
		$this->setupGlobals();
		
		if ( $end === false ) {
			$dbr =& wfGetDB( DB_SLAVE );
			$end = $dbr->selectField( 'page', 'max(page_id)', false, $fname );
		}

		
		for ($id = $start; $id <= $end; $id++) {
			if ( !($id % REPORTING_INTERVAL) ) {
				print("$id\n");
			}
			$title = Title::newFromID( $id );
			if ( $title ) {
				$ns = $title->getNamespace() ;
				if ( $ns != NS_CATEGORY && $ns != NS_IMAGE ) { 
					$this->doArticle( $title );
				}
			}
		}
	}	

	function doSpecials() {
		$this->doMainPage();

		$this->setupGlobals();
		print "Special:Categories...";
		$this->doArticle( Title::makeTitle( NS_SPECIAL, 'Categories' ) );
		print "\n";
	}

	/** Write the main page as index.html */
	function doMainPage() {
		global $wgMakeDumpLinks;

		print "Making index.html  ";

		// Set up globals with no ../../.. in the link URLs
		$this->setupGlobals( 0 );

		// But still use that directory style
		$wgMakeDumpLinks = 3;
		
		$title = Title::newMainPage();
		$text = $this->getArticleHTML( $title );
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
		global $wgSharedUploadDirectory;
		
		$fname = 'DumpHTML::doImageDescriptions';
		
		$this->setupGlobals( 3 );

		/** 
		 * Dump image description pages that don't have an associated article, but do 
		 * have a local image
		 */
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'image', 'page' ) );
		$res = $dbr->select( 'image', array( 'img_name' ), false, $fname );

		$i = 0;
		print "Writing " . $dbr->numRows( $res ) . " image description pages for local images\n";
		while ( $row = $dbr->fetchObject( $res ) ) {
			if ( !( ++$i % REPORTING_INTERVAL ) ) {
				print "$i\t{$row->img_name}\n";
			}
			$title = Title::makeTitle( NS_IMAGE, $row->img_name );
			if ( $title->getArticleID() ) { 
				// Already done by dumpHTML
				continue;
			}
			$this->doArticle( $title );
		}
		/**
		 * Dump images which only have a real description page on commons
		 */
		print "Writing description pages for commons images\n";
		$i = 0;
		for ( $hash = 0; $hash < 256; $hash++ ) {				
			$dir = sprintf( "%01x/%02x", intval( $hash / 16 ), $hash );
			$paths = glob( "{$this->sharedStaticPath}/$dir/*" );
			$paths += glob( "{$this->sharedStaticPath}/thumb/$dir/*" );

			foreach ( $paths as $path ) {
				$file = basename( $path );
				if ( !(++$i % REPORTING_INTERVAL ) ) {
					print "$i\t$file\n";
				}

				$title = Title::makeTitle( NS_IMAGE, $file );
				$this->doArticle( $title );
			}
		}
	}

	function doCategories() {
		$fname = 'DumpHTML::doCategories';
		$this->setupGlobals();

		$dbr =& wfGetDB( DB_SLAVE );
		$categorylinks = $dbr->tableName( 'categorylinks' );
		print "Selecting categories...";
		$sql = 'SELECT DISTINCT cl_to FROM categorylinks';
		$res = $dbr->query( $sql, $fname );

		print "\nWriting " . $dbr->numRows( $res ).  " category pages\n";
		$i = 0;
		while ( $row = $dbr->fetchObject( $res ) ) {
			if ( !(++$i % REPORTING_INTERVAL ) ) {
				print "$i\t{$row->cl_to}\n";
			}
			$title = Title::makeTitle( NS_CATEGORY, $row->cl_to );
			$this->doArticle( $title );
		}
	}


	/** Write an article specified by title */
	function doArticle( $title ) {
		global $wgTitle, $wgSharedUploadPath, $wgSharedUploadDirectory;
		global $wgUploadDirectory;
		
		$text = $this->getArticleHTML( $title );
		if ( $text === false ) {
			return;
		}

		# Parse the XHTML to find the images
		$images = $this->findImages( $text );
		$this->copyImages( $images );

		# Write to file
		$this->writeArticle( $title, $text );
	}

	/** Write the given text to the file identified by the given title object */
	function writeArticle( &$title, $text ) {
		$filename = $title->getHashedFilename();
		$fullName = "{$this->dest}/$filename";
		$fullDir = dirname( $fullName );

		wfMkdirParents( $fullDir, 0755 );
		
		$file = fopen( $fullName, 'w' );
		if ( !$file ) {
			print("Can't open file $fullName for writing\n");
			return;
		}
		
		fwrite( $file, $text );
		fclose( $file );
	}

	/** Set up globals required for parsing */
	function setupGlobals( $depth = NULL ) {
		global $wgUser, $wgTitle, $wgMakeDumpLinks, $wgStylePath, $wgArticlePath;
		global $wgUploadPath, $wgLogo, $wgMaxCredits, $wgSharedUploadPath;
		global $wgHideInterlanguageLinks, $wgUploadDirectory, $wgThumbnailScriptPath;
		global $wgSharedThumbnailScriptPath, $wgEnableParserCache;
		
		if ( is_null( $depth ) ) {
			$wgMakeDumpLinks = $this->depth;
		} else {
			$wgMakeDumpLinks = $depth;
		}
		
		$wgScriptPath = '..' . str_repeat( '/..', $wgMakeDumpLinks );
		$wgArticlePath = str_repeat( '../', $wgMakeDumpLinks ) . '$1';
		$wgStylePath = "$wgScriptPath/skins";
		$wgUploadPath = "$wgScriptPath/images";
		$wgSharedUploadPath = "$wgUploadPath/shared";
		$wgLogo = "$wgStylePath/common/images/wiki.png";
		$wgMaxCredits = -1;
		$wgHideInterlangageLinks = !$this->interwiki;
		$wgThumbnailScriptPath = $wgSharedThumbnailScriptPath = false;
		$wgEnableParserCache = false;

		$wgUser = new User;
		$wgUser->setOption( 'skin', 'htmldump' );
		$wgUser->setOption( 'editsection', 0 );

		$this->sharedStaticPath = "$wgUploadDirectory/shared";

	}

	/** Reads the content of a title object, executes the skin and captures the result */
	function getArticleHTML( &$title ) {
		global $wgOut, $wgTitle, $wgArticle, $wgUser, $wgUseCategoryMagic;
		
		$wgOut = new OutputPage;
		$wgOut->setParserOptions( new ParserOptions );
		
		$wgTitle =& $title;
		if ( is_null( $wgTitle ) ) {
			return false;
		}
		
		$ns = $wgTitle->getNamespace();
		if ( $ns == NS_SPECIAL ) {
			SpecialPage::executePath( $wgTitle );
		} else {
			if ( $ns == NS_IMAGE ) {
				$wgArticle = new ImagePage( $wgTitle );
			} elseif ( $wgUseCategoryMagic && $ns == NS_CATEGORY ) {
				$wgArticle = new CategoryPage( $wgTitle );
			} else {
				$wgArticle = new Article( $wgTitle );
			}
			$wgArticle->view();
		}

		$sk =& $wgUser->getSkin();
		ob_start();
		$sk->outputPage( $wgOut );
		$text = ob_get_contents();
		ob_end_clean();

		return $text;
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
	 * Copy images (or create symlinks) from commons to a static directory.
	 * This is necessary even if you intend to distribute all of commons, because
	 * the directory contents is used to work out which image description pages
	 * are needed.
	 */
	function copyImages( $images ) {
		global $wgSharedUploadPath, $wgSharedUploadDirectory;
		# Find shared uploads and copy them into the static directory
		$sharedPathLength = strlen( $wgSharedUploadPath ); 
		foreach ( $images as $image => $dummy ) {
			# Is it shared?
			if ( substr( $image, 0, $sharedPathLength ) == $wgSharedUploadPath ) {
				# Reconstruct full filename
				$rel = substr( $image, $sharedPathLength + 1 ); // +1 for slash
				$sourceLoc = "$wgSharedUploadDirectory/$rel";
				$staticLoc = "{$this->sharedStaticPath}/$rel";
				#print "Copying $sourceLoc to $staticLoc\n";
				# Copy to static directory
				if ( !file_exists( $staticLoc ) ) {
					wfMkdirParents( dirname( $staticLoc ), 0755 );
					if ( function_exists( 'symlink' ) ) {
						symlink( $staticLoc, $sourceLoc );
					} else {
						copy( $sourceLoc, $staticLoc );
					}
				}

				if ( substr( $rel, 0, 6 ) == 'thumb/' ) {
					# That was a thumbnail
					# We will also copy the real image
					$parts = explode( '/', $rel );
					$rel = "{$parts[1]}/{$parts[2]}/{$parts[3]}";
					$sourceLoc = "$wgSharedUploadDirectory/$rel";
					$staticLoc = "{$this->sharedStaticPath}/$rel";
					#print "Copying $sourceLoc to $staticLoc\n";
					if ( !file_exists( $staticLoc ) ) {
						wfMkdirParents( dirname( $staticLoc ), 0755 );
						if ( function_exists( 'symlink' ) ) {
							symlink( $staticLoc, $sourceLoc );
						} else {
							copy( $sourceLoc, $staticLoc );
						}
					}
				}
			}
		}
	}
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
