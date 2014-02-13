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

/**
 * A special page that list protected titles from creation
 *
 * @ingroup SpecialPage
 */
class SpecialProtectedtitles extends SpecialPage {
	protected $IdLevel = 'level';
	protected $IdType = 'type';

	public function __construct() {
		parent::__construct( 'Protectedtitles' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );

		// Purge expired entries on one in every 10 queries
		if ( !mt_rand( 0, 10 ) ) {
			Title::purgeExpiredRestrictions();
		}

		$request = $this->getRequest();
		$type = $request->getVal( $this->IdType );
		$level = $request->getVal( $this->IdLevel );
		$sizetype = $request->getVal( 'sizetype' );
		$size = $request->getIntOrNull( 'size' );
		$NS = $request->getIntOrNull( 'namespace' );

		$pager = new ProtectedTitlesPager( $this, array(), $type, $level, $NS, $sizetype, $size );

		$this->getOutput()->addHTML( $this->showOptions( $NS, $type, $level ) );

		if ( $pager->getNumRows() ) {
			$this->getOutput()->addHTML(
				$pager->getNavigationBar() .
					$pager->getBody() .
					$pager->getNavigationBar()
			);
		} else {
			$this->getOutput()->addWikiMsg( 'protectedtitlesempty' );
		}
	}

	/**
	 * @param $namespace Integer:
	 * @param $type string
	 * @param $level string
	 * @return string
	 * @private
	 */
	function showOptions( $namespace, $type = 'edit', $level ) {
		global $wgScript;
		$action = htmlspecialchars( $wgScript );
		$title = $this->getPageTitle();
		$special = htmlspecialchars( $title->getPrefixedDBkey() );

		return "<form action=\"$action\" method=\"get\">\n" .
			'<fieldset>' .
			Xml::element( 'legend', array(), $this->msg( 'protectedtitles' )->text() ) .
			Html::hidden( 'title', $special ) . "&#160;\n" .
			$this->getNamespaceMenu( $namespace ) . "&#160;\n" .
			$this->getLevelMenu( $level ) . "&#160;\n" .
			"&#160;" . Xml::submitButton( $this->msg( 'allpagessubmit' )->text() ) . "\n" .
			"</fieldset></form>";
	}

	/**
	 * Prepare the namespace filter drop-down; standard namespace
	 * selector, sans the MediaWiki namespace
	 *
	 * @param $namespace Mixed: pre-select namespace
	 * @return string
	 */
	function getNamespaceMenu( $namespace = null ) {
		return Html::namespaceSelector(
			array(
				'selected' => $namespace,
				'all' => '',
				'label' => $this->msg( 'namespace' )->text()
			), array(
				'name' => 'namespace',
				'id' => 'namespace',
				'class' => 'namespaceselector',
			)
		);
	}

