<?php

/**
 * Although marked as a stub, can work independently.
 *
 * @group Database
 * @group Parser
 * @group Stub
 *
 * @todo covers tags
 */
class NewParserTest extends MediaWikiTestCase {
	static protected $articles = array(); // Array of test articles defined by the tests
	/* The data provider is run on a different instance than the test, so it must be static
	 * When running tests from several files, all tests will see all articles.
	 */
	static protected $backendToUse;

	public $keepUploads = false;
	public $runDisabled = false;
	public $runParsoid = false;
	public $regex = '';
	public $showProgress = true;
	public $savedWeirdGlobals = array();
	public $savedGlobals = array();
	public $hooks = array();
	public $functionHooks = array();

	//Fuzz test
	public $maxFuzzTestLength = 300;
	public $fuzzSeed = 0;
	public $memoryLimit = 50;

	protected $file = false;

	public static function setUpBeforeClass() {
		// Inject ParserTest well-known interwikis
		ParserTest::setupInterwikis();
	}

	protected function setUp() {
		global $wgNamespaceAliases, $wgContLang;
		global $wgHooks, $IP;

		parent::setUp();

		//Setup CLI arguments
		if ( $this->getCliArg( 'regex=' ) ) {
			$this->regex = $this->getCliArg( 'regex=' );
		} else {
			# Matches anything
			$this->regex = '';
		}

		$this->keepUploads = $this->getCliArg( 'keep-uploads' );

		$tmpGlobals = array();

		$tmpGlobals['wgLanguageCode'] = 'en';
		$tmpGlobals['wgContLang'] = Language::factory( 'en' );
		$tmpGlobals['wgSitename'] = 'MediaWiki';
		$tmpGlobals['wgServer'] = 'http://example.org';
		$tmpGlobals['wgScript'] = '/index.php';
		$tmpGlobals['wgScriptPath'] = '/';
		$tmpGlobals['wgArticlePath'] = '/wiki/$1';
		$tmpGlobals['wgActionPaths'] = array();
		$tmpGlobals['wgVariantArticlePath'] = false;
		$tmpGlobals['wgExtensionAssetsPath'] = '/extensions';
		$tmpGlobals['wgStylePath'] = '/skins';
		$tmpGlobals['wgEnableUploads'] = true;
		$tmpGlobals['wgThumbnailScriptPath'] = false;
		$tmpGlobals['wgLocalFileRepo'] = array(
			'class' => 'LocalRepo',
			'name' => 'local',
			'url' => 'http://example.com/images',
			'hashLevels' => 2,
			'transformVia404' => false,
			'backend' => 'local-backend'
		);
		$tmpGlobals['wgForeignFileRepos'] = array();
		$tmpGlobals['wgDefaultExternalStore'] = array();
		$tmpGlobals['wgEnableParserCache'] = false;
		$tmpGlobals['wgCapitalLinks'] = true;
		$tmpGlobals['wgNoFollowLinks'] = true;
		$tmpGlobals['wgNoFollowDomainExceptions'] = array();
		$tmpGlobals['wgExternalLinkTarget'] = false;
		$tmpGlobals['wgThumbnailScriptPath'] = false;
		$tmpGlobals['wgUseImageResize'] = true;
		$tmpGlobals['wgAllowExternalImages'] = true;
		$tmpGlobals['wgRawHtml'] = false;
		$tmpGlobals['wgUseTidy'] = false;
		$tmpGlobals['wgAlwaysUseTidy'] = false;
		$tmpGlobals['wgWellFormedXml'] = true;
		$tmpGlobals['wgAllowMicrodataAttributes'] = true;
		$tmpGlobals['wgExperimentalHtmlIds'] = false;
		$tmpGlobals['wgAdaptiveMessageCache'] = true;
		$tmpGlobals['wgUseDatabaseMessages'] = true;
		$tmpGlobals['wgLocaltimezone'] = 'UTC';
		$tmpGlobals['wgDeferredUpdateList'] = array();
		$tmpGlobals['wgGroupPermissions'] = array(
			'*' => array(
				'createaccount' => true,
				'read' => true,
				'edit' => true,
				'createpage' => true,
				'createtalk' => true,
		) );
		$tmpGlobals['wgNamespaceProtection'] = array( NS_MEDIAWIKI => 'editinterface' );

		$tmpGlobals['wgParser'] = new StubObject(
			'wgParser', $GLOBALS['wgParserConf']['class'],
			array( $GLOBALS['wgParserConf'] ) );

		$tmpGlobals['wgFileExtensions'][] = 'svg';
		$tmpGlobals['wgSVGConverter'] = 'rsvg';
		$tmpGlobals['wgSVGConverters']['rsvg'] =
			'$path/rsvg-convert -w $width -h $height $input -o $output';

		if ( $GLOBALS['wgStyleDirectory'] === false ) {
			$tmpGlobals['wgStyleDirectory'] = "$IP/skins";
		}

		# Replace all media handlers with a mock. We do not need to generate
		# actual thumbnails to do parser testing, we only care about receiving
		# a ThumbnailImage properly initialized.
		global $wgMediaHandlers;
		foreach ( $wgMediaHandlers as $type => $handler ) {
			$tmpGlobals['wgMediaHandlers'][$type] = 'MockBitmapHandler';
		}
		// Vector images have to be handled slightly differently
		$tmpGlobals['wgMediaHandlers']['image/svg+xml'] = 'MockSvgHandler';

		$tmpHooks = $wgHooks;
		$tmpHooks['ParserTestParser'][] = 'ParserTestParserHook::setup';
		$tmpHooks['ParserGetVariableValueTs'][] = 'ParserTest::getFakeTimestamp';
		$tmpGlobals['wgHooks'] = $tmpHooks;
		# add a namespace shadowing a interwiki link, to test
		# proper precedence when resolving links. (bug 51680)
		$tmpGlobals['wgExtraNamespaces'] = array( 100 => 'MemoryAlpha' );

		$this->setMwGlobals( $tmpGlobals );

		$this->savedWeirdGlobals['image_alias'] = $wgNamespaceAliases['Image'];
		$this->savedWeirdGlobals['image_talk_alias'] = $wgNamespaceAliases['Image_talk'];

		$wgNamespaceAliases['Image'] = NS_FILE;
		$wgNamespaceAliases['Image_talk'] = NS_FILE_TALK;

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
	}

