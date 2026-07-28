<?php

namespace MediaWiki\Tests\Rest\Handler;

use JsonSchemaAssertionTrait;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\Handler\DiscoveryHandler;
use MediaWiki\Rest\Module\ModuleMode;
use MediaWiki\Rest\Reporter\MWErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;

/**
 * @covers \MediaWiki\Rest\Handler\DiscoveryHandler
 */
class DiscoveryHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use JsonSchemaAssertionTrait;

	private function createRouter(
		RequestInterface $request,
		array $specFiles,
		array $moduleGroups = []
	): Router {
		$services = $this->getServiceContainer();

		$conf = $services->getMainConfig();

		$authority = $this->mockRegisteredUltimateAuthority();
		$authorizer = new StaticBasicAuthorizer();

		$objectFactory = $services->getObjectFactory();
		$restValidator = new Validator( $objectFactory,
			$request,
			$authority
		);

		$formatter = new class implements ITextFormatter {
			public function getLangCode(): string {
				return 'qqx';
			}

			public function format( MessageSpecifier $message ): string {
				return $message->dump();
			}
		};
		$textFormatters = [ $formatter ];
		$showExceptionDetails = false;

		$moduleModes = [
			'SpecTestRoutes/v1' => ModuleMode::PUBLISHED,
			'SpecTestRoutes/v2' => ModuleMode::HIDDEN,
			'mockExternal/v1' => ModuleMode::PUBLISHED,
		];

		return ( new Router(
			$this->newMockModuleManager( $specFiles, $moduleModes, $moduleGroups ),
			[],
			new ServiceOptions( Router::CONSTRUCTOR_OPTIONS, $conf ),
			$services->getLocalServerObjectCache(),
			$textFormatters,
			$showExceptionDetails,
			$authorizer,
			$authority,
			$objectFactory,
			$restValidator,
			new MWErrorReporter(),
			$services->getHookContainer(),
			$this->getSession( true )
		) );
	}

	private function newHandler() {
		$config = $this->getServiceContainer()->getMainConfig();
		return new DiscoveryHandler(
			$config
		);
	}

	private function assertWellFormedDiscoveryDoc( array $discovery ) {
		$schemaFile = MW_INSTALL_PATH . '/docs/rest/discovery-1.1.json';

		$this->assertMatchesJsonSchema( $schemaFile, $discovery, [
			'https://www.mediawiki.org/schema/mwapi-1.2' => MW_INSTALL_PATH . '/docs/rest/mwapi-1.2.json',
			'https://spec.openapis.org/oas/3.0/schema/2021-09-28' => __DIR__ . '/data/OpenApi-3.0.json',
		] );
	}

	private static function assertContainsRecursive(
		array $expected,
		array $actual,
		string $message = ''
	) {
		foreach ( $expected as $key => $value ) {
			Assert::assertArrayHasKey( $key, $actual, $message );

			if ( is_array( $value ) ) {
				Assert::assertIsArray( $actual[$key], $message );

				self::assertContainsRecursive( $value, $actual[$key], $message );
			} elseif ( $value instanceof Constraint ) {
				$value->evaluate( $actual[$key], $message );
			} else {
				Assert::assertSame( $value, $actual[$key], $message );
			}
		}
	}

	public function testGetInfoSpecSuccess() {
		$this->overrideConfigValues( [
			MainConfigNames::Sitename => 'Test Site',
			MainConfigNames::RightsText => 'Test License',
			MainConfigNames::RightsUrl => 'https://example.com/license',
			MainConfigNames::EmergencyContact => 'test@example.com',
			MainConfigNames::RestTermsOfServiceUrl => 'https://foundation.wikimedia.org/wiki/Policy:Terms_of_Use#12._API_Terms',
			MainConfigNames::CanonicalServer => 'https://example.com:1234',
			MainConfigNames::RestPath => '/api',
		] );

		$this->overrideConfigValue( MainConfigNames::RestExternalModules, [
			'mockExternal/v1' => [
				'info' => [
					'title' => 'Mock External Module',
					'version' => '1.0.0',
					'description' => 'This is a mock external module.'
				],
				'base' => 'https://example.com/mockExternal/v1',
				'spec' => 'https://example.com/mockExternal/v1/spec.json',
			],
		] );

		$request = new RequestData( [] );
		$router = $this->createRouter(
			$request,
			[
				__DIR__ . '/SpecTestRoutes.v3.json', // intentionally missorted
				__DIR__ . '/SpecTestRoutes.v1.json',
				__DIR__ . '/SpecTestRoutes.v2.json'
			],
			[
				'SpecTestRoutes/v1' => [ 'test-group' ],
				'mockExternal/v1' => [ 'external-test-group' ]
			]
		);

		$handler = $this->newHandler();
		$response = $this->executeHandler(
			$handler,
			$request,
			[],
			[],
			[],
			[],
			null,
			null,
			$router
		);
		$this->assertSame( 200, $response->getStatusCode() );
		$this->assertArrayHasKey( 'Content-Type', $response->getHeaders() );
		$this->assertSame( 'application/json', $response->getHeaderLine( 'Content-Type' ) );
		$data = json_decode( (string)$response->getBody(), true );

		$this->assertIsArray( $data, 'Body must be a JSON array' );
		$this->assertWellFormedDiscoveryDoc( $data );

		$expected = [
			'info' => [
				'title' => 'Test Site',
				'termsOfService' => 'https://foundation.wikimedia.org/wiki/Policy:Terms_of_Use#12._API_Terms',
				'contact' => [
					'email' => 'test@example.com',
				],
			],
			'servers' => [
				[ 'url' => 'https://example.com:1234/api', ],
			],
			'modules' => [
				'SpecTestRoutes/v1' => [
					'info' => [
						'version' => '1.0',
						'title' => 'test module',
						'groups' => [ 'test-group' ],
					],
					'base' => 'https://example.com:1234/api/SpecTestRoutes/v1',
					'spec' => 'https://example.com:1234/api/specs/v0/module/SpecTestRoutes%2Fv1',
				],
				'SpecTestRoutes/v3' => [
					'info' => [
						'version' => '3.0',
						'title' => 'test module',
						'groups' => [],
					],
					'base' => 'https://example.com:1234/api/SpecTestRoutes/v3',
					'spec' => 'https://example.com:1234/api/specs/v0/module/SpecTestRoutes%2Fv3',
				],
				'mockExternal/v1' => [
					'info' => [
						'title' => 'Mock External Module',
						'version' => '1.0.0',
						'description' => 'This is a mock external module.',
						'groups' => [ 'external-test-group' ],
					],
					'base' => 'https://example.com/mockExternal/v1',
					'spec' => 'https://example.com/mockExternal/v1/spec.json',
				],
			],
		];

		// Note that this does not fail on unexpected keys/elements
		self::assertContainsRecursive( $expected, $data );

		// Ensure the hidden module is actually hidden
		self::assertArrayNotHasKey( 'SpecTestRoutes/v2', $data['modules'] );
	}

	public function testGetInfoSpecOmitsTermsOfServiceWhenUnset(): void {
		$this->overrideConfigValues( [
			MainConfigNames::Sitename => 'Test Site',
			MainConfigNames::RightsText => 'Test License',
			MainConfigNames::RightsUrl => 'https://example.com/license',
			MainConfigNames::EmergencyContact => 'test@example.com',
			MainConfigNames::RestTermsOfServiceUrl => null,
			MainConfigNames::CanonicalServer => 'https://example.com:1234',
			MainConfigNames::RestPath => '/api',
		] );

		$request = new RequestData( [] );
		$router = $this->createRouter( $request, [ __DIR__ . '/SpecTestRoutes.v1.json' ] );

		$handler = $this->newHandler();
		$response = $this->executeHandler(
			$handler,
			$request,
			[],
			[],
			[],
			[],
			null,
			null,
			$router
		);
		$this->assertSame( 200, $response->getStatusCode() );

		$data = json_decode( (string)$response->getBody(), true );
		$this->assertIsArray( $data, 'Body must be a JSON array' );
		$this->assertWellFormedDiscoveryDoc( $data );

		$this->assertArrayNotHasKey( 'termsOfService', $data['info'] );
	}

	public function testGetInfoSpecOmitsInvalidContactEmail(): void {
		$this->overrideConfigValues( [
			MainConfigNames::Sitename => 'Test Site',
			MainConfigNames::RightsText => 'Test License',
			MainConfigNames::RightsUrl => 'https://example.com/license',
			MainConfigNames::EmergencyContact => 'not-an-email',
			MainConfigNames::RestTermsOfServiceUrl => 'https://foundation.wikimedia.org/wiki/Policy:Terms_of_Use#12._API_Terms',
			MainConfigNames::CanonicalServer => 'https://example.com:1234',
			MainConfigNames::RestPath => '/api',
		] );

		$request = new RequestData( [] );
		$router = $this->createRouter( $request, [ __DIR__ . '/SpecTestRoutes.v1.json' ] );

		$handler = $this->newHandler();
		$response = $this->executeHandler(
			$handler,
			$request,
			[],
			[],
			[],
			[],
			null,
			null,
			$router
		);
		$this->assertSame( 200, $response->getStatusCode() );

		$data = json_decode( (string)$response->getBody(), true );
		$this->assertIsArray( $data, 'Body must be a JSON array' );
		$this->assertWellFormedDiscoveryDoc( $data );

		$this->assertArrayHasKey( 'contact', $data['info'] );
		$this->assertArrayNotHasKey( 'email', $data['info']['contact'] );
	}

	public function testGetModuleMapEmpty(): void {
		$this->overrideConfigValues( [
			MainConfigNames::Sitename => 'Test Site',
			MainConfigNames::RightsText => 'Test License',
			MainConfigNames::RightsUrl => 'https://example.com/license',
			MainConfigNames::CanonicalServer => 'https://example.com:1234',
			MainConfigNames::RestPath => '/api',
		] );

		$this->overrideConfigValue( MainConfigNames::RestExternalModules, [] );

		$request = new RequestData( [] );
		$router = $this->createRouter( $request, [] );

		$handler = $this->newHandler();
		$response = $this->executeHandler(
			$handler,
			$request,
			[],
			[],
			[],
			[],
			null,
			null,
			$router
		);
		$this->assertSame( 200, $response->getStatusCode() );
		$this->assertArrayHasKey( 'Content-Type', $response->getHeaders() );
		$this->assertSame( 'application/json', $response->getHeaderLine( 'Content-Type' ) );

		$json = (string)$response->getBody();
		$this->assertStringContainsString( '"modules":{}', $json );

		$data = json_decode( $json, true );
		$this->assertIsArray( $data, 'Body must be a JSON array' );
		$this->assertWellFormedDiscoveryDoc( $data );
	}

}
