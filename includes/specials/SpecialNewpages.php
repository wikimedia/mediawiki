<?php
/**
 * Implements Special:Newpages
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
 * A special page that list newly created pages
 *
 * @ingroup SpecialPage
 */
class SpecialNewpages extends IncludableSpecialPage {
	/**
	 * @var FormOptions
	 */
	protected $opts;
	protected $customFilters;

	protected $showNavigation = false;

	public function __construct() {
		parent::__construct( 'Newpages' );
	}

	protected function setup( $par ) {
		// Options
		$opts = new FormOptions();
		$this->opts = $opts; // bind
		$opts->add( 'hideliu', false );
		$opts->add( 'hidepatrolled', $this->getUser()->getBoolOption( 'newpageshidepatrolled' ) );
		$opts->add( 'hidebots', false );
		$opts->add( 'hideredirs', true );
		$opts->add( 'limit', $this->getUser()->getIntOption( 'rclimit' ) );
		$opts->add( 'offset', '' );
		$opts->add( 'namespace', '0' );
		$opts->add( 'username', '' );
		$opts->add( 'feed', '' );
		$opts->add( 'tagfilter', '' );
		$opts->add( 'invert', false );
		$opts->add( 'size-mode', 'max' );
		$opts->add( 'size', 0 );

		$this->customFilters = [];
		Hooks::run( 'SpecialNewPagesFilters', [ $this, &$this->customFilters ] );
		foreach ( $this->customFilters as $key => $params ) {
			$opts->add( $key, $params['default'] );
		}

		// Set values
		$opts->fetchValuesFromRequest( $this->getRequest() );
		if ( $par ) {
			$this->parseParams( $par );
		}

		// Validate
		$opts->validateIntBounds( 'limit', 0, 5000 );
	}