	protected function tearDown() {
		global $wgNamespaceAliases, $wgContLang;

		$wgNamespaceAliases['Image'] = $this->savedWeirdGlobals['image_alias'];
		$wgNamespaceAliases['Image_talk'] = $this->savedWeirdGlobals['image_talk_alias'];

		// Restore backends
		RepoGroup::destroySingleton();
		FileBackendGroup::destroySingleton();

		// Remove temporary pages from the link cache
		LinkCache::singleton()->clear();

		// Restore message cache (temporary pages and $wgUseDatabaseMessages)
		MessageCache::destroyInstance();

		parent::tearDown();

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
	}

	public static function tearDownAfterClass() {
		ParserTest::tearDownInterwikis();
		parent::tearDownAfterClass();
	}

	function addDBData() {
		$this->tablesUsed[] = 'site_stats';
		# disabled for performance
		#$this->tablesUsed[] = 'image';

		# Update certain things in site_stats
		$this->db->insert( 'site_stats',
			array( 'ss_row_id' => 1, 'ss_images' => 2, 'ss_good_articles' => 1 ),
			__METHOD__
		);

		$user = User::newFromId( 0 );
		LinkCache::singleton()->clear(); # Avoids the odd failure at creating the nullRevision

		# Upload DB table entries for files.
		# We will upload the actual files later. Note that if anything causes LocalFile::load()
		# to be triggered before then, it will break via maybeUpgrade() setting the fileExists
		# member to false and storing it in cache.
		# note that the size/width/height/bits/etc of the file
		# are actually set by inspecting the file itself; the arguments
		# to recordUpload2 have no effect.  That said, we try to make things
		# match up so it is less confusing to readers of the code & tests.
		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Foobar.jpg' ) );
		if ( !$this->db->selectField( 'image', '1', array( 'img_name' => $image->getName() ) ) ) {
			$image->recordUpload2(
				'', // archive name
				'Upload of some lame file',
				'Some lame file',
				array(
					'size' => 7881,
					'width' => 1941,
					'height' => 220,
					'bits' => 8,
					'media_type' => MEDIATYPE_BITMAP,
					'mime' => 'image/jpeg',
					'metadata' => serialize( array() ),
					'sha1' => wfBaseConvert( '1', 16, 36, 31 ),
					'fileExists' => true ),
				$this->db->timestamp( '20010115123500' ), $user
			);
		}

		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Thumb.png' ) );
		if ( !$this->db->selectField( 'image', '1', array( 'img_name' => $image->getName() ) ) ) {
			$image->recordUpload2(
				'', // archive name
				'Upload of some lame thumbnail',
				'Some lame thumbnail',
				array(
					'size' => 22589,
					'width' => 135,
					'height' => 135,
					'bits' => 8,
					'media_type' => MEDIATYPE_BITMAP,
					'mime' => 'image/png',
					'metadata' => serialize( array() ),
					'sha1' => wfBaseConvert( '2', 16, 36, 31 ),
					'fileExists' => true ),
				$this->db->timestamp( '20130225203040' ), $user
			);
		}

		# This image will be blacklisted in [[MediaWiki:Bad image list]]
		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Bad.jpg' ) );
		if ( !$this->db->selectField( 'image', '1', array( 'img_name' => $image->getName() ) ) ) {
			$image->recordUpload2(
				'', // archive name
				'zomgnotcensored',
				'Borderline image',
				array(
					'size' => 12345,
					'width' => 320,
					'height' => 240,
					'bits' => 24,
					'media_type' => MEDIATYPE_BITMAP,
					'mime' => 'image/jpeg',
					'metadata' => serialize( array() ),
					'sha1' => wfBaseConvert( '3', 16, 36, 31 ),
					'fileExists' => true ),
				$this->db->timestamp( '20010115123500' ), $user
			);
		}
		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Foobar.svg' ) );
		if ( !$this->db->selectField( 'image', '1', array( 'img_name' => $image->getName() ) ) ) {
			$image->recordUpload2( '', 'Upload of some lame SVG', 'Some lame SVG', array(
					'size'        => 12345,
					'width'       => 200,
					'height'      => 200,
					'bits'        => 24,
					'media_type'  => MEDIATYPE_DRAWING,
					'mime'        => 'image/svg+xml',
					'metadata'    => serialize( array() ),
					'sha1'        => wfBaseConvert( '', 16, 36, 31 ),
					'fileExists'  => true
			), $this->db->timestamp( '20010115123500' ), $user );
		}
	}

