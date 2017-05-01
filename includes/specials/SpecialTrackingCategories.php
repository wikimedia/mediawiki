<?php
/**
 * Implements Special:TrackingCategories
 *
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
 * @ingroup SpecialPage
 */

/**
 * A special page that displays list of tracking categories
 * Tracking categories allow pages with certain characteristics to be tracked.
 * It works by adding any such page to a category automatically.
 * Category is specified by the tracking category's system message.
 *
 * @ingroup SpecialPage
 * @since 1.23
 */

class SpecialTrackingCategories extends SpecialPage {
	function __construct() {
		parent::__construct( 'TrackingCategories' );
	}

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
	];

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->allowClickjacking();
		$this->getOutput()->addHTML(
			Html::openElement( 'table', [ 'class' => 'mw-datatable',
				'id' => 'mw-trackingcategories-table' ] ) . "\n" .
			"<thead><tr>
			<th>" .
				$this->msg( 'trackingcategories-msg' )->escaped() . "
			</th>
			<th>" .
				$this->msg( 'trackingcategories-name' )->escaped() .
			"</th>
			<th>" .
				$this->msg( 'trackingcategories-desc' )->escaped() . "
			</th>
			</tr></thead>"
		);

		$trackingCategories = $this->prepareTrackingCategoriesData();

		$batch = new LinkBatch();
		foreach ( $trackingCategories as $catMsg => $data ) {
			$batch->addObj( $data['msg'] );
			foreach ( $data['cats'] as $catTitle ) {
				$batch->addObj( $catTitle );
			}
		}
		$batch->execute();

		foreach ( $trackingCategories as $catMsg => $data ) {
			$allMsgs = [];
			$catDesc = $catMsg . '-desc';

			$catMsgTitleText = Linker::link(
				$data['msg'],
				htmlspecialchars( $catMsg )
			);

			foreach ( $data['cats'] as $catTitle ) {
				$catTitleText = Linker::link(
					$catTitle,
					htmlspecialchars( $catTitle->getText() )
				);
				$allMsgs[] = $catTitleText;
			}

			# Extra message, when no category was found
			if ( !count( $allMsgs ) ) {
				$allMsgs[] = $this->msg( 'trackingcategories-disabled' )->parse();
			}

			/*
			 * Show category description if it exists as a system message
			 * as category-name-desc
			 */
			$descMsg = $this->msg( $catDesc );
			if ( $descMsg->isBlank() ) {
				$descMsg = $this->msg( 'trackingcategories-nodesc' );
			}

			$this->getOutput()->addHTML(
				Html::openElement( 'tr' ) .
				Html::openElement( 'td', [ 'class' => 'mw-trackingcategories-name' ] ) .
					$this->getLanguage()->commaList( array_unique( $allMsgs ) ) .
				Html::closeElement( 'td' ) .
				Html::openElement( 'td', [ 'class' => 'mw-trackingcategories-msg' ] ) .
					$catMsgTitleText .
				Html::closeElement( 'td' ) .
				Html::openElement( 'td', [ 'class' => 'mw-trackingcategories-desc' ] ) .
					$descMsg->parse() .
				Html::closeElement( 'td' ) .
				Html::closeElement( 'tr' )
			);
		}
		$this->getOutput()->addHTML( Html::closeElement( 'table' ) );
	}

	/**
	 * Read the global and extract title objects from the corresponding messages
	 * @return array Array( 'msg' => Title, 'cats' => Title[] )
	 */
	private function prepareTrackingCategoriesData() {
		$categories = array_merge(
			self::$coreTrackingCategories,
			ExtensionRegistry::getInstance()->getAttribute( 'TrackingCategories' ),
			$this->getConfig()->get( 'TrackingCategories' ) // deprecated
		);

		// Only show magic link tracking categories if they are enabled
		$enableMagicLinks = $this->getConfig()->get( 'EnableMagicLinks' );
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
			$msgObj = $this->msg( $catMsg )->inContentLanguage();
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

	protected function getGroupName() {
		return 'pages';
	}
}
