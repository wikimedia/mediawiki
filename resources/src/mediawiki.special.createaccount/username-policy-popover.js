/**
 * Vue + Codex username policy popover.
 *
 * @module mediawiki.special.createaccount
 */
'use strict';

const Vue = require( 'vue' );
const UsernamePolicyPopover = require( './UsernamePolicyPopover.vue' );

/**
 * Mount the username policy popover (Codex bottom sheet) next to the “Choose carefully” trigger.
 *
 * @param {HTMLElement} trigger
 */
function mountUsernamePolicyPopover( trigger ) {
	const mountPoint = document.body.appendChild( document.createElement( 'div' ) );
	Vue.createMwApp( UsernamePolicyPopover, {
		triggerElement: trigger
	} ).mount( mountPoint );
}

module.exports = mountUsernamePolicyPopover;
