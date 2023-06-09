<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Skins\Vector\FeatureManagement\Requirements;

use Config;
use MediaWiki\Skins\Vector\Constants;
use MediaWiki\Skins\Vector\FeatureManagement\Requirement;
use Title;
use WebRequest;

/**
 * The `MaxWidthRequirement` for content.
 * @package MediaWiki\Skins\Vector\FeatureManagement\Requirements
 */
final class LimitedWidthContentRequirement implements Requirement {
	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var WebRequest
	 */
	private $request;

	/**
	 * @var Title
	 */
	private $title;

	/**
	 * This constructor accepts all dependencies needed to determine whether
	 * the overridable config is enabled for the current user and request.
	 *
	 * @param Config $config
	 * @param WebRequest $request
	 * @param Title|null $title can be null in testing environment
	 */
	public function __construct(
		Config $config,
		WebRequest $request,
		$title = null
	) {
		$this->config = $config;
		$this->title = $title;
		$this->request = $request;
	}

	/**
	 * @inheritDoc
	 */
	public function getName(): string {
		return Constants::REQUIREMENT_LIMITED_WIDTH_CONTENT;
	}

	/**
	 * Per the $options configuration (for use with $wgVectorMaxWidthOptions)
	 * determine whether max-width should be disabled on the page.
	 * For the main page: Check the value of $options['exclude']['mainpage']
	 * For all other pages, the following will happen:
	 * - the array $options['include'] of canonical page names will be checked
	 *   against the current page. If a page has been listed there, function will return false
	 *   (max-width will not be  disabled)
	 * Max width is disabled if:
	 *  1) The current namespace is listed in array $options['exclude']['namespaces']
	 *  OR
	 *  2) A query string parameter matches one of the regex patterns in $exclusions['querystring'].
	 *
	 * @internal only for use inside tests.
	 * @param array $options
	 * @param Title $title
	 * @param WebRequest $request
	 * @return bool
	 */
	private static function shouldDisableMaxWidth( array $options, Title $title, WebRequest $request ) {
		$canonicalTitle = $title->getRootTitle();

		$inclusions = $options['include'] ?? [];
		$exclusions = $options['exclude'] ?? [];

		if ( $title->isMainPage() ) {
			// only one check to make
			return $exclusions['mainpage'] ?? false;
		} elseif ( $canonicalTitle->isSpecialPage() ) {
			$canonicalTitle->fixSpecialName();
		}

		//
		// Check the inclusions based on the canonical title
		// The inclusions are checked first as these trump any exclusions.
		//
		// Now we have the canonical title and the inclusions link we look for any matches.
		foreach ( $inclusions as $titleText ) {
			$includedTitle = Title::newFromText( $titleText );

			if ( $canonicalTitle->equals( $includedTitle ) ) {
				return false;
			}
		}

		//
		// Check the exclusions
		// If nothing matches the exclusions to determine what should happen
		//
		$excludeNamespaces = $exclusions['namespaces'] ?? [];
		// Max width is disabled on certain namespaces
		if ( $title->inNamespaces( $excludeNamespaces ) ) {
			return true;
		}
		$excludeQueryString = $exclusions['querystring'] ?? [];

		foreach ( $excludeQueryString as $param => $excludedParamPattern ) {
			$paramValue = $request->getRawVal( $param );
			if ( $paramValue !== null ) {
				if ( $excludedParamPattern === '*' ) {
					// Backwards compatibility for the '*' wildcard.
					$excludedParamPattern = '.+';
				}
				return (bool)preg_match( "/$excludedParamPattern/", $paramValue );
			}
		}

		return false;
	}

	/**
	 * Check query parameter to override config or not.
	 * Then check for AB test value.
	 * Fallback to config value.
	 *
	 * @inheritDoc
	 */
	public function isMet(): bool {
		return $this->title && !self::shouldDisableMaxWidth(
			$this->config->get( 'VectorMaxWidthOptions' ),
			$this->title,
			$this->request
		);
	}
}