	//ParserTest setup/teardown functions

	/**
	 * Set up the global variables for a consistent environment for each test.
	 * Ideally this should replace the global configuration entirely.
	 */
	protected function setupGlobals( $opts = array(), $config = '' ) {
		global $wgFileBackends;
		# Find out values for some special options.
		$lang =
			self::getOptionValue( 'language', $opts, 'en' );
		$variant =
			self::getOptionValue( 'variant', $opts, false );
		$maxtoclevel =
			self::getOptionValue( 'wgMaxTocLevel', $opts, 999 );
		$linkHolderBatchSize =
			self::getOptionValue( 'wgLinkHolderBatchSize', $opts, 1000 );

		$uploadDir = $this->getUploadDir();
		if ( $this->getCliArg( 'use-filebackend=' ) ) {
			if ( self::$backendToUse ) {
				$backend = self::$backendToUse;
			} else {
				$name = $this->getCliArg( 'use-filebackend=' );
				$useConfig = array();
				foreach ( $wgFileBackends as $conf ) {
					if ( $conf['name'] == $name ) {
						$useConfig = $conf;
					}
				}
				$useConfig['name'] = 'local-backend'; // swap name
				unset( $useConfig['lockManager'] );
				unset( $useConfig['fileJournal'] );
				$class = $useConfig['class'];
				self::$backendToUse = new $class( $useConfig );
				$backend = self::$backendToUse;
			}
		} else {
			# Replace with a mock. We do not care about generating real
			# files on the filesystem, just need to expose the file
			# informations.
			$backend = new MockFileBackend( array(
				'name' => 'local-backend',
				'wikiId' => wfWikiId()
			) );
		}

		$settings = array(
			'wgLocalFileRepo' => array(
				'class' => 'LocalRepo',
				'name' => 'local',
				'url' => 'http://example.com/images',
				'hashLevels' => 2,
				'transformVia404' => false,
				'backend' => $backend
			),
			'wgEnableUploads' => self::getOptionValue( 'wgEnableUploads', $opts, true ),
			'wgLanguageCode' => $lang,
			'wgDBprefix' => $this->db->getType() != 'oracle' ? 'unittest_' : 'ut_',
			'wgRawHtml' => self::getOptionValue( 'wgRawHtml', $opts, false ),
			'wgNamespacesWithSubpages' => array( NS_MAIN => isset( $opts['subpage'] ) ),
			'wgAllowExternalImages' => self::getOptionValue( 'wgAllowExternalImages', $opts, true ),
			'wgMaxTocLevel' => $maxtoclevel,
			'wgUseTeX' => isset( $opts['math'] ) || isset( $opts['texvc'] ),
			'wgMathDirectory' => $uploadDir . '/math',
			'wgDefaultLanguageVariant' => $variant,
			'wgLinkHolderBatchSize' => $linkHolderBatchSize,
		);

		if ( $config ) {
			$configLines = explode( "\n", $config );

			foreach ( $configLines as $line ) {
				list( $var, $value ) = explode( '=', $line, 2 );

				$settings[$var] = eval( "return $value;" ); //???
			}
		}

		$this->savedGlobals = array();

		/** @since 1.20 */
		wfRunHooks( 'ParserTestGlobals', array( &$settings ) );

		$langObj = Language::factory( $lang );
		$settings['wgContLang'] = $langObj;
		$settings['wgLang'] = $langObj;

		$context = new RequestContext();
		$settings['wgOut'] = $context->getOutput();
		$settings['wgUser'] = $context->getUser();
		$settings['wgRequest'] = $context->getRequest();

		foreach ( $settings as $var => $val ) {
			if ( array_key_exists( $var, $GLOBALS ) ) {
				$this->savedGlobals[$var] = $GLOBALS[$var];
			}

			$GLOBALS[$var] = $val;
		}

		MagicWord::clearCache();

		# The entries saved into RepoGroup cache with previous globals will be wrong.
		RepoGroup::destroySingleton();
		FileBackendGroup::destroySingleton();

		# Create dummy files in storage
		$this->setupUploads();

		# Publish the articles after we have the final language set
		$this->publishTestArticles();

		MessageCache::destroyInstance();

		return $context;
	}

