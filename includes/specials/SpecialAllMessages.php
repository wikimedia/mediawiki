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
use MediaWiki\MediaWikiServices;

/**
 * Use this special page to get a list of the MediaWiki system messages.
 *
 * @file
 * @ingroup SpecialPage
 */
class SpecialAllMessages extends SpecialPage {

	public function __construct() {
		parent::__construct( 'Allmessages' );
	}

	/**
	 * @param string|null $par Parameter passed to the page or null
	 */
	public function execute( $par ) {
		$out = $this->getOutput();

		$this->setHeaders();

		if ( !$this->getConfig()->get( 'UseDatabaseMessages' ) ) {
			$out->addWikiMsg( 'allmessages-not-supported-database' );

			return;
		}

		$out->addModuleStyles( 'mediawiki.special' );
		$this->addHelpLink( 'Help:System message' );

		$contLang = MediaWikiServices::getInstance()->getContentLanguage()->getCode();
		$lang = $this->getLanguage();

		$opts = new FormOptions();

		$opts->add( 'prefix', '' );
		$opts->add( 'filter', 'all' );
		$opts->add( 'lang', $contLang );
		$opts->add( 'limit', 50 );

		$opts->fetchValuesFromRequest( $this->getRequest() );
		$opts->validateIntBounds( 'limit', 0, 5000 );

		$pager = new AllMessagesTablePager( $this->getContext(), $opts, $this->getLinkRenderer() );

		$formDescriptor = [
			'prefix' => [
				'type' => 'text',
				'name' => 'prefix',
				'label-message' => 'allmessages-prefix',
			],

			'filter' => [
				'type' => 'radio',
				'name' => 'filter',
				'label-message' => 'allmessages-filter',
				'options-messages' => [
					'allmessages-filter-unmodified' => 'unmodified',
					'allmessages-filter-all' => 'all',
					'allmessages-filter-modified' => 'modified',
				],
				'default' => 'all',
				'flatlist' => true,
			],

			'lang' => [
				'type' => 'language',
				'name' => 'lang',
				'label-message' => 'allmessages-language',
				'default' => $opts->getValue( 'lang' ),
			],

			'limit' => [
				'type' => 'limitselect',
				'name' => 'limit',
				'label-message' => 'table_pager_limit_label',
				'options' => [
					$lang->formatNum( 20 ) => 20,
					$lang->formatNum( 50 ) => 50,
					$lang->formatNum( 100 ) => 100,
					$lang->formatNum( 250 ) => 250,
					$lang->formatNum( 500 ) => 500,
					$lang->formatNum( 5000 ) => 5000,
				],
				'default' => $opts->getValue( 'limit' ),
			],
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm
			->setMethod( 'get' )
			->setIntro( $this->msg( 'allmessagestext' ) )
			->setWrapperLegendMsg( 'allmessages' )
			->setSubmitTextMsg( 'allmessages-filter-submit' )
			->prepareForm()
			->displayForm( false );

		$out->addParserOutputContent( $pager->getFullOutput() );
	}

	protected function getGroupName() {
		return 'wiki';
	}
}
