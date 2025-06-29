<?php

/**
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

namespace MediaWiki\Tests\Unit;

use InvalidArgumentException;
use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Cache\GenderCache;
use MediaWiki\CommentFormatter\CommentParser;
use MediaWiki\CommentFormatter\CommentParserFactory;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Interwiki\Interwiki;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigSchema;
use MediaWiki\Page\PageReference;
use MediaWiki\Tests\MockDatabase;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleParser;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Watchlist\WatchedItem;
use MediaWiki\Watchlist\WatchedItemStore;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Psr\Log\NullLogger;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Rdbms\ConfiguredReadOnlyMode;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LBFactorySingle;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\Services\NoSuchServiceException;

/**
 * Trait to get helper services that can be used in unit tests
 *
 * Getters are in the form getDummy{ServiceName} because they *might* be
 * returning mock objects (like getDummyWatchedItemStore), they *might* be
 * returning real services but with dependencies that are mocks (like
 * getDummyTitleParser), or they *might* be full real services with no mocks
 * (like getDummyNamespaceInfo) but with the name "dummy" to be consistent.
 *
 * @internal
 * @author DannyS712
 */
trait DummyServicesTrait {

	/**
	 * @var array
	 * Data for the watched item store, keys are result of getWatchedItemStoreKey()
	 * and the value is 'true' for indefinitely watched, or a string with an expiration;
	 * if there is no entry here than the page is not watched
	 */
	private $watchedItemStoreData = [];

	/**
	 * @return array keys are the setting name, values are the default value.
	 */
	private static function getDefaultSettings(): array {
		static $defaultSettings = null;
		if ( $defaultSettings !== null ) {
			return $defaultSettings;
		}
		$defaultSettings = iterator_to_array( MainConfigSchema::listDefaultValues() );
		return $defaultSettings;
	}

	/**
	 * @param CommentParser $parser to always return
	 * @return CommentParserFactory
	 */
	private function getDummyCommentParserFactory(
		CommentParser $parser
	): CommentParserFactory {
		return new class( $parser ) extends CommentParserFactory {
			private $parser;

			public function __construct( CommentParser $parser ) {
				$this->parser = $parser;
			}

			/** @inheritDoc */
			public function create() {
				return $this->parser;
			}
		};
	}

	/**
	 * @param array $contentHandlers map of content model to a ContentHandler object to
	 *   return (or to `true` for a content model to be defined but not actually have any
	 *   content handlers).
	 * @param string[] $allContentFormats specific content formats to claim support for,
	 *   by default none
	 * @return IContentHandlerFactory
	 */
	private function getDummyContentHandlerFactory(
		array $contentHandlers = [],
		array $allContentFormats = []
	): IContentHandlerFactory {
		$contentHandlerFactory = $this->createMock( IContentHandlerFactory::class );
		$contentHandlerFactory->method( 'getContentHandler' )
			->willReturnCallback(
				static function ( string $modelId ) use ( $contentHandlers ) {
					// interface has a return typehint, if $contentHandlers
					// doesn't have that key or the value isn't an instance of
					// ContentHandler will throw exception
					return $contentHandlers[ $modelId ];
				}
			);
		$contentHandlerFactory->method( 'getContentModels' )
			->willReturn( array_keys( $contentHandlers ) );
		$contentHandlerFactory->method( 'getAllContentFormats' )
			->willReturn( $allContentFormats );
		$contentHandlerFactory->method( 'isDefinedModel' )
			->willReturnCallback(
				static function ( string $modelId ) use ( $contentHandlers ) {
					return array_key_exists( $modelId, $contentHandlers );
				}
			);
		return $contentHandlerFactory;
	}

	/**
	 * @param array $dbOptions Options for the Database constructor
	 * @return LBFactory
	 */
	private function getDummyDBLoadBalancerFactory( $dbOptions = [] ): LBFactory {
		return LBFactorySingle::newFromConnection( new MockDatabase( $dbOptions ) );
	}

