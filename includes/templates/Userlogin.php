<?php
/**
 * Html form for user login.
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
 * @ingroup Templates
 */

class UserloginTemplate extends QuickTemplate {

	/**
	 * Get a Message object with its context set.
	 *
	 * @param string $name Message name
	 * @param mixed $params... Message parameters
	 * @return Message
	 */
	public function msg( $name/*, $params */ ) {
		$args = func_get_args();
		return call_user_func_array( array( $this->getSkin(), 'msg' ), $args );
	}

	function execute() {
		// Eh?
		echo '<div id="userloginprompt">' . $this->msg( 'loginprompt' )->parse() . '</div>';

		if ( $this->haveData( 'languages' ) ) {
			echo '<div id="languagelinks">'; // Meh
			echo new OOUI\LabelWidget( array(
				'label' => new OOUI\HtmlSnippet( $this->data['languages'] ),
			) );
			echo '</div>';
		}


		$form = new OOUI\FormLayout( array(
			'method' => 'post',
			'action' => $this->data['action'],
		) );
		$form->setAttributes( array( 'id' => 'userloginForm' ) );
		$items = array();

		if ( $this->data['loggedin'] ) {
			$items[] = new OOUI\FieldLayout(
				new OOUI\LabelWidget( array(
					'label' => new OOUI\HtmlSnippet(
						$this->msg( 'userlogin-loggedin' )->params( $this->data['loggedinuser'] )->parse()
					),
					'classes' => array( 'warningbox' ),
				)
			) );
		}

		// Extensions such as ConfirmEdit add form HTML here…
		// This is terrible, we shouldn't be stuff random HTML into FieldsetLayouts, which is why
		// this is so weird.
		$items[] = new OOUI\FieldLayout(
			new OOUI\Widget( array(
				'content' => new OOUI\HtmlSnippet(
					'<section class="mw-form-header">' . $this->data['header'] . '</section>'
				),
			) )
		);

		if ( $this->data['message'] ) {
			$items[] = new OOUI\FieldLayout(
				new OOUI\LabelWidget( array(
					'label' => new OOUI\HtmlSnippet(
						// Damned if I know why we're going through these contortions
						( $this->data['messagetype'] == 'error'
							? '<strong>' . $this->msg( 'loginerror' )->escaped() . '</strong><br />'
							: '' ) .
						$this->data['message']
					),
					'classes' => array( $this->data['messagetype'] . 'box' ),
				) )
			);
		}

		if ( $this->data['secureLoginUrl'] ) {
			$items[] = new OOUI\FieldLayout(
				new OOUI\ButtonWidget( array(
					'label' => $this->msg( 'userlogin-signwithsecure' )->text(),
					'href' => $this->data['secureLoginUrl'],
					'framed' => false,
					'classes' => array( 'mw-ui-flush-right', 'mw-secure' ),
				) )
			);
		}

		// The actual form fields at last

		$items[] = new OOUI\FieldLayout(
			new OOUI\TextInputWidget( array(
				'name' => 'wpName',
				'value' => $this->data['name'],
				'classes' => array( 'loginText' ),
				/*
					// Adding these attributes would suck a lot less if we supported them as config option,
					// or had a catch-all 'attributes' option (like 'classes' and 'content' right now).

					'id' => 'wpName1',
					'tabindex' => '1',
					// 'required' is blacklisted for now in Html.php due to browser issues.
					// Keeping here in case that changes.
					'required' => true,
					// Set focus to this field if it's blank.
					'autofocus' => !$this->data['name'],
					'placeholder' => $this->msg( 'userlogin-yourname-ph' )->text(),
				*/
			) ),
			array(
				'label' => $this->msg( 'userlogin-yourname' )->text(),
				'align' => 'top',
			)
		);

		$items[] = new OOUI\FieldLayout(
			new OOUI\TextInputWidget( array(
				'name' => 'wpPassword',
				'type' => 'password',
				'classes' => array( 'loginPassword' ),
				/*
					'id' => 'wpPassword1',
					'tabindex' => '2',
					// Set focus to this field if username is filled in.
					'autofocus' => (bool)$this->data['name'],
					'placeholder' => $this->msg( 'userlogin-yourpassword-ph' )->text(),
				*/
			) ),
			array(
				'label' => $this->msg( 'userlogin-yourpassword' )->text(),
				'align' => 'top',
			)
		);

		if ( isset( $this->data['usedomain'] ) && $this->data['usedomain'] ) {
			// Aww man, we have no PHP support for any sort of SelectWidget? At all?

			$select = new XmlSelect( 'wpDomain', false, $this->data['domain'] );
			$select->setAttribute( 'tabindex', 3 );
			foreach ( $this->data['domainnames'] as $dom ) {
				$select->addOption( $dom );
			}

			$items[] = new OOUI\FieldLayout(
				new OOUI\Widget( array(
					'content' => new OOUI\HtmlSnippet(
						'<div id="mw-user-domain-section">' .
							'<label for="wpDomain">' . $this->msg( 'yourdomainname' )->escaped() . '</label>' .
							$select->getHTML() .
						'</div>'
					),
				) )
			);
		}

		// I am weeping
		if ( $this->haveData( 'extrafields' ) ) {
			$items[] = new OOUI\FieldLayout(
				new OOUI\Widget( array(
					'content' => new OOUI\HtmlSnippet( $this->data['extrafields'] ),
				) )
			);
		}

		if ( $this->data['canremember'] ) {
			global $wgCookieExpiration;
			$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
			$items[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( array(
					'name' => 'wpRemember',
					// id="wpRemember"
					// tabindex="4"
					'value' => '1',
					'selected' => (bool)$this->data['remember'],
				) ),
				array(
					'label' => $this->msg( 'userlogin-remembermypassword' )->numParams( $expirationDays )->text(),
					'align' => 'inline',
				)
			);
		}

		$items[] = new OOUI\FieldLayout(
			new OOUI\ButtonInputWidget( array(
				'label' => $this->msg( 'pt-login-button' )->text(),
				'name' => 'wpLoginAttempt',
				'value' => '1',
				'type' => 'submit',
				// id="wpLoginAttempt"
				// tabindex="6"
				'value' => '1',
				'selected' => (bool)$this->data['remember'],
				'flags' => array( 'primary', 'constructive' ),
			) )
		);

		// Bottom stuff

		$items[] = new OOUI\FieldLayout(
			new OOUI\ButtonWidget( array(
				'label' => $this->msg( 'userlogin-helplink2' )->text(),
				'href' => Skin::makeInternalOrExternalUrl(
					$this->msg( 'helplogin-url' )->inContentLanguage()->text()
				),
				'framed' => false,
				'classes' => array( 'mw-form-related-link-container' ),
			) )
		);

		if ( $this->data['useemail'] && $this->data['canreset'] && $this->data['resetlink'] === true ) {
			$items[] = new OOUI\FieldLayout(
				new OOUI\ButtonWidget( array(
					'label' => $this->msg( 'userlogin-resetpassword-link' )->text(),
					'href' => SpecialPage::getTitleFor( 'PasswordReset' )->getLinkURL(),
					'framed' => false,
					'classes' => array( 'mw-form-related-link-container' ),
				) )
			);
		}

		if ( $this->haveData( 'createOrLoginHref' ) ) {
			if ( $this->data['loggedin'] ) {
				$items[] = new OOUI\FieldLayout(
					new OOUI\ButtonWidget( array(
						// tabindex="7"
						'label' => $this->msg( 'userlogin-createanother' )->text(),
						'href' => $this->data['createOrLoginHref'],
						'framed' => false,
						'classes' => array( 'mw-form-related-link-container' ),
					) )
				);
			} else {
				$items[] = new OOUI\FieldLayout(
					new OOUI\ButtonWidget( array(
						// tabindex="7"
						'label' => $this->msg( 'userlogin-joinproject' )->text(),
						'href' => $this->data['createOrLoginHref'],
						'flags' => array( 'primary', 'progressive' ),
						'classes' => array( 'mw-form-related-link-container' ),
					) ),
					array(
						// id="mw-createaccount-cta"
						'label' => $this->msg( 'userlogin-noaccount' )->text(),
						'align' => 'top',
					)
				);
			}
		}

		$form->appendContent(
			new OOUI\FieldsetLayout( array(
				'label' => 'FormLayout that is a real HTML form',
				'items' => $items,
			) )
		);

		// Hidden fields
		$fields = '';
		if ( $this->haveData( 'uselang' ) ) {
			$fields .= Html::hidden( 'uselang', $this->data['uselang'] );
		}
		if ( $this->haveData( 'token' ) ) {
			$fields .= Html::hidden( 'wpLoginToken', $this->data['token'] );
		}
		if ( $this->data['cansecurelogin'] ) {
			$fields .= Html::hidden( 'wpForceHttps', $this->data['stickhttps'] );
		}
		if ( $this->data['cansecurelogin'] && $this->haveData( 'fromhttp' )) {
			$fields .= Html::hidden( 'wpFromhttp', $this->data['fromhttp'] );
		}
		$form->appendContent( new OOUI\HtmlSnippet( $fields ) );

		echo $form;
	}
}
