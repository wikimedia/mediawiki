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
 */

class EditsummaryLogFormatter extends LogFormatter {
  public function getMessageParameters() {
		$params = parent::getMessageParameters();
		$rev = Revision::newFromId( $params[5] );

		$params[3] = $this->generateComment( $params[3], $rev );
		$params[4] = $this->generateComment( $params[4], $rev );

		return $params;
	}

	/**
	 * @param $comment
	 * @param Revision $revision
	 */
	public function generateComment( $comment, Revision $revision ) {
		$comment = Linker::commentBlock( $params[$param] );
		$element = ltrim( $comment );
		$canView = $this->context->getUser()->isAllowed( 'deletedhistory' );

		if ( $revision->isDeleted( Revision::DELETED_COMMENT ) ) {
			if ( $canView ) {
				$element = $this->styleRestricedElement( $element );
			} else {
				$element = $this->getRestrictedElement( 'rev-deleted-comment' );
			}
		}

		return $this->formatParameterValue( 'raw', $element );
	}

	public function getActionLinks() {
		$params = $this->extractParameters();
		$links = [];

		$links[] = Linker::linkKnown(
			$this->entry->getTarget(),
			$this->msg( 'diff' )->escaped(),
			[],
			[
				'diff' => $params[5],
				'unhide' => 1
			]
		);

		if ( $this->context->getUser()->isAllowed( 'deletedhistory' ) ) {
			$links[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Revisiondelete' ),
				$this->msg( 'revdel-restore' )->escaped(),
				[],
				[
					'target' => $this->entry->getTarget()->getPrefixedText(),
					'type' => 'revision',
					'ids' => $params[5],
				]
			);
		}

		return $this->msg( 'parentheses' )->rawParams(
			$this->context->getLanguage()->pipeList( $links ) )->escaped();
	}
}