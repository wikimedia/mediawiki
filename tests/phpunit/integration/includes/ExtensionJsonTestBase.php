<?php

declare( strict_types=1 );

namespace MediaWiki\Tests;

use ApiMain;
use ApiQuery;
use ContentHandler;
use MediaWiki\Auth\PreAuthenticationProvider;
use MediaWiki\Auth\PrimaryAuthenticationProvider;
use MediaWiki\Auth\SecondaryAuthenticationProvider;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Api\ApiTestContext;
use MediaWikiIntegrationTestCase;
use Wikimedia\Http\MultiHttpClient;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;

/**
 * Base class for testing extension.json.
 *
 * While {@link \ExtensionJsonValidationTest} tests basic validity of all
 * extension.json and skin.json files that are available,
 * individual extensions can use this class to opt into further testing
 * by adding a test class extending this base class.
 *
 * This includes tests for object factory specifications
 * (API modules, special pages, hook handlers, etc.) to ensure that:
 * * The specifications are valid,
 *   i.e. they can be created without any errors.
 *   This protects, for instance, against misconfigured services.
 *   (This works best if the constructor factory function being called
 *   declares types for the parameters it receives.)
 * * No HTTP or database connections are made during initialization.
 *   Opening connections already when an object is created,
 *   not only when it is used, is a potential performance issue.
 * * Optionally: Each specification's list of services is sorted.
 *   Prescribing an automatically testable order frees the developer
 *   from having to think about the most logical order for any services.
 *
 * @license GPL-2.0-or-later
 */
abstract class ExtensionJsonTestBase extends MediaWikiIntegrationTestCase {

	/**
	 * @var string The path to the extension.json file.
	 * Should be specified as `__DIR__ . '/.../extension.json'`.
	 */
	protected string $extensionJsonPath;

	/**
	 * @var string|null The prefix of the extension's own services in the service container.
	 * If non-null, all services lists must be sorted,
	 * and first list all services outside the extension (without the prefix),
	 * then all services within the extension (with the prefix), in alphabetical order.
	 * If null (default), the order of services lists is not tested.
	 * To require all services to be listed in alphabetical order,
	 * regardless of whether they belong to the extension or not,
	 * set this to the empty string.
	 * @see ExtensionServicesTestBase::$serviceNamePrefix
	 */
	protected ?string $serviceNamePrefix = null;

	/**
	 * @var array[] Cache for extension.json, shared between all tests.
	 * Maps {@link $extensionJsonPath} values to parsed extension.json contents.
	 */
	private static array $extensionJsonCache = [];

	protected function setUp(): void {
		parent::setUp();

		// Factory methods should never access the database or do http requests
		// https://phabricator.wikimedia.org/T243729
		$this->disallowDBAccess();
		$this->disallowHttpAccess();
	}

	final protected function getExtensionJson(): array {
		if ( !array_key_exists( $this->extensionJsonPath, self::$extensionJsonCache ) ) {
			self::$extensionJsonCache[$this->extensionJsonPath] = json_decode(
				file_get_contents( $this->extensionJsonPath ),
				true,
				512,
				JSON_THROW_ON_ERROR
			);
		}
		return self::$extensionJsonCache[$this->extensionJsonPath];
	}

	/** @dataProvider provideHookHandlerNames */
	public function testHookHandler( string $hookHandlerName ): void {
		$specification = $this->getExtensionJson()['HookHandlers'][$hookHandlerName];
		$objectFactory = MediaWikiServices::getInstance()->getObjectFactory();
		$objectFactory->createObject( $specification, [
			'allowClassName' => true,
		] );
		$this->assertTrue( true );
	}

	public function provideHookHandlerNames(): iterable {
		foreach ( $this->getExtensionJson()['HookHandlers'] ?? [] as $hookHandlerName => $specification ) {
			yield [ $hookHandlerName ];
		}
	}

	/** @dataProvider provideContentModelIDs */
	public function testContentHandler( string $contentModelID ): void {
		$specification = $this->getExtensionJson()['ContentHandlers'][$contentModelID];
		$objectFactory = MediaWikiServices::getInstance()->getObjectFactory();
		$objectFactory->createObject( $specification, [
			'assertClass' => ContentHandler::class,
			'allowCallable' => true,
			'allowClassName' => true,
			'extraArgs' => [ $contentModelID ],
		] );
		$this->assertTrue( true );
	}

