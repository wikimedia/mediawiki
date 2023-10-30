<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\RequestContext;
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
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\Handler\ModuleSpecHandler
 *
 * @group Database
 */
class ModuleSpecHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;

	/**
	 * @param RequestInterface $request
	 *
	 * @return Router
	 */
	private function createRouter(
		RequestInterface $request
	): Router {
		$services = $this->getServiceContainer();
		$context = RequestContext::getMain();

		$conf = $services->getMainConfig();

		$authority = $context->getAuthority();
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

			public function format( MessageValue $message ) {
				return $message->dump();
			}
		};
		$responseFactory = new ResponseFactory( [ $formatter ] );

		return ( new Router(
			[ __DIR__ . '/SpecTestRoutes.json' ],
			[
				[
					"path" => "/foo/bar",
					"factory" => "MediaWiki\\Tests\\Rest\\Handler\\ModuleSpecHandlerTest::newFooBarHandler",
				],
			],
			new ServiceOptions( Router::CONSTRUCTOR_OPTIONS, $conf ),
			$services->getLocalServerObjectCache(),
			$responseFactory,
			$authorizer,
			$authority,
			$objectFactory,
			$restValidator,
			new MWErrorReporter(),
			$services->getHookContainer(),
			$context->getRequest()->getSession()
		) );
	}

	private function newHandler() {
		$config = new HashConfig( [
			MainConfigNames::RightsUrl => '',
			MainConfigNames::RightsText => '',
			MainConfigNames::EmergencyContact => '',
			MainConfigNames::Sitename => '',
		] );
		return new ModuleSpecHandler(
			$config
		);
	}

	public static function provideGetInfoSpecSuccess() {
		yield 'module and version' => [
			[
				'pathParams' => [ 'module' => 'mock', 'version' => 'v1' ]
			]
		];
		yield 'prefix-less module' => [
			[
				'pathParams' => [ 'module' => '-' ]
			]
		];
	}

	/**
	 * @dataProvider provideGetInfoSpecSuccess
	 */
	public function testGetInfoSpecSuccess( $params ) {
		$request = new RequestData( $params );

		$router = $this->createRouter( $request );

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
		$data = json_decode( $response->getBody(), true );
		$this->assertIsArray( $data, 'Body must be a JSON array' );
	}

	public static function newFooBarHandler() {
		return new class extends Handler {
			public function execute() {
				return 'foo bar';
			}
		};
	}

}