	protected function parseParams( $par ) {
		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			if ( 'shownav' == $bit ) {
				$this->showNavigation = true;
			}
			if ( 'hideliu' === $bit ) {
				$this->opts->setValue( 'hideliu', true );
			}
			if ( 'hidepatrolled' == $bit ) {
				$this->opts->setValue( 'hidepatrolled', true );
			}
			if ( 'hidebots' == $bit ) {
				$this->opts->setValue( 'hidebots', true );
			}
			if ( 'showredirs' == $bit ) {
				$this->opts->setValue( 'hideredirs', false );
			}
			if ( is_numeric( $bit ) ) {
				$this->opts->setValue( 'limit', intval( $bit ) );
			}

			$m = [];
			if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) ) {
				$this->opts->setValue( 'limit', intval( $m[1] ) );
			}
			// PG offsets not just digits!
			if ( preg_match( '/^offset=([^=]+)$/', $bit, $m ) ) {
				$this->opts->setValue( 'offset', intval( $m[1] ) );
			}
			if ( preg_match( '/^username=(.*)$/', $bit, $m ) ) {
				$this->opts->setValue( 'username', $m[1] );
			}
			if ( preg_match( '/^namespace=(.*)$/', $bit, $m ) ) {
				$ns = $this->getLanguage()->getNsIndex( $m[1] );
				if ( $ns !== false ) {
					$this->opts->setValue( 'namespace', $ns );
				}
			}
		}
	}

	/**
	 * Show a form for filtering namespace and username
	 *
	 * @param string $par
	 */
	public function execute( $par ) {
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();

		$this->showNavigation = !$this->including(); // Maybe changed in setup
		$this->setup( $par );

		$this->addHelpLink( 'Help:New pages' );

		if ( !$this->including() ) {
			// Settings
			$this->form();

			$feedType = $this->opts->getValue( 'feed' );
			if ( $feedType ) {
				$this->feed( $feedType );

				return;
			}

			$allValues = $this->opts->getAllValues();
			unset( $allValues['feed'] );
			$out->setFeedAppendQuery( wfArrayToCgi( $allValues ) );
		}

		$pager = new NewPagesPager( $this, $this->opts );
		$pager->mLimit = $this->opts->getValue( 'limit' );
		$pager->mOffset = $this->opts->getValue( 'offset' );

		if ( $pager->getNumRows() ) {
			$navigation = '';
			if ( $this->showNavigation ) {
				$navigation = $pager->getNavigationBar();
			}
			$out->addHTML( $navigation . $pager->getBody() . $navigation );
		} else {
			$out->addWikiMsg( 'specialpage-empty' );
		}
	}

	protected function filterLinks() {
		// show/hide links
		$showhide = [ $this->msg( 'show' )->escaped(), $this->msg( 'hide' )->escaped() ];

		// Option value -> message mapping
		$filters = [
			'hideliu' => 'rcshowhideliu',
			'hidepatrolled' => 'rcshowhidepatr',
			'hidebots' => 'rcshowhidebots',
			'hideredirs' => 'whatlinkshere-hideredirs'
		];
		foreach ( $this->customFilters as $key => $params ) {
			$filters[$key] = $params['msg'];
		}

		// Disable some if needed
		if ( !User::groupHasPermission( '*', 'createpage' ) ) {
			unset( $filters['hideliu'] );
		}
		if ( !$this->getUser()->useNPPatrol() ) {
			unset( $filters['hidepatrolled'] );
		}

		$links = [];
		$changed = $this->opts->getChangedValues();
		unset( $changed['offset'] ); // Reset offset if query type changes

		// wfArrayToCgi(), called from LinkRenderer/Title, will not output null and false values
		// to the URL, which would omit some options (T158504). Fix it by explicitly setting them
		// to 0 or 1.
		$changed = array_map( function ( $value ) {
			return $value ? '1' : '0';
		}, $changed );

		$self = $this->getPageTitle();
		$linkRenderer = $this->getLinkRenderer();
		foreach ( $filters as $key => $msg ) {
			$onoff = 1 - $this->opts->getValue( $key );
			$link = $linkRenderer->makeLink(
				$self,
				new HtmlArmor( $showhide[$onoff] ),
				[],
				[ $key => $onoff ] + $changed
			);
			$links[$key] = $this->msg( $msg )->rawParams( $link )->escaped();
		}

		return $this->getLanguage()->pipeList( $links );
	}

	protected function form() {
		$out = $this->getOutput();

		// Consume values
		$this->opts->consumeValue( 'offset' ); // don't carry offset, DWIW
		$namespace = $this->opts->consumeValue( 'namespace' );
		$username = $this->opts->consumeValue( 'username' );
		$tagFilterVal = $this->opts->consumeValue( 'tagfilter' );
		$nsinvert = $this->opts->consumeValue( 'invert' );

		$size = $this->opts->consumeValue( 'size' );
		$max = $this->opts->consumeValue( 'size-mode' ) === 'max';

		// Check username input validity
		$ut = Title::makeTitleSafe( NS_USER, $username );
		$userText = $ut ? $ut->getText() : '';

		$formDescriptor = [
			'namespace' => [
				'type' => 'namespaceselect',
				'name' => 'namespace',
				'label-message' => 'namespace',
				'default' => $namespace,
			],
			'nsinvert' => [
				'type' => 'check',
				'name' => 'invert',
				'label-message' => 'invert',
				'default' => $nsinvert,
				'tooltip' => 'invert',
			],
			'tagFilter' => [
				'type' => 'tagfilter',
				'name' => 'tagfilter',
				'label-raw' => $this->msg( 'tag-filter' )->parse(),
				'default' => $tagFilterVal,
			],
			'username' => [
				'type' => 'user',
				'name' => 'username',
				'label-message' => 'newpages-username',
				'default' => $userText,
				'id' => 'mw-np-username',
				'size' => 30,
			],
			'size' => [
				'type' => 'sizefilter',
				'name' => 'size',
				'default' => -$max * $size,
			],
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );

		// Store query values in hidden fields so that form submission doesn't lose them
		foreach ( $this->opts->getUnconsumedValues() as $key => $value ) {
			$htmlForm->addHiddenField( $key, $value );
		}

		$htmlForm
			->setMethod( 'get' )
			->setFormIdentifier( 'newpagesform' )
			// The form should be visible on each request (inclusive requests with submitted forms), so
			// return always false here.
			->setSubmitCallback(
				function () {
					return false;
				}
			)
			->setSubmitText( $this->msg( 'newpages-submit' )->text() )
			->setWrapperLegend( $this->msg( 'newpages' )->text() )
			->addFooterText( Html::rawElement(
				'div',
				null,
				$this->filterLinks()
			) )
			->show();
		$out->addModuleStyles( 'mediawiki.special' );
	}

	/**
	 * @param stdClass $result Result row from recent changes
	 * @param Title $title
	 * @return bool|Revision
	 */
	protected function revisionFromRcResult( stdClass $result, Title $title ) {
		return new Revision( [
			'comment' => CommentStore::getStore()->getComment( 'rc_comment', $result )->text,
			'deleted' => $result->rc_deleted,
			'user_text' => $result->rc_user_text,
			'user' => $result->rc_user,
			'actor' => $result->rc_actor,
		], 0, $title );
	}

	/**
	 * Format a row, providing the timestamp, links to the page/history,
	 * size, user links, and a comment
	 *
	 * @param object $result Result row
	 * @return string
	 */
	public function formatRow( $result ) {
		$title = Title::newFromRow( $result );

		// Revision deletion works on revisions,
		// so cast our recent change row to a revision row.
		$rev = $this->revisionFromRcResult( $result, $title );

		$classes = [];
		$attribs = [ 'data-mw-revid' => $result->rev_id ];

		$lang = $this->getLanguage();
		$dm = $lang->getDirMark();

		$spanTime = Html::element( 'span', [ 'class' => 'mw-newpages-time' ],
			$lang->userTimeAndDate( $result->rc_timestamp, $this->getUser() )
		);
		$linkRenderer = $this->getLinkRenderer();
		$time = $linkRenderer->makeKnownLink(
			$title,
			new HtmlArmor( $spanTime ),
			[],
			[ 'oldid' => $result->rc_this_oldid ]
		);

		$query = $title->isRedirect() ? [ 'redirect' => 'no' ] : [];

		$plink = $linkRenderer->makeKnownLink(
			$title,
			null,
			[ 'class' => 'mw-newpages-pagename' ],
			$query
		);
		$histLink = $linkRenderer->makeKnownLink(
			$title,
			$this->msg( 'hist' )->text(),
			[],
			[ 'action' => 'history' ]
		);
		$hist = Html::rawElement( 'span', [ 'class' => 'mw-newpages-history' ],
			$this->msg( 'parentheses' )->rawParams( $histLink )->escaped() );

		$length = Html::rawElement(
			'span',
			[ 'class' => 'mw-newpages-length' ],
			$this->msg( 'brackets' )->rawParams(
				$this->msg( 'nbytes' )->numParams( $result->length )->escaped()
			)->escaped()
		);

		$ulink = Linker::revUserTools( $rev );
		$comment = Linker::revComment( $rev );

		if ( $this->patrollable( $result ) ) {
			$classes[] = 'not-patrolled';
		}

		# Add a class for zero byte pages
		if ( $result->length == 0 ) {
			$classes[] = 'mw-newpages-zero-byte-page';
		}

		# Tags, if any.
		if ( isset( $result->ts_tags ) ) {
			list( $tagDisplay, $newClasses ) = ChangeTags::formatSummaryRow(
				$result->ts_tags,
				'newpages',
				$this->getContext()
			);
			$classes = array_merge( $classes, $newClasses );
		} else {
			$tagDisplay = '';
		}

		# Display the old title if the namespace/title has been changed
		$oldTitleText = '';
		$oldTitle = Title::makeTitle( $result->rc_namespace, $result->rc_title );

		if ( !$title->equals( $oldTitle ) ) {
			$oldTitleText = $oldTitle->getPrefixedText();
			$oldTitleText = Html::rawElement(
				'span',
				[ 'class' => 'mw-newpages-oldtitle' ],
				$this->msg( 'rc-old-title' )->params( $oldTitleText )->escaped()
			);
		}

		$ret = "{$time} {$dm}{$plink} {$hist} {$dm}{$length} {$dm}{$ulink} {$comment} "
			. "{$tagDisplay} {$oldTitleText}";

		// Let extensions add data
		Hooks::run( 'NewPagesLineEnding', [ $this, &$ret, $result, &$classes, &$attribs ] );
		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);

		if ( count( $classes ) ) {
			$attribs['class'] = implode( ' ', $classes );
		}

		return Html::rawElement( 'li', $attribs, $ret ) . "\n";
	}

	/**
	 * Should a specific result row provide "patrollable" links?
	 *
	 * @param object $result Result row
	 * @return bool
	 */
	protected function patrollable( $result ) {
		return ( $this->getUser()->useNPPatrol() && !$result->rc_patrolled );
	}

	/**
	 * Output a subscription feed listing recent edits to this page.
	 *
	 * @param string $type
	 */
	protected function feed( $type ) {
		if ( !$this->getConfig()->get( 'Feed' ) ) {
			$this->getOutput()->addWikiMsg( 'feed-unavailable' );

			return;
		}

		$feedClasses = $this->getConfig()->get( 'FeedClasses' );
		if ( !isset( $feedClasses[$type] ) ) {
			$this->getOutput()->addWikiMsg( 'feed-invalid' );

			return;
		}

		$feed = new $feedClasses[$type](
			$this->feedTitle(),
			$this->msg( 'tagline' )->text(),
			$this->getPageTitle()->getFullURL()
		);

		$pager = new NewPagesPager( $this, $this->opts );
		$limit = $this->opts->getValue( 'limit' );
		$pager->mLimit = min( $limit, $this->getConfig()->get( 'FeedLimit' ) );

		$feed->outHeader();
		if ( $pager->getNumRows() > 0 ) {
			foreach ( $pager->mResult as $row ) {
				$feed->outItem( $this->feedItem( $row ) );
			}
		}
		$feed->outFooter();
	}

	protected function feedTitle() {
		$desc = $this->getDescription();
		$code = $this->getConfig()->get( 'LanguageCode' );
		$sitename = $this->getConfig()->get( 'Sitename' );

		return "$sitename - $desc [$code]";
	}

	protected function feedItem( $row ) {
		$title = Title::makeTitle( intval( $row->rc_namespace ), $row->rc_title );
		if ( $title ) {
			$date = $row->rc_timestamp;
			$comments = $title->getTalkPage()->getFullURL();

			return new FeedItem(
				$title->getPrefixedText(),
				$this->feedItemDesc( $row ),
				$title->getFullURL(),
				$date,
				$this->feedItemAuthor( $row ),
				$comments
			);
		} else {
			return null;
		}
	}

	protected function feedItemAuthor( $row ) {
		return $row->rc_user_text ?? '';
	}

	protected function feedItemDesc( $row ) {
		$revision = Revision::newFromId( $row->rev_id );
		if ( !$revision ) {
			return '';
		}

		$content = $revision->getContent();
		if ( $content === null ) {
			return '';
		}

		// XXX: include content model/type in feed item?
		return '<p>' . htmlspecialchars( $revision->getUserText() ) .
			$this->msg( 'colon-separator' )->inContentLanguage()->escaped() .
			htmlspecialchars( FeedItem::stripComment( $revision->getComment() ) ) .
			"</p>\n<hr />\n<div>" .
			nl2br( htmlspecialchars( $content->serialize() ) ) . "</div>";
	}

	protected function getGroupName() {
		return 'changes';
	}

	protected function getCacheTTL() {
		return 60 * 5;
	}
}
