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

use MediaWiki\MediaWikiServices;

/**
 * Builds the image revision log shown on image pages
 *
 * @ingroup Media
 */
class ImageHistoryList extends ContextSource {

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var File
	 */
	protected $img;

	/**
	 * @var ImagePage
	 */
	protected $imagePage;

	/**
	 * @var File
	 */
	protected $current;

	protected $repo, $showThumb;
	protected $preventClickjacking = false;

	/**
	 * @param ImagePage $imagePage
	 */
	public function __construct( $imagePage ) {
		$context = $imagePage->getContext();
		$this->current = $imagePage->getPage()->getFile();
		$this->img = $imagePage->getDisplayedFile();
		$this->title = $imagePage->getTitle();
		$this->imagePage = $imagePage;
		$this->showThumb = $context->getConfig()->get( 'ShowArchiveThumbnails' ) &&
			$this->img->canRender();
		$this->setContext( $context );
	}

	/**
	 * @return ImagePage
	 */
	public function getImagePage() {
		return $this->imagePage;
	}

	/**
	 * @return File
	 */
	public function getFile() {
		return $this->img;
	}

	/**
	 * @param string $navLinks
	 * @return string
	 */
	public function beginImageHistoryList( $navLinks = '' ) {
		return Xml::element( 'h2', [ 'id' => 'filehistory' ], $this->msg( 'filehist' )->text() )
		. "\n"
		. "<div id=\"mw-imagepage-section-filehistory\">\n"
		. $this->msg( 'filehist-help' )->parseAsBlock()
		. $navLinks . "\n"
		. Xml::openElement( 'table', [ 'class' => 'wikitable filehistory' ] ) . "\n"
		. '<tr><th></th>'
		. ( $this->current->isLocal()
		&& ( MediaWikiServices::getInstance()
				->getPermissionManager()
				->userHasAnyRight( $this->getUser(), 'delete', 'deletedhistory' ) ) ? '<th></th>' : '' )
		. '<th>' . $this->msg( 'filehist-datetime' )->escaped() . '</th>'
		. ( $this->showThumb ? '<th>' . $this->msg( 'filehist-thumb' )->escaped() . '</th>' : '' )
		. '<th>' . $this->msg( 'filehist-dimensions' )->escaped() . '</th>'
		. '<th>' . $this->msg( 'filehist-user' )->escaped() . '</th>'
		. '<th>' . $this->msg( 'filehist-comment' )->escaped() . '</th>'
		. "</tr>\n";
	}

	/**
	 * @param string $navLinks
	 * @return string
	 */
	public function endImageHistoryList( $navLinks = '' ) {
		return "</table>\n$navLinks\n</div>\n";
	}

