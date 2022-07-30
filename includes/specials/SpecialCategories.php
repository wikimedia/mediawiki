<?php
/**
 * Implements Special:Categories
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

use MediaWiki\Cache\LinkBatchFactory;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @ingroup SpecialPage
 */
class SpecialCategories extends SpecialPage {

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/**
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		ILoadBalancer $loadBalancer
	) {
		parent::__construct( 'Categories' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->loadBalancer = $loadBalancer;
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Categories' );
		$this->getOutput()->setPreventClickjacking( false );

		$from = $this->getRequest()->getText( 'from', $par ?? '' );

		$cap = new CategoryPager(
			$this->getContext(),
			$this->linkBatchFactory,
			$this->getLinkRenderer(),
			$this->loadBalancer,
			$from
		);
		$cap->doQuery();

		$this->getOutput()->addHTML(
			Html::openElement( 'div', [ 'class' => 'mw-spcontent' ] ) .
				$this->msg( 'categoriespagetext', $cap->getNumRows() )->parseAsBlock() .
				$cap->getStartForm( $from ) .
				$cap->getNavigationBar() .
				'<ul>' . $cap->getBody() . '</ul>' .
				$cap->getNavigationBar() .
				Html::closeElement( 'div' )
		);
	}

	protected function getGroupName() {
		return 'pages';
	}
}
