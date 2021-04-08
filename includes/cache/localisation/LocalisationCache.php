<?php
/**
 * Cache of the contents of localisation files.
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
 */

use CLDRPluralRuleParser\Error as CLDRPluralRuleError;
use CLDRPluralRuleParser\Evaluator;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Languages\LanguageNameUtils;
use Psr\Log\LoggerInterface;

/**
 * Class for caching the contents of localisation files, Messages*.php
 * and *.i18n.php.
 *
 * An instance of this class is available using MediaWikiServices.
 *
 * The values retrieved from here are merged, containing items from extension
 * files, core messages files and the language fallback sequence (e.g. zh-cn ->
 * zh-hans -> en ). Some common errors are corrected, for example namespace
 * names with spaces instead of underscores, but heavyweight processing, such
 * as grammatical transformation, is done by the caller.
 */
class LocalisationCache {
	public const VERSION = 4;

	/** @var ServiceOptions */
	private $options;

	/**
	 * True if recaching should only be done on an explicit call to recache().
	 * Setting this reduces the overhead of cache freshness checking, which
	 * requires doing a stat() for every extension i18n file.
	 */
	private $manualRecache = false;

	/**
	 * The cache data. 3-d array, where the first key is the language code,
	 * the second key is the item key e.g. 'messages', and the third key is
	 * an item specific subkey index. Some items are not arrays and so for those
	 * items, there are no subkeys.
	 */
	protected $data = [];

	/**
	 * The persistent store object. An instance of LCStore.
	 *
	 * @var LCStore
	 */
	private $store;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/** @var HookRunner */
	private $hookRunner;

	/** @var callable[] See comment for parameter in constructor */
	private $clearStoreCallbacks;

	/** @var LanguageNameUtils */
	private $langNameUtils;

	/**
	 * A 2-d associative array, code/key, where presence indicates that the item
	 * is loaded. Value arbitrary.
	 *
	 * For split items, if set, this indicates that all of the subitems have been
	 * loaded.
	 *
	 */
	private $loadedItems = [];

	/**
	 * A 3-d associative array, code/key/subkey, where presence indicates that
	 * the subitem is loaded. Only used for the split items, i.e. messages.
	 */
	private $loadedSubitems = [];

	/**
	 * An array where presence of a key indicates that that language has been
	 * initialised. Initialisation includes checking for cache expiry and doing
	 * any necessary updates.
	 */
	private $initialisedLangs = [];

	/**
	 * An array mapping non-existent pseudo-languages to fallback languages. This
	 * is filled by initShallowFallback() when data is requested from a language
	 * that lacks a Messages*.php file.
	 */
	private $shallowFallbacks = [];

	/**
	 * An array where the keys are codes that have been recached by this instance.
	 */
	private $recachedLangs = [];

	/**
	 * All item keys
	 */
	public static $allKeys = [
		'fallback', 'namespaceNames', 'bookstoreList',
		'magicWords', 'messages', 'rtl', 'capitalizeAllNouns',
		'digitTransformTable', 'separatorTransformTable',
		'minimumGroupingDigits', 'fallback8bitEncoding',
		'linkPrefixExtension', 'linkTrail', 'linkPrefixCharset',
		'namespaceAliases', 'dateFormats', 'datePreferences',
		'datePreferenceMigrationMap', 'defaultDateFormat',
		'specialPageAliases', 'imageFiles', 'preloadedMessages',
		'namespaceGenderAliases', 'digitGroupingPattern', 'pluralRules',
		'pluralRuleTypes', 'compiledPluralRules',
	];

	/**
	 * Keys for items which consist of associative arrays, which may be merged
	 * by a fallback sequence.
	 */
	public static $mergeableMapKeys = [ 'messages', 'namespaceNames',
		'namespaceAliases', 'dateFormats', 'imageFiles', 'preloadedMessages'
	];

	/**
	 * Keys for items which are a numbered array.
	 */
	public static $mergeableListKeys = [];

	/**
	 * Keys for items which contain an array of arrays of equivalent aliases
	 * for each subitem. The aliases may be merged by a fallback sequence.
	 */
	public static $mergeableAliasListKeys = [ 'specialPageAliases' ];

	/**
	 * Keys for items which contain an associative array, and may be merged if
	 * the primary value contains the special array key "inherit". That array
	 * key is removed after the first merge.
	 */
	public static $optionalMergeKeys = [ 'bookstoreList' ];

	/**
	 * Keys for items that are formatted like $magicWords
	 */
	public static $magicWordKeys = [ 'magicWords' ];

