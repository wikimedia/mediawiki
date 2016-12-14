<?php

class ConfigRepositoryTest extends MediaWikiTestCase {
	/**
	 * @expectedException ConfigException
	 * @expectedExceptionMessage The configuration option doesNotExist does not exist.
	 */
	public function testNonExistingThrowsConfigException() {
		$configRepo = new \MediaWiki\Config\ConfigRepository();
		$configRepo->get( 'doesNotExist' );
	}

	public function testIsEmpty() {
		$repo = new \MediaWiki\Config\ConfigRepository();

		$this->assertTrue( $repo->isEmpty() );

		$repo->add( 'test', [] );
		// by default, only public values should be considered
		$this->assertTrue( $repo->isEmpty() );
		$this->assertFalse( $repo->isEmpty( true ) );

		$repo->add( 'publicTest', [ 'public' => true ] );
		$this->assertFalse( $repo->isEmpty() );
		$this->assertFalse( $repo->isEmpty( true ) );
	}

	/**
	 * @covers ConfigRepository::add()
	 * @covers ConfigRepository::has()
	 */
	public function testHas() {
		$repo = new \MediaWiki\Config\ConfigRepository();
		$this->assertFalse( $repo->has( 'doesNotExist' ) );

		$repo->add( 'doesExistPrivate', [] );
		// the default of add should be "private", has(), by default, should return public values
		// only
		$this->assertFalse( $repo->has( 'doesExistPrivate' ) );
		$this->assertTrue( $repo->has( 'doesExistPrivate', true ) );

		$repo->add( 'desExistPublic', [ 'public' => true ] );
		$this->assertTrue( $repo->has( 'desExistPublic' ) );
	}

	/**
	 * @covers ConfigRepository::get()
	 * @covers ConfigRepository::getAll()
	 * @covers ConfigRepository::add()
	 */
	public function testGetAndGetAll() {
		$repo = new \MediaWiki\Config\ConfigRepository();

		$private = [ 'value' => 'test' ];
		$public = [ 'public' => true, 'value' => 'test' ];
		$all = [ 'privateConfig' => $private, 'publicConfig' => $public ];
		$repo->add( 'privateConfig', $private );
		$repo->add( 'publicConfig', $public );

		$this->assertArrayEquals( $private, $repo->get( 'privateConfig' ) );
		$this->assertArrayEquals( $public, $repo->get( 'publicConfig' ) );
		$this->assertArrayEquals( $repo->getAll(), $all );
	}

	/**
	 * @covers ConfigRepository::add()
	 * @expectedException ConfigException
	 * @codingStandardsIgnoreStart Generic.Files.LineLength
	 * @expectedExceptionMessage A configuration with the name test does already exist. It is provided by: Extension
	 * @@codingStandardsIgnoreEnd
	 */
	public function testAddDuplicateValuesPrivate() {
		$repo = new \MediaWiki\Config\ConfigRepository();

		$repo->add( 'test', [ 'value' => 'test', 'providedby' => 'Extension' ] );
		$repo->add( 'test', [ 'value' => 'test' ] );
	}

	/**
	 * @covers ConfigRepository::add()
	 * @expectedException ConfigException
	 * @codingStandardsIgnoreStart Generic.Files.LineLength
	 * @expectedExceptionMessage A configuration with the name test does already exist. It is provided by:
	 * @codingStandardsIgnoreEnd
	 */
	public function testAddDuplicateValuesPublic() {
		$repo = new \MediaWiki\Config\ConfigRepository();

		$repo->add( 'test', [ 'value' => 'test', 'public' => true ] );
		$repo->add( 'test', [ 'value' => 'test', 'public' => true ] );
	}

	/**
	 * @covers ConfigRepository::add()
	 */
	public function testAddDuplicateValuesPrivatePublic() {
		$repo = new \MediaWiki\Config\ConfigRepository();

		$repo->add( 'test', [ 'value' => 'test' ] );
		$repo->add( 'test', [ 'value' => 'test', 'public' => true ] );
	}

	public function testGetDescriptionOf() {
		$description = 'a test description';
		$repo = new \MediaWiki\Config\ConfigRepository();
		$repo->add( 'testDescription', [
			'public' => true,
			'description' => $description
		] );

		$repo->add( 'testDescriptionMsg', [
			'public' => true,
			'descriptionmsg' => 'period-pm',
		] );

		$this->assertEquals( $description, $repo->getDescriptionOf( 'testDescription' ) );
		$this->assertEquals( 'PM', $repo->getDescriptionOf( 'testDescriptionMsg' ) );
	}

	public function testGetValueOf() {
		$configFactory = new ConfigFactory();
		$configFactory->register( 'main', 'HashConfig::newInstance' );
		/** @var MutableConfig $config */
		$config = $configFactory->makeConfig( 'main' );
		$config->set( 'withCR', 'CRTest' );

		$repo = new \MediaWiki\Config\ConfigRepository();
		$repo->setConfigFactory( $configFactory );
		$repo->add( 'noCR', [ 'value' => 'test' ] );
		$repo->add( 'withCR', [ 'value', 'test', 'configregistry' => 'main' ] );

		$this->assertEquals( 'test', $repo->getValueOf( 'noCR' ) );
		$this->assertEquals( 'CRTest', $repo->getValueOf( 'withCR' ) );
	}
}
