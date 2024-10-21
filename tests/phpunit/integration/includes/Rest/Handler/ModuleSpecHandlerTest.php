<?php

namespace MediaWiki\Tests\Rest\Handler;

use JsonSchemaAssertionTrait;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\ModuleSpecHandler;
use MediaWiki\Rest\Reporter\MWErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @covers \MediaWiki\Rest\Handler\ModuleSpecHandler
 *
 * @group Database
 */
class ModuleSpecHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use JsonSchemaAssertionTrait;

	private function createRouter(
		RequestInterface $request,
		$specFile
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
			public function getLangCode() {
				return 'qqx';
			}

			public function format( MessageSpecifier $message ): string {
				return $message->dump();
			}
		};
		$responseFactory = new ResponseFactory( [ $formatter ] );

		return ( new Router(
			[ $specFile ],
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

	private function newHandler() {
		$config = $this->getServiceContainer()->getMainConfig();
		return new ModuleSpecHandler(
			$config
		);
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
					'title' => 'mock/v1 Module',
					'version' => '1.3-test',
					'contact' => [
						'email' => 'test@example.com'
					],
				],
				'servers' => [
					[ 'url' => 'https://example.com:1234/api/mock/v1' ]
				],
				'paths' => [
					'/foo/bar' => [
						'get' => [
							'parameters' => [ [ 'name' => 'q', 'in' => 'query' ] ],
							'responses' => [ 200 => [ 'description' => 'OK' ] ]
						],
						'post' => [
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
					'title' => 'Extra Routes',
					'version' => 'undefined',
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
						'get' => [ 'responses' => [ 200 => [ 'description' => 'OK' ] ] ],
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

		$router = $this->createRouter( $request, $specFile );

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
