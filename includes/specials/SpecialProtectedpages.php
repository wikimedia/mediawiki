<?php
/**
 * Implements Special:Protectedpages
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

use MediaWiki\Linker\LinkRenderer;

/**
 * A special page that lists protected pages
 *
 * @ingroup SpecialPage
 */
class SpecialProtectedpages extends SpecialPage {
	protected $IdLevel = 'level';
	protected $IdType = 'type';

	public function __construct() {
		parent::__construct( 'Protectedpages' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );

		$request = $this->getRequest();
		$type = $request->getVal( $this->IdType );
		$level = $request->getVal( $this->IdLevel );
		$sizetype = $request->getVal( 'sizetype' );
		$size = $request->getIntOrNull( 'size' );
		$ns = $request->getIntOrNull( 'namespace' );
		$indefOnly = $request->getBool( 'indefonly' ) ? 1 : 0;
		$cascadeOnly = $request->getBool( 'cascadeonly' ) ? 1 : 0;
		$noRedirect = $request->getBool( 'noredirect' ) ? 1 : 0;

		$pager = new ProtectedPagesPager(
			$this,
			[],
			$type,
			$level,
			$ns,
			$sizetype,
			$size,
			$indefOnly,
			$cascadeOnly,
			$noRedirect,
			$this->getLinkRenderer()
		);

		$this->getOutput()->addHTML( $this->showOptions(
			$ns,
			$type,
			$level,
			$sizetype,
			$size,
			$indefOnly,
			$cascadeOnly,
			$noRedirect
		) );

		if ( $pager->getNumRows() ) {
			$this->getOutput()->addParserOutputContent( $pager->getFullOutput() );
		} else {
			$this->getOutput()->addWikiMsg( 'protectedpagesempty' );
		}
	}

	/**
	 * @param int $namespace
	 * @param string $type Restriction type
	 * @param string $level Restriction level
	 * @param string $sizetype "min" or "max"
	 * @param int $size
	 * @param bool $indefOnly Only indefinite protection
	 * @param bool $cascadeOnly Only cascading protection
	 * @param bool $noRedirect Don't show redirects
	 * @return string Input form
	 */
	protected function showOptions( $namespace, $type = 'edit', $level, $sizetype,
		$size, $indefOnly, $cascadeOnly, $noRedirect
	) {
		$title = $this->getPageTitle();

		return Xml::openElement( 'form', [ 'method' => 'get', 'action' => wfScript() ] ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', [], $this->msg( 'protectedpages' )->text() ) .
			Html::hidden( 'title', $title->getPrefixedDBkey() ) . "\n" .
			$this->getNamespaceMenu( $namespace ) . "\n" .
			$this->getTypeMenu( $type ) . "\n" .
			$this->getLevelMenu( $level ) . "\n" .
			"<br />\n" .
			$this->getExpiryCheck( $indefOnly ) . "\n" .
			$this->getCascadeCheck( $cascadeOnly ) . "\n" .
			$this->getRedirectCheck( $noRedirect ) . "\n" .
			"<br />\n" .
			$this->getSizeLimit( $sizetype, $size ) . "\n" .
			Xml::submitButton( $this->msg( 'protectedpages-submit' )->text() ) . "\n" .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );
	}

	/**
	 * Prepare the namespace filter drop-down; standard namespace
	 * selector, sans the MediaWiki namespace
	 *
	 * @param string|null $namespace Pre-select namespace
	 * @return string
	 */
	protected function getNamespaceMenu( $namespace = null ) {
		return Html::rawElement( 'span', [ 'class' => 'mw-input-with-label' ],
			Html::namespaceSelector(
				[
					'selected' => $namespace,
					'all' => '',
					'label' => $this->msg( 'namespace' )->text()
				], [
					'name' => 'namespace',
					'id' => 'namespace',
					'class' => 'namespaceselector',
				]
			)
		);
	}

	/**
	 * @param bool $indefOnly
	 * @return string Formatted HTML
	 */
	protected function getExpiryCheck( $indefOnly ) {
		return '<span class="mw-input-with-label">' . Xml::checkLabel(
			$this->msg( 'protectedpages-indef' )->text(),
			'indefonly',
			'indefonly',
			$indefOnly
		) . "</span>\n";
	}

