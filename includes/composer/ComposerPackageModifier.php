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

	public function __construct( Package $package,
		ComposerVersionNormalizer $versionNormalizer, MediaWikiVersionFetcher $versionFetcher
	) {
		$this->package = $package;
		$this->versionNormalizer = $versionNormalizer;
		$this->versionFetcher = $versionFetcher;
	}

	public function setProvidesMediaWiki() {
		$this->setLinkAsProvides( $this->newMediaWikiLink() );
	}

	private function setLinkAsProvides( Link $link ) {
		$this->package->setProvides( [ $link ] );
	}

	private function newMediaWikiLink() {
		$version = $this->getMediaWikiVersionConstraint();

		$link = new Link(
			'__root__',
			self::MEDIAWIKI_PACKAGE_NAME,
			$version,
			'provides',
			$version->getPrettyString()
		);

		return $link;
	}

	private function getMediaWikiVersionConstraint() {
		$mvVersion = $this->versionFetcher->fetchVersion();
		$mvVersion = $this->versionNormalizer->normalizeSuffix( $mvVersion );

		$version = new Constraint(
			'==',
			$this->versionNormalizer->normalizeLevelCount( $mvVersion )
		);
		$version->setPrettyString( $mvVersion );

		return $version;
	}

}
