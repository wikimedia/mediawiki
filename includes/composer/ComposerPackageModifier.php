<?php

use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Semver\Constraint\Constraint;

/**
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ComposerPackageModifier {

	private const MEDIAWIKI_PACKAGE_NAME = 'mediawiki/mediawiki';

	protected $package;
	protected $versionNormalizer;
	protected $versionFetcher;

	/**
	 * @param Package $package
	 * @param ComposerVersionNormalizer $versionNormalizer
	 * @param MediaWikiVersionFetcher $versionFetcher
	 */
	public function __construct(
		Package $package,
		ComposerVersionNormalizer $versionNormalizer,
		MediaWikiVersionFetcher $versionFetcher
	) {
		$this->package = $package;
		$this->versionNormalizer = $versionNormalizer;
		$this->versionFetcher = $versionFetcher;
	}

	public function setProvidesMediaWiki() {
		$mvVersion = $this->versionFetcher->fetchVersion();
		$mvVersion = $this->versionNormalizer->normalizeSuffix( $mvVersion );

		$version = new Constraint(
			'==',
			$this->versionNormalizer->normalizeLevelCount( $mvVersion )
		);
		$version->setPrettyString( $mvVersion );

		$link = new Link(
			'__root__',
			self::MEDIAWIKI_PACKAGE_NAME,
			$version,
			'provides',
			$version->getPrettyString()
		);

		$this->package->setProvides( [ $link ] );
	}

}