	/**
	 * Keys for items where the subitems are stored in the backend separately.
	 */
	public static $splitKeys = [ 'messages' ];

	/**
	 * Keys which are loaded automatically by initLanguage()
	 */
	public static $preloadedKeys = [ 'dateFormats', 'namespaceNames' ];

	/**
	 * Associative array of cached plural rules. The key is the language code,
	 * the value is an array of plural rules for that language.
	 */
	private $pluralRules = null;

	/**
	 * Associative array of cached plural rule types. The key is the language
	 * code, the value is an array of plural rule types for that language. For
	 * example, $pluralRuleTypes['ar'] = ['zero', 'one', 'two', 'few', 'many'].
	 * The index for each rule type matches the index for the rule in
	 * $pluralRules, thus allowing correlation between the two. The reason we
	 * don't just use the type names as the keys in $pluralRules is because
	 * Language::convertPlural applies the rules based on numeric order (or
	 * explicit numeric parameter), not based on the name of the rule type. For
	 * example, {{plural:count|wordform1|wordform2|wordform3}}, rather than
	 * {{plural:count|one=wordform1|two=wordform2|many=wordform3}}.
	 */
	private $pluralRuleTypes = null;

	private $mergeableKeys = null;

	/**
	 * Return a suitable LCStore as specified by the given configuration.
	 *
	 * @since 1.34
	 * @param array $conf In the format of $wgLocalisationCacheConf
	 * @param string|false|null $fallbackCacheDir In case 'storeDirectory' isn't specified
	 * @return LCStore
	 */
	public static function getStoreFromConf( array $conf, $fallbackCacheDir ) : LCStore {
		$storeArg = [];
		$storeArg['directory'] =
			$conf['storeDirectory'] ?: $fallbackCacheDir;

		if ( !empty( $conf['storeClass'] ) ) {
			$storeClass = $conf['storeClass'];
		} elseif ( $conf['store'] === 'files' || $conf['store'] === 'file' ||
			( $conf['store'] === 'detect' && $storeArg['directory'] )
		) {
			$storeClass = LCStoreCDB::class;
		} elseif ( $conf['store'] === 'db' || $conf['store'] === 'detect' ) {
			$storeClass = LCStoreDB::class;
			$storeArg['server'] = $conf['storeServer'] ?? [];
		} elseif ( $conf['store'] === 'array' ) {
			$storeClass = LCStoreStaticArray::class;
		} else {
			throw new MWException(
				'Please set $wgLocalisationCacheConf[\'store\'] to something sensible.'
			);
		}

		return new $storeClass( $storeArg );
	}

	/**
	 * @var array
	 * @since 1.34
	 */
	public const CONSTRUCTOR_OPTIONS = [
		// True to treat all files as expired until they are regenerated by this object.
		'forceRecache',
		'manualRecache',
		'ExtensionMessagesFiles',
		'MessagesDirs',
	];

	/**
	 * For constructor parameters, see the documentation in DefaultSettings.php
	 * for $wgLocalisationCacheConf.
	 *
	 * Do not construct this directly. Use MediaWikiServices.
	 *
	 * @param ServiceOptions $options
	 * @param LCStore $store What backend to use for storage
	 * @param LoggerInterface $logger
	 * @param callable[] $clearStoreCallbacks To be called whenever the cache is cleared. Can be
	 *   used to clear other caches that depend on this one, such as ResourceLoader's
	 *   MessageBlobStore.
	 * @param LanguageNameUtils $langNameUtils
	 * @param HookContainer $hookContainer
	 * @throws MWException
	 */
	public function __construct(
		ServiceOptions $options,
		LCStore $store,
		LoggerInterface $logger,
		array $clearStoreCallbacks,
		LanguageNameUtils $langNameUtils,
		HookContainer $hookContainer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->store = $store;
		$this->logger = $logger;
		$this->clearStoreCallbacks = $clearStoreCallbacks;
		$this->langNameUtils = $langNameUtils;
		$this->hookRunner = new HookRunner( $hookContainer );

		// Keep this separate from $this->options so it can be mutable
		$this->manualRecache = $options->get( 'manualRecache' );
	}

	/**
	 * Returns true if the given key is mergeable, that is, if it is an associative
	 * array which can be merged through a fallback sequence.
	 * @param string $key
	 * @return bool
	 */
	public function isMergeableKey( $key ) {
		if ( $this->mergeableKeys === null ) {
			$this->mergeableKeys = array_flip( array_merge(
				self::$mergeableMapKeys,
				self::$mergeableListKeys,
				self::$mergeableAliasListKeys,
				self::$optionalMergeKeys,
				self::$magicWordKeys
			) );
		}

		return isset( $this->mergeableKeys[$key] );
	}

