<?php
/**
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
 * @ingroup Pager
 */

namespace MediaWiki\Pager;

use LocalisationCache;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Use TablePager for prettified output. We have to pretend that we're
 * getting data from a table when in fact not all of it comes from the database.
 *
 * @ingroup Pager
 */
class AllMessagesTablePager extends TablePager {

	/**
	 * @var bool
	 */
	protected $foreign;

	/**
	 * @var string|false
	 */
	protected $prefix;

	/**
	 * @var string
	 */
	protected $suffix;

	/**
	 * @var Language
	 */
	public $lang;

	/**
	 * @var null|bool
	 */
	public $custom;

	private LocalisationCache $localisationCache;

	public function __construct(
		IContextSource $context,
		Language $contentLanguage,
		LanguageFactory $languageFactory,
		LinkRenderer $linkRenderer,
		IConnectionProvider $dbProvider,
		LocalisationCache $localisationCache,
		FormOptions $opts
	) {
		// Set database before parent constructor to avoid setting it there
		$this->mDb = $dbProvider->getReplicaDatabase();
		parent::__construct( $context, $linkRenderer );
		$this->localisationCache = $localisationCache;

		$this->mIndexField = 'am_title';
		// FIXME: Why does this need to be set to DIR_DESCENDING to produce ascending ordering?
		$this->mDefaultDirection = IndexPager::DIR_DESCENDING;

		$this->lang = $languageFactory->getRawLanguage( $opts->getValue( 'lang' ) );

		$this->foreign = !$this->lang->equals( $contentLanguage );

		$filter = $opts->getValue( 'filter' );
		if ( $filter === 'all' ) {
			$this->custom = null; // So won't match in either case
		} else {
			$this->custom = ( $filter === 'unmodified' );
		}

		$prefix = $this->getLanguage()->ucfirst( $opts->getValue( 'prefix' ) );
		$prefix = $prefix !== '' ?
			Title::makeTitleSafe( NS_MEDIAWIKI, $opts->getValue( 'prefix' ) ) :
			null;

		if ( $prefix !== null ) {
			$displayPrefix = $prefix->getDBkey();
			$this->prefix = '/^' . preg_quote( $displayPrefix, '/' ) . '/i';
		} else {
			$this->prefix = false;
		}

		// The suffix that may be needed for message names if we're in a
		// different language (eg [[MediaWiki:Foo/fr]]: $suffix = '/fr'
		if ( $this->foreign ) {
			$this->suffix = '/' . $this->lang->getCode();
		} else {
			$this->suffix = '';
		}
	}

	private function getAllMessages( bool $descending ): array {
		$messageNames = $this->localisationCache->getSubitemList( 'en', 'messages' );

		// Normalise message names so they look like page titles and sort correctly - T86139
		$messageNames = array_map( [ $this->lang, 'ucfirst' ], $messageNames );

		if ( $descending ) {
			rsort( $messageNames );
		} else {
			asort( $messageNames );
		}

		return $messageNames;
	}

