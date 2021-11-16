<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Settings\Config\ConfigBuilder;
use MediaWiki\Settings\Config\MergeStrategy;
use MediaWiki\Settings\SettingsBuilderException;

trait ConfigSinkTestTrait {

	abstract protected function getConfigSink(): ConfigBuilder;

	abstract protected function assertKeyHasValue( string $key, $value );

	public function testSet() {
		$this->getConfigSink()->set( __METHOD__, 'bar' );
		$this->assertKeyHasValue( __METHOD__, 'bar' );
	}

	public function testSetOverrides() {
		$this->getConfigSink()
			->set( __METHOD__, 'bar' )
			->set( __METHOD__, 'baz' );
		$this->assertKeyHasValue( __METHOD__, 'baz' );
	}

	public function testSetDefault() {
		$this->getConfigSink()
			->set( __METHOD__, null )
			->setDefault( 'other' . __METHOD__, 'quux' )
			->setDefault( __METHOD__, 'baz' );

		$this->assertKeyHasValue( 'other' . __METHOD__, 'quux' );
		$this->assertKeyHasValue( __METHOD__, null );
	}

	public function testMerge() {
		$this->getConfigSink()
			->set( __METHOD__, [ 'bar' ] )
			->set(
				__METHOD__,
				[ 'baz' ],
				MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )
			);
		$this->assertKeyHasValue( __METHOD__, [ 'bar', 'baz' ] );
	}

	public function testMergeDefault() {
		$this->getConfigSink()
			->set( __METHOD__, [ 'bar' ] )
			->setDefault(
				__METHOD__,
				[ 'baz' ],
				MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )
			);
		$this->assertKeyHasValue( __METHOD__, [ 'baz', 'bar' ] );
	}

	public function testMergeOverrideEmpty() {
		$this->getConfigSink()
			->set( __METHOD__, [] )
			->set(
				__METHOD__,
				[ 'baz' ],
				MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )
			);
		$this->assertKeyHasValue( __METHOD__, [ 'baz' ] );
	}

	public function testMergeOverrideNonExisting() {
		$this->getConfigSink()
			->set(
				__METHOD__,
				[ 'baz' ],
				MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )
			);
		$this->assertKeyHasValue( __METHOD__, [ 'baz' ] );
	}

	public function testMergeDefaultOverrideEmpty() {
		$this->getConfigSink()
			->set( __METHOD__, [] )
			->setDefault(
				__METHOD__,
				[ 'baz' ],
				MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )
			);
		$this->assertKeyHasValue( __METHOD__, [ 'baz' ] );
	}

	public function testMergeDefaultOverrideNonExisting() {
		$this->getConfigSink()
			->setDefault(
				__METHOD__,
				[ 'baz' ],
				MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )
			);
		$this->assertKeyHasValue( __METHOD__, [ 'baz' ] );
	}

	public function testCannotMergeNonArray() {
		$this->expectException( SettingsBuilderException::class );
		$this->getConfigSink()
			->set(
				__METHOD__,
				'baz',
				MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )
			);
	}
}