	/**
	 * Get a cache item.
	 *
	 * Warning: this may be slow for split items (messages), since it will
	 * need to fetch all of the subitems from the cache individually.
	 * @param string $code
	 * @param string $key
	 * @return mixed
	 */
	public function getItem( $code, $key ) {
		if ( !isset( $this->loadedItems[$code][$key] ) ) {
			$this->loadItem( $code, $key );
		}

		if ( $key === 'fallback' && isset( $this->shallowFallbacks[$code] ) ) {
			return $this->shallowFallbacks[$code];
		}

		return $this->data[$code][$key];
	}

	/**
	 * Get a subitem, for instance a single message for a given language.
	 * @param string $code
	 * @param string $key
	 * @param string $subkey
	 * @return mixed|null
	 */
	public function getSubitem( $code, $key, $subkey ) {
		if ( !isset( $this->loadedSubitems[$code][$key][$subkey] ) &&
			!isset( $this->loadedItems[$code][$key] )
		) {
			$this->loadSubitem( $code, $key, $subkey );
		}

		return $this->data[$code][$key][$subkey] ?? null;
	}

	/**
	 * Get the list of subitem keys for a given item.
	 *
	 * This is faster than array_keys($lc->getItem(...)) for the items listed in
	 * self::$splitKeys.
	 *
	 * Will return null if the item is not found, or false if the item is not an
	 * array.
	 * @param string $code
	 * @param string $key
	 * @return bool|null|string|string[]
	 */
	public function getSubitemList( $code, $key ) {
		if ( in_array( $key, self::$splitKeys ) ) {
			return $this->getSubitem( $code, 'list', $key );
		} else {
			$item = $this->getItem( $code, $key );
			if ( is_array( $item ) ) {
				return array_keys( $item );
			} else {
				return false;
			}
		}
	}

	/**
	 * Load an item into the cache.
	 * @param string $code
	 * @param string $key
	 */
	protected function loadItem( $code, $key ) {
		if ( !isset( $this->initialisedLangs[$code] ) ) {
			$this->initLanguage( $code );
		}

		// Check to see if initLanguage() loaded it for us
		if ( isset( $this->loadedItems[$code][$key] ) ) {
			return;
		}

		if ( isset( $this->shallowFallbacks[$code] ) ) {
			$this->loadItem( $this->shallowFallbacks[$code], $key );

			return;
		}

		if ( in_array( $key, self::$splitKeys ) ) {
			$subkeyList = $this->getSubitem( $code, 'list', $key );
			foreach ( $subkeyList as $subkey ) {
				if ( isset( $this->data[$code][$key][$subkey] ) ) {
					continue;
				}
				$this->data[$code][$key][$subkey] = $this->getSubitem( $code, $key, $subkey );
			}
		} else {
			$this->data[$code][$key] = $this->store->get( $code, $key );
		}

		$this->loadedItems[$code][$key] = true;
	}

	/**
	 * Load a subitem into the cache
	 * @param string $code
	 * @param string $key
	 * @param string $subkey
	 */
	protected function loadSubitem( $code, $key, $subkey ) {
		if ( !in_array( $key, self::$splitKeys ) ) {
			$this->loadItem( $code, $key );

			return;
		}

		if ( !isset( $this->initialisedLangs[$code] ) ) {
			$this->initLanguage( $code );
		}

		// Check to see if initLanguage() loaded it for us
		if ( isset( $this->loadedItems[$code][$key] ) ||
			isset( $this->loadedSubitems[$code][$key][$subkey] )
		) {
			return;
		}

		if ( isset( $this->shallowFallbacks[$code] ) ) {
			$this->loadSubitem( $this->shallowFallbacks[$code], $key, $subkey );

			return;
		}

		$value = $this->store->get( $code, "$key:$subkey" );
		$this->data[$code][$key][$subkey] = $value;
		$this->loadedSubitems[$code][$key][$subkey] = true;
	}

