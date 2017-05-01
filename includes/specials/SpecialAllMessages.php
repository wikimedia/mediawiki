<?php
/**
 * Implements Special:Allmessages
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
 * Use this special page to get a list of the MediaWiki system messages.
 *
 * @file
 * @ingroup SpecialPage
 */
class SpecialAllMessages extends SpecialPage {
	/**
	 * @var AllMessagesTablePager
	 */
	protected $table;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'Allmessages' );
	}

	/**
	 * Show the special page
	 *
	 * @param string $par Parameter passed to the page or null
	 */
	public function execute( $par ) {
		$request = $this->getRequest();
		$out = $this->getOutput();

		$this->setHeaders();

		if ( !$this->getConfig()->get( 'UseDatabaseMessages' ) ) {
			$out->addWikiMsg( 'allmessagesnotsupportedDB' );

			return;
		}

		$this->outputHeader( 'allmessagestext' );
		$out->addModuleStyles( 'mediawiki.special' );
		$this->addHelpLink( 'Help:System message' );

		$this->table = new AllMessagesTablePager(
			$this,
			[],
			wfGetLangObj( $request->getVal( 'lang', $par ) )
		);

		$out->addHTML( $this->table->buildForm() );
		$out->addParserOutputContent( $this->table->getFullOutput() );
	}

	protected function getGroupName() {
		return 'wiki';
	}
}
