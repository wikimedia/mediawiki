<?php
/**
 * Special page to show global file usage. Also contains hook functions for
 * showing usage on an image page.
 */

class SpecialGlobalFileUsage extends SpecialPage {
	public function __construct() {
		parent::__construct( 'GlobalFileUsage' );
	}

	/**
	 * Entry point
	 */
	public function execute( $par ) {
		$request = $this->getRequest();
		$target = $par ? $par : $request->getVal( 'target' );
		$this->target = Title::makeTitleSafe( NS_FILE, $target );

		$this->filterLocal = $request->getCheck( 'filterlocal' );

		$this->setHeaders();

		$this->showForm();

		if ( !is_null( $this->target ) ) {
			$this->getOutput()->setPageTitle( wfMsg( 'globalfileusage-for', $this->target->getPrefixedText() ) );
			$this->showResult();
		}
	}

	/**
	 * Shows the search form
	 */
	private function showForm() {
		global $wgScript, $wgContLang;

		/* Build form */
		$html = Xml::openElement( 'form', array( 'action' => $wgScript ) ) . "\n";
		// Name of SpecialPage
		$html .= Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) . "\n";
		// Limit
		$html .= Html::hidden( 'limit', $this->getRequest()->getInt( 'limit', 50 ) );
		// Input box with target prefilled if available
		$formContent = "\t" . Xml::input( 'target', 40, is_null( $this->target ) ? ''
					: $this->target->getText() )
		// Submit button
			. "\n\t" . Xml::element( 'input', array(
					'type' => 'submit',
					'value' => wfMsg( 'globalfileusage-ok' )
					) )
		// Filter local checkbox
			. "\n\t<p>" . Xml::checkLabel( wfMsg( 'globalfileusage-filterlocal' ),
					'filterlocal', 'mw-filterlocal', $this->filterLocal ) . '</p>';

		if ( !is_null( $this->target ) ) {
			$file = wfFindFile( $this->target );
			if ( $file !== null ) {
				// Show the image if it exists
				$html .= Linker::makeImageLink2( $this->target, $file,
					array( 'align' => $this->getLang()->alignEnd(), 'thumbnail' => true ) );
			}
		}

		// Wrap the entire form in a nice fieldset
		$html .= Xml::fieldSet( wfMsg( 'globalfileusage-text' ), $formContent ) . "\n</form>";

		$this->getOutput()->addHtml( $html );
	}

	/**
	 * Creates as queryer and executes it based on the WebRequest object
	 */
	private function showResult() {
		$request = $this->getRequest();
		$query = new GlobalUsageQuery( $this->target );

		// Extract params from the WebRequest object
		if ( $request->getText( 'from' ) ) {
			$query->setOffset( $request->getText( 'from' ) );
		} elseif ( $request->getText( 'to' ) ) {
			$query->setOffset( $request->getText( 'to' ), true );
		}
		$query->setLimit( $request->getInt( 'limit', 50 ) );
		$query->filterLocal( $this->filterLocal );

		// Perform query
		$query->searchFile();

		// Show result
		$out = $this->getOutput();

		// Don't show form element if there is no data
		if ( $query->count() == 0 ) {
			$out->addWikiMsg( 'globalfileusage-no-results', $this->target->getPrefixedText() );
			return;
		}

		$offset = $query->getOffsetString();
		$navbar = $this->getNavBar( $query );
		$targetName = $this->target->getText();

		// Top navbar
		$out->addHtml( $navbar );

		$out->addHtml( '<div id="mw-globalfileusage-result">' );
		foreach ( $query->getSingleResult() as $wiki => $result ) {
			$out->addHtml(
					'<h2>' . wfMsgExt(
						'globalfileusage-on-wiki', 'parseinline',
						$targetName, WikiMap::getWikiName( $wiki ) )
					. "</h2><ul>\n" );
			foreach ( $result as $item ) {
				$out->addHtml( "\t<li>" . self::formatItem( $item ) . "</li>\n" );
			}
			$out->addHtml( "</ul>\n" );
		}
		$out->addHtml( '</div>' );

		// Bottom navbar
		$out->addHtml( $navbar );
	}

	/**
	 * Helper to format a specific item
	 */
	public static function formatItem( $item ) {
		if ( !$item['namespace'] ) {
			$page = $item['title'];
		} else {
			$page = "{$item['namespace']}:{$item['title']}";
		}

		$link = WikiMap::makeForeignLink( $item['wiki'], $page,
				str_replace( '_', ' ', $page ) );
		// Return only the title if no link can be constructed
		return $link === false ? $page : $link;
	}

	/**
	 * Helper function to create the navbar, stolen from wfViewPrevNext
	 *
	 * @param $query GlobalUsageQuery An executed GlobalUsageQuery object
	 * @return string Navbar HTML
	 */
	protected function getNavBar( $query ) {
		$lang = $this->getLang();
		$target = $this->target->getText();
		$limit = $query->getLimit();
		$fmtLimit = $lang->formatNum( $limit );

		# Find out which strings are for the prev and which for the next links
		$offset = $query->getOffsetString();
		$continue = $query->getContinueFileString();
		if ( $query->isReversed() ) {
			$from = $offset;
			$to = $continue;
		} else {
			$from = $continue;
			$to = $offset;
		}

		# Get prev/next link display text
		$prev =  wfMsgExt( 'prevn', array( 'parsemag', 'escape' ), $fmtLimit );
		$next =  wfMsgExt( 'nextn', array( 'parsemag', 'escape' ), $fmtLimit );
		# Get prev/next link title text
		$pTitle = wfMsgExt( 'prevn-title', array( 'parsemag', 'escape' ), $fmtLimit );
		$nTitle = wfMsgExt( 'nextn-title', array( 'parsemag', 'escape' ), $fmtLimit );

		# Fetch the title object
		$title = $this->getTitle();

		# Make 'previous' link
		if ( $to ) {
			$attr = array( 'title' => $pTitle, 'class' => 'mw-prevlink' );
			$q = array( 'limit' => $limit, 'to' => $to, 'target' => $target );
			if ( $this->filterLocal )
				$q['filterlocal'] = '1';
			$plink = Linker::link( $title, $prev, $attr, $q );
		} else {
			$plink = $prev;
		}

		# Make 'next' link
		if ( $from ) {
			$attr = array( 'title' => $nTitle, 'class' => 'mw-nextlink' );
			$q = array( 'limit' => $limit, 'from' => $from, 'target' => $target );
			if ( $this->filterLocal )
				$q['filterlocal'] = '1';
			$nlink = Linker::link( $title, $next, $attr, $q );
		} else {
			$nlink = $next;
		}

		# Make links to set number of items per page
		$numLinks = array();
		foreach ( array( 20, 50, 100, 250, 500 ) as $num ) {
			$fmtLimit = $lang->formatNum( $num );

			$q = array( 'offset' => $offset, 'limit' => $num, 'target' => $target );
			if ( $this->filterLocal )
				$q['filterlocal'] = '1';
			$lTitle = wfMsgExt( 'shown-title', array( 'parsemag', 'escape' ), $num );
			$attr = array( 'title' => $lTitle, 'class' => 'mw-numlink' );

			$numLinks[] = Linker::link( $title, $fmtLimit, $attr, $q );
		}
		$nums = $lang->pipeList( $numLinks );

		return wfMsgHtml( 'viewprevnext', $plink, $nlink, $nums );
	}
}

