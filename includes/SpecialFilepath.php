<?php

function wfSpecialFilepath( $par ) {
	global $wgRequest, $wgOut;

	$file = isset( $par ) ? $par : $wgRequest->getText( 'file' );

	$title = Title::newFromText( $file, NS_IMAGE );

	if ( ! $title instanceof Title || $title->getNamespace() != NS_IMAGE ) {
		$cform = new FilepathForm( $title );
		$cform->execute();
	} else {
		$file = wfFindFile( $title );
		if ( $file && $file->exists() ) {
			$wgOut->redirect( $file->getURL() );
		} else {
			$wgOut->setStatusCode( 404 );
			$cform = new FilepathForm( $title );
			$cform->execute();
		}
	}
}

class FilepathForm {
	var $mTitle;

	function FilepathForm( &$title ) {
		$this->mTitle =& $title;
	}

	function execute() {
		global $wgOut, $wgTitle, $wgScript;

		$wgOut->addHTML(
			wfElement( 'form',
				array(
					'id' => 'specialfilepath',
					'method' => 'get',
					'action' => $wgScript,
				),
				null
			) .
				wfHidden( 'title', $wgTitle->getPrefixedText() ) .
				wfOpenElement( 'label' ) .
					wfMsgHtml( 'filepath-page' ) .
					' ' .
					wfElement( 'input',
						array(
							'type' => 'text',
							'size' => 25,
							'name' => 'file',
							'value' => is_object( $this->mTitle ) ? $this->mTitle->getText() : ''
						),
						''
					) .
					' ' .
					wfElement( 'input',
						array(
							'type' => 'submit',
							'value' => wfMsgHtml( 'filepath-submit' )
						),
						''
					) .
				wfCloseElement( 'label' ) .
			wfCloseElement( 'form' )
		);
	}
}
