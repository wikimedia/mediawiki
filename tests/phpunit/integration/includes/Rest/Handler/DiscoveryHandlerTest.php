<?php

namespace MediaWiki\Tests\Rest\Handler;

use JsonSchemaAssertionTrait;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\Handler\DiscoveryHandler;
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

/**
 * @covers \MediaWiki\Rest\Handler\DiscoveryHandler
 *
 * @group Database
 */
class DiscoveryHandlerTest extends MediaWikiIntegrationTestCase {
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
		return new DiscoveryHandler(
			$config
		);
	}

	private function assertWellFormedDiscoveryDoc( array $discovery ) {
		$schemaFile = MW_INSTALL_PATH . '/docs/rest/discovery-1.0.json';

		$this->assertMatchesJsonSchema( $schemaFile, $discovery, [
			'https://www.mediawiki.org/schema/mwapi-1.0' => MW_INSTALL_PATH . '/docs/rest/mwapi-1.0.json',
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
			MainConfigNames::CanonicalServer => 'https://example.com:1234',
			MainConfigNames::RestPath => '/api',
		] );

		$request = new RequestData( [] );
		$router = $this->createRouter( $request, __DIR__ . '/SpecTestRoutes.json' );

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
				'contact' => [
					'email' => 'test@example.com',
				],
			],
			'servers' => [
				[ 'url' => 'https://example.com:1234/api', ],
			],
			'modules' => [
				'mock/v1' => [
					'info' => [
						'version' => '1.0',
						'title' => 'test module',
					],
					'base' => 'https://example.com:1234/api/mock/v1',
					'spec' => 'https://example.com:1234/api/specs/v0/module/mock%2Fv1',
				],
			],
		];

		self::assertContainsRecursive( $expected, $data );
	}

}
