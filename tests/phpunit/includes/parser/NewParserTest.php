<?php

/**
 * @group Database
 * @group Parser
 * @group Stub (can also work independently)
 */
class NewParserTest extends MediaWikiTestCase {

	static protected $articles = array();	// Array of test articles defined by the tests
	/* The dataProvider is run on a different instance than the test, so it must be static
	 * When running tests from several files, all tests will see all articles.
	 */
	
	public $uploadDir;
	public $keepUploads = false;
	public $runDisabled = false;
	public $regex = '';
	public $showProgress = true;
	public $savedInitialGlobals = array();
	public $savedWeirdGlobals = array();
	public $savedGlobals = array();
	public $hooks = array();
	public $functionHooks = array();

	//Fuzz test
	public $maxFuzzTestLength = 300;
	public $fuzzSeed = 0;
	public $memoryLimit = 50;

	protected $file = false;
	
	/*function __construct($a = null,$b = array(),$c = null ) {
		parent::__construct($a,$b,$c);
	}*/
	
	function setUp() {
		global $wgContLang, $wgNamespaceProtection, $wgNamespaceAliases;
		global $wgHooks, $IP;
		$wgContLang = Language::factory( 'en' );
		
		//Setup CLI arguments
		if ( $this->getCliArg( 'regex=' ) ) {
			$this->regex = $this->getCliArg( 'regex=' );
		} else {
			# Matches anything
			$this->regex = '';
		}
		
		$this->keepUploads = $this->getCliArg( 'keep-uploads' );
				
		$tmpGlobals = array();
		
		$tmpGlobals['wgScript'] = '/index.php';
		$tmpGlobals['wgScriptPath'] = '/';
		$tmpGlobals['wgArticlePath'] = '/wiki/$1';
		$tmpGlobals['wgStyleSheetPath'] = '/skins';
		$tmpGlobals['wgStylePath'] = '/skins';
		$tmpGlobals['wgThumbnailScriptPath'] = false;
		$tmpGlobals['wgLocalFileRepo'] = array(
			'class' => 'LocalRepo',
			'name' => 'local',
			'directory' => wfTempDir() . '/test-repo',
			'url' => 'http://example.com/images',
			'deletedDir' => wfTempDir() . '/test-repo/delete',
			'hashLevels' => 2,
			'transformVia404' => false,
		);
		
		$tmpGlobals['wgEnableParserCache'] = false;
		$tmpGlobals['wgHooks'] = $wgHooks;
		$tmpGlobals['wgDeferredUpdateList'] = array();
		$tmpGlobals['wgMemc'] = wfGetMainCache();
		$tmpGlobals['messageMemc'] = wfGetMessageCacheStorage();
		$tmpGlobals['parserMemc'] = wfGetParserCacheStorage();

		// $tmpGlobals['wgContLang'] = new StubContLang;
		$tmpGlobals['wgUser'] = new User;
		$tmpGlobals['wgLang'] = new StubUserLang;
		$tmpGlobals['wgOut'] = new StubObject( 'wgOut', 'OutputPage' );
		$tmpGlobals['wgParser'] = new StubObject( 'wgParser', $GLOBALS['wgParserConf']['class'], array( $GLOBALS['wgParserConf'] ) );
		$tmpGlobals['wgRequest'] = new WebRequest;

		if ( $GLOBALS['wgStyleDirectory'] === false ) {
			$tmpGlobals['wgStyleDirectory'] = "$IP/skins";
		}
		
		
		foreach ( $tmpGlobals as $var => $val ) {
			if ( array_key_exists( $var, $GLOBALS ) ) {
				$this->savedInitialGlobals[$var] = $GLOBALS[$var];
			}

			$GLOBALS[$var] = $val;
		}
		
		$this->savedWeirdGlobals['mw_namespace_protection'] = $wgNamespaceProtection[NS_MEDIAWIKI];
		$this->savedWeirdGlobals['image_alias'] = $wgNamespaceAliases['Image'];
		$this->savedWeirdGlobals['image_talk_alias'] = $wgNamespaceAliases['Image_talk'];
		
		$wgNamespaceProtection[NS_MEDIAWIKI] = 'editinterface';
		$wgNamespaceAliases['Image'] = NS_FILE;
		$wgNamespaceAliases['Image_talk'] = NS_FILE_TALK;
		
	}
	