	/**
	 * Returns true if the cache identified by $code is missing or expired.
	 *
	 * @param string $code
	 *
	 * @return bool
	 */
	public function isExpired( $code ) {
		if ( $this->options->get( 'forceRecache' ) && !isset( $this->recachedLangs[$code] ) ) {
			$this->logger->debug( __METHOD__ . "($code): forced reload" );

			return true;
		}

		$deps = $this->store->get( $code, 'deps' );
		$keys = $this->store->get( $code, 'list' );
		$preload = $this->store->get( $code, 'preload' );
		// Different keys may expire separately for some stores
		if ( $deps === null || $keys === null || $preload === null ) {
			$this->logger->debug( __METHOD__ . "($code): cache missing, need to make one" );

			return true;
		}

		foreach ( $deps as $dep ) {
			// Because we're unserializing stuff from cache, we
			// could receive objects of classes that don't exist
			// anymore (e.g. uninstalled extensions)
			// When this happens, always expire the cache
			if ( !$dep instanceof CacheDependency || $dep->isExpired() ) {
				$this->logger->debug( __METHOD__ . "($code): cache for $code expired due to " .
					get_class( $dep ) );

				return true;
			}
		}

		return false;
	}

	/**
	 * Initialise a language in this object. Rebuild the cache if necessary.
	 * @param string $code
	 * @throws MWException
	 */
	protected function initLanguage( $code ) {
		if ( isset( $this->initialisedLangs[$code] ) ) {
			return;
		}

		$this->initialisedLangs[$code] = true;

		# If the code is of the wrong form for a Messages*.php file, do a shallow fallback
		if ( !$this->langNameUtils->isValidBuiltInCode( $code ) ) {
			$this->initShallowFallback( $code, 'en' );

			return;
		}

		# Recache the data if necessary
		if ( !$this->manualRecache && $this->isExpired( $code ) ) {
			if ( $this->langNameUtils->isSupportedLanguage( $code ) ) {
				$this->recache( $code );
			} elseif ( $code === 'en' ) {
				throw new MWException( 'MessagesEn.php is missing.' );
			} else {
				$this->initShallowFallback( $code, 'en' );
			}

			return;
		}

		# Preload some stuff
		$preload = $this->getItem( $code, 'preload' );
		if ( $preload === null ) {
			if ( $this->manualRecache ) {
				// No Messages*.php file. Do shallow fallback to en.
				if ( $code === 'en' ) {
					throw new MWException( 'No localisation cache found for English. ' .
						'Please run maintenance/rebuildLocalisationCache.php.' );
				}
				$this->initShallowFallback( $code, 'en' );

				return;
			} else {
				throw new MWException( 'Invalid or missing localisation cache.' );
			}
		}
		$this->data[$code] = $preload;
		foreach ( $preload as $key => $item ) {
			if ( in_array( $key, self::$splitKeys ) ) {
				foreach ( $item as $subkey => $subitem ) {
					$this->loadedSubitems[$code][$key][$subkey] = true;
				}
			} else {
				$this->loadedItems[$code][$key] = true;
			}
		}
	}

	/**
	 * Create a fallback from one language to another, without creating a
	 * complete persistent cache.
	 * @param string $primaryCode
	 * @param string $fallbackCode
	 */
	public function initShallowFallback( $primaryCode, $fallbackCode ) {
		$this->data[$primaryCode] =& $this->data[$fallbackCode];
		$this->loadedItems[$primaryCode] =& $this->loadedItems[$fallbackCode];
		$this->loadedSubitems[$primaryCode] =& $this->loadedSubitems[$fallbackCode];
		$this->shallowFallbacks[$primaryCode] = $fallbackCode;
	}

	/**
	 * Read a PHP file containing localisation data.
	 * @param string $_fileName
	 * @param string $_fileType
	 * @throws MWException
	 * @return array
	 */
	protected function readPHPFile( $_fileName, $_fileType ) {
		include $_fileName;

		$data = [];
		if ( $_fileType == 'core' || $_fileType == 'extension' ) {
			foreach ( self::$allKeys as $key ) {
				// Not all keys are set in language files, so
				// check they exist first
				if ( isset( $$key ) ) {
					$data[$key] = $$key;
				}
			}
		} elseif ( $_fileType == 'aliases' ) {
			// @phan-suppress-next-line PhanImpossibleCondition May be set in included file
			if ( isset( $aliases ) ) {
				$data['aliases'] = $aliases;
			}
		} else {
			throw new MWException( __METHOD__ . ": Invalid file type: $_fileType" );
		}

		return $data;
	}

