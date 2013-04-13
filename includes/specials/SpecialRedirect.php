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
 * the file for a given filename, or the page for a given revision id.
 *
 * @ingroup SpecialPage
 */
class SpecialRedirect extends FormSpecialPage {
	// the type of the redirect (user/file/revision)
	protected $mType;
	// the identifier for the redirect (which id, which name)
	protected $mValue;

	function __construct() {
		parent::__construct( 'Redirect' );
		$this->mType = null;
		$this->mValue = null;
	}

	function setParameter( $subpage ) {
		// parse $subpage to pull out the parts
		$parts = explode( '/', $subpage, 2 );
		$this->mType =    count( $parts ) > 0 ? $parts[0] : null;
		$this->mValue =   count( $parts ) > 1 ? $parts[1] : null;
	}

	// handle Special:Redirect/user/xxxx
	function dispatchUser() {
		if ( !ctype_digit( $this->mValue ) ) {
			return null;
		}
		$user = User::newFromId( (int)$this->mValue );
		$username = $user->getName(); // load User as side-effect
		if ( $user->isAnon() ) {
			return null;
		}
		$userpage = Title::makeTitle( NS_USER, $username );
		return $userpage->getFullURL( '', false, PROTO_CURRENT );
	}

	// handle Special:Redirect/file/xxxx
	function dispatchFile() {
		$title = Title::makeTitleSafe( NS_FILE, $this->mValue );

		if ( ! $title instanceof Title ) {
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

	//    and Special:Redirect/revision/xxx
	function dispatchRevision() {
		$oldid = $this->mValue;
		if ( !ctype_digit( $oldid ) ) {
			return null;
		}
		$oldid = (int)$oldid;
		if ( $oldid === 0 ) {
			return null;
		}
		return wfAppendQuery( wfScript( 'index' ), array(
			'oldid' => $oldid
		) );
	}

	function dispatch() {
		// the various namespaces supported by Special:Redirect
		switch( $this->mType ) {
		case 'user':
			$url = $this->dispatchUser();
			break;
		case 'file':
			$url = $this->dispatchFile();
			break;
		case 'revision':
			$url = $this->dispatchRevision();
			break;
		default:
			$this->getOutput()->setStatusCode( 404 );
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
		$mp = $this->getMessagePrefix();
		$ns = array(
			// subpage => message
			"user"     => $mp . "-user",
			"revision" => $mp . "-revision",
			"file"     => $mp . "-file",
		);
		$a = array();
		$a['type'] = array(
			'type' => 'select',
			'label-message' => $mp . '-lookup',
			'options' => array(),
			'default' => current( array_keys( $ns ) ),
		);
		foreach( $ns as $n => $m ) {
			$m = $this->msg( $m )->text();
			$a['type']['options'][$m] = $n;
		}
		$a['value'] = array(
			'type' => 'text',
			'label-message' => $mp . '-value'
		);
		// set the defaults according to the parsed subpage path
		if ( !empty( $this->mType ) ) {
			$a['type']['default'] = $this->mType;
		}
		if ( !empty( $this->mValue ) ) {
			$a['value']['default'] = $this->mValue;
		}
		return $a;
	}

	public function onSubmit( array $data ) {
		if ( !empty( $data['type'] ) && !empty( $data['value'] ) ) {
			$this->setParameter( $data['type'] . '/' . $data['value'] );
		}
		/* if this returns false, will show the form */
		return $this->dispatch();
	}

	public function onSuccess() {
		/* do nothing, we redirect in $this->dispatch if successful. */
	}

	protected function alterForm( HTMLForm $form ) {
		/* display summary at top of page */
		$this->outputHeader();
		/* tweak label on submit button */
		$form->setSubmitTextMsg( $this->getMessagePrefix() . '-submit' );
		/* submit form every time */
		$form->setMethod( 'get' );
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
