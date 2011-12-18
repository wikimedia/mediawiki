<?php
/**
 * Internationalisation code
 *
 * @file
 * @ingroup Language
 */

/**
 * @defgroup Language Language
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This file is part of MediaWiki, it is not a valid entry point.\n";
	exit( 1 );
}

# Read language names
global $wgLanguageNames;
require_once( dirname( __FILE__ ) . '/Names.php' );

if ( function_exists( 'mb_strtoupper' ) ) {
	mb_internal_encoding( 'UTF-8' );
}

/**
 * a fake language converter
 *
 * @ingroup Language
 */
class FakeConverter {
	var $mLang;
	function __construct( $langobj ) { $this->mLang = $langobj; }
	function autoConvertToAllVariants( $text ) { return array( $this->mLang->getCode() => $text ); }
	function convert( $t ) { return $t; }
	function convertTitle( $t ) { return $t->getPrefixedText(); }
	function getVariants() { return array( $this->mLang->getCode() ); }
	function getPreferredVariant() { return $this->mLang->getCode(); }
	function getDefaultVariant() { return $this->mLang->getCode(); }
	function getURLVariant() { return ''; }
	function getConvRuleTitle() { return false; }
	function findVariantLink( &$l, &$n, $ignoreOtherCond = false ) { }
	function getExtraHashOptions() { return ''; }
	function getParsedTitle() { return ''; }
	function markNoConversion( $text, $noParse = false ) { return $text; }
	function convertCategoryKey( $key ) { return $key; }
	function convertLinkToAllVariants( $text ) { return $this->autoConvertToAllVariants( $text ); }
	function armourMath( $text ) { return $text; }
}

/**
 * Internationalisation code
 * @ingroup Language
 */
class Language {

	/**
	 * @var LanguageConverter
	 */
	var $mConverter;

	var $mVariants, $mCode, $mLoaded = false;
	var $mMagicExtensions = array(), $mMagicHookDone = false;
	private $mHtmlCode = null;

	var $mNamespaceIds, $namespaceNames, $namespaceAliases;
	var $dateFormatStrings = array();
	var $mExtendedSpecialPageAliases;

	/**
	 * ReplacementArray object caches
	 */
	var $transformData = array();

	/**
	 * @var LocalisationCache
	 */
	static public $dataCache;

	static public $mLangObjCache = array();

	static public $mWeekdayMsgs = array(
		'sunday', 'monday', 'tuesday', 'wednesday', 'thursday',
		'friday', 'saturday'
	);

	static public $mWeekdayAbbrevMsgs = array(
		'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'
	);

	static public $mMonthMsgs = array(
		'january', 'february', 'march', 'april', 'may_long', 'june',
		'july', 'august', 'september', 'october', 'november',
		'december'
	);
	static public $mMonthGenMsgs = array(
		'january-gen', 'february-gen', 'march-gen', 'april-gen', 'may-gen', 'june-gen',
		'july-gen', 'august-gen', 'september-gen', 'october-gen', 'november-gen',
		'december-gen'
	);
	static public $mMonthAbbrevMsgs = array(
		'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug',
		'sep', 'oct', 'nov', 'dec'
	);

	static public $mIranianCalendarMonthMsgs = array(
		'iranian-calendar-m1', 'iranian-calendar-m2', 'iranian-calendar-m3',
		'iranian-calendar-m4', 'iranian-calendar-m5', 'iranian-calendar-m6',
		'iranian-calendar-m7', 'iranian-calendar-m8', 'iranian-calendar-m9',
		'iranian-calendar-m10', 'iranian-calendar-m11', 'iranian-calendar-m12'
	);

	static public $mHebrewCalendarMonthMsgs = array(
		'hebrew-calendar-m1', 'hebrew-calendar-m2', 'hebrew-calendar-m3',
		'hebrew-calendar-m4', 'hebrew-calendar-m5', 'hebrew-calendar-m6',
		'hebrew-calendar-m7', 'hebrew-calendar-m8', 'hebrew-calendar-m9',
		'hebrew-calendar-m10', 'hebrew-calendar-m11', 'hebrew-calendar-m12',
		'hebrew-calendar-m6a', 'hebrew-calendar-m6b'
	);

	static public $mHebrewCalendarMonthGenMsgs = array(
		'hebrew-calendar-m1-gen', 'hebrew-calendar-m2-gen', 'hebrew-calendar-m3-gen',
		'hebrew-calendar-m4-gen', 'hebrew-calendar-m5-gen', 'hebrew-calendar-m6-gen',
		'hebrew-calendar-m7-gen', 'hebrew-calendar-m8-gen', 'hebrew-calendar-m9-gen',
		'hebrew-calendar-m10-gen', 'hebrew-calendar-m11-gen', 'hebrew-calendar-m12-gen',
		'hebrew-calendar-m6a-gen', 'hebrew-calendar-m6b-gen'
	);

	static public $mHijriCalendarMonthMsgs = array(
		'hijri-calendar-m1', 'hijri-calendar-m2', 'hijri-calendar-m3',
		'hijri-calendar-m4', 'hijri-calendar-m5', 'hijri-calendar-m6',
		'hijri-calendar-m7', 'hijri-calendar-m8', 'hijri-calendar-m9',
		'hijri-calendar-m10', 'hijri-calendar-m11', 'hijri-calendar-m12'
	);

	/**
	 * Get a cached language object for a given language code
	 * @param $code String
	 * @return Language
	 */
	static function factory( $code ) {
		if ( !isset( self::$mLangObjCache[$code] ) ) {
			if ( count( self::$mLangObjCache ) > 10 ) {
				// Don't keep a billion objects around, that's stupid.
				self::$mLangObjCache = array();
			}
			self::$mLangObjCache[$code] = self::newFromCode( $code );
		}
		return self::$mLangObjCache[$code];
	}

	/**
	 * Create a language object for a given language code
	 * @param $code String
	 * @return Language
	 */
	protected static function newFromCode( $code ) {
		// Protect against path traversal below
		if ( !Language::isValidCode( $code )
			|| strcspn( $code, ":/\\\000" ) !== strlen( $code ) )
		{
			throw new MWException( "Invalid language code \"$code\"" );
		}

		if ( !Language::isValidBuiltInCode( $code ) ) {
			// It's not possible to customise this code with class files, so
			// just return a Language object. This is to support uselang= hacks.
			$lang = new Language;
			$lang->setCode( $code );
			return $lang;
		}

		// Check if there is a language class for the code
		$class = self::classFromCode( $code );
		self::preloadLanguageClass( $class );
		if ( MWInit::classExists( $class ) ) {
			$lang = new $class;
			return $lang;
		}

		// Keep trying the fallback list until we find an existing class
		$fallbacks = Language::getFallbacksFor( $code );
		foreach ( $fallbacks as $fallbackCode ) {
			if ( !Language::isValidBuiltInCode( $fallbackCode ) ) {
				throw new MWException( "Invalid fallback '$fallbackCode' in fallback sequence for '$code'" );
			}

			$class = self::classFromCode( $fallbackCode );
			self::preloadLanguageClass( $class );
			if ( MWInit::classExists( $class ) ) {
				$lang = Language::newFromCode( $fallbackCode );
				$lang->setCode( $code );
				return $lang;
			}
		}

		throw new MWException( "Invalid fallback sequence for language '$code'" );
	}

	/**
	 * Returns true if a language code string is of a valid form, whether or
	 * not it exists. This includes codes which are used solely for
	 * customisation via the MediaWiki namespace.
	 *
	 * @param $code string
	 *
	 * @return bool
	 */
	public static function isValidCode( $code ) {
		return
			strcspn( $code, ":/\\\000" ) === strlen( $code )
			&& !preg_match( Title::getTitleInvalidRegex(), $code );
	}

	/**
	 * Returns true if a language code is of a valid form for the purposes of
	 * internal customisation of MediaWiki, via Messages*.php.
	 *
	 * @param $code string
	 *
	 * @since 1.18
	 * @return bool
	 */
	public static function isValidBuiltInCode( $code ) {
		return preg_match( '/^[a-z0-9-]+$/i', $code );
	}

	/**
	 * @param $code
	 * @return String Name of the language class
	 */
	public static function classFromCode( $code ) {
		if ( $code == 'en' ) {
			return 'Language';
		} else {
			return 'Language' . str_replace( '-', '_', ucfirst( $code ) );
		}
	}

	/**
	 * Includes language class files
	 *
	 * @param $class string Name of the language class
	 */
	public static function preloadLanguageClass( $class ) {
		global $IP;

		if ( $class === 'Language' ) {
			return;
		}

		if ( !defined( 'MW_COMPILED' ) ) {
			// Preload base classes to work around APC/PHP5 bug
			if ( file_exists( "$IP/languages/classes/$class.deps.php" ) ) {
				include_once( "$IP/languages/classes/$class.deps.php" );
			}
			if ( file_exists( "$IP/languages/classes/$class.php" ) ) {
				include_once( "$IP/languages/classes/$class.php" );
			}
		}
	}

	/**
	 * Get the LocalisationCache instance
	 *
	 * @return LocalisationCache
	 */
	public static function getLocalisationCache() {
		if ( is_null( self::$dataCache ) ) {
			global $wgLocalisationCacheConf;
			$class = $wgLocalisationCacheConf['class'];
			self::$dataCache = new $class( $wgLocalisationCacheConf );
		}
		return self::$dataCache;
	}

	function __construct() {
		$this->mConverter = new FakeConverter( $this );
		// Set the code to the name of the descendant
		if ( get_class( $this ) == 'Language' ) {
			$this->mCode = 'en';
		} else {
			$this->mCode = str_replace( '_', '-', strtolower( substr( get_class( $this ), 8 ) ) );
		}
		self::getLocalisationCache();
	}

	/**
	 * Reduce memory usage
	 */
	function __destruct() {
		foreach ( $this as $name => $value ) {
			unset( $this->$name );
		}
	}

	/**
	 * Hook which will be called if this is the content language.
	 * Descendants can use this to register hook functions or modify globals
	 */
	function initContLang() { }

	/**
	 * Same as getFallbacksFor for current language.
	 * @return array|bool
	 * @deprecated in 1.19
	 */
	function getFallbackLanguageCode() {
		wfDeprecated( __METHOD__ );
		return self::getFallbackFor( $this->mCode );
	}

	/**
	 * @return array
	 * @since 1.19
	 */
	function getFallbackLanguages() {
		return self::getFallbacksFor( $this->mCode );
	}

	/**
	 * Exports $wgBookstoreListEn
	 * @return array
	 */
	function getBookstoreList() {
		return self::$dataCache->getItem( $this->mCode, 'bookstoreList' );
	}

	/**
	 * @return array
	 */
	function getNamespaces() {
		if ( is_null( $this->namespaceNames ) ) {
			global $wgMetaNamespace, $wgMetaNamespaceTalk, $wgExtraNamespaces;

			$this->namespaceNames = self::$dataCache->getItem( $this->mCode, 'namespaceNames' );
			$validNamespaces = MWNamespace::getCanonicalNamespaces();

			$this->namespaceNames = $wgExtraNamespaces + $this->namespaceNames + $validNamespaces;

			$this->namespaceNames[NS_PROJECT] = $wgMetaNamespace;
			if ( $wgMetaNamespaceTalk ) {
				$this->namespaceNames[NS_PROJECT_TALK] = $wgMetaNamespaceTalk;
			} else {
				$talk = $this->namespaceNames[NS_PROJECT_TALK];
				$this->namespaceNames[NS_PROJECT_TALK] =
					$this->fixVariableInNamespace( $talk );
			}

			# Sometimes a language will be localised but not actually exist on this wiki.
			foreach ( $this->namespaceNames as $key => $text ) {
				if ( !isset( $validNamespaces[$key] ) ) {
					unset( $this->namespaceNames[$key] );
				}
			}

			# The above mixing may leave namespaces out of canonical order.
			# Re-order by namespace ID number...
			ksort( $this->namespaceNames );

			wfRunHooks( 'LanguageGetNamespaces', array( &$this->namespaceNames ) );
		}
		return $this->namespaceNames;
	}

	/**
	 * A convenience function that returns the same thing as
	 * getNamespaces() except with the array values changed to ' '
	 * where it found '_', useful for producing output to be displayed
	 * e.g. in <select> forms.
	 *
	 * @return array
	 */
	function getFormattedNamespaces() {
		$ns = $this->getNamespaces();
		foreach ( $ns as $k => $v ) {
			$ns[$k] = strtr( $v, '_', ' ' );
		}
		return $ns;
	}

	/**
	 * Get a namespace value by key
	 * <code>
	 * $mw_ns = $wgContLang->getNsText( NS_MEDIAWIKI );
	 * echo $mw_ns; // prints 'MediaWiki'
	 * </code>
	 *
	 * @param $index Int: the array key of the namespace to return
	 * @return mixed, string if the namespace value exists, otherwise false
	 */
	function getNsText( $index ) {
		$ns = $this->getNamespaces();
		return isset( $ns[$index] ) ? $ns[$index] : false;
	}

	/**
	 * A convenience function that returns the same thing as
	 * getNsText() except with '_' changed to ' ', useful for
	 * producing output.
	 *
	 * @param $index string
	 *
	 * @return array
	 */
	function getFormattedNsText( $index ) {
		$ns = $this->getNsText( $index );
		return strtr( $ns, '_', ' ' );
	}

	/**
	 * Returns gender-dependent namespace alias if available.
	 * @param $index Int: namespace index
	 * @param $gender String: gender key (male, female... )
	 * @return String
	 * @since 1.18
	 */
	function getGenderNsText( $index, $gender ) {
		global $wgExtraGenderNamespaces;

		$ns = $wgExtraGenderNamespaces + self::$dataCache->getItem( $this->mCode, 'namespaceGenderAliases' );
		return isset( $ns[$index][$gender] ) ? $ns[$index][$gender] : $this->getNsText( $index );
	}

	/**
	 * Whether this language makes distinguishes genders for example in
	 * namespaces.
	 * @return bool
	 * @since 1.18
	 */
	function needsGenderDistinction() {
		global $wgExtraGenderNamespaces, $wgExtraNamespaces;
		if ( count( $wgExtraGenderNamespaces ) > 0 ) {
			// $wgExtraGenderNamespaces overrides everything
			return true;
		} elseif ( isset( $wgExtraNamespaces[NS_USER] ) && isset( $wgExtraNamespaces[NS_USER_TALK] ) ) {
			/// @todo There may be other gender namespace than NS_USER & NS_USER_TALK in the future
			// $wgExtraNamespaces overrides any gender aliases specified in i18n files
			return false;
		} else {
			// Check what is in i18n files
			$aliases = self::$dataCache->getItem( $this->mCode, 'namespaceGenderAliases' );
			return count( $aliases ) > 0;
		}
	}

	/**
	 * Get a namespace key by value, case insensitive.
	 * Only matches namespace names for the current language, not the
	 * canonical ones defined in Namespace.php.
	 *
	 * @param $text String
	 * @return mixed An integer if $text is a valid value otherwise false
	 */
	function getLocalNsIndex( $text ) {
		$lctext = $this->lc( $text );
		$ids = $this->getNamespaceIds();
		return isset( $ids[$lctext] ) ? $ids[$lctext] : false;
	}

