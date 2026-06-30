<?php

namespace MediaWiki\Tests\Rest\Handler;

use JsonSchemaAssertionTrait;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\ModuleSpecHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Module\ModuleMode;
use MediaWiki\Rest\Reporter\MWErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Session\SessionManagerInterface;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Rest\Handler\ModuleSpecHandler
 */
class ModuleSpecHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use JsonSchemaAssertionTrait;

	private function createRouter(
		RequestInterface $request,
		$specFile,
		$moduleModes = []
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
		$responseFactory = new ResponseFactory( [ $formatter ] );

		return ( new Router(
			$this->newMockModuleManager( [ $specFile ], $moduleModes ),
			[],
			new ServiceOptions( Router::CONSTRUCTOR_OPTIONS, $conf ),
			$services->getLocalServerObjectCache(),
			$responseFactory,
			$authorizer,
			$authority,
			$objectFactory,
			$restValidator,
			new MWErrorReporter(),
			$services->getHookContainer(),
			$this->getSession( true )
		) );
	}

	/**
	 * @param array $securitySchemes Flat security-scheme map returned by the mocked
	 *   SessionManager::getAllOpenApiSecuritySchemes(); defaults to none.
	 * @return ModuleSpecHandler
	 */
	private function newHandler( array $securitySchemes = [] ) {
		$config = $this->getServiceContainer()->getMainConfig();
		$sessionManager = $this->createMock( SessionManagerInterface::class );
		$sessionManager->method( 'getAllOpenApiSecuritySchemes' )
			->willReturn( $securitySchemes );
		return new ModuleSpecHandler(
			$config,
			$sessionManager
		);
	}

	/**
	 * A bare handler whose only relevant trait is its write-access requirement,
	 * used to drive getOpenApiSecurityRequirements().
	 */
	private function newAccessHandler( bool $needsWriteAccess ): Handler {
		return new class( $needsWriteAccess ) extends Handler {
			private bool $needsWriteAccess;

			public function __construct( bool $needsWriteAccess ) {
				$this->needsWriteAccess = $needsWriteAccess;
			}

			public function needsWriteAccess() {
				return $this->needsWriteAccess;
			}

			public function execute() {
				return null;
			}
		};
	}

	/**
	 * A representative flat security-scheme map: a multi-scheme cookie provider
	 * (grouped with AND) and a single-scheme OAuth-like provider (a separate OR option).
	 */
	private static function getTestSecuritySchemes(): array {
		return [
			'MediaWiki-Session-CookieSessionProvider-Session' => [
				'type' => 'apiKey',
				'in' => 'cookie',
				'name' => 'wiki_session',
				'description' => new MessageValue( 'sessionprovider-cookie-openapi-description-session' ),
			],
			'MediaWiki-Session-CookieSessionProvider-UserID' => [
				'type' => 'apiKey',
				'in' => 'cookie',
				'name' => 'wikiUserID',
			],
			'MediaWiki-Session-CookieSessionProvider-Token' => [
				'type' => 'apiKey',
				'in' => 'cookie',
				'name' => 'wikiToken',
			],
			'MediaWiki-Extension-OAuth-SessionProvider' => [
				'type' => 'http',
				'scheme' => 'bearer',
				'description' => 'OAuth 2.0',
			],
		];
	}

	private function assertWellFormedOAS( array $spec ) {
		$this->assertMatchesJsonSchema(
			__DIR__ . '/data/OpenApi-3.0.json',
			$spec
		);
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

	public static function provideGetInfoSpecSuccess() {
		yield 'module and version' => [
			__DIR__ . '/SpecTestModule.json',
			[
				'pathParams' => [ 'module' => 'mock', 'version' => 'v1' ]
			],
			[
				'info' => [
					'title' => 'mock/v1 <message key="rest-module"></message>',
					'version' => '1.3-test',
					'contact' => [
						'email' => 'test@example.com'
					],
				],
				'externalDocs' => [
					'description' => 'Test docs',
					'url' => 'https://example.com/docs',
				],
				'tags' => [
					[
						'name' => 'Foo',
						'description' => 'Foo operations',
					],
					[
						'name' => 'Bar',
						'description' => 'Bar operations',
					],
				],
				'servers' => [
					[ 'url' => 'https://example.com:1234/api/mock/v1' ]
				],
				'paths' => [
					'/foo/bar' => [
						'get' => [
							'operationId' => 'getFooBarAction',
							'parameters' => [ [ 'name' => 'q', 'in' => 'query' ] ],
							'responses' => [ 200 => [ 'description' => 'OK' ] ]
						],
						'post' => [
							'operationId' => 'postFooBar',
							'requestBody' => [
								'required' => true,
								'content' => [
									'application/json' => [
										'schema' => [
											'type' => 'object',
											'required' => [ 'b' ],
											'properties' => [
												'b' => [ 'type' => 'string' ]
											],
										]
									]
								]
							],
							'responses' => [ 200 => [ 'description' => 'OK' ] ]
						],
					]
				],
				'components' => [
					'schemas' => [
						'boolean-param' => [ 'type' => 'boolean' ],
					],
					'responses' => [
						'GenericErrorResponse' => self::anything(),
					],
				]
			]
		];
		yield 'prefix-less module' => [
			__DIR__ . '/SpecTestFlatRoutes.json',
			[
				'pathParams' => [ 'module' => '-' ]
			],
			[
				'info' => [
					'title' => '<message key="rest-module-extra-routes-title"></message>',
					'version' => '0.1.0',
					'license' => [
						'name' => 'Test License',
						'url' => 'https://example.com/license',
					],
				],
				'servers' => [
					[ 'url' => 'https://example.com:1234/api' ]
				],
				'paths' => [
					'/mock/v1/foo/bar' => [
						'get' => [
							'operationId' => 'getMockV1FooBar',
							'responses' => [ 200 => [ 'description' => 'OK' ] ]
						],
					]
				],
			]
		];
	}

	/**
	 * @dataProvider provideGetInfoSpecSuccess
	 */
	public function testGetInfoSpecSuccess( $specFile, $params, $expected ) {
		$this->overrideConfigValues( [
			MainConfigNames::RightsText => 'Test License',
			MainConfigNames::RightsUrl => 'https://example.com/license',
			MainConfigNames::EmergencyContact => 'test@example.com',
			MainConfigNames::CanonicalServer => 'https://example.com:1234',
			MainConfigNames::RestPath => '/api',
		] );

		$request = new RequestData( $params );

		$moduleModes = [
			'mock/v1' => ModuleMode::PUBLISHED,
			'' => ModuleMode::PUBLISHED,
		];
		$router = $this->createRouter( $request, $specFile, $moduleModes );

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
		$this->assertWellFormedOAS( $data );
		$this->assertContainsRecursive( $expected, $data );
	}

	public function testExternalModuleSpecs() {
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

		$request = new RequestData(
			[ 'pathParams' => [ 'module' => 'mockExternal', 'version' => 'v1' ] ]
		);

		$moduleModes = [
			'mockExternal/v1' => ModuleMode::PUBLISHED,
		];
		$router = $this->createRouter( $request, null, $moduleModes );

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
		$this->assertSame( 301, $response->getStatusCode() );
		$this->assertArrayHasKey( 'Location', $response->getHeaders() );
		$this->assertSame(
			'https://example.com/mockExternal/v1/spec.json',
			$response->getHeaderLine( 'Location' )
		);
	}

	public static function provideGenerateOperationId(): iterable {
		// ASCII summary
		yield 'GET + summary' => [ 'GET', 'Search pages', '/v1/page', 'getSearchPages' ];
		// No summary → falls back to path
		yield 'GET + no summary' => [ 'GET', null, '/v1/page/{title}', 'getV1PageByTitle' ];
		// Empty summary → falls back to path
		yield 'GET + empty summary' => [ 'GET', '', '/v1/page', 'getV1Page' ];
		// Method case is irrelevant; output is always lowercase-prefixed
		yield 'lowercase method' => [ 'get', 'Search pages', '/v1/page', 'getSearchPages' ];
		// Accented characters: non-ASCII letters are stripped; surrounding ASCII letters remain
		yield 'accented chars in summary' => [ 'GET', 'Récupérer la page', '/v1/page', 'getRCupRerLaPage' ];
	}

	/**
	 * @dataProvider provideGenerateOperationId
	 */
	public function testGenerateOperationId(
		string $method,
		?string $summary,
		string $path,
		string $expected
	): void {
		$handler = TestingAccessWrapper::newFromClass( ModuleSpecHandler::class );
		$this->assertSame( $expected, $handler->generateOperationId( $method, $summary, $path ) );
	}

	public function testHiddenSpec() {
		$this->overrideConfigValues( [
			MainConfigNames::RightsText => 'Test License',
			MainConfigNames::RightsUrl => 'https://example.com/license',
			MainConfigNames::EmergencyContact => 'test@example.com',
			MainConfigNames::CanonicalServer => 'https://example.com:1234',
			MainConfigNames::RestPath => '/api',
		] );

		$params = [
			'pathParams' => [ 'module' => 'mock', 'version' => 'v1' ]
		];
		$request = new RequestData( $params );

		$overrides = [
			'mock/v1' => ModuleMode::HIDDEN,
		];
		$router = $this->createRouter( $request, __DIR__ . '/SpecTestModule.json', $overrides );

		$handler = $this->newHandler();

		$this->expectException( LocalizedHttpException::class );
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
		$this->assertSame( 403, $response->getStatusCode() );
	}

	public function testGetOpenApiSecurityRequirements() {
		$handler = $this->newHandler( self::getTestSecuritySchemes() );
		$wrapper = TestingAccessWrapper::newFromObject( $handler );

		$cookieGroup = [
			'MediaWiki-Session-CookieSessionProvider-Session' => [],
			'MediaWiki-Session-CookieSessionProvider-UserID' => [],
			'MediaWiki-Session-CookieSessionProvider-Token' => [],
		];
		$oauthGroup = [ 'MediaWiki-Extension-OAuth-SessionProvider' => [] ];

		// Read-only endpoint: anonymous access ({}) plus each provider as an OR option.
		$read = $wrapper->getOpenApiSecurityRequirements( $this->newAccessHandler( false ) );
		$this->assertCount( 3, $read );
		$this->assertEquals( new \stdClass(), $read[0], 'Read endpoints allow anonymous access' );
		$this->assertSame( $cookieGroup, $read[1], 'Cookie provider schemes are AND-grouped' );
		$this->assertSame( $oauthGroup, $read[2], 'OAuth provider is a separate OR option' );

		// Write endpoint: authentication mandatory, so the {} option is omitted.
		$write = $wrapper->getOpenApiSecurityRequirements( $this->newAccessHandler( true ) );
		$this->assertCount( 2, $write );
		$this->assertSame( $cookieGroup, $write[0] );
		$this->assertSame( $oauthGroup, $write[1] );
	}

	public function testGetOpenApiSecurityRequirementsWithoutProviders() {
		$wrapper = TestingAccessWrapper::newFromObject( $this->newHandler() );

		// With no provider schemes, a read endpoint still offers anonymous access...
		$this->assertEquals(
			[ new \stdClass() ],
			$wrapper->getOpenApiSecurityRequirements( $this->newAccessHandler( false ) )
		);
		// ...while a write endpoint ends up with no satisfiable requirement.
		$this->assertSame(
			[],
			$wrapper->getOpenApiSecurityRequirements( $this->newAccessHandler( true ) )
		);
	}

	public function testSecuritySchemesAndPerOperationSecurity() {
		$this->overrideConfigValues( [
			MainConfigNames::RightsText => 'Test License',
			MainConfigNames::RightsUrl => 'https://example.com/license',
			MainConfigNames::EmergencyContact => 'test@example.com',
			MainConfigNames::CanonicalServer => 'https://example.com:1234',
			MainConfigNames::RestPath => '/api',
		] );

		$request = new RequestData(
			[ 'pathParams' => [ 'module' => 'mock', 'version' => 'v1' ] ]
		);
		$router = $this->createRouter(
			$request,
			__DIR__ . '/SpecTestModule.json',
			[ 'mock/v1' => ModuleMode::PUBLISHED ]
		);

		$handler = $this->newHandler( self::getTestSecuritySchemes() );
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

		// components.securitySchemes is built from the installed session providers...
		$schemes = $data['components']['securitySchemes'];
		$this->assertSame(
			'apiKey',
			$schemes['MediaWiki-Session-CookieSessionProvider-Session']['type']
		);
		$this->assertSame(
			'wiki_session',
			$schemes['MediaWiki-Session-CookieSessionProvider-Session']['name']
		);
		// ...with MessageValue descriptions localized (the test formatter dumps the key)...
		$this->assertSame(
			'<message key="sessionprovider-cookie-openapi-description-session"></message>',
			$schemes['MediaWiki-Session-CookieSessionProvider-Session']['description']
		);
		// ...and plain-string descriptions emitted unchanged.
		$this->assertSame(
			'OAuth 2.0',
			$schemes['MediaWiki-Extension-OAuth-SessionProvider']['description']
		);

		// newFooBarHandler requires write access → no anonymous {}; schemes grouped per provider.
		$expectedSecurity = [
			[
				'MediaWiki-Session-CookieSessionProvider-Session' => [],
				'MediaWiki-Session-CookieSessionProvider-UserID' => [],
				'MediaWiki-Session-CookieSessionProvider-Token' => [],
			],
			[ 'MediaWiki-Extension-OAuth-SessionProvider' => [] ],
		];
		$this->assertSame( $expectedSecurity, $data['paths']['/foo/bar']['get']['security'] );
		$this->assertSame( $expectedSecurity, $data['paths']['/foo/bar']['post']['security'] );

		$this->assertWellFormedOAS( $data );
	}

	public static function newFooBarHandler() {
		return new class extends Handler {
			public function getParamSettings() {
				return [
					'q' => [
						Handler::PARAM_SOURCE => 'query',
						ParamValidator::PARAM_REQUIRED => 'false',
					],
				];
			}

			public function getBodyParamSettings(): array {
				return [
					'b' => [
						Handler::PARAM_SOURCE => 'body',
						ParamValidator::PARAM_REQUIRED => 'true',
					],
				];
			}

			public function execute() {
				return 'foo bar';
			}
		};
	}

}