	public function tearDown() {
	
		foreach ( $this->savedInitialGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
		}
		
		global $wgNamespaceProtection, $wgNamespaceAliases;
		
		$wgNamespaceProtection[NS_MEDIAWIKI] = $this->savedWeirdGlobals['mw_namespace_protection'];
		$wgNamespaceAliases['Image'] = $this->savedWeirdGlobals['image_alias'];
		$wgNamespaceAliases['Image_talk'] = $this->savedWeirdGlobals['image_talk_alias'];
	}
	
	function addDBData() {
		# Hack: insert a few Wikipedia in-project interwiki prefixes,
		# for testing inter-language links
		$this->db->insert( 'interwiki', array(
			array( 'iw_prefix' => 'wikipedia',
				   'iw_url'    => 'http://en.wikipedia.org/wiki/$1',
				   'iw_api'    => '',
				   'iw_wikiid' => '',
				   'iw_local'  => 0 ),
			array( 'iw_prefix' => 'meatball',
				   'iw_url'    => 'http://www.usemod.com/cgi-bin/mb.pl?$1',
				   'iw_api'    => '',
				   'iw_wikiid' => '',
				   'iw_local'  => 0 ),
			array( 'iw_prefix' => 'zh',
				   'iw_url'    => 'http://zh.wikipedia.org/wiki/$1',
				   'iw_api'    => '',
				   'iw_wikiid' => '',
				   'iw_local'  => 1 ),
			array( 'iw_prefix' => 'es',
				   'iw_url'    => 'http://es.wikipedia.org/wiki/$1',
				   'iw_api'    => '',
				   'iw_wikiid' => '',
				   'iw_local'  => 1 ),
			array( 'iw_prefix' => 'fr',
				   'iw_url'    => 'http://fr.wikipedia.org/wiki/$1',
				   'iw_api'    => '',
				   'iw_wikiid' => '',
				   'iw_local'  => 1 ),
			array( 'iw_prefix' => 'ru',
				   'iw_url'    => 'http://ru.wikipedia.org/wiki/$1',
				   'iw_api'    => '',
				   'iw_wikiid' => '',
				   'iw_local'  => 1 ),
			) );


		# Update certain things in site_stats
		$this->db->insert( 'site_stats', array( 'ss_row_id' => 1, 'ss_images' => 2, 'ss_good_articles' => 1 ) );

		# Reinitialise the LocalisationCache to match the database state
		Language::getLocalisationCache()->unloadAll();

		# Clear the message cache
		MessageCache::singleton()->clear();

		$this->uploadDir = $this->setupUploadDir();

		$user = User::newFromId( 0 );
		LinkCache::singleton()->clear(); # Avoids the odd failure at creating the nullRevision
		
		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Foobar.jpg' ) );
		$image->recordUpload2( '', 'Upload of some lame file', 'Some lame file', array(
			'size'        => 12345,
			'width'       => 1941,
			'height'      => 220,
			'bits'        => 24,
			'media_type'  => MEDIATYPE_BITMAP,
			'mime'        => 'image/jpeg',
			'metadata'    => serialize( array() ),
			'sha1'        => wfBaseConvert( '', 16, 36, 31 ),
			'fileExists'  => true
			), $this->db->timestamp( '20010115123500' ), $user );

		# This image will be blacklisted in [[MediaWiki:Bad image list]]
		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Bad.jpg' ) );
		$image->recordUpload2( '', 'zomgnotcensored', 'Borderline image', array(
			'size'        => 12345,
			'width'       => 320,
			'height'      => 240,
			'bits'        => 24,
			'media_type'  => MEDIATYPE_BITMAP,
			'mime'        => 'image/jpeg',
			'metadata'    => serialize( array() ),
			'sha1'        => wfBaseConvert( '', 16, 36, 31 ),
			'fileExists'  => true
			), $this->db->timestamp( '20010115123500' ), $user );

	}
	
	
	
	
	//ParserTest setup/teardown functions
	
