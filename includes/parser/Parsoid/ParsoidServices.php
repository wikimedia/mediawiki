<?php
/**
 * Copyright (C) 2011-2022 Wikimedia Foundation and others.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
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

	/** @var MediaWikiServices */
	private $services;

	/**
	 * @param MediaWikiServices $services
	 */
	public function __construct( MediaWikiServices $services ) {
		$this->services = $services;
	}

	/**
	 * @return DataAccess
	 */
	public function getParsoidDataAccess(): DataAccess {
		wfDeprecated( __METHOD__, '1.39' );
		return $this->services->getParsoidDataAccess();
	}

	/**
	 * @return PageConfigFactory
	 */
	public function getParsoidPageConfigFactory(): PageConfigFactory {
		wfDeprecated( __METHOD__, '1.39' );
		return $this->services->getParsoidPageConfigFactory();
	}

	/**
	 * @return SiteConfig
	 */
	public function getParsoidSiteConfig(): SiteConfig {
		wfDeprecated( __METHOD__, '1.39' );
		return $this->services->getParsoidSiteConfig();
	}

}
