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

use CommentStore;
use ConfiguredReadOnlyMode;
use GenderCache;
use Interwiki;
use InvalidArgumentException;
use Language;
use MalformedTitleException;
use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigSchema;
use MediaWiki\Page\PageReference;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use MediaWikiTitleCodec;
use NamespaceInfo;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use ReadOnlyMode;
use TitleFormatter;
use TitleParser;
use WatchedItem;
use WatchedItemStore;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Trait to get helper services that can be used in unit tests
 *
 * Getters are in the form getDummy{ServiceName} because they *might* be
 * returning mock objects (like getDummyWatchedItemStore), they *might* be
 * returning real services but with dependencies that are mocks (like
 * getDummyMediaWikiTitleCodec), or they *might* be full real services
 * with no mocks (like getDummyNamespaceInfo) but with the name "dummy"
 * to be consistent.
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

			public function __construct( $allInterwikiRows ) {
				$this->allInterwikiRows = $allInterwikiRows;
			}

			public function isValidInterwiki( $prefix ) {
				return (bool)$this->fetch( $prefix );
			}

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

			public function invalidateCache( $prefix ) {
				// Nothing to do
			}
		};
	}

	/**
	 * @param array $options see getDummyMediaWikiTitleCodec for supported options
	 * @return TitleFormatter
	 */
	private function getDummyTitleFormatter( array $options = [] ): TitleFormatter {
		return $this->getDummyMediaWikiTitleCodec( $options );
	}

	/**
	 * @param array $options see getDummyMediaWikiTitleCodec for supported options
	 * @return TitleParser
	 */
	private function getDummyTitleParser( array $options = [] ): TitleParser {
		return $this->getDummyMediaWikiTitleCodec( $options );
	}

	/**
	 * Note: you should probably use getDummyTitleFormatter or getDummyTitleParser,
	 * unless you actually need both services, in which case it doesn't make sense
	 * to get two different objects when they are implemented together.
	 *
	 * Note that MediaWikiTitleCodec can throw MalformedTitleException which cannot be
	 * created in unit tests - you can change this by providing a callback to
	 * MediaWikiTitleCodec::overrideCreateMalformedTitleExceptionCallback() to use to
	 * create the exception that can return a mock. If you use the option 'throwMockExceptions'
	 * here, the callback will be replaced with one that throws a generic mock
	 * MalformedTitleException, i.e. without taking into account the actual message or
	 * parameters provided. This is useful for cases where only the fact that an exception
	 * is thrown, rather than the specific message in the exception, matters, like for
	 * detecting invalid titles.
	 *
	 * @param array $options Supported keys:
	 *    - validInterwikis: array of interwiki info to pass to getDummyInterwikiLookup
	 *    - throwMockExceptions: boolean, see above
	 *    - any of the options passed to getDummyNamespaceInfo (the same $options is passed on)
	 *
	 * @return MediaWikiTitleCodec
	 */
	private function getDummyMediaWikiTitleCodec( array $options = [] ): MediaWikiTitleCodec {
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

		$interwikiLookup = $this->getDummyInterwikiLookup( $config['validInterwikis'] );

		$titleCodec = new MediaWikiTitleCodec(
			$language,
			$genderCache,
			[ 'en' ],
			$interwikiLookup,
			$namespaceInfo
		);

		if ( $config['throwMockExceptions'] ) {
			// Throw mock `MalformedTitleException`s, doesn't take into account the
			// specifics of the parameters provided
			$titleCodec->overrideCreateMalformedTitleExceptionCallback(
				function ( $errorMessage, $titleText = null, $errorMessageParameters = [] ) {
					return $this->createMock( MalformedTitleException::class );
				}
			);
		}

		return $titleCodec;
	}

	/**
	 * @param array $options Valid keys are 'hookContainer' for a specific HookContainer
	 *   to use (falls back to just creating an empty one), plus any of the configuration
	 *   included in NamespaceInfo::CONSTRUCTOR_OPTIONS
	 * @return NamespaceInfo
	 */
	private function getDummyNamespaceInfo( array $options = [] ): NamespaceInfo {
		// Rather than trying to use a complicated mock, it turns out that almost
		// all of the NamespaceInfo service works fine in unit tests. The only issues:
		//   - in two places, NamespaceInfo tries to read extension attributes through
		//     ExtensionRegistry::getInstance()->getAttribute() - this should work fine
		//     in unit tests, it just won't include any extension info since those are
		//     not loaded
		//   - ::getRestrictionLevels() is a deprecated wrapper that calls
		//     PermissionManager::getNamespaceRestrictionLevels() - the PermissionManager
		//     is retrieved from MediaWikiServices, which doesn't work in unit tests.
		//     This shouldn't be an issue though, since it should only be called in
		//     the dedicated tests for that deprecation method, which use the real service

		// configuration is based on the defaults in MainConfigSchema
		$serviceOptions = new ServiceOptions(
			NamespaceInfo::CONSTRUCTOR_OPTIONS,
			$options, // caller can override the default config by specifying it here
			self::getDefaultSettings()
		);
		return new NamespaceInfo(
			$serviceOptions,
			$options['hookContainer'] ?? $this->createHookContainer()
		);
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
		return new ReadOnlyMode(
			new ConfiguredReadOnlyMode( $startingReason, null ),
			$loadBalancer
		);
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

		$textFormatter = $options['textFormatter'] ?? new class implements ITextFormatter {
			public function getLangCode() {
				return 'qqx';
			}

			public function format( MessageValue $message ) {
				return $message->getKey();
			}
		};

		$titleParser = $options['titleParser'] ?? false;
		if ( !$titleParser ) {
			// The TitleParser from DummyServicesTrait::getDummyTitleParser is really a
			// MediaWikiTitleCodec object, and by passing `throwMockExceptions` we replace
			// the actual creation of `MalformedTitleException`s with mocks - see
			// MediaWikiTitleCodec::overrideCreateMalformedTitleExceptionCallback()
			// The UserNameUtils code doesn't care about the message in the exception,
			// just whether it is thrown.
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
			new RealTempUserConfig( [
				'enabled' => true,
				'actions' => [ 'edit' ],
				'serialProvider' => [ 'type' => 'local' ],
				'serialMapping' => [ 'type' => 'plain-numeric' ],
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
		$mock->method( 'isTempWatched' )->willreturnCallback( function ( $user, $target ) {
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
		return new CommentStore( $mockLang, MIGRATION_NEW );
	}
}