	/**
	 * Get an FS upload directory (only applies to FSFileBackend)
	 *
	 * @return String: the directory
	 */
	protected function getUploadDir() {
		if ( $this->keepUploads ) {
			$dir = wfTempDir() . '/mwParser-images';

			if ( is_dir( $dir ) ) {
				return $dir;
			}
		} else {
			$dir = wfTempDir() . "/mwParser-" . mt_rand() . "-images";
		}

		// wfDebug( "Creating upload directory $dir\n" );
		if ( file_exists( $dir ) ) {
			wfDebug( "Already exists!\n" );

			return $dir;
		}

		return $dir;
	}

	/**
	 * Create a dummy uploads directory which will contain a couple
	 * of files in order to pass existence tests.
	 *
	 * @return String: the directory
	 */
	protected function setupUploads() {
		global $IP;

		$base = $this->getBaseDir();
		$backend = RepoGroup::singleton()->getLocalRepo()->getBackend();
		$backend->prepare( array( 'dir' => "$base/local-public/3/3a" ) );
		$backend->store( array(
			'src' => "$IP/skins/monobook/headbg.jpg", 'dst' => "$base/local-public/3/3a/Foobar.jpg"
		) );
		$backend->prepare( array( 'dir' => "$base/local-public/e/ea" ) );
		$backend->store( array(
			'src' => "$IP/skins/monobook/wiki.png", 'dst' => "$base/local-public/e/ea/Thumb.png"
		) );
		$backend->prepare( array( 'dir' => "$base/local-public/0/09" ) );
		$backend->store( array(
			'src' => "$IP/skins/monobook/headbg.jpg", 'dst' => "$base/local-public/0/09/Bad.jpg"
		) );

		// No helpful SVG file to copy, so make one ourselves
		$data = '<?xml version="1.0" encoding="utf-8"?>' .
			'<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><text>Foo</text></svg>';

		$backend->prepare( array( 'dir' => "$base/local-public/f/ff" ) );
		$backend->quickCreate( array(
			'content' => $data, 'dst' => "$base/local-public/f/ff/Foobar.svg"
		) );
	}