	/**
	 * @param array $interwikis valid interwikis, either a string if all that matters is
	 *     that it is valid, or an array with some or all of the information for a row
	 *     from the interwiki table (iw_prefix, iw_url, iw_api, iw_wikiid, iw_local, iw_trans).
	 *     Like the real InterwikiLookup interface, the iw_api/iw_wikiid/iw_local/iw_trans are
	 *     all optional, defaulting to empty strings or 0 as approriate. *Unlike* the real
	 *     InterwikiLookup interface, iw_url is also optional, defaulting to an empty string.
	 * @return InterwikiLookup
	 */
	private function getDummyInterwikiLookup( array $interwikis = [] ): InterwikiLookup {
		// Normalize into full arrays, indexed by prefix
		$allInterwikiRows = [];
		$defaultInterwiki = [
			// No prefix
			'iw_url' => '',
			'iw_api' => '',
			'iw_wikiid' => '',
			'iw_local' => 0,
			'iw_trans' => 0,
		];
		foreach ( $interwikis as $validInterwiki ) {
			if ( is_string( $validInterwiki ) ) {
				// All we got is that a prefix is valid
				$interwikiRow = [ 'iw_prefix' => $validInterwiki ] + $defaultInterwiki;
			} elseif ( is_array( $validInterwiki ) ) {
				if ( !isset( $validInterwiki['iw_prefix'] ) ) {
					throw new InvalidArgumentException(
						'Cannot save a valid interwiki without a prefix'
					);
				}
				$interwikiRow = $validInterwiki + $defaultInterwiki;
			} else {
				throw new InvalidArgumentException(
					'Interwikis must be in the form of a string or an array'
				);
			}

			// Indexed by prefix to make lookup easier
			$allInterwikiRows[ $interwikiRow['iw_prefix'] ] = $interwikiRow;
		}

		// Actual implementation
		return new class( $allInterwikiRows ) implements InterwikiLookup {
			private $allInterwikiRows;

			/** @inheritDoc */
			public function __construct( $allInterwikiRows ) {
				$this->allInterwikiRows = $allInterwikiRows;
			}

			/** @inheritDoc */
			public function isValidInterwiki( $prefix ) {
				return (bool)$this->fetch( $prefix );
			}

			/** @inheritDoc */
			public function fetch( $prefix ) {
				if ( $prefix == '' ) {
					return null;
				}
				// Interwikis are lowercase, but we might be given a prefix that
				// has uppercase characters, eg. from UserNameUtils normalization
				// in ClassicInterwikiLookup::fetch this would use Language::lc which
				// would decide between mb_strtolower and strtolower, but we can assume
				// that everything is in English for tests
				$prefix = strtolower( $prefix );
				if ( !isset( $this->allInterwikiRows[ $prefix ] ) ) {
					return false;
				}

				$row = $this->allInterwikiRows[ $prefix ];
				return new Interwiki(
					$row['iw_prefix'],
					$row['iw_url'],
					$row['iw_api'],
					$row['iw_wikiid'],
					$row['iw_local'],
					$row['iw_trans']
				);
			}

			/** @inheritDoc */
			public function getAllPrefixes( $local = null ) {
				if ( $local === null ) {
					return array_values( $this->allInterwikiRows );
				}
				return array_values(
					array_filter(
						$this->allInterwikiRows,
						static function ( $row ) use ( $local ) {
							return $row['iw_local'] == (int)$local;
						}
					)
				);
			}

			/** @inheritDoc */
			public function invalidateCache( $prefix ) {
				// Nothing to do
			}
		};
	}

	/**
	 * @param array $options keys are
	 *   - anything in LanguageNameUtils::CONSTRUCTOR_OPTIONS, any missing options will default
	 *     to the MainConfigSchema defaults
	 *   - 'hookContainer' if specific hooks need to be registered, otherwise an empty
	 *     container will be used
	 * @return LanguageNameUtils
	 */
	private function getDummyLanguageNameUtils( array $options = [] ): LanguageNameUtils {
		// configuration is based on the defaults in MainConfigSchema
		$serviceOptions = new ServiceOptions(
			LanguageNameUtils::CONSTRUCTOR_OPTIONS,
			$options, // caller can override the default config by specifying it here
			self::getDefaultSettings()
		);
		return new LanguageNameUtils(
			$serviceOptions,
			$options['hookContainer'] ?? $this->createHookContainer()
		);
	}