	/**
	 * Determine which of the MediaWiki and MediaWiki_talk namespace pages exist.
	 * Returns [ 'pages' => ..., 'talks' => ... ], where the subarrays have
	 * an entry for each existing page, with the key being the message name and
	 * value arbitrary.
	 *
	 * @since 1.36 Added $dbr parameter
	 *
	 * @param array $messageNames
	 * @param string $langcode What language code
	 * @param bool $foreign Whether the $langcode is not the content language
	 * @param IReadableDatabase|null $dbr
	 * @return array A 'pages' and 'talks' array with the keys of existing pages
	 */
	public static function getCustomisedStatuses(
		$messageNames,
		$langcode = 'en',
		$foreign = false,
		?IReadableDatabase $dbr = null
	) {
		// FIXME: This function should be moved to Language:: or something.
		// Fallback to global state, if not provided
		$dbr ??= MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'page_namespace', 'page_title' ] )
			->from( 'page' )
			->where( [ 'page_namespace' => [ NS_MEDIAWIKI, NS_MEDIAWIKI_TALK ] ] )
			->useIndex( 'page_name_title' )
			->caller( __METHOD__ )->fetchResultSet();
		$xNames = array_fill_keys( $messageNames, true );

		$pageFlags = $talkFlags = [];

		foreach ( $res as $s ) {
			$exists = false;

			if ( $foreign ) {
				$titleParts = explode( '/', $s->page_title );
				if ( count( $titleParts ) === 2 &&
					$langcode === $titleParts[1] &&
					isset( $xNames[$titleParts[0]] )
				) {
					$exists = $titleParts[0];
				}
			} elseif ( isset( $xNames[$s->page_title] ) ) {
				$exists = $s->page_title;
			}

			$title = Title::newFromRow( $s );
			if ( $exists && $title->inNamespace( NS_MEDIAWIKI ) ) {
				$pageFlags[$exists] = true;
			} elseif ( $exists && $title->inNamespace( NS_MEDIAWIKI_TALK ) ) {
				$talkFlags[$exists] = true;
			}
		}

		return [ 'pages' => $pageFlags, 'talks' => $talkFlags ];
	}

	/**
	 * This function normally does a database query to get the results; we need
	 * to make a pretend result using a FakeResultWrapper.
	 * @param string $offset
	 * @param int $limit
	 * @param bool $order
	 * @return IResultWrapper
	 */
	public function reallyDoQuery( $offset, $limit, $order ) {
		$asc = ( $order === self::QUERY_ASCENDING );

		$messageNames = $this->getAllMessages( $order );
		$statuses = self::getCustomisedStatuses(
			$messageNames,
			$this->lang->getCode(),
			$this->foreign,
			$this->getDatabase()
		);

		$rows = [];
		$count = 0;
		foreach ( $messageNames as $key ) {
			$customised = isset( $statuses['pages'][$key] );
			if ( $customised !== $this->custom &&
				( ( $asc && ( $key < $offset || !$offset ) ) || ( !$asc && $key > $offset ) ) &&
				( ( $this->prefix && preg_match( $this->prefix, $key ) ) || $this->prefix === false )
			) {
				$actual = $this->msg( $key )->inLanguage( $this->lang )->plain();
				$default = $this->msg( $key )->inLanguage( $this->lang )->useDatabase( false )->plain();
				$rows[] = [
					'am_title' => $key,
					'am_actual' => $actual,
					'am_default' => $default,
					'am_customised' => $customised,
					'am_talk_exists' => isset( $statuses['talks'][$key] )
				];
				$count++;
			}

			if ( $count === $limit ) {
				break;
			}
		}

		return new FakeResultWrapper( $rows );
	}

	/** @inheritDoc */
	protected function getStartBody() {
		return Html::openElement( 'table', [
			'class' => $this->getTableClass(),
			'id' => 'mw-allmessagestable'
		] ) .
		"\n" .
		"<thead><tr>
				<th rowspan=\"2\">" .
		$this->msg( 'allmessagesname' )->escaped() . "
				</th>
				<th>" .
		$this->msg( 'allmessagesdefault' )->escaped() .
		"</th>
			</tr>\n
			<tr>
				<th>" .
		$this->msg( 'allmessagescurrent' )->escaped() .
		"</th>
			</tr></thead>\n";
	}

	/** @inheritDoc */
	protected function getEndBody() {
		return Html::closeElement( 'table' );
	}

	/**
	 * @param string $field
	 * @param string|null $value
	 * @return string HTML
	 */
	public function formatValue( $field, $value ) {
		$linkRenderer = $this->getLinkRenderer();
		switch ( $field ) {
			case 'am_title':
				$title = Title::makeTitle( NS_MEDIAWIKI, $value . $this->suffix );
				$talk = Title::makeTitle( NS_MEDIAWIKI_TALK, $value . $this->suffix );
				$message = $this->msg( $value )->inLanguage( $this->lang )->useDatabase( false )->plain();
				$translation = $linkRenderer->makeExternalLink(
					'https://translatewiki.net/w/i.php?' . wfArrayToCgi( [
						'title' => 'Special:SearchTranslations',
						'group' => 'mediawiki',
						'grouppath' => 'mediawiki',
						'language' => $this->lang->getCode(),
						'query' => $value . ' ' . $message
					] ),
					$this->msg( 'allmessages-filter-translate' ),
					$this->getTitle()
				);
				$talkLink = $this->msg( 'talkpagelinktext' )->text();

				if ( $this->mCurrentRow->am_customised ) {
					$title = $linkRenderer->makeKnownLink( $title, $this->getLanguage()->lcfirst( $value ) );
				} else {
					$title = $linkRenderer->makeBrokenLink(
						$title, $this->getLanguage()->lcfirst( $value )
					);
				}
				if ( $this->mCurrentRow->am_talk_exists ) {
					$talk = $linkRenderer->makeKnownLink( $talk, $talkLink );
				} else {
					$talk = $linkRenderer->makeBrokenLink(
						$talk,
						$talkLink
					);
				}

				return $title . ' ' .
					$this->msg( 'parentheses' )->rawParams( $talk )->escaped() .
					' ' .
					$this->msg( 'parentheses' )->rawParams( $translation )->escaped();

			case 'am_default':
			case 'am_actual':
				return Sanitizer::escapeHtmlAllowEntities( $value );
		}

		return '';
	}

	/**
	 * @param stdClass $row
	 * @return string HTML
	 */
	public function formatRow( $row ) {
		// Do all the normal stuff
		$s = parent::formatRow( $row );

		// But if there's a customised message, add that too.
		if ( $row->am_customised ) {
			$s .= Html::openElement( 'tr', $this->getRowAttrs( $row ) );
			$formatted = strval( $this->formatValue( 'am_actual', $row->am_actual ) );

			if ( $formatted === '' ) {
				$formatted = "\u{00A0}";
			}

			$s .= Html::rawElement( 'td', $this->getCellAttrs( 'am_actual', $row->am_actual ), $formatted )
				. Html::closeElement( 'tr' );
		}

		return Html::rawElement( 'tbody', [], $s );
	}

	/** @inheritDoc */
	protected function getRowAttrs( $row ) {
		return [];
	}

	/**
	 * @param string $field
	 * @param string $value
	 * @return array HTML attributes
	 */
	protected function getCellAttrs( $field, $value ) {
		$attr = [];
		if ( $field === 'am_title' ) {
			if ( $this->mCurrentRow->am_customised ) {
				$attr += [ 'rowspan' => '2' ];
			}
		} else {
			$attr += [
				'lang' => $this->lang->getHtmlCode(),
				'dir' => $this->lang->getDir(),
			];
			if ( $this->mCurrentRow->am_customised ) {
				// CSS class: am_default, am_actual
				$attr += [ 'class' => $field ];
			}
		}
		return $attr;
	}

	/** @inheritDoc */
	protected function getFieldNames() {
		// This is not actually used, as getStartBody is overridden above
		return [
			'am_title' => $this->msg( 'allmessagesname' )->text(),
			'am_default' => $this->msg( 'allmessagesdefault' )->text()
		];
	}

	/** @inheritDoc */
	public function getTitle() {
		return SpecialPage::getTitleFor( 'Allmessages', false );
	}

	/** @inheritDoc */
	protected function isFieldSortable( $x ) {
		return false;
	}

	/** @inheritDoc */
	public function getDefaultSort() {
		return '';
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		return [];
	}

}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( AllMessagesTablePager::class, 'AllMessagesTablePager' );