	/**
	 * Restore default values and perform any necessary clean-up
	 * after each test runs.
	 */
	protected function teardownGlobals() {
		$this->teardownUploads();

		foreach ( $this->savedGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
		}
	}

	/**
	 * Remove the dummy uploads directory
	 */
	private function teardownUploads() {
		if ( $this->keepUploads ) {
			return;
		}

		$backend = RepoGroup::singleton()->getLocalRepo()->getBackend();
		if ( $backend instanceof MockFileBackend ) {
			# In memory backend, so dont bother cleaning them up.
			return;
		}

		$base = $this->getBaseDir();
		// delete the files first, then the dirs.
		self::deleteFiles(
			array(
				"$base/local-public/3/3a/Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/180px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/200px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/640px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/120px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/1280px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/20px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/270px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/300px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/30px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/360px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/400px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/40px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/70px-Foobar.jpg",
				"$base/local-thumb/3/3a/Foobar.jpg/960px-Foobar.jpg",

				"$base/local-public/e/ea/Thumb.png",

				"$base/local-public/0/09/Bad.jpg",

				"$base/local-public/f/ff/Foobar.svg",
				"$base/local-thumb/f/ff/Foobar.svg/180px-Foobar.svg.jpg",
				"$base/local-thumb/f/ff/Foobar.svg/270px-Foobar.svg.jpg",
				"$base/local-thumb/f/ff/Foobar.svg/360px-Foobar.svg.jpg",
				"$base/local-thumb/f/ff/Foobar.svg/langde-180px-Foobar.svg.jpg",
				"$base/local-thumb/f/ff/Foobar.svg/langde-270px-Foobar.svg.jpg",
				"$base/local-thumb/f/ff/Foobar.svg/langde-360px-Foobar.svg.jpg",

				"$base/local-public/math/f/a/5/fa50b8b616463173474302ca3e63586b.png",
			)
		);
	}

	/**
	 * Delete the specified files, if they exist.
	 * @param $files Array: full paths to files to delete.
	 */
	private static function deleteFiles( $files ) {
		$backend = RepoGroup::singleton()->getLocalRepo()->getBackend();
		foreach ( $files as $file ) {
			$backend->delete( array( 'src' => $file ), array( 'force' => 1 ) );
		}
		foreach ( $files as $file ) {
			$tmp = $file;
			while ( $tmp = FileBackend::parentStoragePath( $tmp ) ) {
				if ( !$backend->clean( array( 'dir' => $tmp ) )->isOK() ) {
					break;
				}
			}
		}
	}

	protected function getBaseDir() {
		return 'mwstore://local-backend';
	}

	public function parserTestProvider() {
		if ( $this->file === false ) {
			global $wgParserTestFiles;
			$this->file = $wgParserTestFiles[0];
		}

		return new TestFileIterator( $this->file, $this );
	}

	/**
	 * Set the file from whose tests will be run by this instance
	 */
	public function setParserTestFile( $filename ) {
		$this->file = $filename;
	}

