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
class ProtectedTitlesPager extends AlphabeticPager {

	public $mForm, $mConds;

	function __construct( $form, $conds, $type, $level, $namespace,
		$sizetype = '', $size = 0
	) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->level = $level;
		$this->namespace = $namespace;
		$this->size = intval( $size );
		parent::__construct( $form->getContext() );
	}

	function getStartBody() {
		# Do a link batch query
		$this->mResult->seek( 0 );
		$lb = new LinkBatch;

		foreach ( $this->mResult as $row ) {
			$lb->add( $row->pt_namespace, $row->pt_title );
		}

		$lb->execute();

		return '';
	}

	/**
	 * @return Title
	 */
	function getTitle() {
		return $this->mForm->getTitle();
	}

	function formatRow( $row ) {
		return $this->mForm->formatRow( $row );
	}

	/**
	 * @return array
	 */
	function getQueryInfo() {
		$conds = $this->mConds;
		$conds[] = 'pt_expiry > ' . $this->mDb->addQuotes( $this->mDb->timestamp() ) .
			' OR pt_expiry IS NULL';
		if ( $this->level ) {
			$conds['pt_create_perm'] = $this->level;
		}

		if ( !is_null( $this->namespace ) ) {
			$conds[] = 'pt_namespace=' . $this->mDb->addQuotes( $this->namespace );
		}

		return [
			'tables' => 'protected_titles',
			'fields' => [ 'pt_namespace', 'pt_title', 'pt_create_perm',
				'pt_expiry', 'pt_timestamp' ],
			'conds' => $conds
		];
	}

	function getIndexField() {
		return 'pt_timestamp';
	}
}