	/**
	 * @param bool $cascadeOnly
	 * @return string Formatted HTML
	 */
	protected function getCascadeCheck( $cascadeOnly ) {
		return '<span class="mw-input-with-label">' . Xml::checkLabel(
			$this->msg( 'protectedpages-cascade' )->text(),
			'cascadeonly',
			'cascadeonly',
			$cascadeOnly
		) . "</span>\n";
	}

	/**
	 * @param bool $noRedirect
	 * @return string Formatted HTML
	 */
	protected function getRedirectCheck( $noRedirect ) {
		return '<span class="mw-input-with-label">' . Xml::checkLabel(
			$this->msg( 'protectedpages-noredirect' )->text(),
			'noredirect',
			'noredirect',
			$noRedirect
		) . "</span>\n";
	}

	/**
	 * @param string $sizetype "min" or "max"
	 * @param mixed $size
	 * @return string Formatted HTML
	 */
	protected function getSizeLimit( $sizetype, $size ) {
		$max = $sizetype === 'max';

		return '<span class="mw-input-with-label">' . Xml::radioLabel(
			$this->msg( 'minimum-size' )->text(),
			'sizetype',
			'min',
			'wpmin',
			!$max
		) .
			' ' .
			Xml::radioLabel(
				$this->msg( 'maximum-size' )->text(),
				'sizetype',
				'max',
				'wpmax',
				$max
			) .
			' ' .
			Xml::input( 'size', 9, $size, [ 'id' => 'wpsize' ] ) .
			' ' .
			Xml::label( $this->msg( 'pagesize' )->text(), 'wpsize' ) . "</span>\n";
	}

	/**
	 * Creates the input label of the restriction type
	 * @param string $pr_type Protection type
	 * @return string Formatted HTML
	 */
	protected function getTypeMenu( $pr_type ) {
		$m = []; // Temporary array
		$options = [];

		// First pass to load the log names
		foreach ( Title::getFilteredRestrictionTypes( true ) as $type ) {
			// Messages: restriction-edit, restriction-move, restriction-create, restriction-upload
			$text = $this->msg( "restriction-$type" )->text();
			$m[$text] = $type;
		}

		// Third pass generates sorted XHTML content
		foreach ( $m as $text => $type ) {
			$selected = ( $type == $pr_type );
			$options[] = Xml::option( $text, $type, $selected ) . "\n";
		}

		return '<span class="mw-input-with-label">' .
			Xml::label( $this->msg( 'restriction-type' )->text(), $this->IdType ) . ' ' .
			Xml::tags( 'select',
				[ 'id' => $this->IdType, 'name' => $this->IdType ],
				implode( "\n", $options ) ) . "</span>";
	}

