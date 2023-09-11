<?php
/**
 * Implements Special:Protectedtitles
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

namespace MediaWiki\Specials;

use HTMLForm;
use HTMLSelectNamespace;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Pager\ProtectedTitlesPager;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * A special page that list protected titles from creation
 *
 * @ingroup SpecialPage
 */
class SpecialProtectedTitles extends SpecialPage {
	protected $IdLevel = 'level';
	protected $IdType = 'type';

	private LinkBatchFactory $linkBatchFactory;
	private IConnectionProvider $dbProvider;

	/**
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param IConnectionProvider $dbProvider
	 */
	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider
	) {
		parent::__construct( 'Protectedtitles' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->dbProvider = $dbProvider;
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Protected_pages' );

		$request = $this->getRequest();
		$type = $request->getVal( $this->IdType );
		$level = $request->getVal( $this->IdLevel );
		$sizetype = $request->getVal( 'sizetype' );
		$size = $request->getIntOrNull( 'size' );
		$NS = $request->getIntOrNull( 'namespace' );

		$pager = new ProtectedTitlesPager(
			$this->getContext(),
			$this->getLinkRenderer(),
			$this->linkBatchFactory,
			$this->dbProvider,
			[],
			$type,
			$level,
			$NS,
			$sizetype,
			$size
		);

		$this->getOutput()->addHTML( $this->showOptions() );

		if ( $pager->getNumRows() ) {
			$this->getOutput()->addHTML(
				$pager->getNavigationBar() .
					'<ul>' . $pager->getBody() . '</ul>' .
					$pager->getNavigationBar()
			);
		} else {
			$this->getOutput()->addWikiMsg( 'protectedtitlesempty' );
		}
	}

	/**
	 * @return string
	 */
	private function showOptions() {
		$formDescriptor = [
			'namespace' => [
				'class' => HTMLSelectNamespace::class,
				'name' => 'namespace',
				'id' => 'namespace',
				'cssclass' => 'namespaceselector',
				'all' => '',
				'label' => $this->msg( 'namespace' )->text()
			],
			'levelmenu' => $this->getLevelMenu()
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setMethod( 'get' )
			->setWrapperLegendMsg( 'protectedtitles' )
			->setSubmitTextMsg( 'protectedtitles-submit' );

		return $htmlForm->prepareForm()->getHTML( false );
	}

	/**
	 * @return string|array
	 */
	private function getLevelMenu() {
		$options = [ 'restriction-level-all' => 0 ];

		// Load the log names as options
		foreach ( $this->getConfig()->get( MainConfigNames::RestrictionLevels ) as $type ) {
			if ( $type != '' && $type != '*' ) {
				// Messages: restriction-level-sysop, restriction-level-autoconfirmed
				$options["restriction-level-$type"] = $type;
			}
		}

		// Is there only one level (aside from "all")?
		if ( count( $options ) <= 2 ) {
			return '';
		}

		return [
			'type' => 'select',
			'options-messages' => $options,
			'label-message' => 'restriction-level',
			'name' => $this->IdLevel,
			'id' => $this->IdLevel
		];
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialProtectedTitles::class, 'SpecialProtectedtitles' );