	/**
	 * Read a JSON file containing localisation messages.
	 * @param string $fileName Name of file to read
	 * @throws MWException If there is a syntax error in the JSON file
	 * @return array Array with a 'messages' key, or empty array if the file doesn't exist
	 */
	public function readJSONFile( $fileName ) {
		if ( !is_readable( $fileName ) ) {
			return [];
		}

		$json = file_get_contents( $fileName );
		if ( $json === false ) {
			return [];
		}

		$data = FormatJson::decode( $json, true );
		if ( $data === null ) {
			throw new MWException( __METHOD__ . ": Invalid JSON file: $fileName" );
		}

		// Remove keys starting with '@', they're reserved for metadata and non-message data
		foreach ( $data as $key => $unused ) {
			if ( $key === '' || $key[0] === '@' ) {
				unset( $data[$key] );
			}
		}

		// The JSON format only supports messages, none of the other variables, so wrap the data
		return [ 'messages' => $data ];
	}

	/**
	 * Get the compiled plural rules for a given language from the XML files.
	 * @since 1.20
	 * @param string $code
	 * @return array|null
	 */
	public function getCompiledPluralRules( $code ) {
		$rules = $this->getPluralRules( $code );
		if ( $rules === null ) {
			return null;
		}
		try {
			$compiledRules = Evaluator::compile( $rules );
		} catch ( CLDRPluralRuleError $e ) {
			$this->logger->debug( $e->getMessage() );

			return [];
		}

		return $compiledRules;
	}

	/**
	 * Get the plural rules for a given language from the XML files.
	 * Cached.
	 * @since 1.20
	 * @param string $code
	 * @return array|null
	 */
	public function getPluralRules( $code ) {
		if ( $this->pluralRules === null ) {
			$this->loadPluralFiles();
		}
		return $this->pluralRules[$code] ?? null;
	}

	/**
	 * Get the plural rule types for a given language from the XML files.
	 * Cached.
	 * @since 1.22
	 * @param string $code
	 * @return array|null
	 */
	public function getPluralRuleTypes( $code ) {
		if ( $this->pluralRuleTypes === null ) {
			$this->loadPluralFiles();
		}
		return $this->pluralRuleTypes[$code] ?? null;
	}

	/**
	 * Load the plural XML files.
	 */
	protected function loadPluralFiles() {
		global $IP;
		$cldrPlural = "$IP/languages/data/plurals.xml";
		$mwPlural = "$IP/languages/data/plurals-mediawiki.xml";
		// Load CLDR plural rules
		$this->loadPluralFile( $cldrPlural );
		if ( file_exists( $mwPlural ) ) {
			// Override or extend
			$this->loadPluralFile( $mwPlural );
		}
	}

	/**
	 * Load a plural XML file with the given filename, compile the relevant
	 * rules, and save the compiled rules in a process-local cache.
	 *
	 * @param string $fileName
	 * @throws MWException
	 */
	protected function loadPluralFile( $fileName ) {
		// Use file_get_contents instead of DOMDocument::load (T58439)
		$xml = file_get_contents( $fileName );
		if ( !$xml ) {
			throw new MWException( "Unable to read plurals file $fileName" );
		}
		$doc = new DOMDocument;
		$doc->loadXML( $xml );
		$rulesets = $doc->getElementsByTagName( "pluralRules" );
		foreach ( $rulesets as $ruleset ) {
			$codes = $ruleset->getAttribute( 'locales' );
			$rules = [];
			$ruleTypes = [];
			$ruleElements = $ruleset->getElementsByTagName( "pluralRule" );
			foreach ( $ruleElements as $elt ) {
				$ruleType = $elt->getAttribute( 'count' );
				if ( $ruleType === 'other' ) {
					// Don't record "other" rules, which have an empty condition
					continue;
				}
				$rules[] = $elt->nodeValue;
				$ruleTypes[] = $ruleType;
			}
			foreach ( explode( ' ', $codes ) as $code ) {
				$this->pluralRules[$code] = $rules;
				$this->pluralRuleTypes[$code] = $ruleTypes;
			}
		}
	}

	/**
	 * Read the data from the source files for a given language, and register
	 * the relevant dependencies in the $deps array. If the localisation
	 * exists, the data array is returned, otherwise false is returned.
	 *
	 * @param string $code
	 * @param array &$deps
	 * @return array
	 */
	protected function readSourceFilesAndRegisterDeps( $code, &$deps ) {
		global $IP;

		// This reads in the PHP i18n file with non-messages l10n data
		$fileName = $this->langNameUtils->getMessagesFileName( $code );
		if ( !file_exists( $fileName ) ) {
			$data = [];
		} else {
			$deps[] = new FileDependency( $fileName );
			$data = $this->readPHPFile( $fileName, 'core' );
		}

		# Load CLDR plural rules for JavaScript
		$data['pluralRules'] = $this->getPluralRules( $code );
		# And for PHP
		$data['compiledPluralRules'] = $this->getCompiledPluralRules( $code );
		# Load plural rule types
		$data['pluralRuleTypes'] = $this->getPluralRuleTypes( $code );

		$deps['plurals'] = new FileDependency( "$IP/languages/data/plurals.xml" );
		$deps['plurals-mw'] = new FileDependency( "$IP/languages/data/plurals-mediawiki.xml" );

		return $data;
	}

