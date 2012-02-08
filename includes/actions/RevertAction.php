<?php
/**
 * File reversion user interface
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 * @ingroup Media
 * @author Alexandre Emsenhuber
 * @author Rob Church <robchur@gmail.com>
 */

/**
 * Dummy class for pages not in NS_FILE
 *
 * @ingroup Actions
 */
class RevertAction extends Action {

	public function getName() {
		return 'revert';
	}

	public function show() {
		$this->getOutput()->showErrorPage( 'nosuchaction', 'nosuchactiontext' );
	}

	public function execute() {}
}

/**
 * Class for pages in NS_FILE
 *
 * @ingroup Actions
 */
class RevertFileAction extends FormAction {
	protected $oldFile;

	public function getName() {
		return 'revert';
	}

	public function getRestriction() {
		return 'upload';
	}

	protected function checkCanExecute( User $user ) {
		parent::checkCanExecute( $user );

		$oldimage = $this->getRequest()->getText( 'oldimage' );
		if ( strlen( $oldimage ) < 16
			|| strpos( $oldimage, '/' ) !== false
			|| strpos( $oldimage, '\\' ) !== false )
		{
			throw new ErrorPageError( 'internalerror', 'unexpected', array( 'oldimage', $oldimage ) );
		}

		$this->oldFile = RepoGroup::singleton()->getLocalRepo()->newFromArchiveName( $this->getTitle(), $oldimage );
		if ( !$this->oldFile->exists() ) {
			throw new ErrorPageError( '', 'filerevert-badversion' );
		}
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegend( wfMsgHtml( 'filerevert-legend' ) );
		$form->setSubmitText( wfMsg( 'filerevert-submit' ) );
		$form->addHiddenField( 'oldimage', $this->getRequest()->getText( 'oldimage' ) );
	}

	protected function getFormFields() {
		global $wgContLang;

		$timestamp = $this->oldFile->getTimestamp();

		return array(
			'intro' => array(
				'type' => 'info',
				'vertical-label' => true,
				'raw' => true,
				'default' => wfMsgExt( 'filerevert-intro', 'parse', $this->getTitle()->getText(),
					$this->getLanguage()->date( $timestamp, true ), $this->getLanguage()->time( $timestamp, true ),
					wfExpandUrl( $this->page->getFile()->getArchiveUrl( $this->getRequest()->getText( 'oldimage' ) ),
						PROTO_CURRENT
				) )
			),
			'comment' => array(
				'type' => 'text',
				'label-message' => 'filerevert-comment',
				'default' => wfMsgForContent( 'filerevert-defaultcomment',
					$wgContLang->date( $timestamp, false, false ), $wgContLang->time( $timestamp, false, false ) ),
			)
		);
	}

	public function onSubmit( $data ) {
		$source = $this->page->getFile()->getArchiveVirtualUrl( $this->getRequest()->getText( 'oldimage' ) );
		$comment = $data['comment'];
		// TODO: Preserve file properties from database instead of reloading from file
		return $this->page->getFile()->upload( $source, $comment, $comment );
	}

	public function onSuccess() {
		$timestamp = $this->oldFile->getTimestamp();
		$this->getOutput()->addHTML( wfMsgExt( 'filerevert-success', 'parse', $this->getTitle()->getText(),
			$this->getLanguage()->date( $timestamp, true ),
			$this->getLanguage()->time( $timestamp, true ),
			wfExpandUrl( $this->page->getFile()->getArchiveUrl( $this->getRequest()->getText( 'oldimage' ) ),
				PROTO_CURRENT
		) ) );
		$this->getOutput()->returnToMain( false, $this->getTitle() );
	}

	protected function getPageTitle() {
		return wfMsg( 'filerevert', $this->getTitle()->getText() );
	}
	
	protected function getDescription() {
		$this->getOutput()->addBacklinkSubtitle( $this->getTitle() );
		return '';
	}
}
