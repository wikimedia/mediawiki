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

/**
 * @ingroup Pager
 */
class MergeHistoryPager extends ReverseChronologicalPager {

	/** @var SpecialMergeHistory */
	public $mForm;

	/** @var array */
	public $mConds;

	public function __construct( SpecialMergeHistory $form, $conds, Title $source, Title $dest ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->title = $source;
		$this->articleID = $source->getArticleID();

		$dbr = wfGetDB( DB_REPLICA );
		$maxtimestamp = $dbr->selectField(
			'revision',
			'MIN(rev_timestamp)',
			[ 'rev_page' => $dest->getArticleID() ],
			__METHOD__
		);
		$this->maxTimestamp = $maxtimestamp;

		parent::__construct( $form->getContext() );
	}

	protected function getStartBody() {
		# Do a link batch query
		$this->mResult->seek( 0 );
		$batch = new LinkBatch();
		# Give some pointers to make (last) links
		$this->mForm->prevId = [];
		$rev_id = null;
		foreach ( $this->mResult as $row ) {
			$batch->addObj( Title::makeTitleSafe( NS_USER, $row->user_name ) );
			$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->user_name ) );

			if ( isset( $rev_id ) ) {
				if ( $rev_id > $row->rev_id ) {
					$this->mForm->prevId[$rev_id] = $row->rev_id;
				} elseif ( $rev_id < $row->rev_id ) {
					$this->mForm->prevId[$row->rev_id] = $rev_id;
				}
			}

			$rev_id = $row->rev_id;
		}

		$batch->execute();
		$this->mResult->seek( 0 );

		return '';
	}

	function formatRow( $row ) {
		return $this->mForm->formatRevisionRow( $row );
	}

	function getQueryInfo() {
		$conds = $this->mConds;
		$conds['rev_page'] = $this->articleID;
		$conds[] = "rev_timestamp < " . $this->mDb->addQuotes( $this->maxTimestamp );

		$revQuery = Revision::getQueryInfo( [ 'page', 'user' ] );
		return [
			'tables' => $revQuery['tables'],
			'fields' => $revQuery['fields'],
			'conds' => $conds,
			'join_conds' => $revQuery['joins']
		];
	}

	function getIndexField() {
		return 'rev_timestamp';
	}
}