	/**
	 * @param bool $iscur
	 * @param File $file
	 * @return string
	 */
	public function imageHistoryLine( $iscur, $file ) {
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$pm = MediaWikiServices::getInstance()->getPermissionManager();
		$timestamp = wfTimestamp( TS_MW, $file->getTimestamp() );
		// @phan-suppress-next-line PhanUndeclaredMethod
		$img = $iscur ? $file->getName() : $file->getArchiveName();
		$userId = $file->getUser( 'id' );
		$userText = $file->getUser( 'text' );
		$description = $file->getDescription( File::FOR_THIS_USER, $user );

		$local = $this->current->isLocal();
		$row = $selected = '';

		// Deletion link
		if ( $local && ( $pm->userHasAnyRight( $user, 'delete', 'deletedhistory' ) ) ) {
			$row .= '<td>';
			# Link to remove from history
			if ( $pm->userHasRight( $user, 'delete' ) ) {
				$q = [ 'action' => 'delete' ];
				if ( !$iscur ) {
					$q['oldimage'] = $img;
				}
				$row .= Linker::linkKnown(
					$this->title,
					$this->msg( $iscur ? 'filehist-deleteall' : 'filehist-deleteone' )->escaped(),
					[], $q
				);
			}
			# Link to hide content. Don't show useless link to people who cannot hide revisions.
			$canHide = $pm->userHasRight( $user, 'deleterevision' );
			if ( $canHide || ( $pm->userHasRight( $user, 'deletedhistory' )
					&& $file->getVisibility() ) ) {
				if ( $pm->userHasRight( $user, 'delete' ) ) {
					$row .= '<br />';
				}
				// If file is top revision or locked from this user, don't link
				if ( $iscur || !$file->userCan( File::DELETED_RESTRICTED, $user ) ) {
					$del = Linker::revDeleteLinkDisabled( $canHide );
				} else {
					list( $ts, ) = explode( '!', $img, 2 );
					$query = [
						'type' => 'oldimage',
						'target' => $this->title->getPrefixedText(),
						'ids' => $ts,
					];
					$del = Linker::revDeleteLink( $query,
						$file->isDeleted( File::DELETED_RESTRICTED ), $canHide );
				}
				$row .= $del;
			}
			$row .= '</td>';
		}

		// Reversion link/current indicator
		$row .= '<td>';
		if ( $iscur ) {
			$row .= $this->msg( 'filehist-current' )->escaped();
		} elseif ( $local && $pm->quickUserCan( 'edit', $user, $this->title )
			&& $pm->quickUserCan( 'upload', $user, $this->title )
		) {
			if ( $file->isDeleted( File::DELETED_FILE ) ) {
				$row .= $this->msg( 'filehist-revert' )->escaped();
			} else {
				$row .= Linker::linkKnown(
					$this->title,
					$this->msg( 'filehist-revert' )->escaped(),
					[],
					[
						'action' => 'revert',
						'oldimage' => $img,
					]
				);
			}
		}
		$row .= '</td>';

		// Date/time and image link
		if ( $file->getTimestamp() === $this->img->getTimestamp() ) {
			$selected = "class='filehistory-selected'";
		}
		$row .= "<td $selected style='white-space: nowrap;'>";
		if ( !$file->userCan( File::DELETED_FILE, $user ) ) {
			# Don't link to unviewable files
			$row .= Html::element( 'span', [ 'class' => 'history-deleted' ],
				$lang->userTimeAndDate( $timestamp, $user )
			);
		} elseif ( $file->isDeleted( File::DELETED_FILE ) ) {
			$timeAndDate = htmlspecialchars( $lang->userTimeAndDate( $timestamp, $user ) );
			if ( $local ) {
				$this->preventClickjacking();
				$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
				# Make a link to review the image
				$url = Linker::linkKnown(
					$revdel,
					$timeAndDate,
					[],
					[
						'target' => $this->title->getPrefixedText(),
						'file' => $img,
						'token' => $user->getEditToken( $img )
					]
				);
			} else {
				$url = $timeAndDate;
			}
			$row .= '<span class="history-deleted">' . $url . '</span>';
		} elseif ( !$file->exists() ) {
			$row .= Html::element( 'span', [ 'class' => 'mw-file-missing' ],
				$lang->userTimeAndDate( $timestamp, $user )
			);
		} else {
			$url = $iscur ? $this->current->getUrl() : $this->current->getArchiveUrl( $img );
			$row .= Xml::element(
				'a',
				[ 'href' => $url ],
				$lang->userTimeAndDate( $timestamp, $user )
			);
		}
		$row .= "</td>";

		// Thumbnail
		if ( $this->showThumb ) {
			$row .= '<td>' . $this->getThumbForLine( $file ) . '</td>';
		}

		// Image dimensions + size
		$row .= '<td>';
		$row .= htmlspecialchars( $file->getDimensionsString() );
		$row .= $this->msg( 'word-separator' )->escaped();
		$row .= '<span style="white-space: nowrap;">';
		$row .= $this->msg( 'parentheses' )->sizeParams( $file->getSize() )->escaped();
		$row .= '</span>';
		$row .= '</td>';

		// Uploading user
		$row .= '<td>';
		// Hide deleted usernames
		if ( $file->isDeleted( File::DELETED_USER ) ) {
			$row .= '<span class="history-deleted">'
				. $this->msg( 'rev-deleted-user' )->escaped() . '</span>';
		} else {
			if ( $local ) {
				$row .= Linker::userLink( $userId, $userText );
				$row .= '<span style="white-space: nowrap;">';
				$row .= Linker::userToolLinks( $userId, $userText );
				$row .= '</span>';
			} else {
				$row .= htmlspecialchars( $userText );
			}
		}
		$row .= '</td>';

		// Don't show deleted descriptions
		if ( $file->isDeleted( File::DELETED_COMMENT ) ) {
			$row .= '<td><span class="history-deleted">' .
				$this->msg( 'rev-deleted-comment' )->escaped() . '</span></td>';
		} else {
			$contLang = MediaWikiServices::getInstance()->getContentLanguage();
			$row .= Html::rawElement(
				'td',
				[ 'dir' => $contLang->getDir() ],
				Linker::formatComment( $description, $this->title )
			);
		}

		$rowClass = null;
		Hooks::run( 'ImagePageFileHistoryLine', [ $this, $file, &$row, &$rowClass ] );
		$classAttr = $rowClass ? " class='$rowClass'" : '';

		return "<tr{$classAttr}>{$row}</tr>\n";
	}

	/**
	 * @param File $file
	 * @return string
	 */
	protected function getThumbForLine( $file ) {
		$lang = $this->getLanguage();
		$user = $this->getUser();
		if ( $file->allowInlineDisplay() && $file->userCan( File::DELETED_FILE, $user )
			&& !$file->isDeleted( File::DELETED_FILE )
		) {
			$params = [
				'width' => '120',
				'height' => '120',
			];
			$timestamp = wfTimestamp( TS_MW, $file->getTimestamp() );

			$thumbnail = $file->transform( $params );
			$options = [
				'alt' => $this->msg( 'filehist-thumbtext',
					$lang->userTimeAndDate( $timestamp, $user ),
					$lang->userDate( $timestamp, $user ),
					$lang->userTime( $timestamp, $user ) )->text(),
				'file-link' => true,
			];

			if ( !$thumbnail ) {
				return $this->msg( 'filehist-nothumb' )->escaped();
			}

			return $thumbnail->toHtml( $options );
		} else {
			return $this->msg( 'filehist-nothumb' )->escaped();
		}
	}

	/**
	 * @param bool $enable
	 */
	protected function preventClickjacking( $enable = true ) {
		$this->preventClickjacking = $enable;
	}

	/**
	 * @return bool
	 */
	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}
}
