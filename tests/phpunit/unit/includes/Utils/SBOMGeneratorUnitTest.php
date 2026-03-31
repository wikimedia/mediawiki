<?php

namespace MediaWiki\Tests\Utils;

use MediaWiki\Context\IContextSource;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Utils\SBOMGenerator;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * @covers \MediaWiki\Utils\SBOMGenerator
 */
class SBOMGeneratorUnitTest extends MediaWikiUnitTestCase {

	/**
	 * @return SBOMGenerator
	 */
	private function newSBOMGenerator( ?IConnectionProvider $connectionProvider = null ) {
		return TestingAccessWrapper::newFromObject( new SBOMGenerator(
			$connectionProvider ?? $this->createMock( IConnectionProvider::class ),
			$this->createMock( ExtensionRegistry::class ),
			$this->createMock( GlobalIdGenerator::class ),
		) );
	}

	public function testGetPHPExtensionComponents_RemovesPHPCore() {
		$generator = $this->newSBOMGenerator();

		$components = $generator->getPHPExtensionComponents( [
			'Core',
			'extension1',
			'extension2',
		] );
		$this->assertCount( 2, $components );
	}

	/**
	 * @dataProvider provideExtensions
	 */
	public function testGetExtensionComponents( array $extensions, array $expected ) {
		$generator = $this->newSBOMGenerator();
		$context = $this->createMock( IContextSource::class );
		$context->method( 'msg' )
			->willReturnCallback( fn ( $key ) => $this->getMockMessage( "Test translation for \"$key\"" ) );
		$this->assertArrayEquals(
			$expected,
			$generator->getExtensionComponents( $extensions, $context ),
			ordered: true,
		);
	}

	public static function provideExtensions(): array {
		return [
			[
				[
					'Extension1' => [
						'author' => 'Author 1',
						'version' => '1.2.3',
						'license-name' => 'License 1',
						'url' => 'https://mediawiki.org/wiki/Extension:Extension1',
						'descriptionmsg' => 'test-description',
					],
					'Extension3' => [],
					'Extension2' => [
						'author' => [
							'Author 2',
							'Author 3',
						],
						'version' => '4.5.6',
						'license-name' => 'License 2',
						'url' => 'https://mediawiki.org/wiki/Extension:Extension2',
						'description' => 'Example description of Extension2',
					],
				],
				[
					[
						'type' => 'application',
						'name' => 'Extension1',
						'authors' => [
							[
								'name' => 'Author 1',
							],
						],
						'version' => '1.2.3',
						'licences' => [
							'License 1',
						],
						'url' => 'https://mediawiki.org/wiki/Extension:Extension1',
						'description' => 'Test translation for "test-description"',
					],
					[
						'type' => 'application',
						'name' => 'Extension2',
						'authors' => [
							[
								'name' => 'Author 2',
							],
							[
								'name' => 'Author 3',
							],
						],
						'version' => '4.5.6',
						'licences' => [
							'License 2',
						],
						'url' => 'https://mediawiki.org/wiki/Extension:Extension2',
						'description' => 'Example description of Extension2',
					],
					[
						'type' => 'application',
						'name' => 'Extension3',
					],
				],
			],
			[
				[],
				[],
			],
		];
	}

	public function testGetPlatformComponents() {
		$connectionProvider = $this->createMock( IConnectionProvider::class );
		$database = $this->createMock( IReadableDatabase::class );
		$database->method( 'getType' )->willReturn( 'testdbtype' );
		$database->method( 'getServerVersion' )->willReturn( '1.2.3-test' );
		$connectionProvider->method( 'getReplicaDatabase' )->willReturn( $database );

		$generator = $this->newSBOMGenerator( $connectionProvider );
		$platformComponents = $generator->getPlatformComponents();
		$this->assertCount( 3, $platformComponents );

		$phpComponent = array_find( $platformComponents, static fn ( $c ) => $c['name'] === 'PHP' );
		$this->assertNotNull( $phpComponent );
		$this->assertIsString( $phpComponent['version'] );
		$this->assertEquals( 'platform', $phpComponent['type'] );
		$this->assertIsArray( $phpComponent['components'] );

		$dbComponent = array_find( $platformComponents, static fn ( $c ) => $c['name'] === 'testdbtype' );
		$this->assertNotNull( $dbComponent );
		$this->assertEquals( 'platform', $dbComponent['type'] );
		$this->assertEquals( '1.2.3-test', $dbComponent['version'] );
	}

}