	/**
	 * Merge two localisation values, a primary and a fallback, overwriting the
	 * primary value in place.
	 * @param string $key
	 * @param mixed &$value
	 * @param mixed $fallbackValue
	 */
	protected function mergeItem( $key, &$value, $fallbackValue ) {
		if ( $value !== null ) {
			if ( $fallbackValue !== null ) {
				if ( in_array( $key, self::$mergeableMapKeys ) ) {
					$value += $fallbackValue;
				} elseif ( in_array( $key, self::$mergeableListKeys ) ) {
					$value = array_unique( array_merge( $fallbackValue, $value ) );
				} elseif ( in_array( $key, self::$mergeableAliasListKeys ) ) {
					$value = array_merge_recursive( $value, $fallbackValue );
				} elseif ( in_array( $key, self::$optionalMergeKeys ) ) {
					if ( !empty( $value['inherit'] ) ) {
						$value = array_merge( $fallbackValue, $value );
					}

					if ( isset( $value['inherit'] ) ) {
						unset( $value['inherit'] );
					}
				} elseif ( in_array( $key, self::$magicWordKeys ) ) {
					$this->mergeMagicWords( $value, $fallbackValue );
				}
			}
		} else {
			$value = $fallbackValue;
		}
	}

	/**
	 * @param mixed &$value
	 * @param mixed $fallbackValue
	 */
	protected function mergeMagicWords( &$value, $fallbackValue ) {
		foreach ( $fallbackValue as $magicName => $fallbackInfo ) {
			if ( !isset( $value[$magicName] ) ) {
				$value[$magicName] = $fallbackInfo;
			} else {
				$oldSynonyms = array_slice( $fallbackInfo, 1 );
				$newSynonyms = array_slice( $value[$magicName], 1 );
				$synonyms = array_values( array_unique( array_merge(
					$newSynonyms, $oldSynonyms ) ) );
				$value[$magicName] = array_merge( [ $fallbackInfo[0] ], $synonyms );
			}
		}
	}

	/**
	 * Given an array mapping language code to localisation value, such as is
	 * found in extension *.i18n.php files, iterate through a fallback sequence
	 * to merge the given data with an existing primary value.
	 *
	 * Returns true if any data from the extension array was used, false
	 * otherwise.
	 * @param array $codeSequence
	 * @param string $key
	 * @param mixed &$value
	 * @param mixed $fallbackValue
	 * @return bool
	 */
	protected function mergeExtensionItem( $codeSequence, $key, &$value, $fallbackValue ) {
		$used = false;
		foreach ( $codeSequence as $code ) {
			if ( isset( $fallbackValue[$code] ) ) {
				$this->mergeItem( $key, $value, $fallbackValue[$code] );
				$used = true;
			}
		}

		return $used;
	}

	/**
	 * Gets the combined list of messages dirs from
	 * core and extensions
	 *
	 * @since 1.25
	 * @return array
	 */
	public function getMessagesDirs() {
		global $IP;

		return [
			'core' => "$IP/languages/i18n",
			'exif' => "$IP/languages/i18n/exif",
			'api' => "$IP/includes/api/i18n",
			'rest' => "$IP/includes/Rest/i18n",
			'oojs-ui' => "$IP/resources/lib/ooui/i18n",
			'paramvalidator' => "$IP/includes/libs/ParamValidator/i18n",
		] + $this->options->get( 'MessagesDirs' );
	}

