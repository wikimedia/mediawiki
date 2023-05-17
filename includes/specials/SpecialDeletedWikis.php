<?php

namespace Miraheze\ManageWiki\Specials;

use Miraheze\ManageWiki\Helpers\ManageWikiDeletedWikiPager;
use SpecialPage;

class SpecialDeletedWikis extends SpecialPage {
	public function __construct() {
		parent::__construct( 'DeletedWikis' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$pager = new ManageWikiDeletedWikiPager( $this );

		$this->getOutput()->addParserOutputContent( $pager->getFullOutput() );
	}
}
