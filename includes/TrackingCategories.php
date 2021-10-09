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

use MediaWiki\Config\ServiceOptions;

/**
 * This class performs some operations related to tracking categories, such as creating
 * a list of all such categories.
 * @since 1.29
 */
class TrackingCategories {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'TrackingCategories',
		'EnableMagicLinks',
	];

	/** @var ServiceOptions */
	private $options;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var ExtensionRegistry */
	private $extensionRegistry;

	/**
	 * Tracking categories that exist in core
	 *
	 * @var array
	 */
	private const CORE_TRACKING_CATEGORIES = [
		'broken-file-category',
		'duplicate-args-category',
		'expansion-depth-exceeded-category',
		'expensive-parserfunction-category',
		'hidden-category-category',
		'index-category',
		'node-count-exceeded-category',
		'noindex-category',
		'nonnumeric-formatnum',
		'post-expand-template-argument-category',
		'post-expand-template-inclusion-category',
		'restricted-displaytitle-ignored',
		'template-equals-category',
		'template-loop-category',
	];

	/**
	 * @param ServiceOptions $options
	 * @param NamespaceInfo $namespaceInfo
	 * @param TitleFactory $titleFactory
	 */
	public function __construct(
		ServiceOptions $options,
		NamespaceInfo $namespaceInfo,
		TitleFactory $titleFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->namespaceInfo = $namespaceInfo;
		$this->titleFactory = $titleFactory;

		// TODO convert ExtensionRegistry to a service and inject it
		$this->extensionRegistry = ExtensionRegistry::getInstance();
	}

	/**
	 * Read the global and extract title objects from the corresponding messages
	 *
	 * TODO consider renaming this method, since this class is retrieved from
	 * MediaWikiServices, resulting in calls like:
	 * MediaWikiServices::getInstance()->getTrackingCategories()->getTrackingCategories()
	 *
	 * @return array[] [ 'msg' => Title, 'cats' => Title[] ]
	 * @phan-return array<string,array{msg:Title,cats:Title[]}>
	 */
	public function getTrackingCategories() {
		$categories = array_merge(
			self::CORE_TRACKING_CATEGORIES,
			$this->extensionRegistry->getAttribute( 'TrackingCategories' ),
			$this->options->get( 'TrackingCategories' ) // deprecated
		);

		// Only show magic link tracking categories if they are enabled
		$enableMagicLinks = $this->options->get( 'EnableMagicLinks' );
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
			 *
			 * TODO replace uses of wfMessage with an injected service once that is available
			 */
			$msgObj = wfMessage( $catMsg )->inContentLanguage();
			$allCats = [];
			$catMsgTitle = $this->titleFactory->makeTitleSafe( NS_MEDIAWIKI, $catMsg );
			if ( !$catMsgTitle ) {
				continue;
			}

			// Match things like {{NAMESPACE}} and {{NAMESPACENUMBER}}.
			// False positives are ok, this is just an efficiency shortcut
			if ( strpos( $msgObj->plain(), '{{' ) !== false ) {
				$ns = $this->namespaceInfo->getValidNamespaces();
				foreach ( $ns as $namesp ) {
					$tempTitle = $this->titleFactory->makeTitleSafe( $namesp, $catMsg );
					if ( !$tempTitle ) {
						continue;
					}
					$catName = $msgObj->page( $tempTitle )->text();
					# Allow tracking categories to be disabled by setting them to "-"
					if ( $catName !== '-' ) {
						$catTitle = $this->titleFactory->makeTitleSafe( NS_CATEGORY, $catName );
						if ( $catTitle ) {
							$allCats[] = $catTitle;
						}
					}
				}
			} else {
				$catName = $msgObj->text();
				# Allow tracking categories to be disabled by setting them to "-"
				if ( $catName !== '-' ) {
					$catTitle = $this->titleFactory->makeTitleSafe( NS_CATEGORY, $catName );
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
