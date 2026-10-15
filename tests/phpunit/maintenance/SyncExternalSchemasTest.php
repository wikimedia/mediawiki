<?php

namespace MediaWiki\Tests\Maintenance;

use MockHttpTrait;
use SyncExternalSchemas;

/**
 * @covers \SyncExternalSchemas
 */
class SyncExternalSchemasTest extends MaintenanceBaseTestCase {
	use MockHttpTrait;

	private string $dir;

	protected function getMaintenanceClass() {
		return SyncExternalSchemas::class;
	}

	protected function setUp(): void {
		parent::setUp();
		$this->dir = $this->getNewTempDirectory();
		mkdir( "$this->dir/schema.example" );
		file_put_contents( "$this->dir/schema.example/error.json", 'old' );
		file_put_contents( "$this->dir/README.md", 'not a copy' );
		$this->maintenance->dir = $this->dir;
	}

	/**
	 * @param array<string,string> $published Content by URL; other URLs fail to download
	 */
	private function publish( array $published ): void {
		$this->installMockHttp( fn ( $url ) => isset( $published[$url] )
			? $this->makeFakeHttpRequest( $published[$url] )
			: $this->makeFakeHttpRequest( '', 404 )
		);
	}

	public function testUpdatesOutdatedCopies() {
		$this->publish( [ 'https://schema.example/error.json' => 'new' ] );

		$this->maintenance->execute();

		$this->assertStringEqualsFile( "$this->dir/schema.example/error.json", 'new' );
		$this->expectOutputRegex( '/Updated https:\/\/schema\.example\/error\.json/' );
	}

	public function testCheckReportsOutdatedCopiesWithoutWriting() {
		$this->publish( [ 'https://schema.example/error.json' => 'new' ] );
		$this->maintenance->setOption( 'check', true );

		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Outdated https:\/\/schema\.example\/error\.json/' );
		try {
			$this->maintenance->execute();
		} finally {
			$this->assertStringEqualsFile( "$this->dir/schema.example/error.json", 'old' );
		}
	}

	public function testCheckPassesWhenUpToDate() {
		$this->publish( [ 'https://schema.example/error.json' => 'old' ] );
		$this->maintenance->setOption( 'check', true );

		$this->maintenance->execute();

		$this->expectOutputRegex( '/All local copies are up to date/' );
	}

	public function testAddsNewSchema() {
		$this->publish( [ 'https://schema.example/v2/error.json' => 'added' ] );
		$this->maintenance->setArg( 'url', 'https://schema.example/v2/error.json' );

		$this->maintenance->execute();

		$this->assertStringEqualsFile( "$this->dir/schema.example/v2/error.json", 'added' );
		$this->assertStringEqualsFile( "$this->dir/schema.example/error.json", 'old' );
		$this->expectOutputRegex( '/Updated https:\/\/schema\.example\/v2\/error\.json/' );
	}

	public function testSyncsOnlyJsonAndYamlFiles() {
		file_put_contents( "$this->dir/schema.example/error.yaml", 'old' );
		file_put_contents( "$this->dir/schema.example/error.json~", 'backup' );
		file_put_contents( "$this->dir/schema.example/.DS_Store", 'junk' );
		$this->publish( [
			'https://schema.example/error.json' => 'new',
			'https://schema.example/error.yaml' => 'new',
		] );

		$this->maintenance->execute();

		$this->assertStringEqualsFile( "$this->dir/schema.example/error.json", 'new' );
		$this->assertStringEqualsFile( "$this->dir/schema.example/error.yaml", 'new' );
		$this->assertStringEqualsFile( "$this->dir/schema.example/error.json~", 'backup' );
		$this->expectOutputRegex( '/Updated https:\/\/schema\.example\/error\.yaml/' );
	}

	public function testRejectsUrlsThatAreNotSchemas() {
		$this->publish( [] );
		$this->maintenance->setArg( 'url', 'https://schema.example/error.txt' );

		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Not a \.json or \.yaml URL/' );
		$this->maintenance->execute();
	}

	public function testFailsWhenDownloadFails() {
		$this->publish( [] );

		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Failed to download https:\/\/schema\.example\/error\.json/' );
		$this->maintenance->execute();
	}

	public function testRejectsNonHttpsUrls() {
		$this->publish( [] );
		$this->maintenance->setArg( 'url', 'http://schema.example/error.json' );

		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Not an https:\/\/ URL/' );
		$this->maintenance->execute();
	}
}