	/**
	 * @param string $pr_level Determines which option is selected as default
	 * @return string Formatted HTML
	 * @private
	 */
	function getLevelMenu( $pr_level ) {
		global $wgRestrictionLevels;

		// Temporary array
		$m = array( $this->msg( 'restriction-level-all' )->text() => 0 );
		$options = array();

		// First pass to load the log names
		foreach ( $wgRestrictionLevels as $type ) {
			if ( $type != '' && $type != '*' ) {
				// Messages: restriction-level-sysop, restriction-level-autoconfirmed
				$text = $this->msg( "restriction-level-$type" )->text();
				$m[$text] = $type;
			}
		}

		// Is there only one level (aside from "all")?
		if ( count( $m ) <= 2 ) {
			return '';
		}
		// Third pass generates sorted XHTML content
		foreach ( $m as $text => $type ) {
			$selected = ( $type == $pr_level );
			$options[] = Xml::option( $text, $type, $selected );
		}

		return Xml::label( $this->msg( 'restriction-level' )->text(), $this->IdLevel ) . '&#160;' .
			Xml::tags( 'select',
				array( 'id' => $this->IdLevel, 'name' => $this->IdLevel ),
				implode( "\n", $options ) );
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * @todo document
 * @ingroup Pager
 */
class ProtectedTitlesPager extends TablePager {
	public $mForm, $mConds;

	function __construct( $form, $conds = array(), $type, $level, $namespace,
		$sizetype = '', $size = 0
	) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->level = $level;
		$this->namespace = $namespace;
		$this->size = intval( $size );
		parent::__construct( $form->getContext() );
	}

	function preprocessResults( $result ) {
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		$lb = new LinkBatch;
		$userids = array();

		foreach ( $result as $row ) {
			$lb->add( $row->pt_namespace, $row->pt_title );
			$userids[] = $row->pt_user;
		}

		// fill LinkBatch with user page and user talk
		$userCache = UserCache::singleton();
		$userCache->doQuery( $userids, array(), __METHOD__ );
		foreach ( $userids as $userid ) {
			$name = $userCache->getProp( $userid, 'name' );
			if ( $name !== false ) {
				$lb->add( NS_USER, $name );
				$lb->add( NS_USER_TALK, $name );
			}
		}

		$lb->execute();
		wfProfileOut( __METHOD__ );
	}

	/**
	 * @return Title
	 */
	function getTitle() {
		return $this->mForm->getTitle();
	}

	function getFieldNames() {
		static $headers = null;

		if ( $headers == array() ) {
			$headers = array(
				'pt_timestamp' => 'protectedtitles-timestamp',
				'pt_page' => 'protectedtitles-page',
				'pt_expiry' => 'protectedtitles-expiry',
				'pt_user' => 'protectedtitles-user',
				'pt_params' => 'protectedtitles-params',
				'pt_reason' => 'protectedtitles-reason',
			);
			foreach ( $headers as $key => $val ) {
				$headers[$key] = $this->msg( $val )->text();
			}
		}

		return $headers;
	}

	function formatValue( $name, $value ) {
		/** @var $row object */
		$row = $this->mCurrentRow;

		$formatted = '';

		switch ( $name ) {
			case 'pt_timestamp':
				$formatted = $this->getLanguage()->userTimeAndDate( $value, $this->getUser() );
				break;

			case 'pt_page':
				$title = Title::makeTitleSafe( $row->pt_namespace, $row->pt_title );
				if ( !$title ) {
					$formatted = Html::element(
						'span',
						array( 'class' => 'mw-invalidtitle' ),
						Linker::getInvalidTitleDescription(
							$this->getContext(),
							$row->pt_namespace,
							$row->pt_title
						)
					);
				} else {
					$formatted = Linker::link( $title );
				}
				break;

			case 'pt_expiry':
				$formatted = $this->getLanguage()->formatExpiry( $value, /* User preference timezone */true );
				$title = Title::makeTitleSafe( $row->pt_namespace, $row->pt_title );
				if ( $this->getUser()->isAllowed( 'protect' ) && $title ) {
					$changeProtection = Linker::linkKnown(
						$title,
						$this->msg( 'protect_change' )->escaped(),
						array(),
						array( 'action' => 'unprotect' )
					);
					$formatted .= ' ' . Html::rawElement(
						'span',
						array( 'class' => 'mw-protectedtitles-actions' ),
						$this->msg( 'parentheses' )->rawParams( $changeProtection )->escaped()
					);
				}
				break;

			case 'pt_user':
				$username = UserCache::singleton()->getProp( $value, 'name' );
				if ( $username === false ) {
					$formatted = htmlspecialchars( $value );
				} else {
					$formatted = Linker::userLink( $value, $username )
						. Linker::userToolLinks( $value, $username );
				}
				break;

			case 'pt_reason':
				// Field is nullable, take a null reason as a empty reason
				$formatted = Linker::formatComment( $value !== null ? $value : '' );
				break;

			case 'pt_params':
				// Messages: restriction-level-sysop, restriction-level-autoconfirmed
				$formatted = $this->msg( 'restriction-level-' . $row->pt_create_perm )->escaped();
				break;

			default:
				$formatted = "Unable to format $name";
				break;
		}

		return $formatted;
	}

	/**
	 * @return array
	 */
	function getQueryInfo() {
		$conds = $this->mConds;
		$conds[] = 'pt_expiry>' . $this->mDb->addQuotes( $this->mDb->timestamp() );
		if ( $this->level ) {
			$conds['pt_create_perm'] = $this->level;
		}

		if ( !is_null( $this->namespace ) ) {
			$conds[] = 'pt_namespace=' . $this->mDb->addQuotes( $this->namespace );
		}

		return array(
			'tables' => 'protected_titles',
			'fields' => array(
				'pt_namespace',
				'pt_title',
				'pt_create_perm',
				'pt_expiry',
				'pt_timestamp',
				'pt_user',
				'pt_reason',
			),
			'conds' => $conds
		);
	}

	public function getTableClass() {
		return 'TablePager mw-protectedtitles';
	}

	function getIndexField() {
		return 'pt_timestamp';
	}

	function getDefaultSort() {
		return 'pt_timestamp';
	}

	function isFieldSortable( $name ) {
		// no index for sorting exists
		return false;
	}
}