	public function provideContentModelIDs(): iterable {
		foreach ( $this->getExtensionJson()['ContentHandlers'] ?? [] as $contentModelID => $specification ) {
			yield [ $contentModelID ];
		}
	}

	/** @dataProvider provideApiModuleNames */
	public function testApiModule( string $moduleName ): void {
		$specification = $this->getExtensionJson()['APIModules'][$moduleName];
		$objectFactory = MediaWikiServices::getInstance()->getObjectFactory();
		$objectFactory->createObject( $specification, [
			'allowClassName' => true,
			'extraArgs' => [ $this->mockApiMain(), 'modulename' ],
		] );
		$this->assertTrue( true );
	}

	public function provideApiModuleNames(): iterable {
		foreach ( $this->getExtensionJson()['APIModules'] ?? [] as $moduleName => $specification ) {
			yield [ $moduleName ];
		}
	}

	/** @dataProvider provideApiQueryModuleListsAndNames */
	public function testApiQueryModule( string $moduleList, string $moduleName ): void {
		$specification = $this->getExtensionJson()[$moduleList][$moduleName];
		$objectFactory = MediaWikiServices::getInstance()->getObjectFactory();
		$objectFactory->createObject( $specification, [
			'allowClassName' => true,
			'extraArgs' => [ $this->mockApiQuery(), 'query' ],
		] );
		$this->assertTrue( true );
	}

	public function provideApiQueryModuleListsAndNames(): iterable {
		foreach ( [ 'APIListModules', 'APIMetaModules', 'APIPropModules' ] as $moduleList ) {
			foreach ( $this->getExtensionJson()[$moduleList] ?? [] as $moduleName => $specification ) {
				yield [ $moduleList, $moduleName ];
			}
		}
	}

	/** @dataProvider provideSpecialPageNames */
	public function testSpecialPage( string $specialPageName ): void {
		$specification = $this->getExtensionJson()['SpecialPages'][$specialPageName];
		$objectFactory = MediaWikiServices::getInstance()->getObjectFactory();
		$objectFactory->createObject( $specification, [
			'allowClassName' => true,
		] );
		$this->assertTrue( true );
	}

	public function provideSpecialPageNames(): iterable {
		foreach ( $this->getExtensionJson()['SpecialPages'] ?? [] as $specialPageName => $specification ) {
			yield [ $specialPageName ];
		}
	}

	/** @dataProvider provideAuthenticationProviders */
	public function testAuthenticationProviders( string $providerType, string $providerName, string $providerClass ): void {
		$specification = $this->getExtensionJson()['AuthManagerAutoConfig'][$providerType][$providerName];
		$objectFactory = MediaWikiServices::getInstance()->getObjectFactory();
		$objectFactory->createObject( $specification, [
			'assertClass' => $providerClass,
		] );
		$this->assertTrue( true );
	}

	public function provideAuthenticationProviders(): iterable {
		$config = $this->getExtensionJson()['AuthManagerAutoConfig'] ?? [];

		$types = [
			'preauth'       => PreAuthenticationProvider::class,
			'primaryauth'   => PrimaryAuthenticationProvider::class,
			'secondaryauth' => SecondaryAuthenticationProvider::class,
		];

		foreach ( $types as $providerType => $providerClass ) {
			foreach ( $config[$providerType] ?? [] as $providerName => $specification ) {
				yield [ $providerType, $providerName, $providerClass ];
			}
		}
	}

	/** @dataProvider provideSessionProviders */
	public function testSessionProviders( string $providerName ): void {
		$specification = $this->getExtensionJson()['SessionProviders'][$providerName];
		$objectFactory = MediaWikiServices::getInstance()->getObjectFactory();
		$objectFactory->createObject( $specification );
		$this->assertTrue( true );
	}

	public function provideSessionProviders(): iterable {
		foreach ( $this->getExtensionJson()['SessionProviders'] ?? [] as $providerName => $specification ) {
			yield [ $providerName ];
		}
	}

