/** @interface MwApi */

let /** @type {MwApi} */ api;
const debounce = require( /** @type {string} */ ( 'mediawiki.util' ) ).debounce;

/**
 * Saves preference to user preferences and/or cookies.
 *
 * @param {string} feature
 * @param {boolean} enabled
 */
function save( feature, enabled ) {
	if ( mw.user.isAnon() ) {
		switch ( feature ) {
			case 'limited-width':
				if ( enabled ) {
					mw.cookie.set( 'mwclientprefs', null );
				} else {
					mw.cookie.set( 'mwclientprefs', 'vector-feature-limited-width' );
				}
				break;
			default:
				// not a supported anonymous preference
				break;
		}
	} else {
		debounce( function () {
			api = api || new mw.Api();
			api.saveOption( 'vector-' + feature, enabled ? 1 : 0 );
		}, 500 )();
	}
}

/**
 *
 * @param {string} name feature name
 * @param {boolean} [override] option to force enabled or disabled state.
 *
 * @return {boolean} The new feature state (false=disabled, true=enabled).
 * @throws {Error} if unknown feature toggled.
 */
function toggleDocClasses( name, override ) {
	const featureClassEnabled = `vector-feature-${name}-enabled`,
		classList = document.documentElement.classList,
		featureClassDisabled = `vector-feature-${name}-disabled`;

	if ( classList.contains( featureClassDisabled ) || override === true ) {
		classList.remove( featureClassDisabled );
		classList.add( featureClassEnabled );
		return true;
	} else if ( classList.contains( featureClassEnabled ) || override === false ) {
		classList.add( featureClassDisabled );
		classList.remove( featureClassEnabled );
		return false;
	} else {
		throw new Error( `Attempt to toggle unknown feature: ${name}` );
	}
}

/**
 * @param {string} name
 * @throws {Error} if unknown feature toggled.
 */
function toggle( name ) {
	const featureState = toggleDocClasses( name );
	save( name, featureState );
}

/**
 * Checks if the feature is enabled.
 *
 * @param {string} name
 * @return {boolean}
 */
function isEnabled( name ) {
	return document.documentElement.classList.contains( getClass( name, true ) );
}

/**
 * Get name of feature class.
 *
 * @param {string} name
 * @param {boolean} featureEnabled
 * @return {string}
 */
function getClass( name, featureEnabled ) {
	if ( featureEnabled ) {
		return `vector-feature-${name}-enabled`;
	} else {
		return `vector-feature-${name}-disabled`;
	}
}

module.exports = { getClass, isEnabled, toggle, toggleDocClasses };