	/**
	 * @return array
	 */
	function getNamespaceAliases() {
		if ( is_null( $this->namespaceAliases ) ) {
			$aliases = self::$dataCache->getItem( $this->mCode, 'namespaceAliases' );
			if ( !$aliases ) {
				$aliases = array();
			} else {
				foreach ( $aliases as $name => $index ) {
					if ( $index === NS_PROJECT_TALK ) {
						unset( $aliases[$name] );
						$name = $this->fixVariableInNamespace( $name );
						$aliases[$name] = $index;
					}
				}
			}

			global $wgExtraGenderNamespaces;
			$genders = $wgExtraGenderNamespaces + (array)self::$dataCache->getItem( $this->mCode, 'namespaceGenderAliases' );
			foreach ( $genders as $index => $forms ) {
				foreach ( $forms as $alias ) {
					$aliases[$alias] = $index;
				}
			}

			$this->namespaceAliases = $aliases;
		}
		return $this->namespaceAliases;
	}

	/**
	 * @return array
	 */
	function getNamespaceIds() {
		if ( is_null( $this->mNamespaceIds ) ) {
			global $wgNamespaceAliases;
			# Put namespace names and aliases into a hashtable.
			# If this is too slow, then we should arrange it so that it is done
			# before caching. The catch is that at pre-cache time, the above
			# class-specific fixup hasn't been done.
			$this->mNamespaceIds = array();
			foreach ( $this->getNamespaces() as $index => $name ) {
				$this->mNamespaceIds[$this->lc( $name )] = $index;
			}
			foreach ( $this->getNamespaceAliases() as $name => $index ) {
				$this->mNamespaceIds[$this->lc( $name )] = $index;
			}
			if ( $wgNamespaceAliases ) {
				foreach ( $wgNamespaceAliases as $name => $index ) {
					$this->mNamespaceIds[$this->lc( $name )] = $index;
				}
			}
		}
		return $this->mNamespaceIds;
	}

	/**
	 * Get a namespace key by value, case insensitive.  Canonical namespace
	 * names override custom ones defined for the current language.
	 *
	 * @param $text String
	 * @return mixed An integer if $text is a valid value otherwise false
	 */
	function getNsIndex( $text ) {
		$lctext = $this->lc( $text );
		$ns = MWNamespace::getCanonicalIndex( $lctext );
		if ( $ns !== null ) {
			return $ns;
		}
		$ids = $this->getNamespaceIds();
		return isset( $ids[$lctext] ) ? $ids[$lctext] : false;
	}

	/**
	 * short names for language variants used for language conversion links.
	 *
	 * @param $code String
	 * @param $usemsg bool Use the "variantname-xyz" message if it exists
	 * @return string
	 */
	function getVariantname( $code, $usemsg = true ) {
		$msg = "variantname-$code";
		list( $rootCode ) = explode( '-', $code );
		if ( $usemsg && wfMessage( $msg )->exists() ) {
			return $this->getMessageFromDB( $msg );
		}
		$name = self::getLanguageName( $code );
		if ( $name ) {
			return $name; # if it's defined as a language name, show that
		} else {
			# otherwise, output the language code
			return $code;
		}
	}

	/**
	 * @param $name string
	 * @return string
	 */
	function specialPage( $name ) {
		$aliases = $this->getSpecialPageAliases();
		if ( isset( $aliases[$name][0] ) ) {
			$name = $aliases[$name][0];
		}
		return $this->getNsText( NS_SPECIAL ) . ':' . $name;
	}

	/**
	 * @return array
	 */
	function getQuickbarSettings() {
		return array(
			$this->getMessage( 'qbsettings-none' ),
			$this->getMessage( 'qbsettings-fixedleft' ),
			$this->getMessage( 'qbsettings-fixedright' ),
			$this->getMessage( 'qbsettings-floatingleft' ),
			$this->getMessage( 'qbsettings-floatingright' ),
			$this->getMessage( 'qbsettings-directionality' )
		);
	}

	/**
	 * @return array
	 */
	function getDatePreferences() {
		return self::$dataCache->getItem( $this->mCode, 'datePreferences' );
	}

	/**
	 * @return array
	 */
	function getDateFormats() {
		return self::$dataCache->getItem( $this->mCode, 'dateFormats' );
	}

	/**
	 * @return array|string
	 */
	function getDefaultDateFormat() {
		$df = self::$dataCache->getItem( $this->mCode, 'defaultDateFormat' );
		if ( $df === 'dmy or mdy' ) {
			global $wgAmericanDates;
			return $wgAmericanDates ? 'mdy' : 'dmy';
		} else {
			return $df;
		}
	}

	/**
	 * @return array
	 */
	function getDatePreferenceMigrationMap() {
		return self::$dataCache->getItem( $this->mCode, 'datePreferenceMigrationMap' );
	}

	/**
	 * @param  $image
	 * @return array|null
	 */
	function getImageFile( $image ) {
		return self::$dataCache->getSubitem( $this->mCode, 'imageFiles', $image );
	}

	/**
	 * @return array
	 */
	function getExtraUserToggles() {
		return (array)self::$dataCache->getItem( $this->mCode, 'extraUserToggles' );
	}

	/**
	 * @param  $tog
	 * @return string
	 */
	function getUserToggle( $tog ) {
		return $this->getMessageFromDB( "tog-$tog" );
	}

	/**
	 * Get native language names, indexed by code.
	 * Only those defined in MediaWiki, no other data like CLDR.
	 * If $customisedOnly is true, only returns codes with a messages file
	 *
	 * @param $customisedOnly bool
	 *
	 * @return array
	 */
	public static function getLanguageNames( $customisedOnly = false ) {
		global $wgExtraLanguageNames;
		static $coreLanguageNames;

		if ( $coreLanguageNames === null ) {
			include( MWInit::compiledPath( 'languages/Names.php' ) );
		}

		$allNames = $wgExtraLanguageNames + $coreLanguageNames;
		if ( !$customisedOnly ) {
			return $allNames;
		}

		$names = array();
		// We do this using a foreach over the codes instead of a directory
		// loop so that messages files in extensions will work correctly.
		foreach ( $allNames as $code => $value ) {
			if ( is_readable( self::getMessagesFileName( $code ) ) ) {
				$names[$code] = $allNames[$code];
			}
		}
		return $names;
	}

	/**
	 * Get translated language names. This is done on best effort and
	 * by default this is exactly the same as Language::getLanguageNames.
	 * The CLDR extension provides translated names.
	 * @param $code String Language code.
	 * @return Array language code => language name
	 * @since 1.18.0
	 */
	public static function getTranslatedLanguageNames( $code ) {
		$names = array();
		wfRunHooks( 'LanguageGetTranslatedLanguageNames', array( &$names, $code ) );

		foreach ( self::getLanguageNames() as $code => $name ) {
			if ( !isset( $names[$code] ) ) $names[$code] = $name;
		}

		return $names;
	}

	/**
	 * Get a message from the MediaWiki namespace.
	 *
	 * @param $msg String: message name
	 * @return string
	 */
	function getMessageFromDB( $msg ) {
		return wfMsgExt( $msg, array( 'parsemag', 'language' => $this ) );
	}

	/**
	 * Get the native language name of $code.
	 * Only if defined in MediaWiki, no other data like CLDR.
	 * @param $code string
	 * @return string
	 */
	function getLanguageName( $code ) {
		$names = self::getLanguageNames();
		if ( !array_key_exists( $code, $names ) ) {
			return '';
		}
		return $names[$code];
	}

	/**
	 * @param $key string
	 * @return string
	 */
	function getMonthName( $key ) {
		return $this->getMessageFromDB( self::$mMonthMsgs[$key - 1] );
	}

	/**
	 * @return array
	 */
	function getMonthNamesArray() {
		$monthNames = array( '' );
		for ( $i = 1; $i < 13; $i++ ) {
			$monthNames[] = $this->getMonthName( $i );
		}
		return $monthNames;
	}

	/**
	 * @param $key string
	 * @return string
	 */
	function getMonthNameGen( $key ) {
		return $this->getMessageFromDB( self::$mMonthGenMsgs[$key - 1] );
	}

	/**
	 * @param $key string
	 * @return string
	 */
	function getMonthAbbreviation( $key ) {
		return $this->getMessageFromDB( self::$mMonthAbbrevMsgs[$key - 1] );
	}

	/**
	 * @return array
	 */
	function getMonthAbbreviationsArray() {
		$monthNames = array( '' );
		for ( $i = 1; $i < 13; $i++ ) {
			$monthNames[] = $this->getMonthAbbreviation( $i );
		}
		return $monthNames;
	}

	/**
	 * @param $key string
	 * @return string
	 */
	function getWeekdayName( $key ) {
		return $this->getMessageFromDB( self::$mWeekdayMsgs[$key - 1] );
	}

	/**
	 * @param $key string
	 * @return string
	 */
	function getWeekdayAbbreviation( $key ) {
		return $this->getMessageFromDB( self::$mWeekdayAbbrevMsgs[$key - 1] );
	}

	/**
	 * @param $key string
	 * @return string
	 */
	function getIranianCalendarMonthName( $key ) {
		return $this->getMessageFromDB( self::$mIranianCalendarMonthMsgs[$key - 1] );
	}

	/**
	 * @param $key string
	 * @return string
	 */
	function getHebrewCalendarMonthName( $key ) {
		return $this->getMessageFromDB( self::$mHebrewCalendarMonthMsgs[$key - 1] );
	}

	/**
	 * @param $key string
	 * @return string
	 */
	function getHebrewCalendarMonthNameGen( $key ) {
		return $this->getMessageFromDB( self::$mHebrewCalendarMonthGenMsgs[$key - 1] );
	}

	/**
	 * @param $key string
	 * @return string
	 */
	function getHijriCalendarMonthName( $key ) {
		return $this->getMessageFromDB( self::$mHijriCalendarMonthMsgs[$key - 1] );
	}

	/**
	 * This is a workalike of PHP's date() function, but with better
	 * internationalisation, a reduced set of format characters, and a better
	 * escaping format.
	 *
	 * Supported format characters are dDjlNwzWFmMntLoYyaAgGhHiscrU. See the
	 * PHP manual for definitions. There are a number of extensions, which
	 * start with "x":
	 *
	 *    xn   Do not translate digits of the next numeric format character
	 *    xN   Toggle raw digit (xn) flag, stays set until explicitly unset
	 *    xr   Use roman numerals for the next numeric format character
	 *    xh   Use hebrew numerals for the next numeric format character
	 *    xx   Literal x
	 *    xg   Genitive month name
	 *
	 *    xij  j (day number) in Iranian calendar
	 *    xiF  F (month name) in Iranian calendar
	 *    xin  n (month number) in Iranian calendar
	 *    xiy  y (two digit year) in Iranian calendar
	 *    xiY  Y (full year) in Iranian calendar
	 *
	 *    xjj  j (day number) in Hebrew calendar
	 *    xjF  F (month name) in Hebrew calendar
	 *    xjt  t (days in month) in Hebrew calendar
	 *    xjx  xg (genitive month name) in Hebrew calendar
	 *    xjn  n (month number) in Hebrew calendar
	 *    xjY  Y (full year) in Hebrew calendar
	 *
	 *    xmj  j (day number) in Hijri calendar
	 *    xmF  F (month name) in Hijri calendar
	 *    xmn  n (month number) in Hijri calendar
	 *    xmY  Y (full year) in Hijri calendar
	 *
	 *    xkY  Y (full year) in Thai solar calendar. Months and days are
	 *                       identical to the Gregorian calendar
	 *    xoY  Y (full year) in Minguo calendar or Juche year.
	 *                       Months and days are identical to the
	 *                       Gregorian calendar
	 *    xtY  Y (full year) in Japanese nengo. Months and days are
	 *                       identical to the Gregorian calendar
	 *
	 * Characters enclosed in double quotes will be considered literal (with
	 * the quotes themselves removed). Unmatched quotes will be considered
	 * literal quotes. Example:
	 *
	 * "The month is" F       => The month is January
	 * i's"                   => 20'11"
	 *
	 * Backslash escaping is also supported.
	 *
	 * Input timestamp is assumed to be pre-normalized to the desired local
	 * time zone, if any.
	 *
	 * @param $format String
	 * @param $ts String: 14-character timestamp
	 *      YYYYMMDDHHMMSS
	 *      01234567890123
	 * @todo handling of "o" format character for Iranian, Hebrew, Hijri & Thai?
	 *
	 * @return string
	 */
	function sprintfDate( $format, $ts ) {
		$s = '';
		$raw = false;
		$roman = false;
		$hebrewNum = false;
		$unix = false;
		$rawToggle = false;
		$iranian = false;
		$hebrew = false;
		$hijri = false;
		$thai = false;
		$minguo = false;
		$tenno = false;
		for ( $p = 0; $p < strlen( $format ); $p++ ) {
			$num = false;
			$code = $format[$p];
			if ( $code == 'x' && $p < strlen( $format ) - 1 ) {
				$code .= $format[++$p];
			}

			if ( ( $code === 'xi' || $code == 'xj' || $code == 'xk' || $code == 'xm' || $code == 'xo' || $code == 'xt' ) && $p < strlen( $format ) - 1 ) {
				$code .= $format[++$p];
			}

			switch ( $code ) {
				case 'xx':
					$s .= 'x';
					break;
				case 'xn':
					$raw = true;
					break;
				case 'xN':
					$rawToggle = !$rawToggle;
					break;
				case 'xr':
					$roman = true;
					break;
				case 'xh':
					$hebrewNum = true;
					break;
				case 'xg':
					$s .= $this->getMonthNameGen( substr( $ts, 4, 2 ) );
					break;
				case 'xjx':
					if ( !$hebrew ) $hebrew = self::tsToHebrew( $ts );
					$s .= $this->getHebrewCalendarMonthNameGen( $hebrew[1] );
					break;
				case 'd':
					$num = substr( $ts, 6, 2 );
					break;
				case 'D':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$s .= $this->getWeekdayAbbreviation( gmdate( 'w', $unix ) + 1 );
					break;
				case 'j':
					$num = intval( substr( $ts, 6, 2 ) );
					break;
				case 'xij':
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$num = $iranian[2];
					break;
				case 'xmj':
					if ( !$hijri ) {
						$hijri = self::tsToHijri( $ts );
					}
					$num = $hijri[2];
					break;
				case 'xjj':
					if ( !$hebrew ) {
						$hebrew = self::tsToHebrew( $ts );
					}
					$num = $hebrew[2];
					break;
				case 'l':
					if ( !$unix ) {
						$unix = wfTimestamp( TS_UNIX, $ts );
					}
					$s .= $this->getWeekdayName( gmdate( 'w', $unix ) + 1 );
					break;
				case 'N':
					if ( !$unix ) {
						$unix = wfTimestamp( TS_UNIX, $ts );
					}
					$w = gmdate( 'w', $unix );
					$num = $w ? $w : 7;
					break;
				case 'w':
					if ( !$unix ) {
						$unix = wfTimestamp( TS_UNIX, $ts );
					}
					$num = gmdate( 'w', $unix );
					break;
				case 'z':
					if ( !$unix ) {
						$unix = wfTimestamp( TS_UNIX, $ts );
					}
					$num = gmdate( 'z', $unix );
					break;
				case 'W':
					if ( !$unix ) {
						$unix = wfTimestamp( TS_UNIX, $ts );
					}
					$num = gmdate( 'W', $unix );
					break;
				case 'F':
					$s .= $this->getMonthName( substr( $ts, 4, 2 ) );
					break;
				case 'xiF':
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$s .= $this->getIranianCalendarMonthName( $iranian[1] );
					break;
				case 'xmF':
					if ( !$hijri ) {
						$hijri = self::tsToHijri( $ts );
					}
					$s .= $this->getHijriCalendarMonthName( $hijri[1] );
					break;
				case 'xjF':
					if ( !$hebrew ) {
						$hebrew = self::tsToHebrew( $ts );
					}
					$s .= $this->getHebrewCalendarMonthName( $hebrew[1] );
					break;
				case 'm':
					$num = substr( $ts, 4, 2 );
					break;
				case 'M':
					$s .= $this->getMonthAbbreviation( substr( $ts, 4, 2 ) );
					break;
				case 'n':
					$num = intval( substr( $ts, 4, 2 ) );
					break;
				case 'xin':
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$num = $iranian[1];
					break;
				case 'xmn':
					if ( !$hijri ) {
						$hijri = self::tsToHijri ( $ts );
					}
					$num = $hijri[1];
					break;
				case 'xjn':
					if ( !$hebrew ) {
						$hebrew = self::tsToHebrew( $ts );
					}
					$num = $hebrew[1];
					break;
				case 't':
					if ( !$unix ) {
						$unix = wfTimestamp( TS_UNIX, $ts );
					}
					$num = gmdate( 't', $unix );
					break;
				case 'xjt':
					if ( !$hebrew ) {
						$hebrew = self::tsToHebrew( $ts );
					}
					$num = $hebrew[3];
					break;
				case 'L':
					if ( !$unix ) {
						$unix = wfTimestamp( TS_UNIX, $ts );
					}
					$num = gmdate( 'L', $unix );
					break;
				case 'o':
					if ( !$unix ) {
						$unix = wfTimestamp( TS_UNIX, $ts );
					}
					$num = date( 'o', $unix );
					break;
				case 'Y':
					$num = substr( $ts, 0, 4 );
					break;
				case 'xiY':
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$num = $iranian[0];
					break;
				case 'xmY':
					if ( !$hijri ) {
						$hijri = self::tsToHijri( $ts );
					}
					$num = $hijri[0];
					break;
				case 'xjY':
					if ( !$hebrew ) {
						$hebrew = self::tsToHebrew( $ts );
					}
					$num = $hebrew[0];
					break;
				case 'xkY':
					if ( !$thai ) {
						$thai = self::tsToYear( $ts, 'thai' );
					}
					$num = $thai[0];
					break;
				case 'xoY':
					if ( !$minguo ) {
						$minguo = self::tsToYear( $ts, 'minguo' );
					}
					$num = $minguo[0];
					break;
				case 'xtY':
					if ( !$tenno ) {
						$tenno = self::tsToYear( $ts, 'tenno' );
					}
					$num = $tenno[0];
					break;
				case 'y':
					$num = substr( $ts, 2, 2 );
					break;
				case 'xiy':
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$num = substr( $iranian[0], -2 );
					break;
				case 'a':
					$s .= intval( substr( $ts, 8, 2 ) ) < 12 ? 'am' : 'pm';
					break;
				case 'A':
					$s .= intval( substr( $ts, 8, 2 ) ) < 12 ? 'AM' : 'PM';
					break;
				case 'g':
					$h = substr( $ts, 8, 2 );
					$num = $h % 12 ? $h % 12 : 12;
					break;
				case 'G':
					$num = intval( substr( $ts, 8, 2 ) );
					break;
				case 'h':
					$h = substr( $ts, 8, 2 );
					$num = sprintf( '%02d', $h % 12 ? $h % 12 : 12 );
					break;
				case 'H':
					$num = substr( $ts, 8, 2 );
					break;
				case 'i':
					$num = substr( $ts, 10, 2 );
					break;
				case 's':
					$num = substr( $ts, 12, 2 );
					break;
				case 'c':
					if ( !$unix ) {
						$unix = wfTimestamp( TS_UNIX, $ts );
					}
					$s .= gmdate( 'c', $unix );
					break;
				case 'r':
					if ( !$unix ) {
						$unix = wfTimestamp( TS_UNIX, $ts );
					}
					$s .= gmdate( 'r', $unix );
					break;
				case 'U':
					if ( !$unix ) {
						$unix = wfTimestamp( TS_UNIX, $ts );
					}
					$num = $unix;
					break;
				case '\\':
					# Backslash escaping
					if ( $p < strlen( $format ) - 1 ) {
						$s .= $format[++$p];
					} else {
						$s .= '\\';
					}
					break;
				case '"':
					# Quoted literal
					if ( $p < strlen( $format ) - 1 ) {
						$endQuote = strpos( $format, '"', $p + 1 );
						if ( $endQuote === false ) {
							# No terminating quote, assume literal "
							$s .= '"';
						} else {
							$s .= substr( $format, $p + 1, $endQuote - $p - 1 );
							$p = $endQuote;
						}
					} else {
						# Quote at end of string, assume literal "
						$s .= '"';
					}
					break;
				default:
					$s .= $format[$p];
			}
			if ( $num !== false ) {
				if ( $rawToggle || $raw ) {
					$s .= $num;
					$raw = false;
				} elseif ( $roman ) {
					$s .= self::romanNumeral( $num );
					$roman = false;
				} elseif ( $hebrewNum ) {
					$s .= self::hebrewNumeral( $num );
					$hebrewNum = false;
				} else {
					$s .= $this->formatNum( $num, true );
				}
			}
		}
		return $s;
	}

