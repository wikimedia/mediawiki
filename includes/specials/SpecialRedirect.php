<?php
/**
 * Implements Special:Redirect
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
 * A special page that redirects to: the user for a numeric user id,
 * the file for a given filename, or the page for a given revisionid.
 *
 * @ingroup SpecialPage
 */
class SpecialRedirect extends FormSpecialPage {
	// the namespace of the redirect (user/revision/file)
	protected $mNamespace;
	// type of the redirect (by-id, by-name)
	protected $mType;
	// the identifier for the redirect
	protected $mValue;

	function __construct() {
		parent::__construct( 'Redirect' );
		$this->mNamespace = null;
		$this->mType = null;
		$this->mValue = null;
	}

	function setParameter( $subpage ) {
		// parse $subpage to pull out the parts
		$parts = explode( '/', $subpage, 3 );
		$this->mNamespace = $parts[0];
		$this->mType = $parts[1];
		$this->mValue = $parts[2];
	}

	// handle Special:Redirect/user/by-id/xxxx
	function dispatchUser() {
		if ( $this->mType !== 'by-id' ||
		     !is_numeric( $this->mValue ) ) {
			return null;
		}
		$username = User::whoIs( ( int )$this->mValue );
		if ( !$username ) {
			return null;
		}
		$userpage = Title::makeTitle( NS_USER, $username );
		return $userpage->getFullURL( '', false, PROTO_CURRENT );
	}

	// handle Special:Redirect/file/by-name/xxxx
	function dispatchFile() {
		if ( $this->mType !== 'by-name' ) {
			return null;
		}
		$title = Title::newFromText( $this->mValue, NS_FILE );

		if ( ! $title instanceof Title || $title->getNamespace() != NS_FILE ) {
			return null;
		}
		$file = wfFindFile( $title );

		if ( !$file || !$file->exists() ) {
			return null;
		}
		// Default behavior: Use the direct link to the file.
		$url = $file->getURL();
		$request = $this->getRequest();
		$width = $request->getInt( 'width', -1 );
		$height = $request->getInt( 'height', -1 );

		// If a width is requested...
		if ( $width != -1 ) {
			$mto = $file->transform( array( 'width' => $width, 'height' => $height ) );
			// ... and we can
			if ( $mto && !$mto->isError() ) {
				// ... change the URL to point to a thumbnail.
				$url = $mto->getURL();
			}
		}
		return $url;
	}

	// handle Special:Redirect/page/by-permalink/xxx
	//    and Special:Redirect/page/by-revision/xxx
	function dispatchPageByRevision() {
		$oldid = $this->mValue;
		if ( !is_numeric( $oldid ) ) {
			return null;
		}
		$oldid = ( int )$oldid;
		if ( $oldid === 0 ) {
			return null;
		}
		return wfAppendQuery( wfScript( 'index' ), array(
			'oldid' => $oldid
		) );
	}

	function dispatchPage() {
		switch( $this->mType ) {
		// permalink and revision id are actually the same!
		case 'by-permalink':
		case 'by-revision':
			return $this->dispatchPageByRevision();
		default:
			return null;
		}
	}

	function dispatch() {
		// the various namespaces supported by Special:Redirect
		switch( $this->mNamespace ) {
		case 'user':
			$url = $this->dispatchUser();
			break;
		case 'file':
			$url = $this->dispatchFile();
			break;
		case 'page':
			$url = $this->dispatchPage();
			break;
		default:
			$url = null;
			break;
		}
		if ( $url ) {
			$this->getOutput()->redirect( $url );
			return true;
		}
		return false;
	}

	protected function getFormFields() {
		$ns = array(
			"user/by-id",
			"page/by-permalink",
			"page/by-revision",
			"file/by-name"
		);
		$a = array();
		$a['namespace'] = array(
			'type' => 'select',
			'label-message' => $this->getMessagePrefix() . '-lookup',
			'options' => array()
		);
		foreach( $ns as $n ) {
			$m = $this->getMessagePrefix() . '-' . str_replace( '/', '-', $n );
			$m = $this->msg( $m )->text();
			$a['namespace']['options'][$m] = $n;
		}
		$a['value'] = array(
			'type' => 'text',
			'label-message' => $this->getMessagePrefix() . '-value'
		);
		return $a;
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		// This will throw exceptions if there's a problem
		$this->checkExecutePermissions( $this->getUser() );
		// parse $par to pull out the user/revision/file part.
		$request = $this->getRequest();
		$this->setParameter( $par ?: $request->getText( 'to' ) );

		if ( !$this->dispatch() ) {
			if ( $this->mNamespace ) {
				$this->getOutput()->setStatusCode( 404 );
			}
			$form = $this->getForm();
			if ( $form->show() ) {
				$this->onSuccess();
			}
		}
	}
	public function onSubmit( array $data ) {
		$this->setParameter( $data['namespace'] . '/' . $data['value'] );
		return $this->dispatch();
	}

	public function onSuccess() {
		/* do nothing, we redirect in $this->dispatch if successful. */
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setSubmitTextMsg( $this->getMessagePrefix() . '-submit' );
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
