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
		$this->mNamespace = count($parts) > 0 ? $parts[0] : null;
		$this->mType = count($parts) > 1 ? $parts[1] : null;
		$this->mValue = count($parts) > 2 ? $parts[2] : null;
	}

	// handle Special:Redirect/user/by-id/xxxx
	function dispatchUser() {
		if ( $this->mType !== 'by-id' ||
		     !ctype_digit( $this->mValue ) ) {
			return null;
		}
		$username = User::whoIs( (int)$this->mValue );
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
		if ( !ctype_digit( $oldid ) ) {
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
		if ( !is_null( $this->mValue ) ) {
			$this->getOutput()->setStatusCode( 404 );
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
			'options' => array(),
			'default' => $ns[0]
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
		if ( !empty( $this->mNamespace ) && !empty( $this->mType ) ) {
			$a['namespace']['default'] = $this->mNamespace . '/' . $this->mType;
		}
		if ( !empty( $this->mValue ) ) {
			$a['value']['default'] = $this->mValue;
		}
		return $a;
	}

	public function onSubmit( array $data ) {
		if ( !empty( $data['namespace'] ) && !empty( $data['value'] ) ) {
			$this->setParameter( $data['namespace'] . '/' . $data['value'] );
		}
		/* if this returns false, will show the form */
		return $this->dispatch();
	}

	public function onSuccess() {
		/* do nothing, we redirect in $this->dispatch if successful. */
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setSubmitTextMsg( $this->getMessagePrefix() . '-submit' );
		/* submit form every time */
		$form->setMethod( 'get' );
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
