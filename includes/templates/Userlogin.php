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

/**
 * @defgroup Templates Templates
 */

/**
 * HTML template for Special:Userlogin form
 * @ingroup Templates
 */
class UserloginTemplate extends QuickTemplate {
	function execute() {
		if( $this->data['message'] ) {
?>
	<div class="<?php $this->text('messagetype') ?>box">
		<?php if ( $this->data['messagetype'] == 'error' ) { ?>
			<strong><?php $this->msg( 'loginerror' )?></strong><br />
		<?php } ?>
		<?php $this->html('message') ?>
	</div>
	<div class="visualClear"></div>
<?php } ?>

<!-- No idea what to do with this - Munaf -->
<!-- <div id="loginstart"> -->
	<?php /* $this->msgWiki( 'loginstart' );*/  ?>
<!-- </div> -->

<!-- Start of new login form -->
<div id="userloginForm">
    <form name="userlogin" method="post" action="<?php $this->text('action') ?>">
    	<?php /* $this->html('header'); */ /* pre-table point for form plugins... */ ?>
    	<?php /* if( $this->haveData( 'languages' ) ) { */ ?> 
    	<!--<div id="languagelinks"><p>--><?php /* $this->html( 'languages' );*/ ?>
    	<!-- </p></div>--> <?php /*}*/ ?>
        <ul class="mw-form-list">
            <li>
                <label for="wpName1" class="pos-above">
                    <?php $this->msg( 'yourname' ) ?>
                    <a href="#" class="pull-right">
                    	<img src="resources/mediawiki.special/images/icon-lock.png"/>&nbsp;
                    	<?php $this->msg( 'signwithsecure' ) ?>
                    </a>
                </label>
                <input id="wpName1" type="text" placeholder="<?php $this->msg('yournamehelp') ?>" tabindex="1" autofocus />
            </li>
            <li>
                <label for="wpPassword1" class="pos-above"><?php $this->msg( 'yourpassword' ) ?></label>
                <input id="wpPassword1" type="password" placeholder="<?php $this->msg( 'yourpasswordhelp' ) ?>" tabindex="2" />
            </li>
            <li class="pull-up">
                <input id="wpRemember" type="checkbox" value="1" tabindex="3" />
                <label for="wpRemember">
                    <?php $this->msg( 'remembermypassword' ) ?>
                    <a href="#" class="pull-right"><?php $this->msg( 'userlogin-resetlink' ) ?></a>
                </label>
            </li>
            <li>
                <button id="wpLoginAttempt" class="mw-btn blue xl" tabindex="4"><?php wfMessage( 'login' )->text() ?></button>
            </li>
            <li class="center-text">
                <label><a href="#" tabindex="5"><?php $this->msg( 'helplogin-link' ) ?></a></label>
            </li>
        </ul>
        <?php if( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
		<?php if( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpLoginToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
    </form>
</div>
<div id="createaccount-cta">
    <h3><?php $this->msg( 'donthaveaccount' ) ?><button class="mw-btn green"><?php $this->msg( 'joinwikipedia' ) ?></button></h3> 
</div>
<div id="loginend"></div>
<?php

	}
}