	/**
	 * @group medium
	 * @dataProvider parserTestProvider
	 */
	public function testParserTest( $desc, $input, $result, $opts, $config ) {
		if ( $this->regex != '' && !preg_match( '/' . $this->regex . '/', $desc ) ) {
			$this->assertTrue( true ); // XXX: don't flood output with "test made no assertions"
			//$this->markTestSkipped( 'Filtered out by the user' );
			return;
		}

		if ( !$this->isWikitextNS( NS_MAIN ) ) {
			// parser tests frequently assume that the main namespace contains wikitext.
			// @todo When setting up pages, force the content model. Only skip if
			//        $wgtContentModelUseDB is false.
			$this->markTestSkipped( "Main namespace does not support wikitext,"
				. "skipping parser test: $desc" );
		}

		wfDebug( "Running parser test: $desc\n" );

		$opts = $this->parseOptions( $opts );
		$context = $this->setupGlobals( $opts, $config );

		$user = $context->getUser();
		$options = ParserOptions::newFromContext( $context );

		if ( isset( $opts['title'] ) ) {
			$titleText = $opts['title'];
		} else {
			$titleText = 'Parser test';
		}

		$local = isset( $opts['local'] );
		$preprocessor = isset( $opts['preprocessor'] ) ? $opts['preprocessor'] : null;
		$parser = $this->getParser( $preprocessor );

		$title = Title::newFromText( $titleText );

		# Parser test requiring math. Make sure texvc is executable
		# or just skip such tests.
		if ( isset( $opts['math'] ) || isset( $opts['texvc'] ) ) {
			global $wgTexvc;

			if ( !isset( $wgTexvc ) ) {
				$this->markTestSkipped( "SKIPPED: \$wgTexvc is not set" );
			} elseif ( !is_executable( $wgTexvc ) ) {
				$this->markTestSkipped( "SKIPPED: texvc binary does not exist"
					. " or is not executable.\n"
					. "Current configuration is:\n\$wgTexvc = '$wgTexvc'" );
			}
		}

		if ( isset( $opts['pst'] ) ) {
			$out = $parser->preSaveTransform( $input, $title, $user, $options );
		} elseif ( isset( $opts['msg'] ) ) {
			$out = $parser->transformMsg( $input, $options, $title );
		} elseif ( isset( $opts['section'] ) ) {
			$section = $opts['section'];
			$out = $parser->getSection( $input, $section );
		} elseif ( isset( $opts['replace'] ) ) {
			$section = $opts['replace'][0];
			$replace = $opts['replace'][1];
			$out = $parser->replaceSection( $input, $section, $replace );
		} elseif ( isset( $opts['comment'] ) ) {
			$out = Linker::formatComment( $input, $title, $local );
		} elseif ( isset( $opts['preload'] ) ) {
			$out = $parser->getPreloadText( $input, $title, $options );
		} else {
			$output = $parser->parse( $input, $title, $options, true, true, 1337 );
			$output->setTOCEnabled( !isset( $opts['notoc'] ) );
			$out = $output->getText();

			if ( isset( $opts['showtitle'] ) ) {
				if ( $output->getTitleText() ) {
					$title = $output->getTitleText();
				}

				$out = "$title\n$out";
			}

			if ( isset( $opts['ill'] ) ) {
				$out = $this->tidy( implode( ' ', $output->getLanguageLinks() ) );
			} elseif ( isset( $opts['cat'] ) ) {
				$outputPage = $context->getOutput();
				$outputPage->addCategoryLinks( $output->getCategories() );
				$cats = $outputPage->getCategoryLinks();

				if ( isset( $cats['normal'] ) ) {
					$out = $this->tidy( implode( ' ', $cats['normal'] ) );
				} else {
					$out = '';
				}
			}
			$parser->mPreprocessor = null;

			$result = $this->tidy( $result );
		}

		$this->teardownGlobals();

		$this->assertEquals( $result, $out, $desc );
	}