	private static $GREG_DAYS = array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );
	private static $IRANIAN_DAYS = array( 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 );

	/**
	 * Algorithm by Roozbeh Pournader and Mohammad Toossi to convert
	 * Gregorian dates to Iranian dates. Originally written in C, it
	 * is released under the terms of GNU Lesser General Public
	 * License. Conversion to PHP was performed by Niklas Laxström.
	 *
	 * Link: http://www.farsiweb.info/jalali/jalali.c
	 *
	 * @param $ts string
	 *
	 * @return string
	 */
	private static function tsToIranian( $ts ) {
		$gy = substr( $ts, 0, 4 ) -1600;
		$gm = substr( $ts, 4, 2 ) -1;
		$gd = substr( $ts, 6, 2 ) -1;

		# Days passed from the beginning (including leap years)
		$gDayNo = 365 * $gy
			+ floor( ( $gy + 3 ) / 4 )
			- floor( ( $gy + 99 ) / 100 )
			+ floor( ( $gy + 399 ) / 400 );

		// Add days of the past months of this year
		for ( $i = 0; $i < $gm; $i++ ) {
			$gDayNo += self::$GREG_DAYS[$i];
		}

		// Leap years
		if ( $gm > 1 && ( ( $gy % 4 === 0 && $gy % 100 !== 0 || ( $gy % 400 == 0 ) ) ) ) {
			$gDayNo++;
		}

		// Days passed in current month
		$gDayNo += (int)$gd;

		$jDayNo = $gDayNo - 79;

		$jNp = floor( $jDayNo / 12053 );
		$jDayNo %= 12053;

		$jy = 979 + 33 * $jNp + 4 * floor( $jDayNo / 1461 );
		$jDayNo %= 1461;

		if ( $jDayNo >= 366 ) {
			$jy += floor( ( $jDayNo - 1 ) / 365 );
			$jDayNo = floor( ( $jDayNo - 1 ) % 365 );
		}

		for ( $i = 0; $i < 11 && $jDayNo >= self::$IRANIAN_DAYS[$i]; $i++ ) {
			$jDayNo -= self::$IRANIAN_DAYS[$i];
		}

		$jm = $i + 1;
		$jd = $jDayNo + 1;

		return array( $jy, $jm, $jd );
	}

	/**
	 * Converting Gregorian dates to Hijri dates.
	 *
	 * Based on a PHP-Nuke block by Sharjeel which is released under GNU/GPL license
	 *
	 * @link http://phpnuke.org/modules.php?name=News&file=article&sid=8234&mode=thread&order=0&thold=0
	 *
	 * @param $ts string
	 *
	 * @return string
	 */
	private static function tsToHijri( $ts ) {
		$year = substr( $ts, 0, 4 );
		$month = substr( $ts, 4, 2 );
		$day = substr( $ts, 6, 2 );

		$zyr = $year;
		$zd = $day;
		$zm = $month;
		$zy = $zyr;

		if (
			( $zy > 1582 ) || ( ( $zy == 1582 ) && ( $zm > 10 ) ) ||
			( ( $zy == 1582 ) && ( $zm == 10 ) && ( $zd > 14 ) )
		)
		{
			$zjd = (int)( ( 1461 * ( $zy + 4800 + (int)( ( $zm - 14 ) / 12 ) ) ) / 4 ) +
					(int)( ( 367 * ( $zm - 2 - 12 * ( (int)( ( $zm - 14 ) / 12 ) ) ) ) / 12 ) -
					(int)( ( 3 * (int)( ( ( $zy + 4900 + (int)( ( $zm - 14 ) / 12 ) ) / 100 ) ) ) / 4 ) +
					$zd - 32075;
		} else {
			$zjd = 367 * $zy - (int)( ( 7 * ( $zy + 5001 + (int)( ( $zm - 9 ) / 7 ) ) ) / 4 ) +
								(int)( ( 275 * $zm ) / 9 ) + $zd + 1729777;
		}

		$zl = $zjd -1948440 + 10632;
		$zn = (int)( ( $zl - 1 ) / 10631 );
		$zl = $zl - 10631 * $zn + 354;
		$zj = ( (int)( ( 10985 - $zl ) / 5316 ) ) * ( (int)( ( 50 * $zl ) / 17719 ) ) + ( (int)( $zl / 5670 ) ) * ( (int)( ( 43 * $zl ) / 15238 ) );
		$zl = $zl - ( (int)( ( 30 - $zj ) / 15 ) ) * ( (int)( ( 17719 * $zj ) / 50 ) ) - ( (int)( $zj / 16 ) ) * ( (int)( ( 15238 * $zj ) / 43 ) ) + 29;
		$zm = (int)( ( 24 * $zl ) / 709 );
		$zd = $zl - (int)( ( 709 * $zm ) / 24 );
		$zy = 30 * $zn + $zj - 30;

		return array( $zy, $zm, $zd );
	}

	/**
	 * Converting Gregorian dates to Hebrew dates.
	 *
	 * Based on a JavaScript code by Abu Mami and Yisrael Hersch
	 * (abu-mami@kaluach.net, http://www.kaluach.net), who permitted
	 * to translate the relevant functions into PHP and release them under
	 * GNU GPL.
	 *
	 * The months are counted from Tishrei = 1. In a leap year, Adar I is 13
	 * and Adar II is 14. In a non-leap year, Adar is 6.
	 *
	 * @param $ts string
	 *
	 * @return string
	 */
	private static function tsToHebrew( $ts ) {
		# Parse date
		$year = substr( $ts, 0, 4 );
		$month = substr( $ts, 4, 2 );
		$day = substr( $ts, 6, 2 );

		# Calculate Hebrew year
		$hebrewYear = $year + 3760;

		# Month number when September = 1, August = 12
		$month += 4;
		if ( $month > 12 ) {
			# Next year
			$month -= 12;
			$year++;
			$hebrewYear++;
		}

		# Calculate day of year from 1 September
		$dayOfYear = $day;
		for ( $i = 1; $i < $month; $i++ ) {
			if ( $i == 6 ) {
				# February
				$dayOfYear += 28;
				# Check if the year is leap
				if ( $year % 400 == 0 || ( $year % 4 == 0 && $year % 100 > 0 ) ) {
					$dayOfYear++;
				}
			} elseif ( $i == 8 || $i == 10 || $i == 1 || $i == 3 ) {
				$dayOfYear += 30;
			} else {
				$dayOfYear += 31;
			}
		}

		# Calculate the start of the Hebrew year
		$start = self::hebrewYearStart( $hebrewYear );

		# Calculate next year's start
		if ( $dayOfYear <= $start ) {
			# Day is before the start of the year - it is the previous year
			# Next year's start
			$nextStart = $start;
			# Previous year
			$year--;
			$hebrewYear--;
			# Add days since previous year's 1 September
			$dayOfYear += 365;
			if ( ( $year % 400 == 0 ) || ( $year % 100 != 0 && $year % 4 == 0 ) ) {
				# Leap year
				$dayOfYear++;
			}
			# Start of the new (previous) year
			$start = self::hebrewYearStart( $hebrewYear );
		} else {
			# Next year's start
			$nextStart = self::hebrewYearStart( $hebrewYear + 1 );
		}

		# Calculate Hebrew day of year
		$hebrewDayOfYear = $dayOfYear - $start;

		# Difference between year's days
		$diff = $nextStart - $start;
		# Add 12 (or 13 for leap years) days to ignore the difference between
		# Hebrew and Gregorian year (353 at least vs. 365/6) - now the
		# difference is only about the year type
		if ( ( $year % 400 == 0 ) || ( $year % 100 != 0 && $year % 4 == 0 ) ) {
			$diff += 13;
		} else {
			$diff += 12;
		}

		# Check the year pattern, and is leap year
		# 0 means an incomplete year, 1 means a regular year, 2 means a complete year
		# This is mod 30, to work on both leap years (which add 30 days of Adar I)
		# and non-leap years
		$yearPattern = $diff % 30;
		# Check if leap year
		$isLeap = $diff >= 30;

		# Calculate day in the month from number of day in the Hebrew year
		# Don't check Adar - if the day is not in Adar, we will stop before;
		# if it is in Adar, we will use it to check if it is Adar I or Adar II
		$hebrewDay = $hebrewDayOfYear;
		$hebrewMonth = 1;
		$days = 0;
		while ( $hebrewMonth <= 12 ) {
			# Calculate days in this month
			if ( $isLeap && $hebrewMonth == 6 ) {
				# Adar in a leap year
				if ( $isLeap ) {
					# Leap year - has Adar I, with 30 days, and Adar II, with 29 days
					$days = 30;
					if ( $hebrewDay <= $days ) {
						# Day in Adar I
						$hebrewMonth = 13;
					} else {
						# Subtract the days of Adar I
						$hebrewDay -= $days;
						# Try Adar II
						$days = 29;
						if ( $hebrewDay <= $days ) {
							# Day in Adar II
							$hebrewMonth = 14;
						}
					}
				}
			} elseif ( $hebrewMonth == 2 && $yearPattern == 2 ) {
				# Cheshvan in a complete year (otherwise as the rule below)
				$days = 30;
			} elseif ( $hebrewMonth == 3 && $yearPattern == 0 ) {
				# Kislev in an incomplete year (otherwise as the rule below)
				$days = 29;
			} else {
				# Odd months have 30 days, even have 29
				$days = 30 - ( $hebrewMonth - 1 ) % 2;
			}
			if ( $hebrewDay <= $days ) {
				# In the current month
				break;
			} else {
				# Subtract the days of the current month
				$hebrewDay -= $days;
				# Try in the next month
				$hebrewMonth++;
			}
		}

		return array( $hebrewYear, $hebrewMonth, $hebrewDay, $days );
	}

	/**
	 * This calculates the Hebrew year start, as days since 1 September.
	 * Based on Carl Friedrich Gauss algorithm for finding Easter date.
	 * Used for Hebrew date.
	 *
	 * @param $year int
	 *
	 * @return string
	 */
	private static function hebrewYearStart( $year ) {
		$a = intval( ( 12 * ( $year - 1 ) + 17 ) % 19 );
		$b = intval( ( $year - 1 ) % 4 );
		$m = 32.044093161144 + 1.5542417966212 * $a +  $b / 4.0 - 0.0031777940220923 * ( $year - 1 );
		if ( $m < 0 ) {
			$m--;
		}
		$Mar = intval( $m );
		if ( $m < 0 ) {
			$m++;
		}
		$m -= $Mar;

		$c = intval( ( $Mar + 3 * ( $year - 1 ) + 5 * $b + 5 ) % 7 );
		if ( $c == 0 && $a > 11 && $m >= 0.89772376543210 ) {
			$Mar++;
		} elseif ( $c == 1 && $a > 6 && $m >= 0.63287037037037 ) {
			$Mar += 2;
		} elseif ( $c == 2 || $c == 4 || $c == 6 ) {
			$Mar++;
		}

		$Mar += intval( ( $year - 3761 ) / 100 ) - intval( ( $year - 3761 ) / 400 ) - 24;
		return $Mar;
	}

	/**
	 * Algorithm to convert Gregorian dates to Thai solar dates,
	 * Minguo dates or Minguo dates.
	 *
	 * Link: http://en.wikipedia.org/wiki/Thai_solar_calendar
	 *       http://en.wikipedia.org/wiki/Minguo_calendar
	 *       http://en.wikipedia.org/wiki/Japanese_era_name
	 *
	 * @param $ts String: 14-character timestamp
	 * @param $cName String: calender name
	 * @return Array: converted year, month, day
	 */
	private static function tsToYear( $ts, $cName ) {
		$gy = substr( $ts, 0, 4 );
		$gm = substr( $ts, 4, 2 );
		$gd = substr( $ts, 6, 2 );

		if ( !strcmp( $cName, 'thai' ) ) {
			# Thai solar dates
			# Add 543 years to the Gregorian calendar
			# Months and days are identical
			$gy_offset = $gy + 543;
		} elseif ( ( !strcmp( $cName, 'minguo' ) ) || !strcmp( $cName, 'juche' ) ) {
			# Minguo dates
			# Deduct 1911 years from the Gregorian calendar
			# Months and days are identical
			$gy_offset = $gy - 1911;
		} elseif ( !strcmp( $cName, 'tenno' ) ) {
			# Nengō dates up to Meiji period
			# Deduct years from the Gregorian calendar
			# depending on the nengo periods
			# Months and days are identical
			if ( ( $gy < 1912 ) || ( ( $gy == 1912 ) && ( $gm < 7 ) ) || ( ( $gy == 1912 ) && ( $gm == 7 ) && ( $gd < 31 ) ) ) {
				# Meiji period
				$gy_gannen = $gy - 1868 + 1;
				$gy_offset = $gy_gannen;
				if ( $gy_gannen == 1 ) {
					$gy_offset = '元';
				}
				$gy_offset = '明治' . $gy_offset;
			} elseif (
				( ( $gy == 1912 ) && ( $gm == 7 ) && ( $gd == 31 ) ) ||
				( ( $gy == 1912 ) && ( $gm >= 8 ) ) ||
				( ( $gy > 1912 ) && ( $gy < 1926 ) ) ||
				( ( $gy == 1926 ) && ( $gm < 12 ) ) ||
				( ( $gy == 1926 ) && ( $gm == 12 ) && ( $gd < 26 ) )
			)
			{
				# Taishō period
				$gy_gannen = $gy - 1912 + 1;
				$gy_offset = $gy_gannen;
				if ( $gy_gannen == 1 ) {
					$gy_offset = '元';
				}
				$gy_offset = '大正' . $gy_offset;
			} elseif (
				( ( $gy == 1926 ) && ( $gm == 12 ) && ( $gd >= 26 ) ) ||
				( ( $gy > 1926 ) && ( $gy < 1989 ) ) ||
				( ( $gy == 1989 ) && ( $gm == 1 ) && ( $gd < 8 ) )
			)
			{
				# Shōwa period
				$gy_gannen = $gy - 1926 + 1;
				$gy_offset = $gy_gannen;
				if ( $gy_gannen == 1 ) {
					$gy_offset = '元';
				}
				$gy_offset = '昭和' . $gy_offset;
			} else {
				# Heisei period
				$gy_gannen = $gy - 1989 + 1;
				$gy_offset = $gy_gannen;
				if ( $gy_gannen == 1 ) {
					$gy_offset = '元';
				}
				$gy_offset = '平成' . $gy_offset;
			}
		} else {
			$gy_offset = $gy;
		}

		return array( $gy_offset, $gm, $gd );
	}

	/**
	 * Roman number formatting up to 3000
	 *
	 * @param $num int
	 *
	 * @return string
	 */
	static function romanNumeral( $num ) {
		static $table = array(
			array( '', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X' ),
			array( '', 'X', 'XX', 'XXX', 'XL', 'L', 'LX', 'LXX', 'LXXX', 'XC', 'C' ),
			array( '', 'C', 'CC', 'CCC', 'CD', 'D', 'DC', 'DCC', 'DCCC', 'CM', 'M' ),
			array( '', 'M', 'MM', 'MMM' )
		);

		$num = intval( $num );
		if ( $num > 3000 || $num <= 0 ) {
			return $num;
		}

		$s = '';
		for ( $pow10 = 1000, $i = 3; $i >= 0; $pow10 /= 10, $i-- ) {
			if ( $num >= $pow10 ) {
				$s .= $table[$i][(int)floor( $num / $pow10 )];
			}
			$num = $num % $pow10;
		}
		return $s;
	}

	/**
	 * Hebrew Gematria number formatting up to 9999
	 *
	 * @param $num int
	 *
	 * @return string
	 */
	static function hebrewNumeral( $num ) {
		static $table = array(
			array( '', 'א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ז', 'ח', 'ט', 'י' ),
			array( '', 'י', 'כ', 'ל', 'מ', 'נ', 'ס', 'ע', 'פ', 'צ', 'ק' ),
			array( '', 'ק', 'ר', 'ש', 'ת', 'תק', 'תר', 'תש', 'תת', 'תתק', 'תתר' ),
			array( '', 'א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ז', 'ח', 'ט', 'י' )
		);

		$num = intval( $num );
		if ( $num > 9999 || $num <= 0 ) {
			return $num;
		}

		$s = '';
		for ( $pow10 = 1000, $i = 3; $i >= 0; $pow10 /= 10, $i-- ) {
			if ( $num >= $pow10 ) {
				if ( $num == 15 || $num == 16 ) {
					$s .= $table[0][9] . $table[0][$num - 9];
					$num = 0;
				} else {
					$s .= $table[$i][intval( ( $num / $pow10 ) )];
					if ( $pow10 == 1000 ) {
						$s .= "'";
					}
				}
			}
			$num = $num % $pow10;
		}
		if ( strlen( $s ) == 2 ) {
			$str = $s . "'";
		} else  {
			$str = substr( $s, 0, strlen( $s ) - 2 ) . '"';
			$str .= substr( $s, strlen( $s ) - 2, 2 );
		}
		$start = substr( $str, 0, strlen( $str ) - 2 );
		$end = substr( $str, strlen( $str ) - 2 );
		switch( $end ) {
			case 'כ':
				$str = $start . 'ך';
				break;
			case 'מ':
				$str = $start . 'ם';
				break;
			case 'נ':
				$str = $start . 'ן';
				break;
			case 'פ':
				$str = $start . 'ף';
				break;
			case 'צ':
				$str = $start . 'ץ';
				break;
		}
		return $str;
	}

	/**
	 * Used by date() and time() to adjust the time output.
	 *
	 * @param $ts Int the time in date('YmdHis') format
	 * @param $tz Mixed: adjust the time by this amount (default false, mean we
	 *            get user timecorrection setting)
	 * @return int
	 */
	function userAdjust( $ts, $tz = false )	{
		global $wgUser, $wgLocalTZoffset;

		if ( $tz === false ) {
			$tz = $wgUser->getOption( 'timecorrection' );
		}

		$data = explode( '|', $tz, 3 );

		if ( $data[0] == 'ZoneInfo' ) {
			wfSuppressWarnings();
			$userTZ = timezone_open( $data[2] );
			wfRestoreWarnings();
			if ( $userTZ !== false ) {
				$date = date_create( $ts, timezone_open( 'UTC' ) );
				date_timezone_set( $date, $userTZ );
				$date = date_format( $date, 'YmdHis' );
				return $date;
			}
			# Unrecognized timezone, default to 'Offset' with the stored offset.
			$data[0] = 'Offset';
		}

		$minDiff = 0;
		if ( $data[0] == 'System' || $tz == '' ) {
			#  Global offset in minutes.
			if ( isset( $wgLocalTZoffset ) ) {
				$minDiff = $wgLocalTZoffset;
			}
		} elseif ( $data[0] == 'Offset' ) {
			$minDiff = intval( $data[1] );
		} else {
			$data = explode( ':', $tz );
			if ( count( $data ) == 2 ) {
				$data[0] = intval( $data[0] );
				$data[1] = intval( $data[1] );
				$minDiff = abs( $data[0] ) * 60 + $data[1];
				if ( $data[0] < 0 ) {
					$minDiff = -$minDiff;
				}
			} else {
				$minDiff = intval( $data[0] ) * 60;
			}
		}

		# No difference ? Return time unchanged
		if ( 0 == $minDiff ) {
			return $ts;
		}

		wfSuppressWarnings(); // E_STRICT system time bitching
		# Generate an adjusted date; take advantage of the fact that mktime
		# will normalize out-of-range values so we don't have to split $minDiff
		# into hours and minutes.
		$t = mktime( (
		  (int)substr( $ts, 8, 2 ) ), # Hours
		  (int)substr( $ts, 10, 2 ) + $minDiff, # Minutes
		  (int)substr( $ts, 12, 2 ), # Seconds
		  (int)substr( $ts, 4, 2 ), # Month
		  (int)substr( $ts, 6, 2 ), # Day
		  (int)substr( $ts, 0, 4 ) ); # Year

		$date = date( 'YmdHis', $t );
		wfRestoreWarnings();

		return $date;
	}

	/**
	 * This is meant to be used by time(), date(), and timeanddate() to get
	 * the date preference they're supposed to use, it should be used in
	 * all children.
	 *
	 *<code>
	 * function timeanddate([...], $format = true) {
	 * 	$datePreference = $this->dateFormat($format);
	 * [...]
	 * }
	 *</code>
	 *
	 * @param $usePrefs Mixed: if true, the user's preference is used
	 *                         if false, the site/language default is used
	 *                         if int/string, assumed to be a format.
	 * @return string
	 */
	function dateFormat( $usePrefs = true ) {
		global $wgUser;

		if ( is_bool( $usePrefs ) ) {
			if ( $usePrefs ) {
				$datePreference = $wgUser->getDatePreference();
			} else {
				$datePreference = (string)User::getDefaultOption( 'date' );
			}
		} else {
			$datePreference = (string)$usePrefs;
		}

		// return int
		if ( $datePreference == '' ) {
			return 'default';
		}

		return $datePreference;
	}

	/**
	 * Get a format string for a given type and preference
	 * @param $type string May be date, time or both
	 * @param $pref string The format name as it appears in Messages*.php
	 *
	 * @return string
	 */
	function getDateFormatString( $type, $pref ) {
		if ( !isset( $this->dateFormatStrings[$type][$pref] ) ) {
			if ( $pref == 'default' ) {
				$pref = $this->getDefaultDateFormat();
				$df = self::$dataCache->getSubitem( $this->mCode, 'dateFormats', "$pref $type" );
			} else {
				$df = self::$dataCache->getSubitem( $this->mCode, 'dateFormats', "$pref $type" );
				if ( is_null( $df ) ) {
					$pref = $this->getDefaultDateFormat();
					$df = self::$dataCache->getSubitem( $this->mCode, 'dateFormats', "$pref $type" );
				}
			}
			$this->dateFormatStrings[$type][$pref] = $df;
		}
		return $this->dateFormatStrings[$type][$pref];
	}

	/**
	 * @param $ts Mixed: the time format which needs to be turned into a
	 *            date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param $adj Bool: whether to adjust the time output according to the
	 *             user configured offset ($timecorrection)
	 * @param $format Mixed: true to use user's date format preference
	 * @param $timecorrection String|bool the time offset as returned by
	 *                        validateTimeZone() in Special:Preferences
	 * @return string
	 */
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		$ts = wfTimestamp( TS_MW, $ts );
		if ( $adj ) {
			$ts = $this->userAdjust( $ts, $timecorrection );
		}
		$df = $this->getDateFormatString( 'date', $this->dateFormat( $format ) );
		return $this->sprintfDate( $df, $ts );
	}

	/**
	 * @param $ts Mixed: the time format which needs to be turned into a
	 *            date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param $adj Bool: whether to adjust the time output according to the
	 *             user configured offset ($timecorrection)
	 * @param $format Mixed: true to use user's date format preference
	 * @param $timecorrection String|bool the time offset as returned by
	 *                        validateTimeZone() in Special:Preferences
	 * @return string
	 */
	function time( $ts, $adj = false, $format = true, $timecorrection = false ) {
		$ts = wfTimestamp( TS_MW, $ts );
		if ( $adj ) {
			$ts = $this->userAdjust( $ts, $timecorrection );
		}
		$df = $this->getDateFormatString( 'time', $this->dateFormat( $format ) );
		return $this->sprintfDate( $df, $ts );
	}

	/**
	 * @param $ts Mixed: the time format which needs to be turned into a
	 *            date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param $adj Bool: whether to adjust the time output according to the
	 *             user configured offset ($timecorrection)
	 * @param $format Mixed: what format to return, if it's false output the
	 *                default one (default true)
	 * @param $timecorrection String|bool the time offset as returned by
	 *                        validateTimeZone() in Special:Preferences
	 * @return string
	 */
	function timeanddate( $ts, $adj = false, $format = true, $timecorrection = false ) {
		$ts = wfTimestamp( TS_MW, $ts );
		if ( $adj ) {
			$ts = $this->userAdjust( $ts, $timecorrection );
		}
		$df = $this->getDateFormatString( 'both', $this->dateFormat( $format ) );
		return $this->sprintfDate( $df, $ts );
	}

	/**
	 * Internal helper function for userDate(), userTime() and userTimeAndDate()
	 *
	 * @param $type String: can be 'date', 'time' or 'both'
	 * @param $ts Mixed: the time format which needs to be turned into a
	 *            date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param $user User object used to get preferences for timezone and format
	 * @param $options Array, can contain the following keys:
	 *        - 'timecorrection': time correction, can have the following values:
	 *             - true: use user's preference
	 *             - false: don't use time correction
	 *             - integer: value of time correction in minutes
	 *        - 'format': format to use, can have the following values:
	 *             - true: use user's preference
	 *             - false: use default preference
	 *             - string: format to use
	 * @return String
	 */
	private function internalUserTimeAndDate( $type, $ts, User $user, array $options ) {
		$ts = wfTimestamp( TS_MW, $ts );
		$options += array( 'timecorrection' => true, 'format' => true );
		if ( $options['timecorrection'] !== false ) {
			if ( $options['timecorrection'] === true ) {
				$offset = $user->getOption( 'timecorrection' );
			} else {
				$offset = $options['timecorrection'];
			}
			$ts = $this->userAdjust( $ts, $offset );
		}
		if ( $options['format'] === true ) {
			$format = $user->getDatePreference();
		} else {
			$format = $options['format'];
		}
		$df = $this->getDateFormatString( $type, $this->dateFormat( $format ) );
		return $this->sprintfDate( $df, $ts );
	}

	/**
	 * Get the formatted date for the given timestamp and formatted for
	 * the given user.
	 *
	 * @param $ts Mixed: the time format which needs to be turned into a
	 *            date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param $user User object used to get preferences for timezone and format
	 * @param $options Array, can contain the following keys:
	 *        - 'timecorrection': time correction, can have the following values:
	 *             - true: use user's preference
	 *             - false: don't use time correction
	 *             - integer: value of time correction in minutes
	 *        - 'format': format to use, can have the following values:
	 *             - true: use user's preference
	 *             - false: use default preference
	 *             - string: format to use
	 * @return String
	 */
	public function userDate( $ts, User $user, array $options = array() ) {
		return $this->internalUserTimeAndDate( 'date', $ts, $user, $options );
	}

	/**
	 * Get the formatted time for the given timestamp and formatted for
	 * the given user.
	 *
	 * @param $ts Mixed: the time format which needs to be turned into a
	 *            date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param $user User object used to get preferences for timezone and format
	 * @param $options Array, can contain the following keys:
	 *        - 'timecorrection': time correction, can have the following values:
	 *             - true: use user's preference
	 *             - false: don't use time correction
	 *             - integer: value of time correction in minutes
	 *        - 'format': format to use, can have the following values:
	 *             - true: use user's preference
	 *             - false: use default preference
	 *             - string: format to use
	 * @return String
	 */
	public function userTime( $ts, User $user, array $options = array() ) {
		return $this->internalUserTimeAndDate( 'time', $ts, $user, $options );
	}

	/**
	 * Get the formatted date and time for the given timestamp and formatted for
	 * the given user.
	 *
	 * @param $ts Mixed: the time format which needs to be turned into a
	 *            date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param $user User object used to get preferences for timezone and format
	 * @param $options Array, can contain the following keys:
	 *        - 'timecorrection': time correction, can have the following values:
	 *             - true: use user's preference
	 *             - false: don't use time correction
	 *             - integer: value of time correction in minutes
	 *        - 'format': format to use, can have the following values:
	 *             - true: use user's preference
	 *             - false: use default preference
	 *             - string: format to use
	 * @return String
	 */
	public function userTimeAndDate( $ts, User $user, array $options = array() ) {
		return $this->internalUserTimeAndDate( 'both', $ts, $user, $options );
	}

	/**
	 * @param $key string
	 * @return array|null
	 */
	function getMessage( $key ) {
		return self::$dataCache->getSubitem( $this->mCode, 'messages', $key );
	}

	/**
	 * @return array
	 */
	function getAllMessages() {
		return self::$dataCache->getItem( $this->mCode, 'messages' );
	}

	/**
	 * @param $in
	 * @param $out
	 * @param $string
	 * @return string
	 */
	function iconv( $in, $out, $string ) {
		# This is a wrapper for iconv in all languages except esperanto,
		# which does some nasty x-conversions beforehand

		# Even with //IGNORE iconv can whine about illegal characters in
		# *input* string. We just ignore those too.
		# REF: http://bugs.php.net/bug.php?id=37166
		# REF: https://bugzilla.wikimedia.org/show_bug.cgi?id=16885
		wfSuppressWarnings();
		$text = iconv( $in, $out . '//IGNORE', $string );
		wfRestoreWarnings();
		return $text;
	}

	// callback functions for uc(), lc(), ucwords(), ucwordbreaks()

	/**
	 * @param $matches array
	 * @return mixed|string
	 */
	function ucwordbreaksCallbackAscii( $matches ) {
		return $this->ucfirst( $matches[1] );
	}

	/**
	 * @param $matches array
	 * @return string
	 */
	function ucwordbreaksCallbackMB( $matches ) {
		return mb_strtoupper( $matches[0] );
	}

	/**
	 * @param $matches array
	 * @return string
	 */
	function ucCallback( $matches ) {
		list( $wikiUpperChars ) = self::getCaseMaps();
		return strtr( $matches[1], $wikiUpperChars );
	}

	/**
	 * @param $matches array
	 * @return string
	 */
	function lcCallback( $matches ) {
		list( , $wikiLowerChars ) = self::getCaseMaps();
		return strtr( $matches[1], $wikiLowerChars );
	}

	/**
	 * @param $matches array
	 * @return string
	 */
	function ucwordsCallbackMB( $matches ) {
		return mb_strtoupper( $matches[0] );
	}

	/**
	 * @param $matches array
	 * @return string
	 */
	function ucwordsCallbackWiki( $matches ) {
		list( $wikiUpperChars ) = self::getCaseMaps();
		return strtr( $matches[0], $wikiUpperChars );
	}

	/**
	 * Make a string's first character uppercase
	 *
	 * @param $str string
	 *
	 * @return string
	 */
	function ucfirst( $str ) {
		$o = ord( $str );
		if ( $o < 96 ) { // if already uppercase...
			return $str;
		} elseif ( $o < 128 ) {
			return ucfirst( $str ); // use PHP's ucfirst()
		} else {
			// fall back to more complex logic in case of multibyte strings
			return $this->uc( $str, true );
		}
	}

	/**
	 * Convert a string to uppercase
	 *
	 * @param $str string
	 * @param $first bool
	 *
	 * @return string
	 */
	function uc( $str, $first = false ) {
		if ( function_exists( 'mb_strtoupper' ) ) {
			if ( $first ) {
				if ( $this->isMultibyte( $str ) ) {
					return mb_strtoupper( mb_substr( $str, 0, 1 ) ) . mb_substr( $str, 1 );
				} else {
					return ucfirst( $str );
				}
			} else {
				return $this->isMultibyte( $str ) ? mb_strtoupper( $str ) : strtoupper( $str );
			}
		} else {
			if ( $this->isMultibyte( $str ) ) {
				$x = $first ? '^' : '';
				return preg_replace_callback(
					"/$x([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)/",
					array( $this, 'ucCallback' ),
					$str
				);
			} else {
				return $first ? ucfirst( $str ) : strtoupper( $str );
			}
		}
	}

	/**
	 * @param $str string
	 * @return mixed|string
	 */
	function lcfirst( $str ) {
		$o = ord( $str );
		if ( !$o ) {
			return strval( $str );
		} elseif ( $o >= 128 ) {
			return $this->lc( $str, true );
		} elseif ( $o > 96 ) {
			return $str;
		} else {
			$str[0] = strtolower( $str[0] );
			return $str;
		}
	}

	/**
	 * @param $str string
	 * @param $first bool
	 * @return mixed|string
	 */
	function lc( $str, $first = false ) {
		if ( function_exists( 'mb_strtolower' ) ) {
			if ( $first ) {
				if ( $this->isMultibyte( $str ) ) {
					return mb_strtolower( mb_substr( $str, 0, 1 ) ) . mb_substr( $str, 1 );
				} else {
					return strtolower( substr( $str, 0, 1 ) ) . substr( $str, 1 );
				}
			} else {
				return $this->isMultibyte( $str ) ? mb_strtolower( $str ) : strtolower( $str );
			}
		} else {
			if ( $this->isMultibyte( $str ) ) {
				$x = $first ? '^' : '';
				return preg_replace_callback(
					"/$x([A-Z]|[\\xc0-\\xff][\\x80-\\xbf]*)/",
					array( $this, 'lcCallback' ),
					$str
				);
			} else {
				return $first ? strtolower( substr( $str, 0, 1 ) ) . substr( $str, 1 ) : strtolower( $str );
			}
		}
	}

	/**
	 * @param $str string
	 * @return bool
	 */
	function isMultibyte( $str ) {
		return (bool)preg_match( '/[\x80-\xff]/', $str );
	}

	/**
	 * @param $str string
	 * @return mixed|string
	 */
	function ucwords( $str ) {
		if ( $this->isMultibyte( $str ) ) {
			$str = $this->lc( $str );

			// regexp to find first letter in each word (i.e. after each space)
			$replaceRegexp = "/^([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)| ([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)/";

			// function to use to capitalize a single char
			if ( function_exists( 'mb_strtoupper' ) ) {
				return preg_replace_callback(
					$replaceRegexp,
					array( $this, 'ucwordsCallbackMB' ),
					$str
				);
			} else {
				return preg_replace_callback(
					$replaceRegexp,
					array( $this, 'ucwordsCallbackWiki' ),
					$str
				);
			}
		} else {
			return ucwords( strtolower( $str ) );
		}
	}

	/**
	 * capitalize words at word breaks
	 *
	 * @param $str string
	 * @return mixed
	 */
	function ucwordbreaks( $str ) {
		if ( $this->isMultibyte( $str ) ) {
			$str = $this->lc( $str );

			// since \b doesn't work for UTF-8, we explicitely define word break chars
			$breaks = "[ \-\(\)\}\{\.,\?!]";

			// find first letter after word break
			$replaceRegexp = "/^([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)|$breaks([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)/";

			if ( function_exists( 'mb_strtoupper' ) ) {
				return preg_replace_callback(
					$replaceRegexp,
					array( $this, 'ucwordbreaksCallbackMB' ),
					$str
				);
			} else {
				return preg_replace_callback(
					$replaceRegexp,
					array( $this, 'ucwordsCallbackWiki' ),
					$str
				);
			}
		} else {
			return preg_replace_callback(
				'/\b([\w\x80-\xff]+)\b/',
				array( $this, 'ucwordbreaksCallbackAscii' ),
				$str
			);
		}
	}

	/**
	 * Return a case-folded representation of $s
	 *
	 * This is a representation such that caseFold($s1)==caseFold($s2) if $s1
	 * and $s2 are the same except for the case of their characters. It is not
	 * necessary for the value returned to make sense when displayed.
	 *
	 * Do *not* perform any other normalisation in this function. If a caller
	 * uses this function when it should be using a more general normalisation
	 * function, then fix the caller.
	 *
	 * @param $s string
	 *
	 * @return string
	 */
	function caseFold( $s ) {
		return $this->uc( $s );
	}

	/**
	 * @param $s string
	 * @return string
	 */
	function checkTitleEncoding( $s ) {
		if ( is_array( $s ) ) {
			wfDebugDieBacktrace( 'Given array to checkTitleEncoding.' );
		}
		# Check for non-UTF-8 URLs
		$ishigh = preg_match( '/[\x80-\xff]/', $s );
		if ( !$ishigh ) {
			return $s;
		}

		$isutf8 = preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
				'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s );
		if ( $isutf8 ) {
			return $s;
		}

		return $this->iconv( $this->fallback8bitEncoding(), 'utf-8', $s );
	}

	/**
	 * @return array
	 */
	function fallback8bitEncoding() {
		return self::$dataCache->getItem( $this->mCode, 'fallback8bitEncoding' );
	}

	/**
	 * Most writing systems use whitespace to break up words.
	 * Some languages such as Chinese don't conventionally do this,
	 * which requires special handling when breaking up words for
	 * searching etc.
	 *
	 * @return bool
	 */
	function hasWordBreaks() {
		return true;
	}

	/**
	 * Some languages such as Chinese require word segmentation,
	 * Specify such segmentation when overridden in derived class.
	 *
	 * @param $string String
	 * @return String
	 */
	function segmentByWord( $string ) {
		return $string;
	}

	/**
	 * Some languages have special punctuation need to be normalized.
	 * Make such changes here.
	 *
	 * @param $string String
	 * @return String
	 */
	function normalizeForSearch( $string ) {
		return self::convertDoubleWidth( $string );
	}

	/**
	 * convert double-width roman characters to single-width.
	 * range: ff00-ff5f ~= 0020-007f
	 *
	 * @param $string string
	 *
	 * @return string
	 */
	protected static function convertDoubleWidth( $string ) {
		static $full = null;
		static $half = null;

		if ( $full === null ) {
			$fullWidth = "０１２３４５６７８９ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚ";
			$halfWidth = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
			$full = str_split( $fullWidth, 3 );
			$half = str_split( $halfWidth );
		}

		$string = str_replace( $full, $half, $string );
		return $string;
	}

	/**
	 * @param $string string
	 * @param $pattern string
	 * @return string
	 */
	protected static function insertSpace( $string, $pattern ) {
		$string = preg_replace( $pattern, " $1 ", $string );
		$string = preg_replace( '/ +/', ' ', $string );
		return $string;
	}

	/**
	 * @param $termsArray array
	 * @return array
	 */
	function convertForSearchResult( $termsArray ) {
		# some languages, e.g. Chinese, need to do a conversion
		# in order for search results to be displayed correctly
		return $termsArray;
	}

	/**
	 * Get the first character of a string.
	 *
	 * @param $s string
	 * @return string
	 */
	function firstChar( $s ) {
		$matches = array();
		preg_match(
			'/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
				'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})/',
			$s,
			$matches
		);

		if ( isset( $matches[1] ) ) {
			if ( strlen( $matches[1] ) != 3 ) {
				return $matches[1];
			}

			// Break down Hangul syllables to grab the first jamo
			$code = utf8ToCodepoint( $matches[1] );
			if ( $code < 0xac00 || 0xd7a4 <= $code ) {
				return $matches[1];
			} elseif ( $code < 0xb098 ) {
				return "\xe3\x84\xb1";
			} elseif ( $code < 0xb2e4 ) {
				return "\xe3\x84\xb4";
			} elseif ( $code < 0xb77c ) {
				return "\xe3\x84\xb7";
			} elseif ( $code < 0xb9c8 ) {
				return "\xe3\x84\xb9";
			} elseif ( $code < 0xbc14 ) {
				return "\xe3\x85\x81";
			} elseif ( $code < 0xc0ac ) {
				return "\xe3\x85\x82";
			} elseif ( $code < 0xc544 ) {
				return "\xe3\x85\x85";
			} elseif ( $code < 0xc790 ) {
				return "\xe3\x85\x87";
			} elseif ( $code < 0xcc28 ) {
				return "\xe3\x85\x88";
			} elseif ( $code < 0xce74 ) {
				return "\xe3\x85\x8a";
			} elseif ( $code < 0xd0c0 ) {
				return "\xe3\x85\x8b";
			} elseif ( $code < 0xd30c ) {
				return "\xe3\x85\x8c";
			} elseif ( $code < 0xd558 ) {
				return "\xe3\x85\x8d";
			} else {
				return "\xe3\x85\x8e";
			}
		} else {
			return '';
		}
	}

	function initEncoding() {
		# Some languages may have an alternate char encoding option
		# (Esperanto X-coding, Japanese furigana conversion, etc)
		# If this language is used as the primary content language,
		# an override to the defaults can be set here on startup.
	}

	/**
	 * @param $s string
	 * @return string
	 */
	function recodeForEdit( $s ) {
		# For some languages we'll want to explicitly specify
		# which characters make it into the edit box raw
		# or are converted in some way or another.
		global $wgEditEncoding;
		if ( $wgEditEncoding == '' || $wgEditEncoding == 'UTF-8' ) {
			return $s;
		} else {
			return $this->iconv( 'UTF-8', $wgEditEncoding, $s );
		}
	}

	/**
	 * @param $s string
	 * @return string
	 */
	function recodeInput( $s ) {
		# Take the previous into account.
		global $wgEditEncoding;
		if ( $wgEditEncoding != '' ) {
			$enc = $wgEditEncoding;
		} else {
			$enc = 'UTF-8';
		}
		if ( $enc == 'UTF-8' ) {
			return $s;
		} else {
			return $this->iconv( $enc, 'UTF-8', $s );
		}
	}

	/**
	 * Convert a UTF-8 string to normal form C. In Malayalam and Arabic, this
	 * also cleans up certain backwards-compatible sequences, converting them
	 * to the modern Unicode equivalent.
	 *
	 * This is language-specific for performance reasons only.
	 *
	 * @param $s string
	 *
	 * @return string
	 */
	function normalize( $s ) {
		global $wgAllUnicodeFixes;
		$s = UtfNormal::cleanUp( $s );
		if ( $wgAllUnicodeFixes ) {
			$s = $this->transformUsingPairFile( 'normalize-ar.ser', $s );
			$s = $this->transformUsingPairFile( 'normalize-ml.ser', $s );
		}

		return $s;
	}

	/**
	 * Transform a string using serialized data stored in the given file (which
	 * must be in the serialized subdirectory of $IP). The file contains pairs
	 * mapping source characters to destination characters.
	 *
	 * The data is cached in process memory. This will go faster if you have the
	 * FastStringSearch extension.
	 *
	 * @param $file string
	 * @param $string string
	 *
	 * @return string
	 */
	function transformUsingPairFile( $file, $string ) {
		if ( !isset( $this->transformData[$file] ) ) {
			$data = wfGetPrecompiledData( $file );
			if ( $data === false ) {
				throw new MWException( __METHOD__ . ": The transformation file $file is missing" );
			}
			$this->transformData[$file] = new ReplacementArray( $data );
		}
		return $this->transformData[$file]->replace( $string );
	}

	/**
	 * For right-to-left language support
	 *
	 * @return bool
	 */
	function isRTL() {
		return self::$dataCache->getItem( $this->mCode, 'rtl' );
	}

	/**
	 * Return the correct HTML 'dir' attribute value for this language.
	 * @return String
	 */
	function getDir() {
		return $this->isRTL() ? 'rtl' : 'ltr';
	}

	/**
	 * Return 'left' or 'right' as appropriate alignment for line-start
	 * for this language's text direction.
	 *
	 * Should be equivalent to CSS3 'start' text-align value....
	 *
	 * @return String
	 */
	function alignStart() {
		return $this->isRTL() ? 'right' : 'left';
	}

	/**
	 * Return 'right' or 'left' as appropriate alignment for line-end
	 * for this language's text direction.
	 *
	 * Should be equivalent to CSS3 'end' text-align value....
	 *
	 * @return String
	 */
	function alignEnd() {
		return $this->isRTL() ? 'left' : 'right';
	}

	/**
	 * A hidden direction mark (LRM or RLM), depending on the language direction
	 *
	 * @param $opposite Boolean Get the direction mark opposite to your language
	 * @return string
	 */
	function getDirMark( $opposite = false ) {
		$rtl = "\xE2\x80\x8F";
		$ltr = "\xE2\x80\x8E";
		if ( $opposite ) { return $this->isRTL() ? $ltr : $rtl; }
		return $this->isRTL() ? $rtl : $ltr;
	}

	/**
	 * @return array
	 */
	function capitalizeAllNouns() {
		return self::$dataCache->getItem( $this->mCode, 'capitalizeAllNouns' );
	}

	/**
	 * An arrow, depending on the language direction
	 *
	 * @return string
	 */
	function getArrow() {
		return $this->isRTL() ? '←' : '→';
	}

	/**
	 * To allow "foo[[bar]]" to extend the link over the whole word "foobar"
	 *
	 * @return bool
	 */
	function linkPrefixExtension() {
		return self::$dataCache->getItem( $this->mCode, 'linkPrefixExtension' );
	}

	/**
	 * @return array
	 */
	function getMagicWords() {
		return self::$dataCache->getItem( $this->mCode, 'magicWords' );
	}

	protected function doMagicHook() {
		if ( $this->mMagicHookDone ) {
			return;
		}
		$this->mMagicHookDone = true;
		wfProfileIn( 'LanguageGetMagic' );
		wfRunHooks( 'LanguageGetMagic', array( &$this->mMagicExtensions, $this->getCode() ) );
		wfProfileOut( 'LanguageGetMagic' );
	}

	/**
	 * Fill a MagicWord object with data from here
	 *
	 * @param $mw
	 */
	function getMagic( $mw ) {
		$this->doMagicHook();

		if ( isset( $this->mMagicExtensions[$mw->mId] ) ) {
			$rawEntry = $this->mMagicExtensions[$mw->mId];
		} else {
			$magicWords = $this->getMagicWords();
			if ( isset( $magicWords[$mw->mId] ) ) {
				$rawEntry = $magicWords[$mw->mId];
			} else {
				$rawEntry = false;
			}
		}

		if ( !is_array( $rawEntry ) ) {
			error_log( "\"$rawEntry\" is not a valid magic word for \"$mw->mId\"" );
		} else {
			$mw->mCaseSensitive = $rawEntry[0];
			$mw->mSynonyms = array_slice( $rawEntry, 1 );
		}
	}

	/**
	 * Add magic words to the extension array
	 *
	 * @param $newWords array
	 */
	function addMagicWordsByLang( $newWords ) {
		$fallbackChain = $this->getFallbackLanguages();
		$fallbackChain = array_reverse( $fallbackChain );
		foreach ( $fallbackChain as $code ) {
			if ( isset( $newWords[$code] ) ) {
				$this->mMagicExtensions = $newWords[$code] + $this->mMagicExtensions;
			}
		}
	}

	/**
	 * Get special page names, as an associative array
	 *   case folded alias => real name
	 */
	function getSpecialPageAliases() {
		// Cache aliases because it may be slow to load them
		if ( is_null( $this->mExtendedSpecialPageAliases ) ) {
			// Initialise array
			$this->mExtendedSpecialPageAliases =
				self::$dataCache->getItem( $this->mCode, 'specialPageAliases' );
			wfRunHooks( 'LanguageGetSpecialPageAliases',
				array( &$this->mExtendedSpecialPageAliases, $this->getCode() ) );
		}

		return $this->mExtendedSpecialPageAliases;
	}

	/**
	 * Italic is unsuitable for some languages
	 *
	 * @param $text String: the text to be emphasized.
	 * @return string
	 */
	function emphasize( $text ) {
		return "<em>$text</em>";
	}

	 /**
	  * Normally we output all numbers in plain en_US style, that is
	  * 293,291.235 for twohundredninetythreethousand-twohundredninetyone
	  * point twohundredthirtyfive. However this is not suitable for all
	  * languages, some such as Pakaran want ੨੯੩,੨੯੫.੨੩੫ and others such as
	  * Icelandic just want to use commas instead of dots, and dots instead
	  * of commas like "293.291,235".
	  *
	  * An example of this function being called:
	  * <code>
	  * wfMsg( 'message', $wgLang->formatNum( $num ) )
	  * </code>
	  *
	  * See LanguageGu.php for the Gujarati implementation and
	  * $separatorTransformTable on MessageIs.php for
	  * the , => . and . => , implementation.
	  *
	  * @todo check if it's viable to use localeconv() for the decimal
	  *       separator thing.
	  * @param $number Mixed: the string to be formatted, should be an integer
	  *        or a floating point number.
	  * @param $nocommafy Bool: set to true for special numbers like dates
	  * @return string
	  */
	public function formatNum( $number, $nocommafy = false ) {
		global $wgTranslateNumerals;
		if ( !$nocommafy ) {
			$number = $this->commafy( $number );
			$s = $this->separatorTransformTable();
			if ( $s ) {
				$number = strtr( $number, $s );
			}
		}

		if ( $wgTranslateNumerals ) {
			$s = $this->digitTransformTable();
			if ( $s ) {
				$number = strtr( $number, $s );
			}
		}

		return $number;
	}

	/**
	 * @param $number string
	 * @return string
	 */
	function parseFormattedNumber( $number ) {
		$s = $this->digitTransformTable();
		if ( $s ) {
			$number = strtr( $number, array_flip( $s ) );
		}

		$s = $this->separatorTransformTable();
		if ( $s ) {
			$number = strtr( $number, array_flip( $s ) );
		}

		$number = strtr( $number, array( ',' => '' ) );
		return $number;
	}

	/**
	 * Adds commas to a given number
	 * @since 1.19
	 * @param $_ mixed
	 * @return string
	 */
	function commafy( $_ ) {
		$digitGroupingPattern = $this->digitGroupingPattern();

		if ( !$digitGroupingPattern || $digitGroupingPattern === "###,###,###" ) {
			// default grouping is at thousands,  use the same for ###,###,### pattern too.
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $_ ) ) );
		} else {
			// Ref: http://cldr.unicode.org/translation/number-patterns
			$sign = "";
			if ( intval( $_ ) < 0 ) {
				// For negative numbers apply the algorithm like positive number and add sign.
				$sign =  "-";
				$_ = substr( $_,1 );
			}
			$numberpart = array();
			$decimalpart = array();
			$numMatches = preg_match_all( "/(#+)/", $digitGroupingPattern, $matches );
			preg_match( "/\d+/", $_, $numberpart );
			preg_match( "/\.\d*/", $_, $decimalpart );
			$groupedNumber = ( count( $decimalpart ) > 0 ) ? $decimalpart[0]:"";
			if ( $groupedNumber  === $_ ) {
				// the string does not have any number part. Eg: .12345
				return $sign . $groupedNumber;
			}
			$start = $end = strlen( $numberpart[0] );
			while ( $start > 0 ) {
				$match = $matches[0][$numMatches -1] ;
				$matchLen = strlen( $match );
				$start = $end - $matchLen;
				if ( $start < 0 ) {
					$start = 0;
				}
				$groupedNumber = substr( $_ , $start, $end -$start ) . $groupedNumber ;
				$end = $start;
				if ( $numMatches > 1 ) {
					// use the last pattern for the rest of the number
					$numMatches--;
				}
				if ( $start > 0 ) {
					$groupedNumber = "," . $groupedNumber;
				}
			}
			return $sign . $groupedNumber;
		}
	}
	/**
	 * @return String
	 */
	function digitGroupingPattern() {
		return self::$dataCache->getItem( $this->mCode, 'digitGroupingPattern' );
	}

	/**
	 * @return array
	 */
	function digitTransformTable() {
		return self::$dataCache->getItem( $this->mCode, 'digitTransformTable' );
	}

	/**
	 * @return array
	 */
	function separatorTransformTable() {
		return self::$dataCache->getItem( $this->mCode, 'separatorTransformTable' );
	}

	/**
	 * Take a list of strings and build a locale-friendly comma-separated
	 * list, using the local comma-separator message.
	 * The last two strings are chained with an "and".
	 *
	 * @param $l Array
	 * @return string
	 */
	function listToText( array $l ) {
		$s = '';
		$m = count( $l ) - 1;
		if ( $m == 1 ) {
			return $l[0] . $this->getMessageFromDB( 'and' ) . $this->getMessageFromDB( 'word-separator' ) . $l[1];
		} else {
			for ( $i = $m; $i >= 0; $i-- ) {
				if ( $i == $m ) {
					$s = $l[$i];
				} elseif ( $i == $m - 1 ) {
					$s = $l[$i] . $this->getMessageFromDB( 'and' ) . $this->getMessageFromDB( 'word-separator' ) . $s;
				} else {
					$s = $l[$i] . $this->getMessageFromDB( 'comma-separator' ) . $s;
				}
			}
			return $s;
		}
	}

	/**
	 * Take a list of strings and build a locale-friendly comma-separated
	 * list, using the local comma-separator message.
	 * @param $list array of strings to put in a comma list
	 * @return string
	 */
	function commaList( array $list ) {
		return implode(
			wfMsgExt(
				'comma-separator',
				array( 'parsemag', 'escapenoentities', 'language' => $this )
			),
			$list
		);
	}

	/**
	 * Take a list of strings and build a locale-friendly semicolon-separated
	 * list, using the local semicolon-separator message.
	 * @param $list array of strings to put in a semicolon list
	 * @return string
	 */
	function semicolonList( array $list ) {
		return implode(
			wfMsgExt(
				'semicolon-separator',
				array( 'parsemag', 'escapenoentities', 'language' => $this )
			),
			$list
		);
	}

	/**
	 * Same as commaList, but separate it with the pipe instead.
	 * @param $list array of strings to put in a pipe list
	 * @return string
	 */
	function pipeList( array $list ) {
		return implode(
			wfMsgExt(
				'pipe-separator',
				array( 'escapenoentities', 'language' => $this )
			),
			$list
		);
	}

	/**
	 * Truncate a string to a specified length in bytes, appending an optional
	 * string (e.g. for ellipses)
	 *
	 * The database offers limited byte lengths for some columns in the database;
	 * multi-byte character sets mean we need to ensure that only whole characters
	 * are included, otherwise broken characters can be passed to the user
	 *
	 * If $length is negative, the string will be truncated from the beginning
	 *
	 * @param $string String to truncate
	 * @param $length Int: maximum length (including ellipses)
	 * @param $ellipsis String to append to the truncated text
	 * @param $adjustLength Boolean: Subtract length of ellipsis from $length.
	 *	$adjustLength was introduced in 1.18, before that behaved as if false.
	 * @return string
	 */
	function truncate( $string, $length, $ellipsis = '...', $adjustLength = true ) {
		# Use the localized ellipsis character
		if ( $ellipsis == '...' ) {
			$ellipsis = wfMsgExt( 'ellipsis', array( 'escapenoentities', 'language' => $this ) );
		}
		# Check if there is no need to truncate
		if ( $length == 0 ) {
			return $ellipsis; // convention
		} elseif ( strlen( $string ) <= abs( $length ) ) {
			return $string; // no need to truncate
		}
		$stringOriginal = $string;
		# If ellipsis length is >= $length then we can't apply $adjustLength
		if ( $adjustLength && strlen( $ellipsis ) >= abs( $length ) ) {
			$string = $ellipsis; // this can be slightly unexpected
		# Otherwise, truncate and add ellipsis...
		} else {
			$eLength = $adjustLength ? strlen( $ellipsis ) : 0;
			if ( $length > 0 ) {
				$length -= $eLength;
				$string = substr( $string, 0, $length ); // xyz...
				$string = $this->removeBadCharLast( $string );
				$string = $string . $ellipsis;
			} else {
				$length += $eLength;
				$string = substr( $string, $length ); // ...xyz
				$string = $this->removeBadCharFirst( $string );
				$string = $ellipsis . $string;
			}
		}
		# Do not truncate if the ellipsis makes the string longer/equal (bug 22181).
		# This check is *not* redundant if $adjustLength, due to the single case where
		# LEN($ellipsis) > ABS($limit arg); $stringOriginal could be shorter than $string.
		if ( strlen( $string ) < strlen( $stringOriginal ) ) {
			return $string;
		} else {
			return $stringOriginal;
		}
	}

	/**
	 * Remove bytes that represent an incomplete Unicode character
	 * at the end of string (e.g. bytes of the char are missing)
	 *
	 * @param $string String
	 * @return string
	 */
	protected function removeBadCharLast( $string ) {
		if ( $string != '' ) {
			$char = ord( $string[strlen( $string ) - 1] );
			$m = array();
			if ( $char >= 0xc0 ) {
				# We got the first byte only of a multibyte char; remove it.
				$string = substr( $string, 0, -1 );
			} elseif ( $char >= 0x80 &&
				  preg_match( '/^(.*)(?:[\xe0-\xef][\x80-\xbf]|' .
							  '[\xf0-\xf7][\x80-\xbf]{1,2})$/', $string, $m ) )
			{
				# We chopped in the middle of a character; remove it
				$string = $m[1];
			}
		}
		return $string;
	}

	/**
	 * Remove bytes that represent an incomplete Unicode character
	 * at the start of string (e.g. bytes of the char are missing)
	 *
	 * @param $string String
	 * @return string
	 */
	protected function removeBadCharFirst( $string ) {
		if ( $string != '' ) {
			$char = ord( $string[0] );
			if ( $char >= 0x80 && $char < 0xc0 ) {
				# We chopped in the middle of a character; remove the whole thing
				$string = preg_replace( '/^[\x80-\xbf]+/', '', $string );
			}
		}
		return $string;
	}

	/**
	 * Truncate a string of valid HTML to a specified length in bytes,
	 * appending an optional string (e.g. for ellipses), and return valid HTML
	 *
	 * This is only intended for styled/linked text, such as HTML with
	 * tags like <span> and <a>, were the tags are self-contained (valid HTML).
	 * Also, this will not detect things like "display:none" CSS.
	 *
	 * Note: since 1.18 you do not need to leave extra room in $length for ellipses.
	 *
	 * @param string $text HTML string to truncate
	 * @param int $length (zero/positive) Maximum length (including ellipses)
	 * @param string $ellipsis String to append to the truncated text
	 * @return string
	 */
	function truncateHtml( $text, $length, $ellipsis = '...' ) {
		# Use the localized ellipsis character
		if ( $ellipsis == '...' ) {
			$ellipsis = wfMsgExt( 'ellipsis', array( 'escapenoentities', 'language' => $this ) );
		}
		# Check if there is clearly no need to truncate
		if ( $length <= 0 ) {
			return $ellipsis; // no text shown, nothing to format (convention)
		} elseif ( strlen( $text ) <= $length ) {
			return $text; // string short enough even *with* HTML (short-circuit)
		}

		$dispLen = 0; // innerHTML legth so far
		$testingEllipsis = false; // checking if ellipses will make string longer/equal?
		$tagType = 0; // 0-open, 1-close
		$bracketState = 0; // 1-tag start, 2-tag name, 0-neither
		$entityState = 0; // 0-not entity, 1-entity
		$tag = $ret = ''; // accumulated tag name, accumulated result string
		$openTags = array(); // open tag stack
		$maybeState = null; // possible truncation state

		$textLen = strlen( $text );
		$neLength = max( 0, $length - strlen( $ellipsis ) ); // non-ellipsis len if truncated
		for ( $pos = 0; true; ++$pos ) {
			# Consider truncation once the display length has reached the maximim.
			# We check if $dispLen > 0 to grab tags for the $neLength = 0 case.
			# Check that we're not in the middle of a bracket/entity...
			if ( $dispLen && $dispLen >= $neLength && $bracketState == 0 && !$entityState ) {
				if ( !$testingEllipsis ) {
					$testingEllipsis = true;
					# Save where we are; we will truncate here unless there turn out to
					# be so few remaining characters that truncation is not necessary.
					if ( !$maybeState ) { // already saved? ($neLength = 0 case)
						$maybeState = array( $ret, $openTags ); // save state
					}
				} elseif ( $dispLen > $length && $dispLen > strlen( $ellipsis ) ) {
					# String in fact does need truncation, the truncation point was OK.
					list( $ret, $openTags ) = $maybeState; // reload state
					$ret = $this->removeBadCharLast( $ret ); // multi-byte char fix
					$ret .= $ellipsis; // add ellipsis
					break;
				}
			}
			if ( $pos >= $textLen ) break; // extra iteration just for above checks

			# Read the next char...
			$ch = $text[$pos];
			$lastCh = $pos ? $text[$pos - 1] : '';
			$ret .= $ch; // add to result string
			if ( $ch == '<' ) {
				$this->truncate_endBracket( $tag, $tagType, $lastCh, $openTags ); // for bad HTML
				$entityState = 0; // for bad HTML
				$bracketState = 1; // tag started (checking for backslash)
			} elseif ( $ch == '>' ) {
				$this->truncate_endBracket( $tag, $tagType, $lastCh, $openTags );
				$entityState = 0; // for bad HTML
				$bracketState = 0; // out of brackets
			} elseif ( $bracketState == 1 ) {
				if ( $ch == '/' ) {
					$tagType = 1; // close tag (e.g. "</span>")
				} else {
					$tagType = 0; // open tag (e.g. "<span>")
					$tag .= $ch;
				}
				$bracketState = 2; // building tag name
			} elseif ( $bracketState == 2 ) {
				if ( $ch != ' ' ) {
					$tag .= $ch;
				} else {
					// Name found (e.g. "<a href=..."), add on tag attributes...
					$pos += $this->truncate_skip( $ret, $text, "<>", $pos + 1 );
				}
			} elseif ( $bracketState == 0 ) {
				if ( $entityState ) {
					if ( $ch == ';' ) {
						$entityState = 0;
						$dispLen++; // entity is one displayed char
					}
				} else {
					if ( $neLength == 0 && !$maybeState ) {
						// Save state without $ch. We want to *hit* the first
						// display char (to get tags) but not *use* it if truncating.
						$maybeState = array( substr( $ret, 0, -1 ), $openTags );
					}
					if ( $ch == '&' ) {
						$entityState = 1; // entity found, (e.g. "&#160;")
					} else {
						$dispLen++; // this char is displayed
						// Add the next $max display text chars after this in one swoop...
						$max = ( $testingEllipsis ? $length : $neLength ) - $dispLen;
						$skipped = $this->truncate_skip( $ret, $text, "<>&", $pos + 1, $max );
						$dispLen += $skipped;
						$pos += $skipped;
					}
				}
			}
		}
		// Close the last tag if left unclosed by bad HTML
		$this->truncate_endBracket( $tag, $text[$textLen - 1], $tagType, $openTags );
		while ( count( $openTags ) > 0 ) {
			$ret .= '</' . array_pop( $openTags ) . '>'; // close open tags
		}
		return $ret;
	}

	/**
	 * truncateHtml() helper function
	 * like strcspn() but adds the skipped chars to $ret
	 *
	 * @param $ret
	 * @param $text
	 * @param $search
	 * @param $start
	 * @param $len
	 * @return int
	 */
	private function truncate_skip( &$ret, $text, $search, $start, $len = null ) {
		if ( $len === null ) {
			$len = -1; // -1 means "no limit" for strcspn
		} elseif ( $len < 0 ) {
			$len = 0; // sanity
		}
		$skipCount = 0;
		if ( $start < strlen( $text ) ) {
			$skipCount = strcspn( $text, $search, $start, $len );
			$ret .= substr( $text, $start, $skipCount );
		}
		return $skipCount;
	}

	/**
	 * truncateHtml() helper function
	 * (a) push or pop $tag from $openTags as needed
	 * (b) clear $tag value
	 * @param &$tag string Current HTML tag name we are looking at
	 * @param $tagType int (0-open tag, 1-close tag)
	 * @param $lastCh char|string Character before the '>' that ended this tag
	 * @param &$openTags array Open tag stack (not accounting for $tag)
	 */
	private function truncate_endBracket( &$tag, $tagType, $lastCh, &$openTags ) {
		$tag = ltrim( $tag );
		if ( $tag != '' ) {
			if ( $tagType == 0 && $lastCh != '/' ) {
				$openTags[] = $tag; // tag opened (didn't close itself)
			} elseif ( $tagType == 1 ) {
				if ( $openTags && $tag == $openTags[count( $openTags ) - 1] ) {
					array_pop( $openTags ); // tag closed
				}
			}
			$tag = '';
		}
	}

	/**
	 * Grammatical transformations, needed for inflected languages
	 * Invoked by putting {{grammar:case|word}} in a message
	 *
	 * @param $word string
	 * @param $case string
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms[$this->getCode()][$case][$word] ) ) {
			return $wgGrammarForms[$this->getCode()][$case][$word];
		}
		return $word;
	}

	/**
	 * Provides an alternative text depending on specified gender.
	 * Usage {{gender:username|masculine|feminine|neutral}}.
	 * username is optional, in which case the gender of current user is used,
	 * but only in (some) interface messages; otherwise default gender is used.
	 * If second or third parameter are not specified, masculine is used.
	 * These details may be overriden per language.
	 *
	 * @param $gender string
	 * @param $forms array
	 *
	 * @return string
	 */
	function gender( $gender, $forms ) {
		if ( !count( $forms ) ) {
			return '';
		}
		$forms = $this->preConvertPlural( $forms, 2 );
		if ( $gender === 'male' ) {
			return $forms[0];
		}
		if ( $gender === 'female' ) {
			return $forms[1];
		}
		return isset( $forms[2] ) ? $forms[2] : $forms[0];
	}

	/**
	 * Plural form transformations, needed for some languages.
	 * For example, there are 3 form of plural in Russian and Polish,
	 * depending on "count mod 10". See [[w:Plural]]
	 * For English it is pretty simple.
	 *
	 * Invoked by putting {{plural:count|wordform1|wordform2}}
	 * or {{plural:count|wordform1|wordform2|wordform3}}
	 *
	 * Example: {{plural:{{NUMBEROFARTICLES}}|article|articles}}
	 *
	 * @param $count Integer: non-localized number
	 * @param $forms Array: different plural forms
	 * @return string Correct form of plural for $count in this language
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) {
			return '';
		}
		$forms = $this->preConvertPlural( $forms, 2 );

		return ( $count == 1 ) ? $forms[0] : $forms[1];
	}

	/**
	 * Checks that convertPlural was given an array and pads it to requested
	 * amount of forms by copying the last one.
	 *
	 * @param $count Integer: How many forms should there be at least
	 * @param $forms Array of forms given to convertPlural
	 * @return array Padded array of forms or an exception if not an array
	 */
	protected function preConvertPlural( /* Array */ $forms, $count ) {
		while ( count( $forms ) < $count ) {
			$forms[] = $forms[count( $forms ) - 1];
		}
		return $forms;
	}

	/**
	 * @todo Maybe translate block durations.  Note that this function is somewhat misnamed: it
	 * deals with translating the *duration* ("1 week", "4 days", etc), not the expiry time
	 * (which is an absolute timestamp). Please note: do NOT add this blindly, as it is used
	 * on old expiry lengths recorded in log entries. You'd need to provide the start date to
	 * match up with it.
	 *
	 * @param $str String: the validated block duration in English
	 * @return Somehow translated block duration
	 * @see LanguageFi.php for example implementation
	 */
	function translateBlockExpiry( $str ) {
		$duration = SpecialBlock::getSuggestedDurations( $this );
		foreach ( $duration as $show => $value ) {
			if ( strcmp( $str, $value ) == 0 ) {
				return htmlspecialchars( trim( $show ) );
			}
		}

		// Since usually only infinite or indefinite is only on list, so try
		// equivalents if still here.
		$indefs = array( 'infinite', 'infinity', 'indefinite' );
		if ( in_array( $str, $indefs ) ) {
			foreach ( $indefs as $val ) {
				$show = array_search( $val, $duration, true );
				if ( $show !== false ) {
					return htmlspecialchars( trim( $show ) );
				}
			}
		}
		// If all else fails, return the original string.
		return $str;
	}

	/**
	 * languages like Chinese need to be segmented in order for the diff
	 * to be of any use
	 *
	 * @param $text String
	 * @return String
	 */
	public function segmentForDiff( $text ) {
		return $text;
	}

	/**
	 * and unsegment to show the result
	 *
	 * @param $text String
	 * @return String
	 */
	public function unsegmentForDiff( $text ) {
		return $text;
	}

	/**
	 * Return the LanguageConverter used in the Language
	 * @return LanguageConverter
	 */
	public function getConverter() {
		return $this->mConverter;
	}

	/**
	 * convert text to all supported variants
	 *
	 * @param $text string
	 * @return array
	 */
	public function autoConvertToAllVariants( $text ) {
		return $this->mConverter->autoConvertToAllVariants( $text );
	}

	/**
	 * convert text to different variants of a language.
	 *
	 * @param $text string
	 * @return string
	 */
	public function convert( $text ) {
		return $this->mConverter->convert( $text );
	}

	/**
	 * Convert a Title object to a string in the preferred variant
	 *
	 * @param $title Title
	 * @return string
	 */
	public function convertTitle( $title ) {
		return $this->mConverter->convertTitle( $title );
	}

	/**
	 * Check if this is a language with variants
	 *
	 * @return bool
	 */
	public function hasVariants() {
		return sizeof( $this->getVariants() ) > 1;
	}

	/**
	 * Check if the language has the specific variant
	 * @param $variant string
	 * @return bool
	 */
	public function hasVariant( $variant ) {
		return (bool)$this->mConverter->validateVariant( $variant );
	}

	/**
	 * Put custom tags (e.g. -{ }-) around math to prevent conversion
	 *
	 * @param $text string
	 * @return string
	 */
	public function armourMath( $text ) {
		return $this->mConverter->armourMath( $text );
	}

	/**
	 * Perform output conversion on a string, and encode for safe HTML output.
	 * @param $text String text to be converted
	 * @param $isTitle Bool whether this conversion is for the article title
	 * @return string
	 * @todo this should get integrated somewhere sane
	 */
	public function convertHtml( $text, $isTitle = false ) {
		return htmlspecialchars( $this->convert( $text, $isTitle ) );
	}

	/**
	 * @param $key string
	 * @return string
	 */
	public function convertCategoryKey( $key ) {
		return $this->mConverter->convertCategoryKey( $key );
	}

	/**
	 * Get the list of variants supported by this language
	 * see sample implementation in LanguageZh.php
	 *
	 * @return array an array of language codes
	 */
	public function getVariants() {
		return $this->mConverter->getVariants();
	}

	/**
	 * @return string
	 */
	public function getPreferredVariant() {
		return $this->mConverter->getPreferredVariant();
	}

	/**
	 * @return string
	 */
	public function getDefaultVariant() {
		return $this->mConverter->getDefaultVariant();
	}

	/**
	 * @return string
	 */
	public function getURLVariant() {
		return $this->mConverter->getURLVariant();
	}

	/**
	 * If a language supports multiple variants, it is
	 * possible that non-existing link in one variant
	 * actually exists in another variant. this function
	 * tries to find it. See e.g. LanguageZh.php
	 *
	 * @param $link String: the name of the link
	 * @param $nt Mixed: the title object of the link
	 * @param $ignoreOtherCond Boolean: to disable other conditions when
	 *      we need to transclude a template or update a category's link
	 * @return null the input parameters may be modified upon return
	 */
	public function findVariantLink( &$link, &$nt, $ignoreOtherCond = false ) {
		$this->mConverter->findVariantLink( $link, $nt, $ignoreOtherCond );
	}

	/**
	 * If a language supports multiple variants, converts text
	 * into an array of all possible variants of the text:
	 *  'variant' => text in that variant
	 *
	 * @deprecated since 1.17 Use autoConvertToAllVariants()
	 *
	 * @param $text string
	 *
	 * @return string
	 */
	public function convertLinkToAllVariants( $text ) {
		return $this->mConverter->convertLinkToAllVariants( $text );
	}

	/**
	 * returns language specific options used by User::getPageRenderHash()
	 * for example, the preferred language variant
	 *
	 * @return string
	 */
	function getExtraHashOptions() {
		return $this->mConverter->getExtraHashOptions();
	}

	/**
	 * For languages that support multiple variants, the title of an
	 * article may be displayed differently in different variants. this
	 * function returns the apporiate title defined in the body of the article.
	 *
	 * @return string
	 */
	public function getParsedTitle() {
		return $this->mConverter->getParsedTitle();
	}

	/**
	 * Enclose a string with the "no conversion" tag. This is used by
	 * various functions in the Parser
	 *
	 * @param $text String: text to be tagged for no conversion
	 * @param $noParse bool
	 * @return string the tagged text
	 */
	public function markNoConversion( $text, $noParse = false ) {
		return $this->mConverter->markNoConversion( $text, $noParse );
	}

	/**
	 * A regular expression to match legal word-trailing characters
	 * which should be merged onto a link of the form [[foo]]bar.
	 *
	 * @return string
	 */
	public function linkTrail() {
		return self::$dataCache->getItem( $this->mCode, 'linkTrail' );
	}

	/**
	 * @return Language
	 */
	function getLangObj() {
		return $this;
	}

	/**
	 * Get the RFC 3066 code for this language object
	 *
	 * @return string
	 */
	public function getCode() {
		return $this->mCode;
	}

	/**
	 * Get the code in Bcp47 format which we can use
	 * inside of html lang="" tags.
	 * @since 1.19
	 * @return string
	 */
	public function getHtmlCode() {
		if ( is_null( $this->mHtmlCode ) ) {
			$this->mHtmlCode = wfBCP47( $this->getCode() );
		}
		return $this->mHtmlCode;
	}

	/**
	 * @param $code string
	 */
	public function setCode( $code ) {
		$this->mCode = $code;
		// Ensure we don't leave an incorrect html code lying around
		$this->mHtmlCode = null;
	}

	/**
	 * Get the name of a file for a certain language code
	 * @param $prefix string Prepend this to the filename
	 * @param $code string Language code
	 * @param $suffix string Append this to the filename
	 * @return string $prefix . $mangledCode . $suffix
	 */
	public static function getFileName( $prefix = 'Language', $code, $suffix = '.php' ) {
		// Protect against path traversal
		if ( !Language::isValidCode( $code )
			|| strcspn( $code, ":/\\\000" ) !== strlen( $code ) )
		{
			throw new MWException( "Invalid language code \"$code\"" );
		}

		return $prefix . str_replace( '-', '_', ucfirst( $code ) ) . $suffix;
	}

	/**
	 * Get the language code from a file name. Inverse of getFileName()
	 * @param $filename string $prefix . $languageCode . $suffix
	 * @param $prefix string Prefix before the language code
	 * @param $suffix string Suffix after the language code
	 * @return string Language code, or false if $prefix or $suffix isn't found
	 */
	public static function getCodeFromFileName( $filename, $prefix = 'Language', $suffix = '.php' ) {
		$m = null;
		preg_match( '/' . preg_quote( $prefix, '/' ) . '([A-Z][a-z_]+)' .
			preg_quote( $suffix, '/' ) . '/', $filename, $m );
		if ( !count( $m ) ) {
			return false;
		}
		return str_replace( '_', '-', strtolower( $m[1] ) );
	}

	/**
	 * @param $code string
	 * @return string
	 */
	public static function getMessagesFileName( $code ) {
		global $IP;
		$file = self::getFileName( "$IP/languages/messages/Messages", $code, '.php' );
		wfRunHooks( 'Language::getMessagesFileName', array( $code, &$file ) );
		return $file;
	}

	/**
	 * @param $code string
	 * @return string
	 */
	public static function getClassFileName( $code ) {
		global $IP;
		return self::getFileName( "$IP/languages/classes/Language", $code, '.php' );
	}

	/**
	 * Get the first fallback for a given language.
	 *
	 * @param $code string
	 *
	 * @return false|string
	 */
	public static function getFallbackFor( $code ) {
		if ( $code === 'en' || !Language::isValidBuiltInCode( $code ) ) {
			return false;
		} else {
			$fallbacks = self::getFallbacksFor( $code );
			$first = array_shift( $fallbacks );
			return $first;
		}
	}

	/**
	 * Get the ordered list of fallback languages.
	 *
	 * @since 1.19
	 * @param $code string Language code
	 * @return array
	 */
	public static function getFallbacksFor( $code ) {
		if ( $code === 'en' || !Language::isValidBuiltInCode( $code ) ) {
			return array();
		} else {
			$v = self::getLocalisationCache()->getItem( $code, 'fallback' );
			$v = array_map( 'trim', explode( ',', $v ) );
			if ( $v[count( $v ) - 1] !== 'en' ) {
				$v[] = 'en';
			}
			return $v;
		}
	}

	/**
	 * Get all messages for a given language
	 * WARNING: this may take a long time. If you just need all message *keys*
	 * but need the *contents* of only a few messages, consider using getMessageKeysFor().
	 *
	 * @param $code string
	 *
	 * @return array
	 */
	public static function getMessagesFor( $code ) {
		return self::getLocalisationCache()->getItem( $code, 'messages' );
	}

	/**
	 * Get a message for a given language
	 *
	 * @param $key string
	 * @param $code string
	 *
	 * @return string
	 */
	public static function getMessageFor( $key, $code ) {
		return self::getLocalisationCache()->getSubitem( $code, 'messages', $key );
	}

	/**
	 * Get all message keys for a given language. This is a faster alternative to
	 * array_keys( Language::getMessagesFor( $code ) )
	 *
	 * @since 1.19
	 * @param $code string Language code
	 * @return array of message keys (strings)
	 */
	public static function getMessageKeysFor( $code ) {
		return self::getLocalisationCache()->getSubItemList( $code, 'messages' );
	}

	/**
	 * @param $talk
	 * @return mixed
	 */
	function fixVariableInNamespace( $talk ) {
		if ( strpos( $talk, '$1' ) === false ) {
			return $talk;
		}

		global $wgMetaNamespace;
		$talk = str_replace( '$1', $wgMetaNamespace, $talk );

		# Allow grammar transformations
		# Allowing full message-style parsing would make simple requests
		# such as action=raw much more expensive than they need to be.
		# This will hopefully cover most cases.
		$talk = preg_replace_callback( '/{{grammar:(.*?)\|(.*?)}}/i',
			array( &$this, 'replaceGrammarInNamespace' ), $talk );
		return str_replace( ' ', '_', $talk );
	}

	/**
	 * @param $m string
	 * @return string
	 */
	function replaceGrammarInNamespace( $m ) {
		return $this->convertGrammar( trim( $m[2] ), trim( $m[1] ) );
	}

	/**
	 * @throws MWException
	 * @return array
	 */
	static function getCaseMaps() {
		static $wikiUpperChars, $wikiLowerChars;
		if ( isset( $wikiUpperChars ) ) {
			return array( $wikiUpperChars, $wikiLowerChars );
		}

		wfProfileIn( __METHOD__ );
		$arr = wfGetPrecompiledData( 'Utf8Case.ser' );
		if ( $arr === false ) {
			throw new MWException(
				"Utf8Case.ser is missing, please run \"make\" in the serialized directory\n" );
		}
		$wikiUpperChars = $arr['wikiUpperChars'];
		$wikiLowerChars = $arr['wikiLowerChars'];
		wfProfileOut( __METHOD__ );
		return array( $wikiUpperChars, $wikiLowerChars );
	}

	/**
	 * Decode an expiry (block, protection, etc) which has come from the DB
	 *
	 * @param $expiry String: Database expiry String
	 * @param $format Bool|Int true to process using language functions, or TS_ constant
	 *     to return the expiry in a given timestamp
	 * @return String
	 */
	public function formatExpiry( $expiry, $format = true ) {
		static $infinity, $infinityMsg;
		if ( $infinity === null ) {
			$infinityMsg = wfMessage( 'infiniteblock' );
			$infinity = wfGetDB( DB_SLAVE )->getInfinity();
		}

		if ( $expiry == '' || $expiry == $infinity ) {
			return $format === true
				? $infinityMsg
				: $infinity;
		} else {
			return $format === true
				? $this->timeanddate( $expiry, /* User preference timezone */ true )
				: wfTimestamp( $format, $expiry );
		}
	}

	/**
	 * @todo Document
	 * @param $seconds int|float
	 * @param $format Array Optional
	 *		If $format['avoid'] == 'avoidseconds' - don't mention seconds if $seconds >= 1 hour
	 *		If $format['avoid'] == 'avoidminutes' - don't mention seconds/minutes if $seconds > 48 hours
	 *		If $format['noabbrevs'] is true - use 'seconds' and friends instead of 'seconds-abbrev' and friends
	 *		For backwards compatibility, $format may also be one of the strings 'avoidseconds' or 'avoidminutes'
	 * @return string
	 */
	function formatTimePeriod( $seconds, $format = array() ) {
		if ( !is_array( $format ) ) {
			$format = array( 'avoid' => $format ); // For backwards compatibility
		}
		if ( !isset( $format['avoid'] ) ) {
			$format['avoid'] = false;
		}
		if ( !isset( $format['noabbrevs' ] ) ) {
			$format['noabbrevs'] = false;
		}
		$secondsMsg = wfMessage(
			$format['noabbrevs'] ? 'seconds' : 'seconds-abbrev' )->inLanguage( $this );
		$minutesMsg = wfMessage(
			$format['noabbrevs'] ? 'minutes' : 'minutes-abbrev' )->inLanguage( $this );
		$hoursMsg = wfMessage(
			$format['noabbrevs'] ? 'hours' : 'hours-abbrev' )->inLanguage( $this );
		$daysMsg = wfMessage(
			$format['noabbrevs'] ? 'days' : 'days-abbrev' )->inLanguage( $this );

		if ( round( $seconds * 10 ) < 100 ) {
			$s = $this->formatNum( sprintf( "%.1f", round( $seconds * 10 ) / 10 ) );
			$s = $secondsMsg->params( $s )->text();
		} elseif ( round( $seconds ) < 60 ) {
			$s = $this->formatNum( round( $seconds ) );
			$s = $secondsMsg->params( $s )->text();
		} elseif ( round( $seconds ) < 3600 ) {
			$minutes = floor( $seconds / 60 );
			$secondsPart = round( fmod( $seconds, 60 ) );
			if ( $secondsPart == 60 ) {
				$secondsPart = 0;
				$minutes++;
			}
			$s = $minutesMsg->params( $this->formatNum( $minutes ) )->text();
			$s .= ' ';
			$s .= $secondsMsg->params( $this->formatNum( $secondsPart ) )->text();
		} elseif ( round( $seconds ) <= 2 * 86400 ) {
			$hours = floor( $seconds / 3600 );
			$minutes = floor( ( $seconds - $hours * 3600 ) / 60 );
			$secondsPart = round( $seconds - $hours * 3600 - $minutes * 60 );
			if ( $secondsPart == 60 ) {
				$secondsPart = 0;
				$minutes++;
			}
			if ( $minutes == 60 ) {
				$minutes = 0;
				$hours++;
			}
			$s = $hoursMsg->params( $this->formatNum( $hours ) )->text();
			$s .= ' ';
			$s .= $minutesMsg->params( $this->formatNum( $minutes ) )->text();
			if ( !in_array( $format['avoid'], array( 'avoidseconds', 'avoidminutes' ) ) ) {
				$s .= ' ' . $secondsMsg->params( $this->formatNum( $secondsPart ) )->text();
			}
		} else {
			$days = floor( $seconds / 86400 );
			if ( $format['avoid'] === 'avoidminutes' ) {
				$hours = round( ( $seconds - $days * 86400 ) / 3600 );
				if ( $hours == 24 ) {
					$hours = 0;
					$days++;
				}
				$s = $daysMsg->params( $this->formatNum( $days ) )->text();
				$s .= ' ';
				$s .= $hoursMsg->params( $this->formatNum( $hours ) )->text();
			} elseif ( $format['avoid'] === 'avoidseconds' ) {
				$hours = floor( ( $seconds - $days * 86400 ) / 3600 );
				$minutes = round( ( $seconds - $days * 86400 - $hours * 3600 ) / 60 );
				if ( $minutes == 60 ) {
					$minutes = 0;
					$hours++;
				}
				if ( $hours == 24 ) {
					$hours = 0;
					$days++;
				}
				$s = $daysMsg->params( $this->formatNum( $days ) )->text();
				$s .= ' ';
				$s .= $hoursMsg->params( $this->formatNum( $hours ) )->text();
				$s .= ' ';
				$s .= $minutesMsg->params( $this->formatNum( $minutes ) )->text();
			} else {
				$s = $daysMsg->params( $this->formatNum( $days ) )->text();
				$s .= ' ';
				$s .= $this->formatTimePeriod( $seconds - $days * 86400, $format );
			}
		}
		return $s;
	}

	/**
	 * @param $bps int
	 * @return string
	 */
	function formatBitrate( $bps ) {
		$units = array( 'bps', 'kbps', 'Mbps', 'Gbps' );
		if ( $bps <= 0 ) {
			return $this->formatNum( $bps ) . $units[0];
		}
		$unitIndex = (int)floor( log10( $bps ) / 3 );
		$mantissa = $bps / pow( 1000, $unitIndex );
		if ( $mantissa < 10 ) {
			$mantissa = round( $mantissa, 1 );
		} else {
			$mantissa = round( $mantissa );
		}
		return $this->formatNum( $mantissa ) . $units[$unitIndex];
	}

	/**
	 * Format a size in bytes for output, using an appropriate
	 * unit (B, KB, MB or GB) according to the magnitude in question
	 *
	 * @param $size int Size to format
	 * @return string Plain text (not HTML)
	 */
	function formatSize( $size ) {
		// For small sizes no decimal places necessary
		$round = 0;
		if ( $size > 1024 ) {
			$size = $size / 1024;
			if ( $size > 1024 ) {
				$size = $size / 1024;
				// For MB and bigger two decimal places are smarter
				$round = 2;
				if ( $size > 1024 ) {
					$size = $size / 1024;
					$msg = 'size-gigabytes';
				} else {
					$msg = 'size-megabytes';
				}
			} else {
				$msg = 'size-kilobytes';
			}
		} else {
			$msg = 'size-bytes';
		}
		$size = round( $size, $round );
		$text = $this->getMessageFromDB( $msg );
		return str_replace( '$1', $this->formatNum( $size ), $text );
	}

	/**
	 * Make a list item, used by various special pages
	 *
	 * @param $page String Page link
	 * @param $details String Text between brackets
	 * @param $oppositedm Boolean Add the direction mark opposite to your
	 *                    language, to display text properly
	 * @return String
	 */
	function specialList( $page, $details, $oppositedm = true ) {
		$dirmark = ( $oppositedm ? $this->getDirMark( true ) : '' ) .
			$this->getDirMark();
		$details = $details ? $dirmark . $this->getMessageFromDB( 'word-separator' ) .
			wfMsgExt( 'parentheses', array( 'escape', 'replaceafter', 'language' => $this ), $details ) : '';
		return $page . $details;
	}

	/**
	 * Generate (prev x| next x) (20|50|100...) type links for paging
	 *
	 * @param $title Title object to link
	 * @param $offset Integer offset parameter
	 * @param $limit Integer limit parameter
	 * @param $query String optional URL query parameter string
	 * @param $atend Bool optional param for specified if this is the last page
	 * @return String
	 */
	public function viewPrevNext( Title $title, $offset, $limit, array $query = array(), $atend = false ) {
		// @todo FIXME: Why on earth this needs one message for the text and another one for tooltip?

		# Make 'previous' link
		$prev = wfMessage( 'prevn' )->inLanguage( $this )->title( $title )->numParams( $limit )->text();
		if( $offset > 0 ) {
			$plink = $this->numLink( $title, max( $offset - $limit, 0 ), $limit,
				$query, $prev, 'prevn-title', 'mw-prevlink' );
		} else {
			$plink = htmlspecialchars( $prev );
		}

		# Make 'next' link
		$next = wfMessage( 'nextn' )->inLanguage( $this )->title( $title )->numParams( $limit )->text();
		if( $atend ) {
			$nlink = htmlspecialchars( $next );
		} else {
			$nlink = $this->numLink( $title, $offset + $limit, $limit,
				$query, $next, 'prevn-title', 'mw-nextlink' );
		}

		# Make links to set number of items per page
		$numLinks = array();
		foreach( array( 20, 50, 100, 250, 500 ) as $num ) {
			$numLinks[] = $this->numLink( $title, $offset, $num,
				$query, $this->formatNum( $num ), 'shown-title', 'mw-numlink' );
		}

		return wfMessage( 'viewprevnext' )->inLanguage( $this )->title( $title
			)->rawParams( $plink, $nlink, $this->pipeList( $numLinks ) )->escaped();
	}

	/**
	 * Helper function for viewPrevNext() that generates links
	 *
	 * @param $title Title object to link
	 * @param $offset Integer offset parameter
	 * @param $limit Integer limit parameter
	 * @param $query Array extra query parameters
	 * @param $link String text to use for the link; will be escaped
	 * @param $tooltipMsg String name of the message to use as tooltip
	 * @param $class String value of the "class" attribute of the link
	 * @return String HTML fragment
	 */
	private function numLink( Title $title, $offset, $limit, array $query, $link, $tooltipMsg, $class ) {
		$query = array( 'limit' => $limit, 'offset' => $offset ) + $query;
		$tooltip = wfMessage( $tooltipMsg )->inLanguage( $this )->title( $title )->numParams( $limit )->text();
		return Html::element( 'a', array( 'href' => $title->getLocalURL( $query ),
			'title' => $tooltip, 'class' => $class ), $link );
	}

	/**
	 * Get the conversion rule title, if any.
	 *
	 * @return string
	 */
	public function getConvRuleTitle() {
		return $this->mConverter->getConvRuleTitle();
	}
}