	/** @dataProvider provideServicesLists */
	public function testServicesSorted( array $services ): void {
		$sortedServices = $services;
		usort( $sortedServices, function ( $serviceA, $serviceB ) {
			$isExtensionServiceA = str_starts_with( $serviceA, $this->serviceNamePrefix );
			$isExtensionServiceB = str_starts_with( $serviceB, $this->serviceNamePrefix );
			if ( $isExtensionServiceA !== $isExtensionServiceB ) {
				return $isExtensionServiceA ? 1 : -1;
			}
			return strcmp( $serviceA, $serviceB );
		} );

		$this->assertSame( $sortedServices, $services,
			'Services should be sorted: first all MediaWiki services, ' .
			"then all {$this->serviceNamePrefix}* ones." );
	}

	public function provideServicesLists(): iterable {
		if ( $this->serviceNamePrefix === null ) {
			return; // do not test sorting
		}
		foreach ( $this->provideSpecifications() as $name => $specification ) {
			if (
				is_array( $specification ) &&
				array_key_exists( 'services', $specification )
			) {
				yield $name => [ $specification['services'] ];
			}
		}
	}

	public function provideSpecifications(): iterable {
		foreach ( $this->provideHookHandlerNames() as [ $hookHandlerName ] ) {
			yield "HookHandlers/$hookHandlerName" => $this->getExtensionJson()['HookHandlers'][$hookHandlerName];
		}

		foreach ( $this->provideContentModelIDs() as [ $contentModelID ] ) {
			yield "ContentHandlers/$contentModelID" => $this->getExtensionJson()['ContentHandlers'][$contentModelID];
		}

		foreach ( $this->provideApiModuleNames() as [ $moduleName ] ) {
			yield "APIModules/$moduleName" => $this->getExtensionJson()['APIModules'][$moduleName];
		}

		foreach ( $this->provideApiQueryModuleListsAndNames() as [ $moduleList, $moduleName ] ) {
			yield "$moduleList/$moduleName" => $this->getExtensionJson()[$moduleList][$moduleName];
		}

		foreach ( $this->provideSpecialPageNames() as [ $specialPageName ] ) {
			yield "SpecialPages/$specialPageName" => $this->getExtensionJson()['SpecialPages'][$specialPageName];
		}

		foreach ( $this->provideAuthenticationProviders() as [ $providerType, $providerName, $providerClass ] ) {
			yield "AuthManagerAutoConfig/$providerType/$providerName" => $this->getExtensionJson()['AuthManagerAutoConfig'][$providerType][$providerName];
		}

		foreach ( $this->provideSessionProviders() as [ $providerName ] ) {
			yield "SessionProviders/$providerName" => $this->getExtensionJson()['SessionProviders'][$providerName];
		}
	}

	private function disallowDBAccess() {
		$this->setService(
			'DBLoadBalancerFactory',
			function () {
				$lb = $this->createMock( ILoadBalancer::class );
				$lb->expects( $this->never() )
					->method( 'getMaintenanceConnectionRef' );
				$lb->method( 'getLocalDomainID' )
					->willReturn( 'banana' );

				// This IDatabase will fail when actually trying to do database actions
				$db = $this->createNoOpMock( IDatabase::class );
				$lb->method( 'getConnection' )
					->willReturn( $db );

				$lbFactory = $this->createMock( LBFactory::class );
				$lbFactory->method( 'getMainLB' )
					->willReturn( $lb );
				$lbFactory->method( 'getLocalDomainID' )
					->willReturn( 'banana' );

				return $lbFactory;
			}
		);
	}

	private function disallowHttpAccess() {
		$this->setService(
			'HttpRequestFactory',
			function () {
				$factory = $this->createMock( HttpRequestFactory::class );
				$factory->expects( $this->never() )
					->method( 'create' );
				$factory->expects( $this->never() )
					->method( 'request' );
				$factory->expects( $this->never() )
					->method( 'get' );
				$factory->expects( $this->never() )
					->method( 'post' );
				$factory->method( 'createMultiClient' )
					->willReturn( $this->createMock( MultiHttpClient::class ) );
				return $factory;
			}
		);
	}

	private function mockApiMain(): ApiMain {
		$request = new FauxRequest();
		$ctx = new ApiTestContext();
		$ctx = $ctx->newTestContext( $request );
		return new ApiMain( $ctx );
	}

	private function mockApiQuery(): ApiQuery {
		return $this->mockApiMain()->getModuleManager()->getModule( 'query' );
	}

}