	/**
	 * @param array $options Options passed to getDummyNamespaceInfo()
	 * @return TitleFormatter
	 */
	private function getDummyTitleFormatter( array $options = [] ): TitleFormatter {
		$namespaceInfo = $this->getDummyNamespaceInfo( $options );

		/** @var Language|MockObject $language */
		$language = $this->createMock( Language::class );
		$language->method( 'getNsText' )->willReturnCallback(
			static function ( $index ) use ( $namespaceInfo ) {
				// based on the real Language::getNsText but without
				// the support for translated namespace names
				$namespaces = $namespaceInfo->getCanonicalNamespaces();
				return $namespaces[$index] ?? false;
			}
		);
		// Not dealing with genders, most languages don't - as a result,
		// the GenderCache is never used and thus a no-op mock
		$language->method( 'needsGenderDistinction' )->willReturn( false );

		/** @var GenderCache|MockObject $genderCache */
		$genderCache = $this->createMock( GenderCache::class );

		return new TitleFormatter(
			$language,
			$genderCache,
			$namespaceInfo
		);
	}

	/**
	 * Note that TitleParser can throw MalformedTitleException which cannot be
	 * created in unit tests - you can change this by providing a callback to
	 * TitleParser::overrideCreateMalformedTitleExceptionCallback() to use to
	 * create the exception that can return a mock. If you use the option 'throwMockExceptions'
	 * here, the callback will be replaced with one that throws a generic mock
	 * MalformedTitleException, i.e. without taking into account the actual message or
	 * parameters provided. This is useful for cases where only the fact that an exception
	 * is thrown, rather than the specific message in the exception, matters, like for
	 * detecting invalid titles.
	 *
	 * @param array $options Supported keys:
	 *     - validInterwikis: array of interwiki info to pass to getDummyInterwikiLookup
	 *     - throwMockExceptions: boolean, see above
	 *     - any of the options passed to getDummyNamespaceInfo (the same $options is passed on)
	 * @return TitleParser
	 */
	private function getDummyTitleParser( array $options = [] ): TitleParser {
		$baseConfig = [
			'validInterwikis' => [],
			'throwMockExceptions' => false,
		];
		$config = $options + $baseConfig;

		$namespaceInfo = $this->getDummyNamespaceInfo( $options );

		/** @var Language|MockObject $language */
		$language = $this->createMock( Language::class );
		$language->method( 'ucfirst' )->willReturnCallback( 'ucfirst' );
		$language->method( 'lc' )->willReturnCallback(
			static function ( $str, $first ) {
				return $first ? lcfirst( $str ) : strtolower( $str );
			}
		);
		$language->method( 'getNsIndex' )->willReturnCallback(
			static function ( $text ) use ( $namespaceInfo ) {
				$text = strtolower( $text );
				if ( $text === '' ) {
					return NS_MAIN;
				}
				// based on the real Language::getNsIndex but without
				// the support for translated namespace names
				// We do still support English aliases "Image" and
				// "Image_talk" though
				$index = $namespaceInfo->getCanonicalIndex( $text );

				if ( $index !== null ) {
					return $index;
				}
				$aliases = [
					'image' => NS_FILE,
					'image_talk' => NS_FILE,
				];
				return $aliases[$text] ?? false;
			}
		);

		$interwikiLookup = $this->getDummyInterwikiLookup( $config['validInterwikis'] );

		$titleParser = new TitleParser(
			$language,
			$interwikiLookup,
			$namespaceInfo,
			[ 'en' ],
		);

		if ( $config['throwMockExceptions'] ) {
			// Throw mock `MalformedTitleException`s, doesn't take into account the
			// specifics of the parameters provided
			$titleParser->overrideCreateMalformedTitleExceptionCallback(
				function ( $errorMessage, $titleText = null, $errorMessageParameters = [] ) {
					return $this->createMock( MalformedTitleException::class );
				}
			);
		}

		return $titleParser;
	}

	/**
	 * @param array $options Valid keys are 'hookContainer' for a specific HookContainer
	 *   to use (falls back to just creating an empty one), plus any of the configuration
	 *   included in NamespaceInfo::CONSTRUCTOR_OPTIONS
	 * @return NamespaceInfo
	 */
	private function getDummyNamespaceInfo( array $options = [] ): NamespaceInfo {
		// configuration is based on the defaults in MainConfigSchema
		$serviceOptions = new ServiceOptions(
			NamespaceInfo::CONSTRUCTOR_OPTIONS,
			$options, // caller can override the default config by specifying it here
			self::getDefaultSettings()
		);
		return new NamespaceInfo(
			$serviceOptions,
			$options['hookContainer'] ?? $this->createHookContainer(),
			[],
			[]
		);
	}