	/**
	 * Set up the global variables for a consistent environment for each test.
	 * Ideally this should replace the global configuration entirely.
	 */
	protected function setupGlobals( $opts = '', $config = '' ) {
		# Find out values for some special options.
		$lang =
			self::getOptionValue( 'language', $opts, 'en' );
		$variant =
			self::getOptionValue( 'variant', $opts, false );
		$maxtoclevel =
			self::getOptionValue( 'wgMaxTocLevel', $opts, 999 );
		$linkHolderBatchSize =
			self::getOptionValue( 'wgLinkHolderBatchSize', $opts, 1000 );

		$settings = array(
			'wgServer' => 'http://Britney-Spears',
			'wgScript' => '/index.php',
			'wgScriptPath' => '/',
			'wgArticlePath' => '/wiki/$1',
			'wgActionPaths' => array(),
			'wgLocalFileRepo' => array(
				'class' => 'LocalRepo',
				'name' => 'local',
				'directory' => $this->uploadDir,
				'url' => 'http://example.com/images',
				'hashLevels' => 2,
				'transformVia404' => false,
			),
			'wgEnableUploads' => self::getOptionValue( 'wgEnableUploads', $opts, true ),
			'wgStylePath' => '/skins',
			'wgStyleSheetPath' => '/skins',
			'wgSitename' => 'MediaWiki',
			'wgLanguageCode' => $lang,
			'wgDBprefix' => $this->db->getType() != 'oracle' ? 'unittest_' : 'ut_',
			'wgRawHtml' => isset( $opts['rawhtml'] ),
			'wgLang' => null,
			'wgContLang' => null,
			'wgNamespacesWithSubpages' => array( 0 => isset( $opts['subpage'] ) ),
			'wgMaxTocLevel' => $maxtoclevel,
			'wgCapitalLinks' => true,
			'wgNoFollowLinks' => true,
			'wgNoFollowDomainExceptions' => array(),
			'wgThumbnailScriptPath' => false,
			'wgUseImageResize' => false,
			'wgUseTeX' => isset( $opts['math'] ),
			'wgMathDirectory' => $this->uploadDir . '/math',
			'wgLocaltimezone' => 'UTC',
			'wgAllowExternalImages' => true,
			'wgUseTidy' => false,
			'wgDefaultLanguageVariant' => $variant,
			'wgVariantArticlePath' => false,
			'wgGroupPermissions' => array( '*' => array(
				'createaccount' => true,
				'read'          => true,
				'edit'          => true,
				'createpage'    => true,
				'createtalk'    => true,
			) ),
			'wgNamespaceProtection' => array( NS_MEDIAWIKI => 'editinterface' ),
			'wgDefaultExternalStore' => array(),
			'wgForeignFileRepos' => array(),
			'wgLinkHolderBatchSize' => $linkHolderBatchSize,
			'wgExperimentalHtmlIds' => false,
			'wgExternalLinkTarget' => false,
			'wgAlwaysUseTidy' => false,
			'wgHtml5' => true,
			'wgWellFormedXml' => true,
			'wgAllowMicrodataAttributes' => true,
			'wgAdaptiveMessageCache' => true,
			'wgUseDatabaseMessages' => true,
		);

		if ( $config ) {
			$configLines = explode( "\n", $config );

			foreach ( $configLines as $line ) {
				list( $var, $value ) = explode( '=', $line, 2 );

				$settings[$var] = eval( "return $value;" ); //???
			}
		}

		$this->savedGlobals = array();

		foreach ( $settings as $var => $val ) {
			if ( array_key_exists( $var, $GLOBALS ) ) {
				$this->savedGlobals[$var] = $GLOBALS[$var];
			}

			$GLOBALS[$var] = $val;
		}

		$langObj = Language::factory( $lang );
		$GLOBALS['wgLang'] = $langObj;
		$GLOBALS['wgContLang'] = $langObj;
		$GLOBALS['wgMemc'] = new EmptyBagOStuff;
		$GLOBALS['wgOut'] = new OutputPage;

		global $wgHooks;

		$wgHooks['ParserTestParser'][] = 'ParserTestParserHook::setup';
		$wgHooks['ParserTestParser'][] = 'ParserTestStaticParserHook::setup';
		$wgHooks['ParserGetVariableValueTs'][] = 'ParserTest::getFakeTimestamp';

		MagicWord::clearCache();

		# Publish the articles after we have the final language set
		$this->publishTestArticles();

		# The entries saved into RepoGroup cache with previous globals will be wrong.
		RepoGroup::destroySingleton();
		MessageCache::singleton()->destroyInstance();
		
		global $wgUser;
		$wgUser = new User();
	}
	
	/**
	 * Restore default values and perform any necessary clean-up
	 * after each test runs.
	 */
	protected function teardownGlobals() {
		RepoGroup::destroySingleton();
		LinkCache::singleton()->clear();

		foreach ( $this->savedGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
		}
	}
	
