<?php
/**
 * Implements Special:Newmoves
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
 * A special page that list newly moved pages
 *
 * @ingroup SpecialPage
 */
class SpecialNewMoves extends IncludableSpecialPage {
	/**
	 * @var FormOptions
	 */
	protected $opts;

	protected $showNavigation = false;

	public function __construct() {
		parent::__construct( 'Newmoves' );
	}

	protected function setup( $par ) {
		// Options
		$opts = new FormOptions();
		$this->opts = $opts; // bind
		$opts->add( 'hideliu', false );
		$opts->add( 'hidepatrolled', $this->getUser()->getBoolOption( 'newpageshidepatrolled' ) );
		$opts->add( 'hidebots', false );
		$opts->add( 'limit', $this->getUser()->getIntOption( 'rclimit' ) );
		$opts->add( 'offset', '' );
		$opts->add( 'namespace', '0' );
		$opts->add( 'username', '' );
		$opts->add( 'tagfilter', '' );
		$opts->add( 'invert', false );

		$this->customFilters = [];
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

			$allValues = $this->opts->getAllValues();
			$out->setFeedAppendQuery( wfArrayToCgi( $allValues ) );
		}

		$pager = new NewMovesPager( $this, $this->opts );
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
		];
		foreach ( $this->customFilters as $key => $params ) {
			$filters[$key] = $params['msg'];
		}

		// Disable some if needed
		if ( !User::groupHasPermission( '*', 'move' ) ) {
			unset( $filters['hideliu'] );
		}
		if ( !$this->getUser()->useMovePatrol() ) {
			unset( $filters['hidepatrolled'] );
		}

		$links = [];
		$changed = $this->opts->getChangedValues();
		unset( $changed['offset'] ); // Reset offset if query type changes

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
		$out->addModules( 'mediawiki.userSuggest' );

		// Consume values
		$this->opts->consumeValue( 'offset' ); // don't carry offset, DWIW
		$namespace = $this->opts->consumeValue( 'namespace' );
		$username = $this->opts->consumeValue( 'username' );
		$tagFilterVal = $this->opts->consumeValue( 'tagfilter' );
		$nsinvert = $this->opts->consumeValue( 'invert' );

		// Check username input validity
		$ut = Title::makeTitleSafe( NS_USER, $username );
		$userText = $ut ? $ut->getText() : '';

		// Store query values in hidden fields so that form submission doesn't lose them
		$hidden = [];
		foreach ( $this->opts->getUnconsumedValues() as $key => $value ) {
			$hidden[] = Html::hidden( $key, $value );
		}
		$hidden = implode( "\n", $hidden );

		$form = [
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
				'type' => 'text',
				'name' => 'username',
				'label-message' => 'newpages-username',
				'default' => $userText,
				'id' => 'mw-np-username',
				'size' => 30,
				'cssclass' => 'mw-autocomplete-user', // used by mediawiki.userSuggest
			],
		];

		$htmlForm = new HTMLForm( $form, $this->getContext() );

		$htmlForm->setSubmitText( $this->msg( 'newpages-submit' )->text() );
		$htmlForm->setSubmitProgressive();
		// The form should be visible on each request (inclusive requests with submitted forms), so
		// return always false here.
		$htmlForm->setSubmitCallback(
			function () {
				return false;
			}
		);
		$htmlForm->setMethod( 'get' );

		$out->addHTML( Xml::fieldset( $this->msg( 'newmoves' )->text() ) );

		$htmlForm->show();

		$out->addHTML(
			Html::rawElement(
				'div',
				null,
				$this->filterLinks()
			) .
			Xml::closeElement( 'fieldset' )
		);
	}

	/**
	 * Format a row, providing the timestamp, links to the page/history,
	 * user links, and a comment
	 *
	 * @param object $result Result row
	 * @return string
	 */
	public function formatRow( $result ) {
		$logEntry = DatabaseLogEntry::newFromRow( $result );
		$formatter = LogFormatter::newFromEntry( $logEntry );
		$formatter->setContext( $this->getContext() );
		$formatter->setShowUserToolLinks( true );

		$time = htmlspecialchars( $this->getLanguage()->userTimeAndDate(
			$logEntry->getTimestamp(), $this->getUser() ) );

		$action = $formatter->getActionText();

		$comment = $formatter->getComment();

		$classes = [];

		if ( $this->patrollable( $result ) ) {
			$classes[] = 'not-patrolled';
		}

		# Tags, if any.
		if ( isset( $result->ts_tags ) ) {
			list( $tagDisplay, $newClasses ) = ChangeTags::formatSummaryRow(
				$result->ts_tags,
				'newmoves',
				$this->getContext()
			);
			$classes = array_merge( $classes, $newClasses );
		} else {
			$tagDisplay = '';
		}

		$revert = $formatter->getActionLinks();

		return Html::rawElement( 'li', [ 'class' => $classes ],
			"$time $action $comment $revert $tagDisplay" ) . "\n";
	}

	/**
	 * Should a specific result row provide "patrollable" links?
	 *
	 * @param object $result Result row
	 * @return bool
	 */
	protected function patrollable( $result ) {
		return ( $this->getUser()->useMovePatrol() && !$result->rc_patrolled );
	}

	protected function getGroupName() {
		return 'changes';
	}

	protected function getCacheTTL() {
		return 60 * 5;
	}
}
