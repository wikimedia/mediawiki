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

namespace MediaWiki\Category;

use ExtensionRegistry;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReference;
use MediaWiki\Title\Title;
use NamespaceInfo;
use ParserOutput;
use Psr\Log\LoggerInterface;
use TitleParser;

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
		MainConfigNames::TrackingCategories,
		MainConfigNames::EnableMagicLinks,
	];

	/** @var ServiceOptions */
	private $options;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var TitleParser */
	private $titleParser;

	/** @var ExtensionRegistry */
	private $extensionRegistry;

	/** @var LoggerInterface */
	private $logger;

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
		# template-equals-category is unused in MW>=1.39, but the category
		# can be left around for a major release or so for an easier
		# transition for anyone who didn't do the cleanup. T91154
		'template-equals-category',
		'template-loop-category',
		'unstrip-depth-category',
		'unstrip-size-category',
	];

	/**
	 * @param ServiceOptions $options
	 * @param NamespaceInfo $namespaceInfo
	 * @param TitleParser $titleParser
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		ServiceOptions $options,
		NamespaceInfo $namespaceInfo,
		TitleParser $titleParser,
		LoggerInterface $logger
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->namespaceInfo = $namespaceInfo;
		$this->titleParser = $titleParser;
		$this->logger = $logger;

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
	 * @return array[] [ 'msg' => LinkTarget, 'cats' => LinkTarget[] ]
	 * @phan-return array<string,array{msg:LinkTarget,cats:LinkTarget[]}>
	 */
	public function getTrackingCategories() {
		$categories = array_merge(
			self::CORE_TRACKING_CATEGORIES,
			$this->extensionRegistry->getAttribute( MainConfigNames::TrackingCategories ),
			$this->options->get( MainConfigNames::TrackingCategories ) // deprecated
		);

		// Only show magic link tracking categories if they are enabled
		$enableMagicLinks = $this->options->get( MainConfigNames::EnableMagicLinks );
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
			$catMsgTitle = $this->titleParser->makeTitleValueSafe( NS_MEDIAWIKI, $catMsg );
			if ( !$catMsgTitle ) {
				continue;
			}

			// Match things like {{NAMESPACE}} and {{NAMESPACENUMBER}}.
			// False positives are ok, this is just an efficiency shortcut
			if ( strpos( $msgObj->plain(), '{{' ) !== false ) {
				$ns = $this->namespaceInfo->getValidNamespaces();
				foreach ( $ns as $namesp ) {
					$tempTitle = $this->titleParser->makeTitleValueSafe( $namesp, $catMsg );
					if ( !$tempTitle ) {
						continue;
					}
					// XXX: should be a better way to convert a TitleValue
					// to a PageReference!
					$tempTitle = Title::newFromLinkTarget( $tempTitle );
					$catName = $msgObj->page( $tempTitle )->text();
					# Allow tracking categories to be disabled by setting them to "-"
					if ( $catName !== '-' ) {
						$catTitle = $this->titleParser->makeTitleValueSafe( NS_CATEGORY, $catName );
						if ( $catTitle ) {
							$allCats[] = $catTitle;
						}
					}
				}
			} else {
				$catName = $msgObj->text();
				# Allow tracking categories to be disabled by setting them to "-"
				if ( $catName !== '-' ) {
					$catTitle = $this->titleParser->makeTitleValueSafe( NS_CATEGORY, $catName );
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

	/**
	 * Resolve a tracking category.
	 * @param string $msg Message key
	 * @param ?PageReference $contextPage Context page title
	 * @return ?LinkTarget the proper category page, or null if
	 *   the tracking category is disabled or unsafe
	 * @since 1.38
	 */
	public function resolveTrackingCategory( string $msg, ?PageReference $contextPage ): ?LinkTarget {
		if ( !$contextPage ) {
			$this->logger->debug( "Not adding tracking category $msg to missing page!" );
			return null;
		}

		if ( $contextPage->getNamespace() === NS_SPECIAL ) {
			$this->logger->debug( "Not adding tracking category $msg to special page!" );
			return null;
		}

		// Important to parse with correct title (T33469)
		// TODO replace uses of wfMessage with an injected service once that is available
		$cat = wfMessage( $msg )
			->page( $contextPage )
			->inContentLanguage()
			->text();

		# Allow tracking categories to be disabled by setting them to "-"
		if ( $cat === '-' ) {
			return null;
		}

		$containerCategory = $this->titleParser->makeTitleValueSafe( NS_CATEGORY, $cat );
		if ( $containerCategory === null ) {
			$this->logger->debug( "[[MediaWiki:$msg]] is not a valid title!" );
			return null;
		}
		return $containerCategory;
	}

	/**
	 * Add a tracking category to a ParserOutput.
	 * @param ParserOutput $parserOutput
	 * @param string $msg Message key
	 * @param ?PageReference $contextPage Context page title
	 * @return bool Whether the addition was successful
	 * @since 1.38
	 */
	public function addTrackingCategory( ParserOutput $parserOutput, string $msg, ?PageReference $contextPage ): bool {
		$categoryPage = $this->resolveTrackingCategory( $msg, $contextPage );
		if ( $categoryPage === null ) {
			return false;
		}
		$parserOutput->addCategory(
			$categoryPage->getDBkey(),
			$parserOutput->getPageProperty( 'defaultsort' ) ?? ''
		);
		return true;
	}
}

class_alias( TrackingCategories::class, 'TrackingCategories' );
