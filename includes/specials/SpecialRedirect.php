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
 * @since 1.22
 */
class SpecialRedirect extends FormSpecialPage {

	/**
	 * The type of the redirect (user/file/revision)
	 *
	 * @var string $mType
	 * @example 'user'
	 */
	protected $mType;

	/**
	 * The identifier/value for the redirect (which id, which file)
	 *
	 * @var string $mValue
	 * @example '42'
	 */
	protected $mValue;

	function __construct() {
		parent::__construct( 'Redirect' );
		$this->mType = null;
		$this->mValue = null;
	}

	/**
	 * Set $mType and $mValue based on parsed value of $subpage.
	 * @param string $subpage
	 */
	function setParameter( $subpage ) {
		// parse $subpage to pull out the parts
		$parts = explode( '/', $subpage, 2 );
		$this->mType = count( $parts ) > 0 ? $parts[0] : null;
		$this->mValue = count( $parts ) > 1 ? $parts[1] : null;
	}

	/**
	 * Handle Special:Redirect/user/xxxx (by redirecting to User:YYYY)
	 *
	 * @return string|null Url to redirect to, or null if $mValue is invalid.
	 */
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

	/**
	 * Handle Special:Redirect/file/xxxx
	 *
	 * @return string|null Url to redirect to, or null if $mValue is not found.
	 */
	function dispatchFile() {
		$title = Title::makeTitleSafe( NS_FILE, $this->mValue );

		if ( !$title instanceof Title ) {
			return null;
		}
		$file = wfFindFile( $title );

		if ( !$file || !$file->exists() ) {
			return null;
		}
		// Default behavior: Use the direct link to the file.
		$url = $file->getUrl();
		$request = $this->getRequest();
		$width = $request->getInt( 'width', -1 );
		$height = $request->getInt( 'height', -1 );

		// If a width is requested...
		if ( $width != -1 ) {
			$mto = $file->transform( [ 'width' => $width, 'height' => $height ] );
			// ... and we can
			if ( $mto && !$mto->isError() ) {
				// ... change the URL to point to a thumbnail.
				$url = $mto->getUrl();
			}
		}

		return $url;
	}

	/**
	 * Handle Special:Redirect/revision/xxx
	 * (by redirecting to index.php?oldid=xxx)
	 *
	 * @return string|null Url to redirect to, or null if $mValue is invalid.
	 */
	function dispatchRevision() {
		$oldid = $this->mValue;
		if ( !ctype_digit( $oldid ) ) {
			return null;
		}
		$oldid = (int)$oldid;
		if ( $oldid === 0 ) {
			return null;
		}

		return wfAppendQuery( wfScript( 'index' ), [
			'oldid' => $oldid
		] );
	}

	/**
	 * Handle Special:Redirect/page/xxx (by redirecting to index.php?curid=xxx)
	 *
	 * @return string|null Url to redirect to, or null if $mValue is invalid.
	 */
	function dispatchPage() {
		$curid = $this->mValue;
		if ( !ctype_digit( $curid ) ) {
			return null;
		}
		$curid = (int)$curid;
		if ( $curid === 0 ) {
			return null;
		}

		return wfAppendQuery( wfScript( 'index' ), [
			'curid' => $curid
		] );
	}