	/**
	 * Load localisation data for a given language for both core and extensions
	 * and save it to the persistent cache store and the process cache
	 * @param string $code
	 * @throws MWException
	 */
	public function recache( $code ) {
		if ( !$code ) {
			throw new MWException( "Invalid language code requested" );
		}
		$this->recachedLangs[ $code ] = true;

		# Initial values
		$initialData = array_fill_keys( self::$allKeys, null );
		$coreData = $initialData;
		$deps = [];

		# Load the primary localisation from the source file
		$data = $this->readSourceFilesAndRegisterDeps( $code, $deps );
		$this->logger->debug( __METHOD__ . ": got localisation for $code from source" );

		# Merge primary localisation
		foreach ( $data as $key => $value ) {
			$this->mergeItem( $key, $coreData[ $key ], $value );
		}

		# Fill in the fallback if it's not there already
		// @phan-suppress-next-line PhanSuspiciousValueComparison
		if ( ( $coreData['fallback'] === null || $coreData['fallback'] === false ) && $code === 'en' ) {
			$coreData['fallback'] = false;
			$coreData['originalFallbackSequence'] = $coreData['fallbackSequence'] = [];
		} else {
			if ( $coreData['fallback'] !== null ) {
				$coreData['fallbackSequence'] = array_map( 'trim', explode( ',', $coreData['fallback'] ) );
			} else {
				$coreData['fallbackSequence'] = [];
			}
			$len = count( $coreData['fallbackSequence'] );

			# Before we add the 'en' fallback for messages, keep a copy of
			# the original fallback sequence
			$coreData['originalFallbackSequence'] = $coreData['fallbackSequence'];

			# Ensure that the sequence ends at 'en' for messages
			if ( !$len || $coreData['fallbackSequence'][$len - 1] !== 'en' ) {
				$coreData['fallbackSequence'][] = 'en';
			}
		}

		$codeSequence = array_merge( [ $code ], $coreData['fallbackSequence'] );
		$messageDirs = $this->getMessagesDirs();

		# Load non-JSON localisation data for extensions
		$extensionData = array_fill_keys( $codeSequence, $initialData );
		foreach ( $this->options->get( 'ExtensionMessagesFiles' ) as $extension => $fileName ) {
			if ( isset( $messageDirs[$extension] ) ) {
				# This extension has JSON message data; skip the PHP shim
				continue;
			}

			$data = $this->readPHPFile( $fileName, 'extension' );
			$used = false;

			foreach ( $data as $key => $item ) {
				foreach ( $codeSequence as $csCode ) {
					if ( isset( $item[$csCode] ) ) {
						$this->mergeItem( $key, $extensionData[$csCode][$key], $item[$csCode] );
						$used = true;
					}
				}
			}

			if ( $used ) {
				$deps[] = new FileDependency( $fileName );
			}
		}

		# Load the localisation data for each fallback, then merge it into the full array
		$allData = $initialData;
		foreach ( $codeSequence as $csCode ) {
			$csData = $initialData;

			# Load core messages and the extension localisations.
			foreach ( $messageDirs as $dirs ) {
				foreach ( (array)$dirs as $dir ) {
					$fileName = "$dir/$csCode.json";
					$data = $this->readJSONFile( $fileName );

					foreach ( $data as $key => $item ) {
						$this->mergeItem( $key, $csData[$key], $item );
					}

					$deps[] = new FileDependency( $fileName );
				}
			}

			# Merge non-JSON extension data
			if ( isset( $extensionData[$csCode] ) ) {
				foreach ( $extensionData[$csCode] as $key => $item ) {
					$this->mergeItem( $key, $csData[$key], $item );
				}
			}

			if ( $csCode === $code ) {
				# Merge core data into extension data
				foreach ( $coreData as $key => $item ) {
					$this->mergeItem( $key, $csData[$key], $item );
				}
			} else {
				# Load the secondary localisation from the source file to
				# avoid infinite cycles on cyclic fallbacks
				$fbData = $this->readSourceFilesAndRegisterDeps( $csCode, $deps );
				# Only merge the keys that make sense to merge
				foreach ( self::$allKeys as $key ) {
					if ( !isset( $fbData[ $key ] ) ) {
						continue;
					}

					if ( ( $coreData[ $key ] ) === null || $this->isMergeableKey( $key ) ) {
						$this->mergeItem( $key, $csData[ $key ], $fbData[ $key ] );
					}
				}
			}

			# Allow extensions an opportunity to adjust the data for this
			# fallback
			$this->hookRunner->onLocalisationCacheRecacheFallback( $this, $csCode, $csData );

			# Merge the data for this fallback into the final array
			if ( $csCode === $code ) {
				$allData = $csData;
			} else {
				foreach ( self::$allKeys as $key ) {
					if ( !isset( $csData[$key] ) ) {
						continue;
					}

					// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
					if ( $allData[$key] === null || $this->isMergeableKey( $key ) ) {
						$this->mergeItem( $key, $allData[$key], $csData[$key] );
					}
				}
			}
		}

		# Add cache dependencies for any referenced globals
		$deps['wgExtensionMessagesFiles'] = new GlobalDependency( 'wgExtensionMessagesFiles' );
		// The 'MessagesDirs' config setting is used in LocalisationCache::getMessagesDirs().
		// We use the key 'wgMessagesDirs' for historical reasons.
		$deps['wgMessagesDirs'] = new MainConfigDependency( 'MessagesDirs' );
		$deps['version'] = new ConstantDependency( 'LocalisationCache::VERSION' );

		# Add dependencies to the cache entry
		$allData['deps'] = $deps;

		# Replace spaces with underscores in namespace names
		$allData['namespaceNames'] = str_replace( ' ', '_', $allData['namespaceNames'] );

		# And do the same for special page aliases. $page is an array.
		foreach ( $allData['specialPageAliases'] as &$page ) {
			$page = str_replace( ' ', '_', $page );
		}
		# Decouple the reference to prevent accidental damage
		unset( $page );

		# If there were no plural rules, return an empty array
		if ( $allData['pluralRules'] === null ) {
			$allData['pluralRules'] = [];
		}
		if ( $allData['compiledPluralRules'] === null ) {
			$allData['compiledPluralRules'] = [];
		}
		# If there were no plural rule types, return an empty array
		if ( $allData['pluralRuleTypes'] === null ) {
			$allData['pluralRuleTypes'] = [];
		}

		# Set the list keys
		$allData['list'] = [];
		foreach ( self::$splitKeys as $key ) {
			$allData['list'][$key] = array_keys( $allData[$key] );
		}
		# Run hooks
		$unused = true; // Used to be $purgeBlobs, removed in 1.34
		$this->hookRunner->onLocalisationCacheRecache( $this, $code, $allData, $unused );

		if ( $allData['namespaceNames'] === null ) {
			throw new MWException( __METHOD__ . ': Localisation data failed sanity check! ' .
				'Check that your languages/messages/MessagesEn.php file is intact.' );
		}

		# Set the preload key
		$allData['preload'] = $this->buildPreload( $allData );

		# Save to the process cache and register the items loaded
		$this->data[$code] = $allData;
		foreach ( $allData as $key => $item ) {
			$this->loadedItems[$code][$key] = true;
		}

		# Save to the persistent cache
		$this->store->startWrite( $code );
		foreach ( $allData as $key => $value ) {
			if ( in_array( $key, self::$splitKeys ) ) {
				foreach ( $value as $subkey => $subvalue ) {
					$this->store->set( "$key:$subkey", $subvalue );
				}
			} else {
				$this->store->set( $key, $value );
			}
		}
		$this->store->finishWrite();

		# Clear out the MessageBlobStore
		# HACK: If using a null (i.e. disabled) storage backend, we
		# can't write to the MessageBlobStore either
		if ( !$this->store instanceof LCStoreNull ) {
			foreach ( $this->clearStoreCallbacks as $callback ) {
				$callback();
			}
		}
	}