	/**
	 * @param array<string,mixed> $services services that exist, keys are service names,
	 *   values are the service to return. Any service not in this array does not exist.
	 * @return ObjectFactory
	 */
	private function getDummyObjectFactory( array $services = [] ): ObjectFactory {
		$container = $this->createMock( ContainerInterface::class );
		$container->method( 'has' )
			->willReturnCallback( static function ( $serviceName ) use ( $services ) {
				return array_key_exists( $serviceName, $services );
			} );
		$container->method( 'get' )
			->willReturnCallback( static function ( $serviceName ) use ( $services ) {
				if ( array_key_exists( $serviceName, $services ) ) {
					return $services[$serviceName];
				}
				// Need to throw some exception that implements the PSR
				// NotFoundExceptionInterface, use the exception from the Services
				// library which implements it and has a helpful message
				throw new NoSuchServiceException( $serviceName );
			} );
		return new ObjectFactory( $container );
	}

	/**
	 * @param string|bool $startingReason If false, the read only mode isn't active,
	 *    otherwise it is active and this is the reason (true maps to a fallback reason)
	 * @return ReadOnlyMode
	 */
	private function getDummyReadOnlyMode( $startingReason ): ReadOnlyMode {
		if ( $startingReason === true ) {
			$startingReason = 'Random reason';
		}
		$loadBalancer = $this->createMock( ILoadBalancer::class );
		$loadBalancer->method( 'getReadOnlyReason' )->willReturn( false );
		$lbFactory = $this->createMock( ILBFactory::class );
		$lbFactory->method( 'getMainLB' )->willReturn( $loadBalancer );
		return new ReadOnlyMode(
			new ConfiguredReadOnlyMode( $startingReason, null ),
			$lbFactory
		);
	}

	/**
	 * @param bool $dumpMessages Whether MessageValue objects should be formatted by dumping
	 *   them rather than just returning the key
	 * @return ITextFormatter
	 */
	private function getDummyTextFormatter( bool $dumpMessages = false ): ITextFormatter {
		return new class( $dumpMessages ) implements ITextFormatter {
			private bool $dumpMessages;

			public function __construct( bool $dumpMessages ) {
				$this->dumpMessages = $dumpMessages;
			}

			public function getLangCode(): string {
				return 'qqx';
			}

			public function format( MessageSpecifier $message ): string {
				if ( $this->dumpMessages && $message instanceof MessageValue ) {
					return $message->dump();
				}
				return $message->getKey();
			}
		};
	}

	/**
	 * @param array $options Supported keys:
	 *   - any of the configuration options used in the ServiceOptions
	 *   - logger: logger to use, defaults to a NullLogger
	 *   - textFormatter: ITextFormatter to use, defaults to a mock where the 'format' method
	 *     (the only one used by UserNameUtils) just returns the key of the MessageValue provided)
	 *   - titleParser: TitleParser to use, otherwise we will use getDummyTitleParser()
	 *   - any of the options passed to getDummyTitleParser (the same $options is passed on if
	 *     no titleParser is provided) (we change the default for "validInterwikis" to be
	 *     [ 'interwiki' ] instead of an empty array if not provided)
	 *   - hookContainer: specific HookContainer to use, default to creating an empty one via
	 *     $this->createHookContainer()
	 * @return UserNameUtils
	 */
	private function getDummyUserNameUtils( array $options = [] ) {
		$serviceOptions = new ServiceOptions(
			UserNameUtils::CONSTRUCTOR_OPTIONS,
			$options,
			self::getDefaultSettings() // fallback for options not in $options
		);

		// The only methods we call on the Language object is ucfirst and getNsText,
		// avoid needing to create a mock in each test.
		// Note that the actual Language::ucfirst is a bit more complicated than this
		// but since the tests are all in English the plain php `ucfirst` should be enough.
		$contentLang = $this->createMock( Language::class );
		$contentLang->method( 'ucfirst' )
			->willReturnCallback( 'ucfirst' );
		$contentLang->method( 'getNsText' )->with( NS_USER )
			->willReturn( 'User' );

		$logger = $options['logger'] ?? new NullLogger();

		$textFormatter = $options['textFormatter'] ?? $this->getDummyTextFormatter();

		$titleParser = $options['titleParser'] ?? false;
		if ( !$titleParser ) {
			// Use `throwMockExceptions` to avoid wfMessage() call
			$titleParser = $this->getDummyTitleParser(
				$options + [
					'validInterwikis' => [ 'interwiki' ],
					'throwMockExceptions' => true
				]
			);
		}

		return new UserNameUtils(
			$serviceOptions,
			$contentLang,
			$logger,
			$titleParser,
			$textFormatter,
			$options['hookContainer'] ?? $this->createHookContainer(),
			$options['tempUserConfig'] ?? new RealTempUserConfig( [
				'enabled' => true,
				'expireAfterDays' => null,
				'actions' => [ 'edit' ],
				'serialProvider' => [ 'type' => 'local' ],
				'serialMapping' => [ 'type' => 'plain-numeric' ],
				'reservedPattern' => '!$1',
				'matchPattern' => '*$1',
				'genPattern' => '*Unregistered $1'
			] )
		);
	}