	/**
	 * Create a dummy uploads directory which will contain a couple
	 * of files in order to pass existence tests.
	 *
	 * @return String: the directory
	 */
	protected function setupUploadDir() {
		global $IP;

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

		wfMkdirParents( $dir . '/3/3a' );
		copy( "$IP/skins/monobook/headbg.jpg", "$dir/3/3a/Foobar.jpg" );
		wfMkdirParents( $dir . '/0/09' );
		copy( "$IP/skins/monobook/headbg.jpg", "$dir/0/09/Bad.jpg" );

		return $dir;
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
	
	/** @dataProvider parserTestProvider */
	public function testParserTest( $desc, $input, $result, $opts, $config ) {
		if ( !preg_match( '/' . $this->regex . '/', $desc ) ) return; //$this->markTestSkipped( 'Filtered out by the user' );
		$opts = $this->parseOptions( $opts );
		$this->setupGlobals( $opts, $config );

		$user = new User();
		$options = ParserOptions::newFromUser( $user );

		if ( isset( $opts['title'] ) ) {
			$titleText = $opts['title'];
		}
		else {
			$titleText = 'Parser test';
		}

		$local = isset( $opts['local'] );
		$preprocessor = isset( $opts['preprocessor'] ) ? $opts['preprocessor'] : null;
		$parser = $this->getParser( $preprocessor );
		
		$title = Title::newFromText( $titleText );

		if ( isset( $opts['pst'] ) ) {
			$out = $parser->preSaveTransform( $input, $title, $user, $options );
		} elseif ( isset( $opts['msg'] ) ) {
			$out = $parser->transformMsg( $input, $options );
		} elseif ( isset( $opts['section'] ) ) {
			$section = $opts['section'];
			$out = $parser->getSection( $input, $section );
		} elseif ( isset( $opts['replace'] ) ) {
			$section = $opts['replace'][0];
			$replace = $opts['replace'][1];
			$out = $parser->replaceSection( $input, $section, $replace );
		} elseif ( isset( $opts['comment'] ) ) {
			$linker = $user->getSkin();
			$out = $linker->formatComment( $input, $title, $local );
		} elseif ( isset( $opts['preload'] ) ) {
			$out = $parser->getpreloadText( $input, $title, $options );
		} else {
			$output = $parser->parse( $input, $title, $options, true, true, 1337 );
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
				global $wgOut;

				$wgOut->addCategoryLinks( $output->getCategories() );
				$cats = $wgOut->getCategoryLinks();

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
	 */
	function testFuzzTests() {
		
		$this->markTestIncomplete( 'Breaks tesla due to memory restrictions' );
		
		global $wgParserTestFiles;
		
		$files = $wgParserTestFiles;
		
		if( $this->getCliArg( 'file=' ) ) {
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
				
				$this->assertTrue( false, "Test $id, fuzz seed {$this->fuzzSeed}. \n\nInput: $input_dump\n\nError: {$exception->getMessage()}\n\nBacktrace: {$exception->getTraceAsString()}" );
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
		global $wgParserConf, $wgHooks;

		$class = $wgParserConf['class'];
		$parser = new $class( array( 'preprocessorClass' => $preprocessor ) + $wgParserConf );

		wfRunHooks( 'ParserTestParser', array( &$parser ) );

		return $parser;
	}

	//Various action functions

	public function addArticle( $name, $text, $line ) {
		self::$articles[$name] = $text;
	}	
	
	public function publishTestArticles() {
		if ( empty( self::$articles ) ) {
			return;
		}

		foreach ( self::$articles as $name => $text ) {
			$title = Title::newFromText( $name );

			if ( $title->getArticleID( Title::GAID_FOR_UPDATE ) == 0 ) {
				ParserTest::addArticle( $name, $text );
			}
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
		$wgParser->firstCallInit( ); // make sure hooks are loaded.
		return isset( $wgParser->mTagHooks[$name] );
	}

	public function requireFunctionHook( $name ) {
		global $wgParser;
		$wgParser->firstCallInit( ); // make sure hooks are loaded.
		return isset( $wgParser->mFunctionHooks[$name] );
	}
	//Various "cleanup" functions
	
	/*
	 * Run the "tidy" command on text if the $wgUseTidy
	 * global is true
	 *
	 * @param $text String: the text to tidy
	 * @return String
	 * @static
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
		}
		else {
			return $s;
		}
	}

	public function showRunFile( $file ) {
		/* NOP */
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