	/**
	 * Run a fuzz test series
	 * Draw input from a set of test files
	 *
	 * @todo fixme Needs some work to not eat memory until the world explodes
	 *
	 * @group ParserFuzz
	 */
	public function testFuzzTests() {
		global $wgParserTestFiles;

		$files = $wgParserTestFiles;

		if ( $this->getCliArg( 'file=' ) ) {
			$files = array( $this->getCliArg( 'file=' ) );
		}

		$dict = $this->getFuzzInput( $files );
		$dictSize = strlen( $dict );
		$logMaxLength = log( $this->maxFuzzTestLength );

		ini_set( 'memory_limit', $this->memoryLimit * 1048576 );

		$user = new User;
		$opts = ParserOptions::newFromUser( $user );
		$title = Title::makeTitle( NS_MAIN, 'Parser_test' );

		$id = 1;

		while ( true ) {

			// Generate test input
			mt_srand( ++$this->fuzzSeed );
			$totalLength = mt_rand( 1, $this->maxFuzzTestLength );
			$input = '';

			while ( strlen( $input ) < $totalLength ) {
				$logHairLength = mt_rand( 0, 1000000 ) / 1000000 * $logMaxLength;
				$hairLength = min( intval( exp( $logHairLength ) ), $dictSize );
				$offset = mt_rand( 0, $dictSize - $hairLength );
				$input .= substr( $dict, $offset, $hairLength );
			}

			$this->setupGlobals();
			$parser = $this->getParser();

			// Run the test
			try {
				$parser->parse( $input, $title, $opts );
				$this->assertTrue( true, "Test $id, fuzz seed {$this->fuzzSeed}" );
			} catch ( Exception $exception ) {
				$input_dump = sprintf( "string(%d) \"%s\"\n", strlen( $input ), $input );

				$this->assertTrue( false, "Test $id, fuzz seed {$this->fuzzSeed}. \n\n" .
					"Input: $input_dump\n\nError: {$exception->getMessage()}\n\n" .
					"Backtrace: {$exception->getTraceAsString()}" );
			}

			$this->teardownGlobals();
			$parser->__destruct();

			if ( $id % 100 == 0 ) {
				$usage = intval( memory_get_usage( true ) / $this->memoryLimit / 1048576 * 100 );
				//echo "{$this->fuzzSeed}: $numSuccess/$numTotal (mem: $usage%)\n";
				if ( $usage > 90 ) {
					$ret = "Out of memory:\n";
					$memStats = $this->getMemoryBreakdown();

					foreach ( $memStats as $name => $usage ) {
						$ret .= "$name: $usage\n";
					}

					throw new MWException( $ret );
				}
			}

			$id++;
		}
	}

	//Various getter functions

	/**
	 * Get an input dictionary from a set of parser test files
	 */
	function getFuzzInput( $filenames ) {
		$dict = '';

		foreach ( $filenames as $filename ) {
			$contents = file_get_contents( $filename );
			preg_match_all( '/!!\s*input\n(.*?)\n!!\s*result/s', $contents, $matches );

			foreach ( $matches[1] as $match ) {
				$dict .= $match . "\n";
			}
		}

		return $dict;
	}

	/**
	 * Get a memory usage breakdown
	 */
	function getMemoryBreakdown() {
		$memStats = array();

		foreach ( $GLOBALS as $name => $value ) {
			$memStats['$' . $name] = strlen( serialize( $value ) );
		}

		$classes = get_declared_classes();

		foreach ( $classes as $class ) {
			$rc = new ReflectionClass( $class );
			$props = $rc->getStaticProperties();
			$memStats[$class] = strlen( serialize( $props ) );
			$methods = $rc->getMethods();

			foreach ( $methods as $method ) {
				$memStats[$class] += strlen( serialize( $method->getStaticVariables() ) );
			}
		}

		$functions = get_defined_functions();

		foreach ( $functions['user'] as $function ) {
			$rf = new ReflectionFunction( $function );
			$memStats["$function()"] = strlen( serialize( $rf->getStaticVariables() ) );
		}

		asort( $memStats );

		return $memStats;
	}