	/**
	 * Creates the input label of the restriction level
	 * @param string $pr_level Protection level
	 * @return string Formatted HTML
	 */
	protected function getLevelMenu( $pr_level ) {
		// Temporary array
		$m = [ $this->msg( 'restriction-level-all' )->text() => 0 ];
		$options = [];

		// First pass to load the log names
		foreach ( $this->getConfig()->get( 'RestrictionLevels' ) as $type ) {
			// Messages used can be 'restriction-level-sysop' and 'restriction-level-autoconfirmed'
			if ( $type != '' && $type != '*' ) {
				$text = $this->msg( "restriction-level-$type" )->text();
				$m[$text] = $type;
			}
		}

		// Third pass generates sorted XHTML content
		foreach ( $m as $text => $type ) {
			$selected = ( $type == $pr_level );
			$options[] = Xml::option( $text, $type, $selected );
		}

		return '<span class="mw-input-with-label">' .
			Xml::label( $this->msg( 'restriction-level' )->text(), $this->IdLevel ) . ' ' .
			Xml::tags( 'select',
				[ 'id' => $this->IdLevel, 'name' => $this->IdLevel ],
				implode( "\n", $options ) ) . "</span>";
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * @todo document
 * @ingroup Pager
 */
class ProtectedPagesPager extends TablePager {
	public $mForm, $mConds;
	private $type, $level, $namespace, $sizetype, $size, $indefonly, $cascadeonly, $noredirect;

	/**
	 * @var LinkRenderer
	 */
	private $linkRenderer;

	/**
	 * @param SpecialProtectedpages $form
	 * @param array $conds
	 * @param $type
	 * @param $level
	 * @param $namespace
	 * @param string $sizetype
	 * @param int $size
	 * @param bool $indefonly
	 * @param bool $cascadeonly
	 * @param bool $noredirect
	 * @param LinkRenderer $linkRenderer
	 */
	function __construct( $form, $conds = [], $type, $level, $namespace,
		$sizetype = '', $size = 0, $indefonly = false, $cascadeonly = false, $noredirect = false,
		LinkRenderer $linkRenderer
	) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->type = ( $type ) ? $type : 'edit';
		$this->level = $level;
		$this->namespace = $namespace;
		$this->sizetype = $sizetype;
		$this->size = intval( $size );
		$this->indefonly = (bool)$indefonly;
		$this->cascadeonly = (bool)$cascadeonly;
		$this->noredirect = (bool)$noredirect;
		$this->linkRenderer = $linkRenderer;
		parent::__construct( $form->getContext() );
	}

	function preprocessResults( $result ) {
		# Do a link batch query
		$lb = new LinkBatch;
		$userids = [];

		foreach ( $result as $row ) {
			$lb->add( $row->page_namespace, $row->page_title );
			// field is nullable, maybe null on old protections
			if ( $row->log_user !== null ) {
				$userids[] = $row->log_user;
			}
		}

		// fill LinkBatch with user page and user talk
		if ( count( $userids ) ) {
			$userCache = UserCache::singleton();
			$userCache->doQuery( $userids, [], __METHOD__ );
			foreach ( $userids as $userid ) {
				$name = $userCache->getProp( $userid, 'name' );
				if ( $name !== false ) {
					$lb->add( NS_USER, $name );
					$lb->add( NS_USER_TALK, $name );
				}
			}
		}

		$lb->execute();
	}

	function getFieldNames() {
		static $headers = null;

		if ( $headers == [] ) {
			$headers = [
				'log_timestamp' => 'protectedpages-timestamp',
				'pr_page' => 'protectedpages-page',
				'pr_expiry' => 'protectedpages-expiry',
				'log_user' => 'protectedpages-performer',
				'pr_params' => 'protectedpages-params',
				'log_comment' => 'protectedpages-reason',
			];
			foreach ( $headers as $key => $val ) {
				$headers[$key] = $this->msg( $val )->text();
			}
		}

		return $headers;
	}

	/**
	 * @param string $field
	 * @param string $value
	 * @return string HTML
	 * @throws MWException
	 */
	function formatValue( $field, $value ) {
		/** @var $row object */
		$row = $this->mCurrentRow;

		switch ( $field ) {
			case 'log_timestamp':
				// when timestamp is null, this is a old protection row
				if ( $value === null ) {
					$formatted = Html::rawElement(
						'span',
						[ 'class' => 'mw-protectedpages-unknown' ],
						$this->msg( 'protectedpages-unknown-timestamp' )->escaped()
					);
				} else {
					$formatted = htmlspecialchars( $this->getLanguage()->userTimeAndDate(
						$value, $this->getUser() ) );
				}
				break;

			case 'pr_page':
				$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
				if ( !$title ) {
					$formatted = Html::element(
						'span',
						[ 'class' => 'mw-invalidtitle' ],
						Linker::getInvalidTitleDescription(
							$this->getContext(),
							$row->page_namespace,
							$row->page_title
						)
					);
				} else {
					$formatted = $this->linkRenderer->makeLink( $title );
				}
				if ( !is_null( $row->page_len ) ) {
					$formatted .= $this->getLanguage()->getDirMark() .
						' ' . Html::rawElement(
						'span',
						[ 'class' => 'mw-protectedpages-length' ],
						Linker::formatRevisionSize( $row->page_len )
					);
				}
				break;

			case 'pr_expiry':
				$formatted = htmlspecialchars( $this->getLanguage()->formatExpiry(
					$value, /* User preference timezone */true ) );
				$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
				if ( $this->getUser()->isAllowed( 'protect' ) && $title ) {
					$changeProtection = $this->linkRenderer->makeKnownLink(
						$title,
						$this->msg( 'protect_change' )->text(),
						[],
						[ 'action' => 'unprotect' ]
					);
					$formatted .= ' ' . Html::rawElement(
						'span',
						[ 'class' => 'mw-protectedpages-actions' ],
						$this->msg( 'parentheses' )->rawParams( $changeProtection )->escaped()
					);
				}
				break;

			case 'log_user':
				// when timestamp is null, this is a old protection row
				if ( $row->log_timestamp === null ) {
					$formatted = Html::rawElement(
						'span',
						[ 'class' => 'mw-protectedpages-unknown' ],
						$this->msg( 'protectedpages-unknown-performer' )->escaped()
					);
				} else {
					$username = UserCache::singleton()->getProp( $value, 'name' );
					if ( LogEventsList::userCanBitfield(
						$row->log_deleted,
						LogPage::DELETED_USER,
						$this->getUser()
					) ) {
						if ( $username === false ) {
							$formatted = htmlspecialchars( $value );
						} else {
							$formatted = Linker::userLink( $value, $username )
								. Linker::userToolLinks( $value, $username );
						}
					} else {
						$formatted = $this->msg( 'rev-deleted-user' )->escaped();
					}
					if ( LogEventsList::isDeleted( $row, LogPage::DELETED_USER ) ) {
						$formatted = '<span class="history-deleted">' . $formatted . '</span>';
					}
				}
				break;

			case 'pr_params':
				$params = [];
				// Messages: restriction-level-sysop, restriction-level-autoconfirmed
				$params[] = $this->msg( 'restriction-level-' . $row->pr_level )->escaped();
				if ( $row->pr_cascade ) {
					$params[] = $this->msg( 'protect-summary-cascade' )->escaped();
				}
				$formatted = $this->getLanguage()->commaList( $params );
				break;

			case 'log_comment':
				// when timestamp is null, this is an old protection row
				if ( $row->log_timestamp === null ) {
					$formatted = Html::rawElement(
						'span',
						[ 'class' => 'mw-protectedpages-unknown' ],
						$this->msg( 'protectedpages-unknown-reason' )->escaped()
					);
				} else {
					if ( LogEventsList::userCanBitfield(
						$row->log_deleted,
						LogPage::DELETED_COMMENT,
						$this->getUser()
					) ) {
						$formatted = Linker::formatComment( $value !== null ? $value : '' );
					} else {
						$formatted = $this->msg( 'rev-deleted-comment' )->escaped();
					}
					if ( LogEventsList::isDeleted( $row, LogPage::DELETED_COMMENT ) ) {
						$formatted = '<span class="history-deleted">' . $formatted . '</span>';
					}
				}
				break;

			default:
				throw new MWException( "Unknown field '$field'" );
		}

		return $formatted;
	}

	function getQueryInfo() {
		$conds = $this->mConds;
		$conds[] = 'pr_expiry > ' . $this->mDb->addQuotes( $this->mDb->timestamp() ) .
			' OR pr_expiry IS NULL';
		$conds[] = 'page_id=pr_page';
		$conds[] = 'pr_type=' . $this->mDb->addQuotes( $this->type );

		if ( $this->sizetype == 'min' ) {
			$conds[] = 'page_len>=' . $this->size;
		} elseif ( $this->sizetype == 'max' ) {
			$conds[] = 'page_len<=' . $this->size;
		}

		if ( $this->indefonly ) {
			$infinity = $this->mDb->addQuotes( $this->mDb->getInfinity() );
			$conds[] = "pr_expiry = $infinity OR pr_expiry IS NULL";
		}
		if ( $this->cascadeonly ) {
			$conds[] = 'pr_cascade = 1';
		}
		if ( $this->noredirect ) {
			$conds[] = 'page_is_redirect = 0';
		}

		if ( $this->level ) {
			$conds[] = 'pr_level=' . $this->mDb->addQuotes( $this->level );
		}
		if ( !is_null( $this->namespace ) ) {
			$conds[] = 'page_namespace=' . $this->mDb->addQuotes( $this->namespace );
		}

		return [
			'tables' => [ 'page', 'page_restrictions', 'log_search', 'logging' ],
			'fields' => [
				'pr_id',
				'page_namespace',
				'page_title',
				'page_len',
				'pr_type',
				'pr_level',
				'pr_expiry',
				'pr_cascade',
				'log_timestamp',
				'log_user',
				'log_comment',
				'log_deleted',
			],
			'conds' => $conds,
			'join_conds' => [
				'log_search' => [
					'LEFT JOIN', [
						'ls_field' => 'pr_id', 'ls_value = ' . $this->mDb->buildStringCast( 'pr_id' )
					]
				],
				'logging' => [
					'LEFT JOIN', [
						'ls_log_id = log_id'
					]
				]
			]
		];
	}

	protected function getTableClass() {
		return parent::getTableClass() . ' mw-protectedpages';
	}

	function getIndexField() {
		return 'pr_id';
	}

	function getDefaultSort() {
		return 'pr_id';
	}

	function isFieldSortable( $field ) {
		// no index for sorting exists
		return false;
	}
}
