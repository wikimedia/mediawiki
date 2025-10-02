<?php
/**
 * Copyright (C) 2011-2022 Wikimedia Foundation and others.
 *
 * @license GPL-2.0-or-later
 */
declare( strict_types = 1 );

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use Wikimedia\Parsoid\Config\DataAccess;
use Wikimedia\Parsoid\Config\SiteConfig;

/**
 * @since 1.39
 * @deprecated since 1.39. This is a marker class indicating that certain
 * code has been moved from Parsoid to core; it will be removed once the
 * transition is complete.  Use MediaWikiServices instead.
 */
class ParsoidServices {
	public function __construct( private readonly MediaWikiServices $services ) {
	}

	/**
	 * @deprecated since 1.39.
	 * @return DataAccess
	 */
	public function getParsoidDataAccess(): DataAccess {
		wfDeprecated( __METHOD__, '1.39' );
		return $this->services->getParsoidDataAccess();
	}

	/**
	 * @deprecated since 1.39.
	 * @return PageConfigFactory
	 */
	public function getParsoidPageConfigFactory(): PageConfigFactory {
		wfDeprecated( __METHOD__, '1.39' );
		return $this->services->getParsoidPageConfigFactory();
	}

	/**
	 * @deprecated since 1.39.
	 * @return SiteConfig
	 */
	public function getParsoidSiteConfig(): SiteConfig {
		wfDeprecated( __METHOD__, '1.39' );
		return $this->services->getParsoidSiteConfig();
	}

}