	/**
	 * Handle Special:Redirect/logid/xxx
	 * (by redirecting to index.php?title=Special:Log)
	 *
	 * @since 1.27
	 * @return string|null Url to redirect to, or null if $mValue is invalid.
	 */
	function dispatchLog() {
		$logid = $this->mValue;
		if ( !ctype_digit( $logid ) ) {
			return null;
		}
		$logid = (int)$logid;
		if ( $logid === 0 ) {
			return null;
		}

		$logparams = [
			'log_id',
			'log_timestamp',
			'log_type',
			'log_user_text',
		];

		$dbr = wfGetDB( DB_REPLICA );

		// Gets the nested SQL statement which
		// returns timestamp of the log with the given log ID
		$inner = $dbr->selectSQLText(
			'logging',
			[ 'log_timestamp' ],
			[ 'log_id' => $logid ]
		);

		// Returns all fields mentioned in $logparams of the logs
		// with the same timestamp as the one returned by the statement above
		$logsSameTimestamps = $dbr->select(
			'logging',
			$logparams,
			[ "log_timestamp = ($inner)" ]
		);
		if ( $logsSameTimestamps->numRows() === 0 ) {
			return null;
		}

		// Stores the row with the same log ID as the one given
		$rowMain = [];
		foreach ( $logsSameTimestamps as $row ) {
			if ( (int)$row->log_id === $logid ) {
				$rowMain = $row;
			}
		}

		array_shift( $logparams );

		// Stores all the rows with the same values in each column
		// as $rowMain
		foreach ( $logparams as $cond ) {
			$matchedRows = [];
			foreach ( $logsSameTimestamps as $row ) {
				if ( $row->$cond === $rowMain->$cond ) {
					$matchedRows[] = $row;
				}
			}
			if ( count( $matchedRows ) === 1 ) {
				break;
			}
			$logsSameTimestamps = $matchedRows;
		}
		$query = [ 'title' => 'Special:Log', 'limit' => count( $matchedRows ) ];

		// A map of database field names from table 'logging' to the values of $logparams
		$keys = [
			'log_timestamp' => 'offset',
			'log_type' => 'type',
			'log_user_text' => 'user'
		];

		foreach ( $logparams as $logKey ) {
			$query[$keys[$logKey]] = $matchedRows[0]->$logKey;
		}
		$query['offset'] = $query['offset'] + 1;
		$url = $query;

		return wfAppendQuery( wfScript( 'index' ), $url );
	}

	/**
	 * Use appropriate dispatch* method to obtain a redirection URL,
	 * and either: redirect, set a 404 error code and error message,
	 * or do nothing (if $mValue wasn't set) allowing the form to be
	 * displayed.
	 *
	 * @return bool True if a redirect was successfully handled.
	 */
	function dispatch() {
		// the various namespaces supported by Special:Redirect
		switch ( $this->mType ) {
			case 'user':
				$url = $this->dispatchUser();
				break;
			case 'file':
				$url = $this->dispatchFile();
				break;
			case 'revision':
				$url = $this->dispatchRevision();
				break;
			case 'page':
				$url = $this->dispatchPage();
				break;
			case 'logid':
				$url = $this->dispatchLog();
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
			// Message: redirect-not-exists
			$msg = $this->getMessagePrefix() . '-not-exists';

			return Status::newFatal( $msg );
		}

		return false;
	}

	protected function getFormFields() {
		$mp = $this->getMessagePrefix();
		$ns = [
			// subpage => message
			// Messages: redirect-user, redirect-page, redirect-revision,
			// redirect-file, redirect-logid
			'user' => $mp . '-user',
			'page' => $mp . '-page',
			'revision' => $mp . '-revision',
			'file' => $mp . '-file',
			'logid' => $mp . '-logid',
		];
		$a = [];
		$a['type'] = [
			'type' => 'select',
			'label-message' => $mp . '-lookup', // Message: redirect-lookup
			'options' => [],
			'default' => current( array_keys( $ns ) ),
		];
		foreach ( $ns as $n => $m ) {
			$m = $this->msg( $m )->text();
			$a['type']['options'][$m] = $n;
		}
		$a['value'] = [
			'type' => 'text',
			'label-message' => $mp . '-value' // Message: redirect-value
		];
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
		// tweak label on submit button
		// Message: redirect-submit
		$form->setSubmitTextMsg( $this->getMessagePrefix() . '-submit' );
		/* submit form every time */
		$form->setMethod( 'get' );
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	/**
	 * Return an array of subpages that this special page will accept.
	 *
	 * @return string[] subpages
	 */
	protected function getSubpagesForPrefixSearch() {
		return [
			'file',
			'page',
			'revision',
			'user',
			'logid',
		];
	}

	/**
	 * @return bool
	 */
	public function requiresWrite() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function requiresUnblock() {
		return false;
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