	/**
	 * @param UserIdentity $user Should only be called with registered users
	 * @param LinkTarget|PageReference $page
	 * @return string
	 */
	private function getWatchedItemStoreKey( UserIdentity $user, $page ): string {
		return 'u' . (string)$user->getId() . ':' . CacheKeyHelper::getKeyForPage( $page );
	}

	/**
	 * @return WatchedItemStore|MockObject
	 */
	private function getDummyWatchedItemStore() {
		// The WatchedItemStoreInterface has a lot of stuff, but most tests only depend
		// on the basic getWatchedItem/addWatch/removeWatch/isWatched/isTempWatched
		// We mock WatchedItemStore and support those 5 methods, and it even handles
		// keep track of different pages and users!
		// Note: we store no expiration as true, so we can use isset(), but its represented
		// by null elsewhere, so we need to convert
		$mock = $this->createNoOpMock(
			WatchedItemStore::class,
			[ 'getWatchedItem', 'addWatch', 'removeWatch', 'isWatched', 'isTempWatched' ]
		);
		$mock->method( 'getWatchedItem' )->willReturnCallback( function ( $user, $target ) {
			$dataKey = $this->getWatchedItemStoreKey( $user, $target );
			if ( isset( $this->watchedItemStoreData[ $dataKey ] ) ) {
				$expiry = $this->watchedItemStoreData[ $dataKey ];
				// We store no expiration as true, so we can use isset(), but its
				// represented by null elsewhere, including in WatchedItem
				$expiry = ( $expiry === true ? null : $expiry );
				return new WatchedItem(
					$user,
					$target,
					null,
					$expiry
				);
			}
			return false;
		} );
		$mock->method( 'addWatch' )->willReturnCallback( function ( $user, $target, $expiry ) {
			if ( !$user->isRegistered() ) {
				return false;
			}
			$dataKey = $this->getWatchedItemStoreKey( $user, $target );
			$this->watchedItemStoreData[ $dataKey ] = ( $expiry === null ? true : $expiry );
			return true;
		} );
		$mock->method( 'removeWatch' )->willReturnCallback( function ( $user, $target ) {
			if ( !$user->isRegistered() ) {
				return false;
			}
			$dataKey = $this->getWatchedItemStoreKey( $user, $target );
			if ( isset( $this->watchedItemStoreData[ $dataKey ] ) ) {
				unset( $this->watchedItemStoreData[ $dataKey ] );
				return true;
			}
			return false;
		} );
		$mock->method( 'isWatched' )->willReturnCallback( function ( $user, $target ) {
			$dataKey = $this->getWatchedItemStoreKey( $user, $target );
			return isset( $this->watchedItemStoreData[ $dataKey ] );
		} );
		$mock->method( 'isTempWatched' )->willReturnCallback( function ( $user, $target ) {
			$dataKey = $this->getWatchedItemStoreKey( $user, $target );
			return isset( $this->watchedItemStoreData[ $dataKey ] ) &&
				$this->watchedItemStoreData[ $dataKey ] !== true;
		} );
		return $mock;
	}

	private function getDummyCommentStore(): CommentStore {
		$mockLang = $this->createNoOpMock( Language::class,
			[ 'truncateForVisual', 'truncateForDatabase' ] );
		$mockLang->method( $this->logicalOr( 'truncateForDatabase', 'truncateForVisual' ) )
			->willReturnCallback(
				static function ( string $text, int $limit ): string {
					if ( strlen( $text ) > $limit - 3 ) {
						return substr( $text, 0, $limit - 3 ) . '...';
					}
					return $text;
				}
			);
		return new CommentStore( $mockLang );
	}
}
