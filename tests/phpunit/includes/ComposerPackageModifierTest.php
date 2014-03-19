<?php

/**
 * Note: this integration test requires Composer to be loaded.
 * Add "composer/composer" in the require section of your composer.json and run "composer update".
 *
 * @covers ComposerPackageModifier
 *
 * @group ComposerHooks
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ComposerPackageModifierTest extends PHPUnit_Framework_TestCase {

	public static function setUpBeforeClass() {
		if ( !class_exists( 'Composer\Package\Package' ) ) {
			self::markTestSkipped( 'Composer needs to be loaded to run ComposerPackageModifierTest' );
		}
	}

	const MW_VERSION_RAW = '1.337alpha';
	const MW_VERSION_CLEAN = '1.337-alpha';
	const MW_VERSION_FULL = '1.337.0.0-alpha';

	public function testIntegration() {
		$versionFetcher = $this->getMock( 'MediaWikiVersionFetcher' );

		$versionFetcher->expects( $this->once() )
			->method( 'fetchVersion' )
			->will( $this->returnValue( self::MW_VERSION_RAW ) );

		$versionNormalizer = $this->getMock( 'ComposerVersionNormalizer' );

		$versionNormalizer->expects( $this->once() )
			->method( 'normalizeSuffix' )
			->with( $this->equalTo( self::MW_VERSION_RAW ) )
			->will( $this->returnValue( self::MW_VERSION_CLEAN ) );

		$versionNormalizer->expects( $this->once() )
			->method( 'normalizeLevelCount' )
			->with( $this->equalTo( self::MW_VERSION_CLEAN ) )
			->will( $this->returnValue( self::MW_VERSION_FULL ) );

		$package = $this->getMockBuilder( 'Composer\Package\Package' )
			->disableOriginalConstructor()->getMock();

		$package->expects( $this->once() )
			->method( 'setProvides' )
			->with( $this->callback( array( $this, 'assertIsValidSetProvidesArgument' ) ) );

		$packageModifier = new ComposerPackageModifier( $package, $versionNormalizer, $versionFetcher );
		$packageModifier->setProvidesMediaWiki();
	}

	public function assertIsValidSetProvidesArgument( $argument ) {
		if ( !is_array( $argument ) || count( $argument ) !== 1 ) {
			return false;
		}

		$link = $argument[0];

		if ( !( $link instanceof Composer\Package\Link ) ) {
			return false;
		}

		return $link->getTarget() === ComposerPackageModifier::MEDIAWIKI_PACKAGE_NAME
			&& $link->getPrettyConstraint() === self::MW_VERSION_FULL;
	}

}
