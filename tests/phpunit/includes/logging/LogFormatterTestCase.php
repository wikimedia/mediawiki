<?php

use MediaWiki\Cache\GenderCache;
use MediaWiki\Cache\LinkCache;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\RequestContext;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Linker\UserLinkRenderer;
use MediaWiki\Logging\LogEntryBase;
use MediaWiki\Logging\LogPage;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageStore;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserFactory;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @since 1.26
 */
abstract class LogFormatterTestCase extends MediaWikiLangTestCase {
	use MockAuthorityTrait;

	public function doTestLogFormatter( $row, $extra, $userGroups = [] ) {
		RequestContext::resetMain();
		$row = $this->expandDatabaseRow( $row, $this->isLegacy( $extra ) );

		$services = $this->getServiceContainer();
		$userGroups = (array)$userGroups;
		$userRights = $services->getGroupPermissionsLookup()->getGroupPermissions( $userGroups );
		$context = new RequestContext();
		$authority = $this->mockRegisteredAuthorityWithPermissions( $userRights );
		$context->setAuthority( $authority );
		$context->setLanguage( 'en' );

		$formatter = $services->getLogFormatterFactory()->newFromRow( $row );
		$formatter->setContext( $context );

		// Create a LinkRenderer without LinkCache to avoid DB access
		$realLinkRenderer = new LinkRenderer(
			$services->getTitleFormatter(),
			$this->createMock( LinkCache::class ),
			$services->getSpecialPageFactory(),
			$services->getHookContainer(),
			$services->getTempUserConfig(),
			$services->getTempUserDetailsLookup(),
			$services->getUserIdentityLookup(),
			$services->getUserNameUtils(),
			new ServiceOptions(
				LinkRenderer::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig(),
				[ 'renderForComment' => false ]
			)
		);
		// Then create a mock LinkRenderer that proxies makeLink calls to the original LinkRenderer, but assumes
		// that all links are known to bypass DB access in Title::exists().
		$linkRenderer = $this->createMock( LinkRenderer::class );
		$linkRenderer->method( 'makeLink' )
			->willReturnCallback(
				static function ( $target, $text = null, $extra = [], $query = [] ) use ( $realLinkRenderer ) {
					return $realLinkRenderer->makeKnownLink( $target, $text, $extra, $query );
				}
			);
		// Proxy 'makeUserLink' as well
		$userLinkRenderer = new UserLinkRenderer(
			$services->getHookContainer(),
			$services->getTempUserConfig(),
			$services->getSpecialPageFactory(),
			$linkRenderer,
			$services->getTempUserDetailsLookup(),
			$services->getUserIdentityLookup(),
			$services->getUserNameUtils()
		);
		$linkRenderer->method( 'makeUserLink' )
			->willReturnCallback(
				static function ( $targetUser, $context, $altUserName = null, $attributes = [] ) use ( $userLinkRenderer ) {
					return $userLinkRenderer->userLink(
						$targetUser, $context, $altUserName, $attributes
					);
				}
			);
		$formatter->setLinkRenderer( $linkRenderer );
		$this->setService( 'LinkRenderer', $linkRenderer );

		// Create a mock PageStore where all pages are existing, in case any calls to Title::exists are not
		// caught by the mocks above.
		$pageStore = $this->getMockBuilder( PageStore::class )
			->onlyMethods( [ 'getPageByName' ] )
			->setConstructorArgs( [
				new ServiceOptions( PageStore::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
				$this->createNoOpMock( ILoadBalancer::class ),
				$services->getNamespaceInfo(),
				$services->getTitleParser(),
				null,
				StatsFactory::newNull()
			] )
			->getMock();
		$pageStore->method( 'getPageByName' )
			->willReturn( $this->createMock( ExistingPageRecord::class ) );
		$this->setService( 'PageStore', $pageStore );

		// Create a mock UserFactory where all registered users are created with ID and name and where loading of
		// other fields is prevented, to avoid DB access.
		$origUserFactory = $services->getUserFactory();
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromName' )
			->willReturnCallback( static function ( $name, $validation ) use ( $origUserFactory ) {
				$ret = $origUserFactory->newFromName( $name, $validation );
				if ( !$ret ) {
					return $ret;
				}
				$userID = IPUtils::isIPAddress( $name ) ? 0 : 42;
				$ret = TestingAccessWrapper::newFromObject( $ret );
				$ret->mId = $userID;
				$ret->mLoadedItems = true;
				return $ret->object;
			} );
		$userFactory->method( 'newFromId' )->willReturnCallback( [ $origUserFactory, 'newFromId' ] );
		$userFactory->method( 'newAnonymous' )->willReturnCallback( [ $origUserFactory, 'newAnonymous' ] );
		$userFactory->method( 'newFromUserIdentity' )
			->willReturnCallback( [ $origUserFactory, 'newFromUserIdentity' ] );
		$this->setService( 'UserFactory', $userFactory );

		// Replace gender cache to avoid gender DB lookups
		$genderCache = $this->createMock( GenderCache::class );
		$genderCache->method( 'getGenderOf' )->willReturn( 'unknown' );
		$this->setService( 'GenderCache', $genderCache );

		$this->assertEquals(
			$extra['text'],
			self::removeSomeHtml( $formatter->getActionText() ),
			'Action text is equal to expected text'
		);

		$this->assertSame( // ensure types and array key order
			$extra['api'],
			self::removeApiMetaData( $formatter->formatParametersForApi() ),
			'Api log params is equal to expected array'
		);

		if ( isset( $extra['preload'] ) ) {
			$this->assertArrayEquals(
				$this->getLinkTargetsAsStrings( $extra['preload'] ),
				$this->getLinkTargetsAsStrings(
					$formatter->getPreloadTitles()
				)
			);
		}
	}

	private function getLinkTargetsAsStrings( array $linkTargets ) {
		return array_map( static function ( LinkTarget $t ) {
			return $t->getInterwiki() . ':' . $t->getNamespace() . ':'
				. $t->getDBkey() . '#' . $t->getFragment();
		}, $linkTargets );
	}

	protected function isLegacy( $extra ) {
		return isset( $extra['legacy'] ) && $extra['legacy'];
	}

	protected function expandDatabaseRow( $data, $legacy ) {
		return [
			// no log_id because no insert in database
			'log_type' => $data['type'],
			'log_action' => $data['action'],
			'log_timestamp' => $data['timestamp'] ?? wfTimestampNow(),
			'log_user' => $data['user'] ?? 42,
			'log_user_text' => $data['user_text'] ?? 'User',
			'log_actor' => $data['actor'] ?? 24,
			'log_namespace' => $data['namespace'] ?? NS_MAIN,
			'log_title' => $data['title'] ?? 'Main_Page',
			'log_page' => $data['page'] ?? 0,
			'log_comment_text' => $data['comment'] ?? '',
			'log_comment_data' => null,
			'log_params' => $legacy
				? LogPage::makeParamBlob( $data['params'] )
				: LogEntryBase::makeParamBlob( $data['params'] ),
			'log_deleted' => $data['deleted'] ?? 0,
		];
	}

	protected static function removeSomeHtml( $html ) {
		$html = str_replace( '&quot;', '"', $html );
		$html = preg_replace( '/\xE2\x80[\x8E\x8F]/', '', $html ); // Strip lrm/rlm
		return trim( strip_tags( $html ) );
	}

	protected static function removeApiMetaData( $val ) {
		if ( is_array( $val ) ) {
			unset( $val['_element'] );
			unset( $val['_type'] );
			foreach ( $val as $key => $value ) {
				$val[$key] = self::removeApiMetaData( $value );
			}
		}
		return $val;
	}
}