	/**
	 * Get a Parser object
	 */
	function getParser( $preprocessor = null ) {
		global $wgParserConf;

		$class = $wgParserConf['class'];
		$parser = new $class( array( 'preprocessorClass' => $preprocessor ) + $wgParserConf );

		wfRunHooks( 'ParserTestParser', array( &$parser ) );

		return $parser;
	}

	//Various action functions

	public function addArticle( $name, $text, $line ) {
		self::$articles[$name] = array( $text, $line );
	}

	public function publishTestArticles() {
		if ( empty( self::$articles ) ) {
			return;
		}

		foreach ( self::$articles as $name => $info ) {
			list( $text, $line ) = $info;
			ParserTest::addArticle( $name, $text, $line, 'ignoreduplicate' );
		}
	}

	/**
	 * Steal a callback function from the primary parser, save it for
	 * application to our scary parser. If the hook is not installed,
	 * abort processing of this file.
	 *
	 * @param $name String
	 * @return Bool true if tag hook is present
	 */
	public function requireHook( $name ) {
		global $wgParser;
		$wgParser->firstCallInit(); // make sure hooks are loaded.
		return isset( $wgParser->mTagHooks[$name] );
	}

	public function requireFunctionHook( $name ) {
		global $wgParser;
		$wgParser->firstCallInit(); // make sure hooks are loaded.
		return isset( $wgParser->mFunctionHooks[$name] );
	}

	//Various "cleanup" functions

	/**
	 * Run the "tidy" command on text if the $wgUseTidy
	 * global is true
	 *
	 * @param $text String: the text to tidy
	 * @return String
	 */
	protected function tidy( $text ) {
		global $wgUseTidy;

		if ( $wgUseTidy ) {
			$text = MWTidy::tidy( $text );
		}

		return $text;
	}

	/**
	 * Remove last character if it is a newline
	 */
	public function removeEndingNewline( $s ) {
		if ( substr( $s, -1 ) === "\n" ) {
			return substr( $s, 0, -1 );
		} else {
			return $s;
		}
	}

	//Test options parser functions

	protected function parseOptions( $instring ) {
		$opts = array();
		// foo
		// foo=bar
		// foo="bar baz"
		// foo=[[bar baz]]
		// foo=bar,"baz quux"
		$regex = '/\b
			([\w-]+)						# Key
			\b
			(?:\s*
				=						# First sub-value
				\s*
				(
					"
						[^"]*			# Quoted val
					"
				|
					\[\[
						[^]]*			# Link target
					\]\]
				|
					[\w-]+				# Plain word
				)
				(?:\s*
					,					# Sub-vals 1..N
					\s*
					(
						"[^"]*"			# Quoted val
					|
						\[\[[^]]*\]\]	# Link target
					|
						[\w-]+			# Plain word
					)
				)*
			)?
			/x';

		if ( preg_match_all( $regex, $instring, $matches, PREG_SET_ORDER ) ) {
			foreach ( $matches as $bits ) {
				array_shift( $bits );
				$key = strtolower( array_shift( $bits ) );
				if ( count( $bits ) == 0 ) {
					$opts[$key] = true;
				} elseif ( count( $bits ) == 1 ) {
					$opts[$key] = $this->cleanupOption( array_shift( $bits ) );
				} else {
					// Array!
					$opts[$key] = array_map( array( $this, 'cleanupOption' ), $bits );
				}
			}
		}

		return $opts;
	}

	protected function cleanupOption( $opt ) {
		if ( substr( $opt, 0, 1 ) == '"' ) {
			return substr( $opt, 1, -1 );
		}

		if ( substr( $opt, 0, 2 ) == '[[' ) {
			return substr( $opt, 2, -2 );
		}

		return $opt;
	}

	/**
	 * Use a regex to find out the value of an option
	 * @param $key String: name of option val to retrieve
	 * @param $opts Options array to look in
	 * @param $default Mixed: default value returned if not found
	 */
	protected static function getOptionValue( $key, $opts, $default ) {
		$key = strtolower( $key );

		if ( isset( $opts[$key] ) ) {
			return $opts[$key];
		} else {
			return $default;
		}
	}
}