	/**
	 * Build the preload item from the given pre-cache data.
	 *
	 * The preload item will be loaded automatically, improving performance
	 * for the commonly-requested items it contains.
	 * @param array $data
	 * @return array
	 */
	protected function buildPreload( $data ) {
		$preload = [ 'messages' => [] ];
		foreach ( self::$preloadedKeys as $key ) {
			$preload[$key] = $data[$key];
		}

		foreach ( $data['preloadedMessages'] as $subkey ) {
			$subitem = $data['messages'][$subkey] ?? null;
			$preload['messages'][$subkey] = $subitem;
		}

		return $preload;
	}

	/**
	 * Unload the data for a given language from the object cache.
	 * Reduces memory usage.
	 * @param string $code
	 */
	public function unload( $code ) {
		unset( $this->data[$code] );
		unset( $this->loadedItems[$code] );
		unset( $this->loadedSubitems[$code] );
		unset( $this->initialisedLangs[$code] );
		unset( $this->shallowFallbacks[$code] );

		foreach ( $this->shallowFallbacks as $shallowCode => $fbCode ) {
			if ( $fbCode === $code ) {
				$this->unload( $shallowCode );
			}
		}
	}

	/**
	 * Unload all data
	 */
	public function unloadAll() {
		foreach ( $this->initialisedLangs as $lang => $unused ) {
			$this->unload( $lang );
		}
	}

	/**
	 * Disable the storage backend
	 */
	public function disableBackend() {
		$this->store = new LCStoreNull;
		$this->manualRecache = false;
	}
}
