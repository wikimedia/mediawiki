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
 * @ingroup Categories
 */

/**
 * This class performs some operations related to tracking categories, such as creating
 * a list of all such categories.
 */
class TrackingCategories {
	/** @var Config */
	private $config;

	/**
	 * Tracking categories that exist in core
	 *
	 * @var array
	 */
	private static $coreTrackingCategories = [
		'index-category',
		'noindex-category',
		'duplicate-args-category',
		'expensive-parserfunction-category',
		'post-expand-template-argument-category',
		'post-expand-template-inclusion-category',
		'hidden-category-category',
		'broken-file-category',
		'node-count-exceeded-category',
		'expansion-depth-exceeded-category',
		'restricted-displaytitle-ignored',
		'deprecated-self-close-category',
		'template-loop-category',
	];

	/**
	 * @param Config $config
	 */
	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * Read the global and extract title objects from the corresponding messages
	 * @return array Array( 'msg' => Title, 'cats' => Title[] )
	 */
	public function getTrackingCategories() {
		$categories = array_merge(
			self::$coreTrackingCategories,
			ExtensionRegistry::getInstance()->getAttribute( 'TrackingCategories' ),
			$this->config->get( 'TrackingCategories' ) // deprecated
		);

		// Only show magic link tracking categories if they are enabled
		$enableMagicLinks = $this->config->get( 'EnableMagicLinks' );
		if ( $enableMagicLinks['ISBN'] ) {
			$categories[] = 'magiclink-tracking-isbn';
		}
		if ( $enableMagicLinks['RFC'] ) {
			$categories[] = 'magiclink-tracking-rfc';
		}
		if ( $enableMagicLinks['PMID'] ) {
			$categories[] = 'magiclink-tracking-pmid';
		}

		$trackingCategories = [];
		foreach ( $categories as $catMsg ) {
			/*
			 * Check if the tracking category varies by namespace
			 * Otherwise only pages in the current namespace will be displayed
			 * If it does vary, show pages considering all namespaces
			 */
			$msgObj = wfMessage( $catMsg )->inContentLanguage();
			$allCats = [];
			$catMsgTitle = Title::makeTitleSafe( NS_MEDIAWIKI, $catMsg );
			if ( !$catMsgTitle ) {
				continue;
			}

			// Match things like {{NAMESPACE}} and {{NAMESPACENUMBER}}.
			// False positives are ok, this is just an efficiency shortcut
			if ( strpos( $msgObj->plain(), '{{' ) !== false ) {
				$ns = MWNamespace::getValidNamespaces();
				foreach ( $ns as $namesp ) {
					$tempTitle = Title::makeTitleSafe( $namesp, $catMsg );
					if ( !$tempTitle ) {
						continue;
					}
					$catName = $msgObj->title( $tempTitle )->text();
					# Allow tracking categories to be disabled by setting them to "-"
					if ( $catName !== '-' ) {
						$catTitle = Title::makeTitleSafe( NS_CATEGORY, $catName );
						if ( $catTitle ) {
							$allCats[] = $catTitle;
						}
					}
				}
			} else {
				$catName = $msgObj->text();
				# Allow tracking categories to be disabled by setting them to "-"
				if ( $catName !== '-' ) {
					$catTitle = Title::makeTitleSafe( NS_CATEGORY, $catName );
					if ( $catTitle ) {
						$allCats[] = $catTitle;
					}
				}
			}
			$trackingCategories[$catMsg] = [
				'cats' => $allCats,
				'msg' => $catMsgTitle,
			];
		}

		return $trackingCategories;
	}
}
