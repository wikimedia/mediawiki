<?php
/**
 * Definition of core ResourceLoader modules.
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
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

return [

	/**
	 * Special modules who have their own classes
	 */
	'startup' => [ 'class' => ResourceLoaderStartUpModule::class ],

	// Scripts managed by the local wiki (stored in the MediaWiki namespace)
	'site' => [ 'class' => ResourceLoaderSiteModule::class ],
	'site.styles' => [ 'class' => ResourceLoaderSiteStylesModule::class ],
	'noscript' => [
		'class' => ResourceLoaderWikiModule::class,
		'styles' => [ 'MediaWiki:Noscript.css' ],
		'group' => 'noscript',
	],
	'filepage' => [
		'class' => ResourceLoaderWikiModule::class,
		'styles' => [ 'MediaWiki:Filepage.css' ],
	],
	'user.groups' => [
		// Merged into 'user' since MediaWiki 1.28 - kept for back-compat
		'dependencies' => 'user',
		'targets' => [ 'desktop', 'mobile' ],
	],

	// Scripts managed by the current user (stored in their user space)
	'user' => [ 'class' => ResourceLoaderUserModule::class ],
	'user.styles' => [ 'class' => ResourceLoaderUserStylesModule::class ],

	// Populate mediawiki.user placeholders with information about the current user
	'user.defaults' => [ 'class' => ResourceLoaderUserDefaultsModule::class ],
	'user.options' => [ 'class' => ResourceLoaderUserOptionsModule::class ],
	'user.tokens' => [ 'class' => ResourceLoaderUserTokensModule::class ],

	/* MediaWiki base skinning modules */

	/**
	 * Common skin styles, grouped into three graded levels.
	 *
	 * Level 1 "elements":
	 *     The base level that only contains the most basic of common skin styles.
	 *     Only styles for single elements are included, no styling for complex structures like the
	 *     TOC is present. This level is for skins that want to implement the entire style of even
	 *     content area structures like the TOC themselves.
	 *
	 * Level 2 "content":
	 *     The most commonly used level for skins implemented from scratch. This level includes all
	 *     the single element styles from "elements" as well as styles for complex structures such
	 *     as the TOC that are output in the content area by MediaWiki rather than the skin.
	 *     Essentially this is the common level that lets skins leave the style of the content area
	 *     as it is normally styled, while leaving the rest of the skin up to the skin
	 *     implementation.
	 *
	 * Level 3 "interface":
	 *     The highest level, this stylesheet contains extra common styles for classes like
	 *     .firstHeading, #contentSub, et cetera which are not outputted by MediaWiki but are common
	 *     to skins like MonoBook, Vector, etc... Essentially this level is for styles that are
	 *     common to MonoBook clones.
	 *
	 * These modules are typically loaded by addModuleStyles(), which has absolutely no concept of
	 * dependency management. As a result they contain duplicate stylesheet references instead of
	 * setting 'dependencies' to the lower level the module is based on. For this reason avoid
	 * including more than one of them into your skin as this will result in duplicate CSS.
	 */
	'mediawiki.skinning.elements' => [
		'styles' => [
			'resources/src/mediawiki.skinning/elements.css' => [ 'media' => 'screen' ],
		],
	],
	'mediawiki.skinning.content' => [
		'styles' => [
			'resources/src/mediawiki.skinning/elements.css' => [ 'media' => 'screen' ],
			'resources/src/mediawiki.skinning/content.css' => [ 'media' => 'screen' ],
		],
	],
	// Used in the web installer. Test it after modifying this definition!
	'mediawiki.skinning.interface' => [
		'class' => ResourceLoaderSkinModule::class,
		'styles' => [
			'resources/src/mediawiki.skinning/elements.css' => [ 'media' => 'screen' ],
			'resources/src/mediawiki.skinning/content.css' => [ 'media' => 'screen' ],
			'resources/src/mediawiki.skinning/interface.css' => [ 'media' => 'screen' ],
		],
	],
	'jquery.makeCollapsible.styles' => [
		'targets' => [ 'desktop', 'mobile' ],
		'class' => ResourceLoaderLessVarFileModule::class,
		'lessMessages' => [
			'collapsible-collapse',
			'collapsible-expand',
		],
		'styles' => [
			'resources/src/jquery/jquery.makeCollapsible.styles.less',
		],
	],

	'mediawiki.skinning.content.parsoid' => [
		// Style Parsoid HTML+RDFa output consistent with wikitext from PHP parser
		// with the interface.css styles; skinStyles should be used if your
		// skin over-rides common content styling.
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.skinning/content.parsoid.less',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.skinning.content.externallinks' => [
		'styles' => [
			'resources/src/mediawiki.skinning/content.externallinks.less' => [ 'media' => 'screen' ],
		],
	],

	/* Base modules */
	// These modules' dependencies MUST also be included in StartUpModule::getBaseModules().
	// These modules' dependencies MUST be dependency-free (having dependencies would cause recursion).

	'jquery' => [
		'scripts' => [
			'resources/lib/jquery/jquery.js',
			'resources/lib/jquery/jquery.migrate.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.base' => [
		'scripts' => [
			// This MUST be kept in sync with maintenance/jsduck/eg-iframe.html
			'resources/src/mediawiki.base/mediawiki.errorLogger.js',
			'resources/src/mediawiki.base/mediawiki.base.js',
		],
		'dependencies' => 'jquery',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.legacy.wikibits' => [
		'scripts' => 'resources/src/mediawiki.legacy/wikibits.js',
		'dependencies' => 'jquery',
		'targets' => [ 'desktop', 'mobile' ],
	],

	/* jQuery Plugins */

	'jquery.accessKeyLabel' => [
		'scripts' => 'resources/src/jquery/jquery.accessKeyLabel.js',
		'dependencies' => [
			'jquery.client',
			'mediawiki.RegExp',
		],
		'messages' => [ 'brackets', 'word-separator' ],
		'targets' => [ 'mobile', 'desktop' ],
	],
	'jquery.async' => [
		'scripts' => 'resources/lib/jquery.async.js',
	],
	'jquery.byteLength' => [
		'scripts' => 'resources/src/jquery/jquery.byteLength.js',
		'deprecated' => 'Use "mediawiki.String" instead.',
		'dependencies' => 'mediawiki.String',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.byteLimit' => [
		'dependencies' => 'jquery.lengthLimit',
		'deprecated' => 'Use "jquery.lengthLimit" instead.',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.checkboxShiftClick' => [
		'scripts' => 'resources/src/jquery/jquery.checkboxShiftClick.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.chosen' => [
		'scripts' => 'resources/lib/jquery.chosen/chosen.jquery.js',
		'styles' => 'resources/lib/jquery.chosen/chosen.css',
	],
	'jquery.client' => [
		'scripts' => 'resources/lib/jquery.client/jquery.client.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.color' => [
		'scripts' => 'resources/src/jquery/jquery.color.js',
		'dependencies' => 'jquery.colorUtil',
	],
	'jquery.colorUtil' => [
		'scripts' => 'resources/src/jquery/jquery.colorUtil.js',
	],
	'jquery.confirmable' => [
		'scripts' => [
			'resources/src/jquery/jquery.confirmable.js',
			'resources/src/jquery/jquery.confirmable.mediawiki.js',
		],
		'messages' => [
			'confirmable-confirm',
			'confirmable-yes',
			'confirmable-no',
			'word-separator',
		],
		'styles' => 'resources/src/jquery/jquery.confirmable.css',
		'dependencies' => 'mediawiki.jqueryMsg',
	],
	'jquery.cookie' => [
		'scripts' => 'resources/lib/jquery.cookie/jquery.cookie.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.form' => [
		'scripts' => 'resources/lib/jquery.form.js',
	],
	'jquery.fullscreen' => [
		'scripts' => 'resources/lib/jquery.fullscreen.js',
	],
	'jquery.getAttrs' => [
		'targets' => [ 'desktop', 'mobile' ],
		'scripts' => 'resources/src/jquery/jquery.getAttrs.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.hidpi' => [
		'deprecated' => 'Use of the srcset polyfill is deprecated since MediaWiki 1.32.0',
		'scripts' => 'resources/src/jquery/jquery.hidpi.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.highlightText' => [
		'scripts' => 'resources/src/jquery/jquery.highlightText.js',
		'dependencies' => [
			'mediawiki.RegExp',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.hoverIntent' => [
		'scripts' => 'resources/lib/jquery.hoverIntent.js',
	],
	'jquery.i18n' => [
		'scripts' => [
			'resources/lib/jquery.i18n/src/jquery.i18n.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.messagestore.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.parser.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.emitter.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.emitter.bidi.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.language.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.fallbacks.js',
		],
		'dependencies' => 'mediawiki.libs.pluralruleparser',
		'languageScripts' => [
			'bs' => 'resources/lib/jquery.i18n/src/languages/bs.js',
			'dsb' => 'resources/lib/jquery.i18n/src/languages/dsb.js',
			'fi' => 'resources/lib/jquery.i18n/src/languages/fi.js',
			'ga' => 'resources/lib/jquery.i18n/src/languages/ga.js',
			'he' => 'resources/lib/jquery.i18n/src/languages/he.js',
			'hsb' => 'resources/lib/jquery.i18n/src/languages/hsb.js',
			'hu' => 'resources/lib/jquery.i18n/src/languages/hu.js',
			'hy' => 'resources/lib/jquery.i18n/src/languages/hy.js',
			'la' => 'resources/lib/jquery.i18n/src/languages/la.js',
			'ml' => 'resources/lib/jquery.i18n/src/languages/ml.js',
			'os' => 'resources/lib/jquery.i18n/src/languages/os.js',
			'ru' => 'resources/lib/jquery.i18n/src/languages/ru.js',
			'sl' => 'resources/lib/jquery.i18n/src/languages/sl.js',
			'uk' => 'resources/lib/jquery.i18n/src/languages/uk.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.lengthLimit' => [
		'scripts' => 'resources/src/jquery/jquery.lengthLimit.js',
		'dependencies' => 'mediawiki.String',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.localize' => [
		'scripts' => 'resources/src/jquery/jquery.localize.js',
		'deprecated' => 'Please use "jquery.i18n" instead.',
	],
	'jquery.makeCollapsible' => [
		'dependencies' => [ 'jquery.makeCollapsible.styles' ],
		'scripts' => 'resources/src/jquery/jquery.makeCollapsible.js',
		'styles' => 'resources/src/jquery/jquery.makeCollapsible.css',
		'messages' => [ 'collapsible-expand', 'collapsible-collapse' ],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.mockjax' => [
		'scripts' => 'resources/lib/jquery.mockjax.js',
	],
	'jquery.mw-jump' => [
		'scripts' => 'resources/src/jquery/jquery.mw-jump.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.qunit' => [
		'scripts' => 'resources/lib/qunitjs/qunit.js',
		'styles' => 'resources/lib/qunitjs/qunit.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.spinner' => [
		'scripts' => 'resources/src/jquery.spinner/spinner.js',
		'styles' => 'resources/src/jquery.spinner/spinner.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.jStorage' => [
		'deprecated' => 'Please use "mediawiki.storage" instead.',
		'scripts' => 'resources/lib/jquery.jStorage.js',
	],
	'jquery.suggestions' => [
		'targets' => [ 'desktop', 'mobile' ],
		'scripts' => 'resources/src/jquery/jquery.suggestions.js',
		'styles' => 'resources/src/jquery/jquery.suggestions.css',
		'dependencies' => 'jquery.highlightText',
	],
	'jquery.tabIndex' => [
		'scripts' => 'resources/src/jquery/jquery.tabIndex.js',
	],
	'jquery.tablesorter' => [
		'targets' => [ 'desktop', 'mobile' ],
		'scripts' => 'resources/src/jquery.tablesorter/jquery.tablesorter.js',
		'styles' => 'resources/src/jquery.tablesorter/jquery.tablesorter.less',
		'messages' => [ 'sort-descending', 'sort-ascending' ],
		'dependencies' => [
			'mediawiki.RegExp',
			'mediawiki.language.months',
		],
	],
	'jquery.textSelection' => [
		'scripts' => 'resources/src/jquery/jquery.textSelection.js',
		'dependencies' => 'jquery.client',
		'targets' => [ 'mobile', 'desktop' ],
	],
	'jquery.throttle-debounce' => [
		'scripts' => 'resources/lib/jquery.ba-throttle-debounce.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.xmldom' => [
		'scripts' => 'resources/lib/jquery.xmldom.js',
	],

	/* jQuery Tipsy */

	'jquery.tipsy' => [
		'deprecated' => true,
		'scripts' => 'resources/src/jquery.tipsy/jquery.tipsy.js',
		'styles' => 'resources/src/jquery.tipsy/jquery.tipsy.css',
	],

	/* jQuery UI */

	'jquery.ui.core' => [
		'deprecated' => 'Please use OOUI instead.',
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.core.js',
		'dependencies' => [
			'jquery.ui.core.styles',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.core.styles' => [
		'skinStyles' => [
			'default' => [
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.core.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.theme.css',
			],
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.accordion' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.accordion.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.accordion.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.autocomplete' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.autocomplete.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.position',
			'jquery.ui.menu',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.autocomplete.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.button' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.button.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.button.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.datepicker' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.datepicker.js',
		'dependencies' => 'jquery.ui.core',
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.datepicker.css',
		],
		'languageScripts' => [
			'af' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-af.js',
			'ar' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ar.js',
			'ar-dz' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ar-DZ.js',
			'az' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-az.js',
			'bg' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-bg.js',
			'bs' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-bs.js',
			'ca' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ca.js',
			'cs' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-cs.js',
			'da' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-da.js',
			'de-at' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-de-AT.js',
			'de-ch' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-de-CH.js',
			'de' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-de.js',
			'el' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-el.js',
			'en-au' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-en-AU.js',
			'en-gb' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-en-GB.js',
			'en-nz' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-en-NZ.js',
			'eo' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-eo.js',
			'es' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-es.js',
			'et' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-et.js',
			'eu' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-eu.js',
			'fa' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fa.js',
			'fi' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fi.js',
			'fo' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fo.js',
			'fr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fr.js',
			'fr-ch' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fr-CH.js',
			'gl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-gl.js',
			'he' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-he.js',
			'hi' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hi.js',
			'hr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hr.js',
			'hu' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hu.js',
			'hy' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hy.js',
			'id' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-id.js',
			'is' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-is.js',
			'it' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-it.js',
			'ja' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ja.js',
			'ka' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ka.js',
			'kk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-kk.js',
			'km' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-km.js',
			'ko' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ko.js',
			'lb' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-lb.js',
			'lt' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-lt.js',
			'lv' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-lv.js',
			'mk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-mk.js',
			'ml' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ml.js',
			'ms' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ms.js',
			'nl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-nl.js',
			'nl-be' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-nl-BE.js',
			'no' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-no.js',
			'pl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-pl.js',
			'pt' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-pt.js',
			'pt-br' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-pt-BR.js',
			'rm' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-rm.js',
			'ro' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ro.js',
			'ru' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ru.js',
			'sk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sk.js',
			'sl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sl.js',
			'sq' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sq.js',
			'sr-ec' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sr.js',
			'sr-el' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sr-SR.js',
			'sv' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sv.js',
			'ta' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ta.js',
			'th' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-th.js',
			'tj' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-tj.js',
			'tr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-tr.js',
			'uk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-uk.js',
			'vi' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-vi.js',
			'zh-cn' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-zh-CN.js',
			'zh-hk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-zh-HK.js',
			'zh-tw' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-zh-TW.js',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.dialog' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.dialog.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.button',
			'jquery.ui.draggable',
			'jquery.ui.mouse',
			'jquery.ui.position',
			'jquery.ui.resizable',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.dialog.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.draggable' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.draggable.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.mouse',
			'jquery.ui.widget',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.droppable' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.droppable.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.mouse',
			'jquery.ui.widget',
			'jquery.ui.draggable',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.menu' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.menu.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.position',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.menu.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.mouse' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.mouse.js',
		'dependencies' => 'jquery.ui.widget',
		'group' => 'jquery.ui',
	],
	'jquery.ui.position' => [
		'deprecated' => true,
		'targets' => [ 'mobile', 'desktop' ],
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.position.js',
		'group' => 'jquery.ui',
	],
	'jquery.ui.progressbar' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.progressbar.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.progressbar.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.resizable' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.resizable.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.mouse',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.resizable.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.selectable' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.selectable.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.mouse',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.selectable.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.slider' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.slider.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.mouse',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.slider.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.sortable' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.sortable.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.mouse',
			'jquery.ui.widget',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.spinner' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.spinner.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.button',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.spinner.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.tabs' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.tabs.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.tabs.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.tooltip' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.tooltip.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.position',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.tooltip.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.widget' => [
		'deprecated' => true,
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.widget.js',
		'group' => 'jquery.ui',
	],
	// Effects
	'jquery.effects.core' => [
		'deprecated' => true,
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect.js',
		'group' => 'jquery.ui',
	],
	'jquery.effects.blind' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-blind.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.bounce' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-bounce.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.clip' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-clip.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.drop' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-drop.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.explode' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-explode.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.fade' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-fade.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.fold' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-fold.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.highlight' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-highlight.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.pulsate' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-pulsate.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.scale' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-scale.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.shake' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-shake.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.slide' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-slide.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.transfer' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-transfer.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],

	/* Moment.js */

	'moment' => [
		'scripts' => [
			// HACK: For some reason if you don't define window.moment first, loading moment fatals
			'resources/src/moment/moment-global.js',
			'resources/lib/moment/moment.js',
		],
		'languageScripts' => [
			'aeb-arab' => 'resources/lib/moment/locale/ar-tn.js',
			'af' => 'resources/lib/moment/locale/af.js',
			'ar' => 'resources/lib/moment/locale/ar.js',
			'ar-ma' => 'resources/lib/moment/locale/ar-ma.js',
			'ar-sa' => 'resources/lib/moment/locale/ar-sa.js',
			'az' => 'resources/lib/moment/locale/az.js',
			'be' => 'resources/lib/moment/locale/be.js',
			'bg' => 'resources/lib/moment/locale/bg.js',
			'bm' => 'resources/lib/moment/locale/bm.js',
			'bn' => 'resources/lib/moment/locale/bn.js',
			'bo' => 'resources/lib/moment/locale/bo.js',
			'br' => 'resources/lib/moment/locale/br.js',
			'bs' => 'resources/lib/moment/locale/bs.js',
			'ca' => 'resources/lib/moment/locale/ca.js',
			'cs' => 'resources/lib/moment/locale/cs.js',
			'cv' => 'resources/lib/moment/locale/cv.js',
			'cy' => 'resources/lib/moment/locale/cy.js',
			'da' => 'resources/lib/moment/locale/da.js',
			'de' => 'resources/lib/moment/locale/de.js',
			'de-at' => 'resources/lib/moment/locale/de-at.js',
			'de-ch' => 'resources/lib/moment/locale/de-ch.js',
			'dv' => 'resources/lib/moment/locale/dv.js',
			'el' => 'resources/lib/moment/locale/el.js',
			'en' => 'resources/src/moment/moment-dmy.js',
			'en-au' => 'resources/lib/moment/locale/en-au.js',
			'en-ca' => 'resources/lib/moment/locale/en-ca.js',
			'en-gb' => 'resources/lib/moment/locale/en-gb.js',
			'eo' => 'resources/lib/moment/locale/eo.js',
			'es' => 'resources/lib/moment/locale/es.js',
			'et' => 'resources/lib/moment/locale/et.js',
			'eu' => 'resources/lib/moment/locale/eu.js',
			'fa' => 'resources/lib/moment/locale/fa.js',
			'fi' => 'resources/lib/moment/locale/fi.js',
			'fo' => 'resources/lib/moment/locale/fo.js',
			'fr' => 'resources/lib/moment/locale/fr.js',
			'fr-ca' => 'resources/lib/moment/locale/fr-ca.js',
			'fy' => 'resources/lib/moment/locale/fy.js',
			'gd' => 'resources/lib/moment/locale/gd.js',
			'gl' => 'resources/lib/moment/locale/gl.js',
			'gom-latn' => 'resources/lib/moment/locale/gom-latn.js',
			'gu' => 'resources/lib/moment/locale/gu.js',
			'he' => 'resources/lib/moment/locale/he.js',
			'hi' => 'resources/lib/moment/locale/hi.js',
			'hr' => 'resources/lib/moment/locale/hr.js',
			'hu' => 'resources/lib/moment/locale/hu.js',
			'hy-am' => 'resources/lib/moment/locale/hy-am.js',
			'id' => 'resources/lib/moment/locale/id.js',
			'is' => 'resources/lib/moment/locale/is.js',
			'it' => 'resources/lib/moment/locale/it.js',
			'ja' => 'resources/lib/moment/locale/ja.js',
			'jv' => 'resources/lib/moment/locale/jv.js',
			'ka' => 'resources/lib/moment/locale/ka.js',
			'kk-cyrl' => 'resources/lib/moment/locale/kk.js',
			'kn' => 'resources/lib/moment/locale/kn.js',
			'ko' => 'resources/lib/moment/locale/ko.js',
			'ky' => 'resources/lib/moment/locale/ky.js',
			'lo' => 'resources/lib/moment/locale/lo.js',
			'lt' => 'resources/lib/moment/locale/lt.js',
			'lv' => 'resources/lib/moment/locale/lv.js',
			'mi' => 'resources/lib/moment/locale/mi.js',
			'mk' => 'resources/lib/moment/locale/mk.js',
			'ml' => 'resources/lib/moment/locale/ml.js',
			'mr' => 'resources/lib/moment/locale/mr.js',
			'ms-my' => 'resources/lib/moment/locale/ms-my.js',
			'ms' => 'resources/lib/moment/locale/ms.js',
			'my' => 'resources/lib/moment/locale/my.js',
			'nb' => 'resources/lib/moment/locale/nb.js',
			'ne' => 'resources/lib/moment/locale/ne.js',
			'nl' => 'resources/lib/moment/locale/nl.js',
			'nn' => 'resources/lib/moment/locale/nn.js',
			'pa' => 'resources/lib/moment/locale/pa-in.js',
			'pl' => 'resources/lib/moment/locale/pl.js',
			'pt' => 'resources/lib/moment/locale/pt.js',
			'pt-br' => 'resources/lib/moment/locale/pt-br.js',
			'ro' => 'resources/lib/moment/locale/ro.js',
			'ru' => 'resources/lib/moment/locale/ru.js',
			'sd' => 'resources/lib/moment/locale/sd.js',
			'se' => 'resources/lib/moment/locale/se.js',
			'si' => 'resources/lib/moment/locale/si.js',
			'sk' => 'resources/lib/moment/locale/sk.js',
			'sl' => 'resources/lib/moment/locale/sl.js',
			'sq' => 'resources/lib/moment/locale/sq.js',
			'sr-ec' => 'resources/lib/moment/locale/sr-cyrl.js',
			'sr-el' => 'resources/lib/moment/locale/sr.js',
			'ss' => 'resources/lib/moment/locale/ss.js',
			'sv' => 'resources/lib/moment/locale/sv.js',
			'sw' => 'resources/lib/moment/locale/sw.js',
			'ta' => 'resources/lib/moment/locale/ta.js',
			'te' => 'resources/lib/moment/locale/te.js',
			'tet' => 'resources/lib/moment/locale/tet.js',
			'th' => 'resources/lib/moment/locale/th.js',
			'tl-ph' => 'resources/lib/moment/locale/tl-ph.js',
			'tr' => 'resources/lib/moment/locale/tr.js',
			'tzm' => 'resources/lib/moment/locale/tzm.js',
			'tzm-latn' => 'resources/lib/moment/locale/tzm-latn.js',
			'uk' => 'resources/lib/moment/locale/uk.js',
			'ur' => 'resources/lib/moment/locale/ur.js',
			'uz' => 'resources/lib/moment/locale/uz.js',
			'uz-latn' => 'resources/lib/moment/locale/uz-latn.js',
			'vi' => 'resources/lib/moment/locale/vi.js',
			'yo' => 'resources/lib/moment/locale/yo.js',
			'zh-hans' => 'resources/lib/moment/locale/zh-cn.js',
			'zh-hant' => 'resources/lib/moment/locale/zh-tw.js',
			'zh-cn' => 'resources/lib/moment/locale/zh-cn.js',
			'zh-hk' => 'resources/lib/moment/locale/zh-hk.js',
			'zh-tw' => 'resources/lib/moment/locale/zh-tw.js',
		],
		// HACK: skinScripts come after languageScripts, and we need locale overrides to come
		// after locale definitions
		'skinScripts' => [
			'default' => [
				'resources/src/moment/moment-locale-overrides.js',
			],
		],
		'dependencies' => [
			'mediawiki.language',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	/* MediaWiki */
	'mediawiki.apihelp' => [
		'styles' => 'resources/src/mediawiki.apihelp.css',
		'targets' => [ 'desktop' ],
	],
	'mediawiki.template' => [
		'scripts' => 'resources/src/mediawiki.template.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.template.mustache' => [
		'scripts' => [
			'resources/lib/mustache/mustache.js',
			'resources/src/mediawiki.template.mustache.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
		'dependencies' => 'mediawiki.template',
	],
	'mediawiki.template.regexp' => [
		'scripts' => 'resources/src/mediawiki.template.regexp.js',
		'targets' => [ 'desktop', 'mobile' ],
		'dependencies' => 'mediawiki.template',
	],
	'mediawiki.apipretty' => [
		'styles' => 'resources/src/mediawiki.apipretty.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api' => [
		'scripts' => [
			'resources/src/mediawiki.api/index.js',
			'resources/src/mediawiki.api/category.js',
			'resources/src/mediawiki.api/edit.js',
			'resources/src/mediawiki.api/login.js',
			'resources/src/mediawiki.api/messages.js',
			'resources/src/mediawiki.api/options.js',
			'resources/src/mediawiki.api/parse.js',
			'resources/src/mediawiki.api/rollback.js',
			'resources/src/mediawiki.api/upload.js',
			'resources/src/mediawiki.api/user.js',
			'resources/src/mediawiki.api/watch.js',
		],
		'dependencies' => [
			'mediawiki.Title',
			'mediawiki.util',
			'user.tokens',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.category' => [
		'deprecated' => 'Use "mediawiki.api" instead.',
		'dependencies' => 'mediawiki.api',
	],
	'mediawiki.api.edit' => [
		'deprecated' => 'Use "mediawiki.api" instead.',
		'dependencies' => [
			'mediawiki.api',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.login' => [
		'deprecated' => 'Use "mediawiki.api" instead.',
		'dependencies' => 'mediawiki.api',
	],
	'mediawiki.api.options' => [
		'deprecated' => 'Use "mediawiki.api" instead.',
		'dependencies' => 'mediawiki.api',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.parse' => [
		'deprecated' => 'Use "mediawiki.api" instead.',
		'dependencies' => 'mediawiki.api',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.upload' => [
		'deprecated' => 'Use "mediawiki.api" instead.',
		'dependencies' => 'mediawiki.api',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.user' => [
		'deprecated' => 'Use "mediawiki.api" instead.',
		'dependencies' => 'mediawiki.api',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.watch' => [
		'deprecated' => 'Use "mediawiki.api" instead.',
		'dependencies' => 'mediawiki.api',
	],
	'mediawiki.api.messages' => [
		'deprecated' => 'Use "mediawiki.api" instead.',
		'dependencies' => 'mediawiki.api',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.rollback' => [
		'deprecated' => 'Use "mediawiki.api" instead.',
		'dependencies' => 'mediawiki.api',
	],
	'mediawiki.content.json' => [
		'styles' => 'resources/src/mediawiki.content.json.less',
	],
	'mediawiki.confirmCloseWindow' => [
		'scripts' => [
			'resources/src/mediawiki.confirmCloseWindow.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.debug' => [
		'scripts' => [
			'resources/src/mediawiki.debug/jquery.footHovzer.js',
			'resources/src/mediawiki.debug/debug.js',
		],
		'styles' => [
			'resources/src/mediawiki.debug/jquery.footHovzer.css',
			'resources/src/mediawiki.debug/debug.less',
		],
		'dependencies' => [
			'oojs-ui-core',
		],
	],
	'mediawiki.diff.styles' => [
		'styles' => [
			'resources/src/mediawiki.diff.styles/diff.css',
			'resources/src/mediawiki.diff.styles/print.css' => [
				'media' => 'print'
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.feedback' => [
		'scripts' => 'resources/src/mediawiki.feedback/feedback.js',
		'styles' => 'resources/src/mediawiki.feedback/feedback.css',
		'dependencies' => [
			'mediawiki.messagePoster',
			'mediawiki.Title',
			'oojs-ui-core',
			'oojs-ui-windows',
		],
		'messages' => [
			'feedback-adding',
			'feedback-back',
			'feedback-bugcheck',
			'feedback-dialog-intro',
			'feedback-external-bug-report-button',
			'feedback-bugnew',
			'feedback-bugornote',
			'feedback-cancel',
			'feedback-close',
			'feedback-dialog-title',
			'feedback-error1',
			'feedback-error2',
			'feedback-error3',
			'feedback-error4',
			'feedback-message',
			'feedback-subject',
			'feedback-submit',
			'feedback-terms',
			'feedback-termsofuse',
			'feedback-thanks',
			'feedback-thanks-title',
			'feedback-useragent'
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.feedlink' => [
		'styles' => 'resources/src/mediawiki.feedlink/feedlink.css',
	],
	'mediawiki.filewarning' => [
		'scripts' => 'resources/src/mediawiki.filewarning/filewarning.js',
		'styles' => 'resources/src/mediawiki.filewarning/filewarning.less',
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui.styles.icons-alerts',
		],
	],
	'mediawiki.ForeignApi' => [
		'targets' => [ 'desktop', 'mobile' ],
		'class' => ResourceLoaderForeignApiModule::class,
		// Additional dependencies generated dynamically
		'dependencies' => 'mediawiki.ForeignApi.core',
	],
	'mediawiki.ForeignApi.core' => [
		'scripts' => 'resources/src/mediawiki.ForeignApi.core.js',
		'dependencies' => [
			'mediawiki.api',
			'oojs',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.helplink' => [
		'styles' => [
			'resources/src/mediawiki.helplink/helplink.less',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.hlist' => [
		'targets' => [ 'desktop', 'mobile' ],
		'styles' => [
			'resources/src/mediawiki.hlist/hlist.less',
		],
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.hlist/default.css',
		],
	],
	'mediawiki.htmlform' => [
		'scripts' => [
			'resources/src/mediawiki.htmlform/htmlform.js',
			'resources/src/mediawiki.htmlform/autocomplete.js',
			'resources/src/mediawiki.htmlform/autoinfuse.js',
			'resources/src/mediawiki.htmlform/checkmatrix.js',
			'resources/src/mediawiki.htmlform/cloner.js',
			'resources/src/mediawiki.htmlform/hide-if.js',
			'resources/src/mediawiki.htmlform/multiselect.js',
			'resources/src/mediawiki.htmlform/selectandother.js',
			'resources/src/mediawiki.htmlform/selectorother.js',
		],
		'dependencies' => [
			'mediawiki.RegExp',
			'jquery.lengthLimit',
		],
		'messages' => [
			'htmlform-chosen-placeholder',
			// @todo Load this message in content language
			'colon-separator',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.htmlform.checker' => [
		'scripts' => [
			'resources/src/mediawiki.htmlform.checker.js',
		],
		'dependencies' => [
			'jquery.throttle-debounce',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.htmlform.ooui' => [
		'scripts' => [
			'resources/src/mediawiki.htmlform.ooui/Element.js',
		],
		'dependencies' => [
			'oojs-ui-core',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.htmlform.styles' => [
		'styles' => 'resources/src/mediawiki.htmlform.styles/styles.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.htmlform.ooui.styles' => [
		'styles' => 'resources/src/mediawiki.htmlform.ooui.styles.less',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.icon' => [
		'styles' => 'resources/src/mediawiki.icon/icon.less',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.inspect' => [
		'scripts' => 'resources/src/mediawiki.inspect.js',
		'dependencies' => [
			'mediawiki.String',
			'mediawiki.RegExp',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.messagePoster' => [
		'scripts' => [
			'resources/src/mediawiki.messagePoster/factory.js',
			'resources/src/mediawiki.messagePoster/MessagePoster.js',
		],
		'dependencies' => [
			'oojs',
			'mediawiki.api',
			'mediawiki.ForeignApi',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.messagePoster.wikitext' => [
		'scripts' => [
			'resources/src/mediawiki.messagePoster.wikitext/WikitextMessagePoster.js',
		],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.messagePoster',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.notification' => [
		'styles' => [
			'resources/src/mediawiki.notification/common.css',
			'resources/src/mediawiki.notification/print.css'
				=> [ 'media' => 'print' ],
		],
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.notification/default.css',
		],
		'scripts' => 'resources/src/mediawiki.notification/notification.js',
		'dependencies' => [
			'mediawiki.util',
			'mediawiki.visibleTimeout',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.notify' => [
		'scripts' => 'resources/src/mediawiki.notify.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.notification.convertmessagebox' => [
		'scripts' => 'resources/src/mediawiki.notification.convertmessagebox.js',
		'dependencies' => [
			'mediawiki.notification',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.notification.convertmessagebox.styles' => [
		'styles' => [
			'resources/src/mediawiki.notification.convertmessagebox.styles.less',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.RegExp' => [
		'scripts' => 'resources/src/mediawiki.RegExp.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.String' => [
		'scripts' => 'resources/src/mediawiki.String.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.pager.tablePager' => [
		'styles' => 'resources/src/mediawiki.pager.tablePager/TablePager.less',
	],
	'mediawiki.searchSuggest' => [
		'targets' => [ 'desktop', 'mobile' ],
		'scripts' => 'resources/src/mediawiki.searchSuggest/searchSuggest.js',
		'styles' => 'resources/src/mediawiki.searchSuggest/searchSuggest.css',
		'messages' => [
			'searchsuggest-search',
			'searchsuggest-containing',
		],
		'dependencies' => [
			'jquery.suggestions',
			'jquery.getAttrs',
			'mediawiki.api',
		],
	],
	'mediawiki.storage' => [
		'scripts' => 'resources/src/mediawiki.storage.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.Title' => [
		'scripts' => [
			'resources/src/mediawiki.Title/Title.js',
			'resources/src/mediawiki.Title/phpCharToUpper.js',
		],
		'dependencies' => [
			'mediawiki.String',
			'mediawiki.util',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.Upload' => [
		'scripts' => 'resources/src/mediawiki.Upload.js',
		'dependencies' => [
			'mediawiki.api',
		],
	],
	'mediawiki.ForeignUpload' => [
		'scripts' => 'resources/src/mediawiki.ForeignUpload.js',
		'dependencies' => [
			'mediawiki.ForeignApi',
			'mediawiki.Upload',
			'oojs',
		],
		'messages' => [
			'uploaddisabledtext',
			'upload-dialog-disabled',
			'upload-foreign-cant-upload',
		]
	],
	'mediawiki.ForeignStructuredUpload.config' => [
		'class' => ResourceLoaderUploadDialogModule::class,
	],
	'mediawiki.ForeignStructuredUpload' => [
		'scripts' => 'resources/src/mediawiki.ForeignStructuredUpload.js',
		'dependencies' => [
			'mediawiki.ForeignUpload',
			'mediawiki.ForeignStructuredUpload.config',
		],
		'messages' => [
			'upload-foreign-cant-load-config',
		],
	],
	'mediawiki.Upload.Dialog' => [
		'scripts' => [
			'resources/src/mediawiki.Upload.Dialog.js',
		],
		'dependencies' => [
			'mediawiki.Upload.BookletLayout',
		],
		'messages' => [
			'upload-dialog-title',
			'upload-dialog-button-cancel',
			'upload-dialog-button-back',
			'upload-dialog-button-done',
			'upload-dialog-button-save',
			'upload-dialog-button-upload',
		],
	],
	'mediawiki.Upload.BookletLayout' => [
		'scripts' => [
			'resources/src/mediawiki.Upload.BookletLayout/BookletLayout.js',
		],
		'styles' => [
			'resources/src/mediawiki.Upload.BookletLayout/BookletLayout.css',
		],
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui-widgets',
			'oojs-ui-windows',
			'oojs-ui.styles.icons-content',
			'oojs-ui.styles.icons-editing-advanced',
			'moment',
			'mediawiki.Title',
			'mediawiki.user',
			'mediawiki.Upload',
			'mediawiki.jqueryMsg',
			'mediawiki.widgets.StashedFileWidget'
		],
		'messages' => [
			'upload-form-label-infoform-title',
			'upload-form-label-infoform-name',
			'upload-form-label-infoform-name-tooltip',
			'upload-form-label-infoform-description',
			'upload-form-label-infoform-description-tooltip',
			'upload-form-label-usage-title',
			'upload-form-label-usage-filename',
			'action-upload',
			'apierror-mustbeloggedin',
			'badaccess-groups',
			'apierror-timeout',
			'apierror-offline',
			'apierror-unknownerror',
			'api-error-unknown-warning',
			'fileexists',
			'filepageexists',
			'file-exists-duplicate',
			'file-deleted-duplicate',
			'filename-bad-prefix',
			'filename-thumb-name',
			'filewasdeleted',
			'badfilename',
			'protectedpagetext',
		],
	],
	'mediawiki.ForeignStructuredUpload.BookletLayout' => [
		'scripts' => 'resources/src/mediawiki.ForeignStructuredUpload.BookletLayout/BookletLayout.js',
		'dependencies' => [
			'mediawiki.ForeignStructuredUpload',
			'mediawiki.Upload.BookletLayout',
			'mediawiki.widgets.CategoryMultiselectWidget',
			'mediawiki.widgets.DateInputWidget',
			'mediawiki.jqueryMsg',
			'mediawiki.api',
			'moment',
			'mediawiki.libs.jpegmeta',
		],
		'messages' => [
			'upload-form-label-own-work',
			'upload-form-label-infoform-categories',
			'upload-form-label-infoform-date',
			'upload-form-label-own-work-message-generic-local',
			'upload-form-label-not-own-work-message-generic-local',
			'upload-form-label-not-own-work-local-generic-local',
			'upload-form-label-own-work-message-generic-foreign',
			'upload-form-label-not-own-work-message-generic-foreign',
			'upload-form-label-not-own-work-local-generic-foreign',
		],
	],
	'mediawiki.toc' => [
		'scripts' => 'resources/src/mediawiki.toc/toc.js',
		'styles' => [
			'resources/src/mediawiki.toc/toc.css'
				=> [ 'media' => 'screen' ],
			'resources/src/mediawiki.toc/print.css'
				=> [ 'media' => 'print' ],
		],
		'dependencies' => 'mediawiki.cookie',
		'messages' => [ 'showtoc', 'hidetoc' ],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.toc.styles' => [
		'class' => ResourceLoaderLessVarFileModule::class,
		'lessMessages' => [ 'hidetoc', 'showtoc' ],
		'styles' => [
			'resources/src/mediawiki.toc.styles/common.css',
			'resources/src/mediawiki.toc.styles/screen.less'
				=> [ 'media' => 'screen' ],
			'resources/src/mediawiki.toc.styles/print.css'
				=> [ 'media' => 'print' ],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.Uri' => [
		'scripts' => 'resources/src/mediawiki.Uri/Uri.js',
		'templates' => [
			'strict.regexp' => 'resources/src/mediawiki.Uri/strict.regexp',
			'loose.regexp' => 'resources/src/mediawiki.Uri/loose.regexp',
		],
		'dependencies' => 'mediawiki.util',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.user' => [
		'scripts' => 'resources/src/mediawiki.user.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.storage',
			'user.options',
			'user.tokens',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.userSuggest' => [
		'scripts' => 'resources/src/mediawiki.userSuggest.js',
		'dependencies' => [
			'jquery.suggestions',
			'mediawiki.api'
		]
	],
	'mediawiki.util' => [
		'class' => ResourceLoaderMediaWikiUtilModule::class,
		'scripts' => 'resources/src/mediawiki.util.js',
		'dependencies' => [
			'jquery.accessKeyLabel',
			'mediawiki.RegExp',
			'mediawiki.notify',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.viewport' => [
		'scripts' => 'resources/src/mediawiki.viewport.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.checkboxtoggle' => [
		'scripts' => 'resources/src/mediawiki.checkboxtoggle.js',
	],
	'mediawiki.checkboxtoggle.styles' => [
		'styles' => 'resources/src/mediawiki.checkboxtoggle.styles.css',
	],
	'mediawiki.cookie' => [
		'scripts' => 'resources/src/mediawiki.cookie.js',
		'dependencies' => 'jquery.cookie',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.experiments' => [
		'scripts' => 'resources/src/mediawiki.experiments.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.editfont.styles' => [
		'styles' => 'resources/src/mediawiki.editfont.less',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.visibleTimeout' => [
		'scripts' => 'resources/src/mediawiki.visibleTimeout.js',
		'targets' => [ 'desktop', 'mobile' ],
	],

	/* MediaWiki Action */

	'mediawiki.action.delete' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.delete.js',
		'dependencies' => [
			'oojs-ui-core',
			'jquery.lengthLimit',
		],
		'messages' => [
			// @todo Load this message in content language
			'colon-separator',
		],
	],
	'mediawiki.action.delete.file' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.delete.file.js',
		'dependencies' => [
			'oojs-ui-core',
			'jquery.lengthLimit',
		],
		'messages' => [
			// @todo Load this message in content language
			'colon-separator',
		],
	],
	'mediawiki.action.edit' => [
		'scripts' => [
			'resources/src/mediawiki.action/mediawiki.action.edit.js',
			'resources/src/mediawiki.action/mediawiki.action.edit.stash.js',
		],
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.edit.css',
		'dependencies' => [
			'mediawiki.action.edit.styles',
			'mediawiki.editfont.styles',
			'jquery.textSelection',
			'oojs-ui-core',
			'mediawiki.widgets.visibleLengthLimit',
			'mediawiki.api',
			'mediawiki.util',
		],
	],
	'mediawiki.action.edit.styles' => [
		'targets' => [ 'desktop', 'mobile' ],
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.edit.styles.less',
	],
	'mediawiki.action.edit.collapsibleFooter' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.collapsibleFooter.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.edit.collapsibleFooter.css',
		'dependencies' => [
			'jquery.makeCollapsible',
			'mediawiki.storage',
			'mediawiki.icon',
		],
	],
	'mediawiki.action.edit.preview' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.preview.js',
		'dependencies' => [
			'jquery.spinner',
			'jquery.textSelection',
			'mediawiki.api',
			'mediawiki.diff.styles',
			'mediawiki.language',
			'mediawiki.util',
			'mediawiki.jqueryMsg',
			'oojs-ui-core',
		],
		'messages' => [
			// Keep the uses message keys in sync with EditPage#setHeaders
			'creating',
			'editconflict',
			'editing',
			'editingcomment',
			'editingsection',
			'pagetitle',
			'otherlanguages',
			'summary-preview',
			'subject-preview',
			'parentheses',
			'previewerrortext',
		],
	],
	'mediawiki.action.history' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.history.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.history.css',
	],
	'mediawiki.action.history.styles' => [
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.history.styles.css',
	],
	'mediawiki.action.view.dblClickEdit' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.dblClickEdit.js',
		'dependencies' => [
			'mediawiki.util',
			'user.options',
		],
	],
	'mediawiki.action.view.metadata' => [
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.metadata.css',
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.metadata.js',
		'messages' => [
			'metadata-expand',
			'metadata-collapse',
		],
		'dependencies' => 'mediawiki.action.view.filepage',
	],
	'mediawiki.action.view.categoryPage.styles' => [
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.categoryPage.less',
		'targets' => [ 'desktop', 'mobile' ]
	],
	'mediawiki.action.view.postEdit' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.postEdit.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.postEdit.less',
		'skinStyles' => [
			'monobook' => 'resources/src/mediawiki.action/mediawiki.action.view.postEdit.monobook.css',
		],
		'dependencies' => [
			'mediawiki.jqueryMsg',
			'mediawiki.notification'
		],
		'messages' => [
			'postedit-confirmation-created',
			'postedit-confirmation-restored',
			'postedit-confirmation-saved',
			'postedit-confirmation-published',
		],
	],
	'mediawiki.action.view.redirect' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.redirect.js',
		'dependencies' => 'jquery.client',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.action.view.redirectPage' => [
		'targets' => [ 'desktop', 'mobile' ],
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.redirectPage.css',
	],
	'mediawiki.action.view.rightClickEdit' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.rightClickEdit.js',
	],
	'mediawiki.action.edit.editWarning' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.editWarning.js',
		'dependencies' => [
			'jquery.textSelection',
			'mediawiki.jqueryMsg',
			'mediawiki.confirmCloseWindow',
			'user.options',
		],
		'messages' => [
			'editwarning-warning',
			// editwarning-warning uses {{int:prefs-editing}}
			'prefs-editing'
		],
	],
	'mediawiki.action.view.filepage' => [
		'styles' => [
			'resources/src/mediawiki.action/mediawiki.action.view.filepage.print.css' =>
				[ 'media' => 'print' ],
			'resources/src/mediawiki.action/mediawiki.action.view.filepage.css',
		],
	],

	/* MediaWiki Language */

	'mediawiki.language' => [
		'class' => ResourceLoaderLanguageDataModule::class,
		'scripts' => [
			'resources/src/mediawiki.language/mediawiki.language.init.js',
			'resources/src/mediawiki.language/mediawiki.language.js',
			'resources/src/mediawiki.language/mediawiki.language.numbers.js',
			'resources/src/mediawiki.language/mediawiki.language.fallback.js',
		],
		'languageScripts' => [
			'bs' => 'resources/src/mediawiki.language/languages/bs.js',
			'dsb' => 'resources/src/mediawiki.language/languages/dsb.js',
			'fi' => 'resources/src/mediawiki.language/languages/fi.js',
			'ga' => 'resources/src/mediawiki.language/languages/ga.js',
			'hsb' => 'resources/src/mediawiki.language/languages/hsb.js',
			'hu' => 'resources/src/mediawiki.language/languages/hu.js',
			'hy' => 'resources/src/mediawiki.language/languages/hy.js',
			'la' => 'resources/src/mediawiki.language/languages/la.js',
			'os' => 'resources/src/mediawiki.language/languages/os.js',
			'sl' => 'resources/src/mediawiki.language/languages/sl.js',
		],
		'dependencies' => [
			'mediawiki.cldr',
		],
		'messages' => [
			'and',
			'comma-separator',
			'word-separator'
		],
	],

	'mediawiki.cldr' => [
		'scripts' => 'resources/src/mediawiki.language/mediawiki.cldr.js',
		'dependencies' => [
			'mediawiki.libs.pluralruleparser',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.libs.pluralruleparser' => [
		'scripts' => [
			'resources/lib/CLDRPluralRuleParser/CLDRPluralRuleParser.js',
			'resources/src/mediawiki.libs.pluralruleparser/export.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.jqueryMsg' => [
		// Add data for mediawiki.jqueryMsg, such as allowed tags
		'class' => ResourceLoaderJqueryMsgModule::class,
		'scripts' => 'resources/src/mediawiki.jqueryMsg/mediawiki.jqueryMsg.js',
		'dependencies' => [
			'mediawiki.util',
			'mediawiki.language',
			'user.options',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.language.months' => [
		'targets' => [ 'desktop', 'mobile' ],
		'scripts' => 'resources/src/mediawiki.language/mediawiki.language.months.js',
		'dependencies' => 'mediawiki.language',
		'messages' => array_merge(
			Language::$mMonthMsgs,
			Language::$mMonthGenMsgs,
			Language::$mMonthAbbrevMsgs
		)
	],

	'mediawiki.language.names' => [ 'class' => ResourceLoaderLanguageNamesModule::class ],

	'mediawiki.language.specialCharacters' => [
		'class' => ResourceLoaderSpecialCharacterDataModule::class
	],

	/* MediaWiki Libs */

	'mediawiki.libs.jpegmeta' => [
		'scripts' => [
			'resources/src/mediawiki.libs.jpegmeta/jpegmeta.js',
			'resources/src/mediawiki.libs.jpegmeta/export.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	/* MediaWiki Page */

	'mediawiki.page.gallery' => [
		'scripts' => 'resources/src/mediawiki.page.gallery.js',
		'dependencies' => [
			'mediawiki.page.gallery.styles',
			'jquery.throttle-debounce',
		]
	],
	'mediawiki.page.gallery.styles' => [
		'styles' => [
			'resources/src/mediawiki.page.gallery.styles/gallery.css',
			'resources/src/mediawiki.page.gallery.styles/print.css' => [ 'media' => 'print' ],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.page.gallery.slideshow' => [
		'scripts' => 'resources/src/mediawiki.page.gallery.slideshow.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.Title',
			'oojs',
			'oojs-ui-core',
			'oojs-ui-widgets',
			'oojs-ui.styles.icons-media',
			'oojs-ui.styles.icons-movement'
		],
		'messages' => [
			'gallery-slideshow-toggle'
		]
	],
	'mediawiki.page.ready' => [
		'scripts' => 'resources/src/mediawiki.page.ready.js',
		'dependencies' => [
			'jquery.accessKeyLabel',
			'jquery.checkboxShiftClick',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.page.startup' => [
		'scripts' => 'resources/src/mediawiki.page.startup.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.page.patrol.ajax' => [
		'scripts' => 'resources/src/mediawiki.page.patrol.ajax.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.util',
			'mediawiki.Title',
			'mediawiki.notify',
			'jquery.spinner',
			'user.tokens'
		],
		'messages' => [
			'markedaspatrollednotify',
			'markedaspatrollederrornotify',
			'markedaspatrollederror-noautopatrol'
		],
	],
	'mediawiki.page.watch.ajax' => [
		'scripts' => 'resources/src/mediawiki.page.watch.ajax.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.notify',
			'mediawiki.util',
			'mediawiki.Title',
			'mediawiki.jqueryMsg',
			'jquery.accessKeyLabel',
			'mediawiki.RegExp',
		],
		'messages' => [
			'watch',
			'unwatch',
			'watching',
			'unwatching',
			'tooltip-ca-watch',
			'tooltip-ca-unwatch',
			'watcherrortext',
			'addedwatchtext',
			'addedwatchtext-talk',
			'removedwatchtext',
			'removedwatchtext-talk',
		],
	],
	'mediawiki.page.rollback' => [
		'scripts' => 'resources/src/mediawiki.page.rollback.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.notify',
			'mediawiki.util',
			'jquery.spinner',
		],
		'messages' => [
			'rollbackfailed',
			'actioncomplete',
		],
	],
	'mediawiki.page.image.pagination' => [
		'scripts' => 'resources/src/mediawiki.page.image.pagination.js',
		'dependencies' => [
			'mediawiki.util',
			'jquery.spinner',
		],
	],

	/* MediaWiki Special pages */

	'mediawiki.rcfilters.filters.base.styles' => [
		'styles' => [
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.less',
		],
	],
	'mediawiki.rcfilters.highlightCircles.seenunseen.styles' => [
		'styles' => [
			'resources/src/mediawiki.rcfilters/' .
			'styles/mw.rcfilters.ui.ChangesListWrapperWidget.highlightCircles.seenunseen.less',
		],
	],
	'mediawiki.rcfilters.filters.dm' => [
		'scripts' => [
			'resources/src/mediawiki.rcfilters/mw.rcfilters.js',
			'resources/src/mediawiki.rcfilters/dm/mw.rcfilters.dm.ItemModel.js',
			'resources/src/mediawiki.rcfilters/dm/mw.rcfilters.dm.FilterItem.js',
			'resources/src/mediawiki.rcfilters/dm/mw.rcfilters.dm.FilterGroup.js',
			'resources/src/mediawiki.rcfilters/dm/mw.rcfilters.dm.FiltersViewModel.js',
			'resources/src/mediawiki.rcfilters/dm/mw.rcfilters.dm.SavedQueryItemModel.js',
			'resources/src/mediawiki.rcfilters/dm/mw.rcfilters.dm.SavedQueriesModel.js',
			'resources/src/mediawiki.rcfilters/dm/mw.rcfilters.dm.ChangesListViewModel.js',
			'resources/src/mediawiki.rcfilters/mw.rcfilters.Controller.js',
			'resources/src/mediawiki.rcfilters/mw.rcfilters.UriProcessor.js',
		],
		'dependencies' => [
			'mediawiki.String',
			'oojs',
			'mediawiki.api',
			'mediawiki.jqueryMsg',
			'mediawiki.Uri',
			'mediawiki.user',
		],
		'messages' => [
			'quotation-marks',
			'rcfilters-filterlist-title',
		],
	],
	'mediawiki.rcfilters.filters.ui' => [
		'scripts' => [
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.GroupWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.CheckboxInputWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.FilterTagMultiselectWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.ItemMenuOptionWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.FilterMenuOptionWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.FilterMenuSectionOptionWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.TagItemWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.FilterTagItemWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.FilterMenuHeaderWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.MenuSelectWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.MainWrapperWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.ViewSwitchWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.ValuePickerWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.ChangesLimitPopupWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.ChangesLimitAndDateButtonWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.DatePopupWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.FilterWrapperWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.ChangesListWrapperWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.SavedLinksListWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.SavedLinksListItemWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.SaveFiltersPopupButtonWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.FormWrapperWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.FilterItemHighlightButton.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.HighlightPopupWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.HighlightColorPickerWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.LiveUpdateButtonWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.MarkSeenButtonWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.RcTopSectionWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.RclTopSectionWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.RclTargetPageWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.RclToOrFromWidget.js',
			'resources/src/mediawiki.rcfilters/ui/mw.rcfilters.ui.WatchlistTopSectionWidget.js',
			'resources/src/mediawiki.rcfilters/mw.rcfilters.HighlightColors.js',
			'resources/src/mediawiki.rcfilters/mw.rcfilters.init.js',
		],
		'styles' => [
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.mixins.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.variables.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.Overlay.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.FilterTagMultiselectWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.ItemMenuOptionWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.FilterMenuOptionWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.FilterMenuSectionOptionWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.TagItemWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.FilterMenuHeaderWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.MenuSelectWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.ViewSwitchWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.ValuePickerWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.ChangesLimitPopupWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.DatePopupWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.FilterWrapperWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.ChangesListWrapperWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.HighlightColorPickerWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.FilterItemHighlightButton.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.SavedLinksListWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.SavedLinksListItemWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.SaveFiltersPopupButtonWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.LiveUpdateButtonWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.RcTopSectionWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.RclToOrFromWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.RclTargetPageWidget.less',
			'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.WatchlistTopSectionWidget.less',
		],
		'skinStyles' => [
			'vector' => [
				'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.Overlay.vector.less',
			],
			'monobook' => [
				'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.Overlay.monobook.less',
				'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.CapsuleItemWidget.monobook.less',
				'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.ui.FilterMenuOptionWidget.monobook.less',
			],
		],
		'messages' => [
			'rcfilters-tag-remove',
			'rcfilters-activefilters',
			'rcfilters-activefilters-hide',
			'rcfilters-activefilters-show',
			'rcfilters-activefilters-hide-tooltip',
			'rcfilters-activefilters-show-tooltip',
			'rcfilters-advancedfilters',
			'rcfilters-group-results-by-page',
			'rcfilters-limit-title',
			'rcfilters-limit-and-date-label',
			'rcfilters-date-popup-title',
			'rcfilters-days-title',
			'rcfilters-hours-title',
			'rcfilters-days-show-days',
			'rcfilters-days-show-hours',
			'rcfilters-highlighted-filters-list',
			'rcfilters-quickfilters',
			'rcfilters-quickfilters-placeholder-title',
			'rcfilters-quickfilters-placeholder-description',
			'rcfilters-savedqueries-defaultlabel',
			'rcfilters-savedqueries-rename',
			'rcfilters-savedqueries-setdefault',
			'rcfilters-savedqueries-unsetdefault',
			'rcfilters-savedqueries-remove',
			'rcfilters-savedqueries-new-name-label',
			'rcfilters-savedqueries-new-name-placeholder',
			'rcfilters-savedqueries-add-new-title',
			'rcfilters-savedqueries-already-saved',
			'rcfilters-savedqueries-apply-label',
			'rcfilters-savedqueries-apply-and-setdefault-label',
			'rcfilters-savedqueries-cancel-label',
			'rcfilters-restore-default-filters',
			'rcfilters-clear-all-filters',
			'rcfilters-show-new-changes',
			'rcfilters-search-placeholder',
			'rcfilters-invalid-filter',
			'rcfilters-empty-filter',
			'rcfilters-filterlist-title',
			'rcfilters-filterlist-feedbacklink',
			'rcfilters-filterlist-noresults',
			'rcfilters-filterlist-whatsthis',
			'rcfilters-highlightbutton-title',
			'rcfilters-highlightmenu-title',
			'rcfilters-highlightmenu-help',
			'rcfilters-noresults-conflict',
			'rcfilters-state-message-subset',
			'rcfilters-state-message-fullcoverage',
			'rcfilters-filter-excluded',
			'rcfilters-tag-prefix-namespace',
			'rcfilters-tag-prefix-namespace-inverted',
			'rcfilters-tag-prefix-tags',
			'rcfilters-exclude-button-off',
			'rcfilters-exclude-button-on',
			'rcfilters-view-tags',
			'rcfilters-view-namespaces-tooltip',
			'rcfilters-view-tags-tooltip',
			'rcfilters-view-return-to-default-tooltip',
			'rcfilters-view-tags-help-icon-tooltip',
			'rcfilters-liveupdates-button',
			'rcfilters-liveupdates-button-title-on',
			'rcfilters-liveupdates-button-title-off',
			'rcfilters-watchlist-markseen-button',
			'rcfilters-watchlist-edit-watchlist-button',
			'rcfilters-other-review-tools',
			'rcfilters-filter-showlinkedfrom-label',
			'rcfilters-filter-showlinkedfrom-option-label',
			'rcfilters-filter-showlinkedto-label',
			'rcfilters-filter-showlinkedto-option-label',
			'rcfilters-target-page-placeholder',
			'blanknamespace',
			'namespaces',
			'tags-title',
			'invert',
			'recentchanges-noresult',
			'recentchanges-timeout',
			'recentchanges-network',
			'recentchanges-notargetpage',
			'allpagesbadtitle',
			'quotation-marks',
		],
		'dependencies' => [
			'oojs-ui-widgets',
			'jquery.makeCollapsible',
			'mediawiki.jqueryMsg',
			'mediawiki.language',
			'mediawiki.user',
			'mediawiki.util',
			'mediawiki.widgets',
			'mediawiki.rcfilters.filters.dm',
			'oojs-ui.styles.icons-content',
			'oojs-ui.styles.icons-moderation',
			'oojs-ui.styles.icons-editing-core',
			'oojs-ui.styles.icons-editing-styling',
			'oojs-ui.styles.icons-interactions',
			'oojs-ui.styles.icons-layout',
			'oojs-ui.styles.icons-media',
		],
	],
	'mediawiki.special' => [
		'styles' => [
			'resources/src/mediawiki.special/special.css',
			'resources/src/mediawiki.special/apisandbox.css',
			'resources/src/mediawiki.special/comparepages.less',
			'resources/src/mediawiki.special/edittags.css',
			'resources/src/mediawiki.special/movePage.css',
			'resources/src/mediawiki.special/newpages.less',
			'resources/src/mediawiki.special/pagesWithProp.css',
			'resources/src/mediawiki.special/upload.css',
			'resources/src/mediawiki.special/userrights.css',
			'resources/src/mediawiki.special/watchlist.css',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.special.apisandbox' => [
		'styles' => 'resources/src/mediawiki.special.apisandbox/apisandbox.css',
		'scripts' => 'resources/src/mediawiki.special.apisandbox/apisandbox.js',
		'targets' => [ 'desktop', 'mobile' ],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.jqueryMsg',
			'mediawiki.util',
			'oojs-ui',
			'oojs-ui.styles.icons-content',
			'oojs-ui.styles.icons-editing-advanced',
			'oojs-ui.styles.icons-interactions',
			'oojs-ui.styles.icons-moderation',
			'mediawiki.widgets.datetime',
			'jquery.makeCollapsible',
		],
		'messages' => [
			'apisandbox-intro',
			'apisandbox-submit',
			'apisandbox-reset',
			'apisandbox-retry',
			'apisandbox-loading',
			'apisandbox-load-error',
			'apisandbox-fetch-token',
			'apisandbox-add-multi',
			'apisandbox-helpurls',
			'apisandbox-examples',
			'apisandbox-dynamic-parameters',
			'apisandbox-dynamic-parameters-add-label',
			'apisandbox-dynamic-parameters-add-placeholder',
			'apisandbox-dynamic-error-exists',
			'apisandbox-templated-parameter-reason',
			'apisandbox-deprecated-parameters',
			'apisandbox-no-parameters',
			'api-help-param-limit',
			'api-help-param-limit2',
			'api-help-param-integer-min',
			'api-help-param-integer-max',
			'api-help-param-integer-minmax',
			'api-help-param-multi-separate',
			'api-help-param-multi-max',
			'api-help-param-maxbytes',
			'api-help-param-maxchars',
			'apisandbox-submit-invalid-fields-title',
			'apisandbox-submit-invalid-fields-message',
			'apisandbox-results',
			'apisandbox-sending-request',
			'apisandbox-loading-results',
			'apisandbox-results-error',
			'apisandbox-results-login-suppressed',
			'apisandbox-request-selectformat-label',
			'apisandbox-request-format-url-label',
			'apisandbox-request-url-label',
			'apisandbox-request-format-json-label',
			'apisandbox-request-json-label',
			'apisandbox-request-time',
			'apisandbox-results-fixtoken',
			'apisandbox-results-fixtoken-fail',
			'apisandbox-alert-page',
			'apisandbox-alert-field',
			'apisandbox-continue',
			'apisandbox-continue-clear',
			'apisandbox-continue-help',
			'apisandbox-param-limit',
			'apisandbox-multivalue-all-namespaces',
			'apisandbox-multivalue-all-values',
			'api-format-prettyprint-status',
			'blanknamespace',
			'comma-separator',
			'word-separator',
			'and'
		],
	],
	'mediawiki.special.block' => [
		'scripts' => 'resources/src/mediawiki.special.block.js',
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui.styles.icons-editing-core',
			'oojs-ui.styles.icons-editing-advanced',
			'mediawiki.widgets.SelectWithInputWidget',
			'mediawiki.widgets.DateInputWidget',
			'mediawiki.util',
			'mediawiki.htmlform',
			'moment',
		],
	],
	'mediawiki.special.changecredentials.js' => [
		'scripts' => 'resources/src/mediawiki.special.changecredentials.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.htmlform.ooui'
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.special.changeslist' => [
		'styles' => 'resources/src/mediawiki.special.changeslist.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.special.changeslist.enhanced' => [
		'styles' => 'resources/src/mediawiki.special.changeslist.enhanced.css',
	],
	'mediawiki.special.changeslist.legend' => [
		'styles' => 'resources/src/mediawiki.special.changeslist.legend.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.special.changeslist.legend.js' => [
		'scripts' => 'resources/src/mediawiki.special.changeslist.legend.js',
		'dependencies' => [
			'jquery.makeCollapsible',
			'mediawiki.cookie',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.special.contributions' => [
		'scripts' => 'resources/src/mediawiki.special.contributions.js',
		'dependencies' => [
			'mediawiki.widgets.DateInputWidget',
			'mediawiki.jqueryMsg',
		]
	],
	'mediawiki.special.edittags' => [
		'scripts' => 'resources/src/mediawiki.special.edittags.js',
		'dependencies' => [
			'jquery.chosen',
			'jquery.lengthLimit',
		],
		'messages' => [
			'tags-edit-chosen-placeholder',
			'tags-edit-chosen-no-results',
		],
	],
	'mediawiki.special.import' => [
		'scripts' => 'resources/src/mediawiki.special.import.js',
	],
	'mediawiki.special.movePage' => [
		'scripts' => 'resources/src/mediawiki.special.movePage.js',
		'dependencies' => [
			'mediawiki.widgets.visibleLengthLimit',
			'mediawiki.widgets',
		],
	],
	'mediawiki.special.pageLanguage' => [
		'scripts' => 'resources/src/mediawiki.special.pageLanguage.js',
		'dependencies' => [
			'oojs-ui-core',
		],
	],
	'mediawiki.special.preferences.ooui' => [
		'targets' => [ 'desktop', 'mobile' ],
		'scripts' => [
			'resources/src/mediawiki.special.preferences.ooui/confirmClose.js',
			'resources/src/mediawiki.special.preferences.ooui/convertmessagebox.js',
			'resources/src/mediawiki.special.preferences.ooui/editfont.js',
			'resources/src/mediawiki.special.preferences.ooui/tabs.js',
			'resources/src/mediawiki.special.preferences.ooui/timezone.js',
			'resources/src/mediawiki.special.preferences.ooui/personalEmail.js',
		],
		'messages' => [
			'prefs-tabs-navigation-hint',
			'prefswarning-warning',
			'saveprefs',
			'savedprefs',
		],
		'dependencies' => [
			'mediawiki.language',
			'mediawiki.confirmCloseWindow',
			'mediawiki.notification.convertmessagebox',
			'oojs-ui-widgets',
			'mediawiki.widgets.SelectWithInputWidget',
			'mediawiki.editfont.styles',
		],
	],
	'mediawiki.special.preferences.styles.ooui' => [
		'targets' => [ 'desktop', 'mobile' ],
		'styles' => 'resources/src/mediawiki.special.preferences.styles.ooui.less',
	],
	'mediawiki.special.recentchanges' => [
		'scripts' => 'resources/src/mediawiki.special.recentchanges.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.special.revisionDelete' => [
		'scripts' => 'resources/src/mediawiki.special.revisionDelete.js',
		'messages' => [
			// @todo Load this message in content language
			'colon-separator',
		],
		'dependencies' => [
			'jquery.lengthLimit',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.special.search' => [
		'scripts' => 'resources/src/mediawiki.special.search/search.js',
		'styles' => 'resources/src/mediawiki.special.search/search.css',
		'dependencies' => 'mediawiki.widgets.SearchInputWidget',
		'messages' => [
			'powersearch-togglelabel',
			'powersearch-toggleall',
			'powersearch-togglenone',
		],
	],
	'mediawiki.special.search.commonsInterwikiWidget' => [
		'scripts' => 'resources/src/mediawiki.special.search.commonsInterwikiWidget.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.Uri',
			'mediawiki.jqueryMsg'
		],
		'targets' => [ 'desktop', 'mobile' ],
		'messages' => [
			'search-interwiki-more-results',
			'searchprofile-images'
		],
	],
	'mediawiki.special.search.interwikiwidget.styles' => [
		'styles' => 'resources/src/mediawiki.special.search.interwikiwidget.styles.less',
		'targets' => [ 'desktop', 'mobile' ]
	],
	'mediawiki.special.search.styles' => [
		'styles' => 'resources/src/mediawiki.special.search.styles.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.special.undelete' => [
		'scripts' => 'resources/src/mediawiki.special.undelete.js',
		'dependencies' => [
			'mediawiki.widgets.visibleLengthLimit',
			'mediawiki.widgets',
		],
	],
	'mediawiki.special.unwatchedPages' => [
		'scripts' => 'resources/src/mediawiki.special.unwatchedPages/unwatchedPages.js',
		'styles' => 'resources/src/mediawiki.special.unwatchedPages/unwatchedPages.css',
		'messages' => [
			'addedwatchtext-short',
			'removedwatchtext-short',
			'unwatch',
			'unwatching',
			'watch',
			'watcherrortext',
			'watching',
		],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.notify',
			'mediawiki.Title',
			'mediawiki.util',
		],
	],
	'mediawiki.special.upload' => [
		'templates' => [
			'thumbnail.html' => 'resources/src/mediawiki.special.upload/templates/thumbnail.html',
		],
		'scripts' => 'resources/src/mediawiki.special.upload/upload.js',
		'messages' => [
			'widthheight',
			'size-bytes',
			'size-kilobytes',
			'size-megabytes',
			'size-gigabytes',
			'largefileserver',
			'editwarning-warning',
			// editwarning-warning uses {{int:prefs-editing}}
			'prefs-editing',
		],
		'dependencies' => [
			'mediawiki.special',
			'jquery.spinner',
			'mediawiki.jqueryMsg',
			'mediawiki.api',
			'mediawiki.libs.jpegmeta',
			'mediawiki.Title',
			'mediawiki.util',
			'mediawiki.confirmCloseWindow',
			'user.options',
		],
	],
	'mediawiki.special.userlogin.common.styles' => [
		'targets' => [ 'desktop', 'mobile' ],
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.special.userlogin.common.styles/userlogin.css',
		],
	],
	'mediawiki.special.userlogin.login.styles' => [
		'styles' => [
			'resources/src/mediawiki.special.userlogin.login.styles/login.css',
		],
	],
	'mediawiki.special.userlogin.signup.js' => [
		'scripts' => 'resources/src/mediawiki.special.userlogin.signup.js',
		'messages' => [
			'createacct-emailrequired',
			'noname',
			'userexists',
		],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.jqueryMsg',
			'jquery.throttle-debounce',
			'mediawiki.htmlform.checker',
		],
	],
	'mediawiki.special.userlogin.signup.styles' => [
		'styles' => [
			'resources/src/mediawiki.special.userlogin.signup.styles/signup.css',
		],
	],
	'mediawiki.special.userrights' => [
		'scripts' => 'resources/src/mediawiki.special.userrights.js',
		'dependencies' => [
			'mediawiki.notification.convertmessagebox',
			'jquery.lengthLimit',
		],
	],
	'mediawiki.special.watchlist' => [
		'scripts' => [
			'resources/src/mediawiki.special.watchlist/watchlist.js',
			'resources/src/mediawiki.special.watchlist/visitedstatus.js',
		],
		'messages' => [
			'addedwatchtext',
			'addedwatchtext-talk',
			'removedwatchtext',
			'removedwatchtext-talk',
			'tooltip-ca-watch',
			'tooltip-ca-unwatch',
			'watchlist-unwatch',
			'watchlist-unwatch-undo',
		],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.jqueryMsg',
			'mediawiki.Title',
			'mediawiki.util',
			'oojs-ui-core',
			'user.options',
		],
	],
	'mediawiki.special.version' => [
		'styles' => 'resources/src/mediawiki.special.version.css',
	],

	/* MediaWiki Installer */

	// Used in the web installer. Test it after modifying this definition!
	'mediawiki.legacy.config' => [
		// These files are not actually loaded via ResourceLoader, so dependencies etc. won't work.
		'scripts' => 'mw-config/config.js',
		'styles' => 'mw-config/config.css',
	],

	/* MediaWiki Legacy */

	'mediawiki.legacy.commonPrint' => [
		'styles' => [
			'resources/src/mediawiki.legacy/commonPrint.css' => [ 'media' => 'print' ]
		],
	],
	'mediawiki.legacy.protect' => [
		'scripts' => 'resources/src/mediawiki.legacy/protect.js',
		'dependencies' => 'jquery.lengthLimit',
		'messages' => [ 'protect-unchain-permissions' ]
	],
	// Used in the web installer. Test it after modifying this definition!
	'mediawiki.legacy.shared' => [
		'targets' => [ 'desktop', 'mobile' ],
		'styles' => [
			'resources/src/mediawiki.legacy/shared.css' => [ 'media' => 'screen' ]
		],
	],
	'mediawiki.legacy.oldshared' => [
		'styles' => [
			'resources/src/mediawiki.legacy/oldshared.css' => [ 'media' => 'screen' ]
		],
	],

	/* MediaWiki UI */

	'mediawiki.ui' => [
		'deprecated' => 'Please use OOUI instead.',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/default.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.ui.checkbox' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/checkbox.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.ui.radio' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/radio.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	// Lightweight module for anchor styles
	'mediawiki.ui.anchor' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/anchors.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	// Lightweight module for button styles
	'mediawiki.ui.button' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/buttons.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.ui.input' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/inputs.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.ui.icon' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/icons.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	// Lightweight module for text styles
	'mediawiki.ui.text' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/text.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.widgets' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.NamespaceInputWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.ComplexNamespaceInputWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.TitleWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.TitleInputWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.TitleSearchWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.ComplexTitleInputWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.TitleOptionWidget.js',
		],
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.TitleWidget.less',
			],
		],
		'dependencies' => [
			'oojs-ui-widgets',
			'mediawiki.widgets.styles',
			// TitleInputWidget
			'mediawiki.Title',
			'mediawiki.api',
			'mediawiki.String',
		],
		'messages' => [
			// NamespaceInputWidget
			'blanknamespace',
			'namespacesall',
			// TitleInputWidget
			'mw-widgets-titleinput-description-new-page',
			'mw-widgets-titleinput-description-redirect',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.styles' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.ComplexNamespaceInputWidget.base.css',
				'resources/src/mediawiki.widgets/mw.widgets.ComplexTitleInputWidget.base.css',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.DateInputWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.CalendarWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.DateInputWidget.js',
		],
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.CalendarWidget.less',
				'resources/src/mediawiki.widgets/mw.widgets.DateInputWidget.less',
			],
		],
		'messages' => [
			'mw-widgets-dateinput-no-date',
			'mw-widgets-dateinput-placeholder-day',
			'mw-widgets-dateinput-placeholder-month',
		],
		'dependencies' => [
			'oojs-ui-widgets',
			'oojs-ui.styles.icons-movement',
			'moment',
			'mediawiki.widgets.DateInputWidget.styles',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.DateInputWidget.styles' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.DateInputWidget.styles.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.visibleLengthLimit' => [
		'scripts' => [
			'resources/src/mediawiki.widgets.visibleLengthLimit/mediawiki.widgets.visibleLengthLimit.js'
		],
		'dependencies' => [
			'oojs-ui-core',
			'jquery.lengthLimit',
			'mediawiki.language',
			'mediawiki.String',
		],
		'targets' => [ 'desktop', 'mobile' ]
	],
	'mediawiki.widgets.datetime' => [
		'scripts' => [
			'resources/src/mediawiki.widgets.datetime/mediawiki.widgets.datetime.js',
			'resources/src/mediawiki.widgets.datetime/CalendarWidget.js',
			'resources/src/mediawiki.widgets.datetime/DateTimeFormatter.js',
			'resources/src/mediawiki.widgets.datetime/DateTimeInputWidget.js',
			'resources/src/mediawiki.widgets.datetime/ProlepticGregorianDateTimeFormatter.js',
		],
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets.datetime/CalendarWidget.less',
				'resources/src/mediawiki.widgets.datetime/DateTimeInputWidget.less',
			],
		],
		'messages' => [
			'timezone-utc',
			'timezone-local',
			'january',
			'february',
			'march',
			'april',
			'may_long',
			'june',
			'july',
			'august',
			'september',
			'october',
			'november',
			'december',
			'jan',
			'feb',
			'mar',
			'apr',
			'may',
			'jun',
			'jul',
			'aug',
			'sep',
			'oct',
			'nov',
			'dec',
			'sunday',
			'monday',
			'tuesday',
			'wednesday',
			'thursday',
			'friday',
			'saturday',
			'sun',
			'mon',
			'tue',
			'wed',
			'thu',
			'fri',
			'sat',
			'period-am',
			'period-pm',
		],
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui.styles.icons-moderation',
			'oojs-ui.styles.icons-movement',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.expiry' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.ExpiryInputWidget.js',
		],
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui-widgets',
			'moment',
			'mediawiki.widgets.datetime'
		],
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.widgets/mw.widgets.ExpiryInputWidget.less',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.CheckMatrixWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.CheckMatrixWidget.js',
		],
		'dependencies' => [
			'oojs-ui-core',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.CategoryMultiselectWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.CategoryTagItemWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.CategoryMultiselectWidget.js',
		],
		'dependencies' => [
			'oojs-ui-widgets',
			'mediawiki.api',
			'mediawiki.ForeignApi',
			'mediawiki.Title',
		],
		'messages' => [
			'red-link-title',
			'mw-widgets-categoryselector-add-category-placeholder',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.SelectWithInputWidget' => [
		'scripts' => 'resources/src/mediawiki.widgets/mw.widgets.SelectWithInputWidget.js',
		'dependencies' => [
			'mediawiki.widgets.SelectWithInputWidget.styles',
			'oojs-ui-widgets',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.SelectWithInputWidget.styles' => [
		'styles' => 'resources/src/mediawiki.widgets/mw.widgets.SelectWithInputWidget.base.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.SizeFilterWidget' => [
		'scripts' => 'resources/src/mediawiki.widgets/mw.widgets.SizeFilterWidget.js',
		'dependencies' => [
			'mediawiki.widgets.SizeFilterWidget.styles',
			'oojs-ui-widgets',
		],
		'messages' => [
			'minimum-size',
			'maximum-size',
			'pagesize',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.SizeFilterWidget.styles' => [
		'styles' => 'resources/src/mediawiki.widgets/mw.widgets.SizeFilterWidget.base.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.MediaSearch' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.APIResultsProvider.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.APIResultsQueue.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaResourceProvider.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaSearchProvider.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaResourceQueue.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaSearchQueue.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaSearchWidget.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaResultWidget.js',
		],
		'styles' => [
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaSearchWidget.css',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaResultWidget.css',
		],
		'dependencies' => [
			'oojs-ui-widgets',
			'mediawiki.ForeignApi',
			'mediawiki.Title',
			'mediawiki.util',
		],
		'messages' => [
			'mw-widgets-mediasearch-noresults',
			'mw-widgets-mediasearch-input-placeholder',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.UserInputWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.UserInputWidget.js',
		],
		'dependencies' => [
			'mediawiki.api',
			'oojs-ui-widgets',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.UsersMultiselectWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.UsersMultiselectWidget.js',
		],
		'dependencies' => [
			'mediawiki.api',
			'oojs-ui-widgets',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.SearchInputWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.SearchInputWidget.js',
		],
		'dependencies' => [
			'mediawiki.searchSuggest',
			'oojs-ui.styles.icons-interactions',
			// FIXME: Needs TitleInputWidget only
			'mediawiki.widgets',
		],
	],
	'mediawiki.widgets.SearchInputWidget.styles' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.SearchInputWidget.css',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.StashedFileWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.StashedFileWidget.js',
		],
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.StashedFileWidget.less',
			],
		],
		'dependencies' => [
			'mediawiki.api',
			'oojs-ui-core',
		],
	],

	/**
	 * html5shiv
	 *
	 * This library is intended to run on older browsers
	 * that MediaWiki no longer supports as Grade A, and
	 * is not loaded through the normal module loading
	 * system.
	 */
	'html5shiv' => [
		'scripts' => [
			'resources/lib/html5shiv/html5shiv.js'
		],
		'raw' => true,
	],

	/* EasyDeflate */

	'easy-deflate.core' => [
		'scripts' => [ 'resources/lib/easy-deflate/easydeflate.js' ],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'easy-deflate.deflate' => [
		'scripts' => [ 'resources/lib/easy-deflate/deflate.js' ],
		'dependencies' => [ 'easy-deflate.core' ],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'easy-deflate.inflate' => [
		'scripts' => [ 'resources/lib/easy-deflate/inflate.js' ],
		'dependencies' => [ 'easy-deflate.core' ],
		'targets' => [ 'desktop', 'mobile' ],
	],

	/* OOjs */
	'oojs' => [
		'scripts' => [
			'resources/lib/oojs/oojs.jquery.js',
			'resources/src/oojs-global.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.router' => [
		'scripts' => [
			'resources/src/mediawiki.router/index.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
		'dependencies' => [
			'oojs-router',
		],
	],

	'oojs-router' => [
		'scripts' => [
			'resources/lib/oojs-router/oojs-router.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
		'dependencies' => [
			'oojs',
		],
	],

	/* OOjs UI */

	// Omnibus module.
	'oojs-ui' => [
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui-widgets',
			'oojs-ui-toolbars',
			'oojs-ui-windows',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	// The core JavaScript library.
	'oojs-ui-core' => [
		'class' => ResourceLoaderOOUIFileModule::class,
		'scripts' => [
			'resources/lib/ooui/oojs-ui-core.js',
			'resources/src/ooui-local.js',
		],
		'themeScripts' => 'core',
		'dependencies' => [
			'oojs',
			'oojs-ui-core.styles',
			'oojs-ui.styles.indicators',
			'oojs-ui.styles.textures',
			'mediawiki.language',
			'oojs-ui.styles.icons-content',
			'oojs-ui.styles.icons-alerts',
			'oojs-ui.styles.icons-interactions',
		],
		'messages' => [
			'ooui-field-help'
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	// This contains only the styles required by core widgets.
	'oojs-ui-core.styles' => [
		'class' => ResourceLoaderOOUIFileModule::class,
		'styles' => [
			'resources/lib/ooui/wikimedia-ui-base.less', // Providing Wikimedia UI LESS variables to all
		],
		'themeStyles' => 'core',
		'targets' => [ 'desktop', 'mobile' ],
	],
	// Additional widgets and layouts module.
	'oojs-ui-widgets' => [
		'class' => ResourceLoaderOOUIFileModule::class,
		'scripts' => 'resources/lib/ooui/oojs-ui-widgets.js',
		'themeStyles' => 'widgets',
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui.styles.icons-interactions',
			'oojs-ui.styles.icons-content',
			'oojs-ui.styles.icons-editing-advanced',
			'oojs-ui.styles.icons-movement',
			'oojs-ui.styles.icons-moderation',
		],
		'messages' => [
			'ooui-item-remove',
			'ooui-outline-control-move-down',
			'ooui-outline-control-move-up',
			'ooui-outline-control-remove',
			'ooui-selectfile-button-select',
			'ooui-selectfile-dragdrop-placeholder',
			'ooui-selectfile-not-supported',
			'ooui-selectfile-placeholder',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	// You should never directly load this module. The CSS classes it defines are not a public API,
	// they depend on the internal structure of OOUI widgets, which can change at any time. If you
	// find that you need to load this module, you're probably doing something wrong or very hacky.
	'oojs-ui-widgets.styles' => [
		'class' => ResourceLoaderOOUIFileModule::class,
		'themeStyles' => 'widgets',
		'targets' => [ 'desktop', 'mobile' ],
	],
	// Toolbar and tools module.
	'oojs-ui-toolbars' => [
		'class' => ResourceLoaderOOUIFileModule::class,
		'scripts' => 'resources/lib/ooui/oojs-ui-toolbars.js',
		'themeStyles' => 'toolbars',
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui.styles.icons-movement',
		],
		'messages' => [
			'ooui-toolbar-more',
			'ooui-toolgroup-collapse',
			'ooui-toolgroup-expand',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	// Windows and dialogs module.
	'oojs-ui-windows' => [
		'class' => ResourceLoaderOOUIFileModule::class,
		'scripts' => 'resources/lib/ooui/oojs-ui-windows.js',
		'themeStyles' => 'windows',
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui.styles.icons-movement',
		],
		'messages' => [
			'ooui-dialog-message-accept',
			'ooui-dialog-message-reject',
			'ooui-dialog-process-continue',
			'ooui-dialog-process-dismiss',
			'ooui-dialog-process-error',
			'ooui-dialog-process-retry',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'oojs-ui.styles.indicators' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'indicators',
	],
	'oojs-ui.styles.textures' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'textures',
	],
	'oojs-ui.styles.icons-accessibility' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-accessibility',
	],
	'oojs-ui.styles.icons-alerts' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-alerts',
	],
	'oojs-ui.styles.icons-content' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-content',
	],
	'oojs-ui.styles.icons-editing-advanced' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-editing-advanced',
	],
	'oojs-ui.styles.icons-editing-citation' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-editing-citation',
	],
	'oojs-ui.styles.icons-editing-core' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-editing-core',
	],
	'oojs-ui.styles.icons-editing-list' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-editing-list',
	],
	'oojs-ui.styles.icons-editing-styling' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-editing-styling',
	],
	'oojs-ui.styles.icons-interactions' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-interactions',
	],
	'oojs-ui.styles.icons-layout' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-layout',
	],
	'oojs-ui.styles.icons-location' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-location',
	],
	'oojs-ui.styles.icons-media' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-media',
	],
	'oojs-ui.styles.icons-moderation' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-moderation',
	],
	'oojs-ui.styles.icons-movement' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-movement',
	],
	'oojs-ui.styles.icons-user' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-user',
	],
	'oojs-ui.styles.icons-wikimedia' => [
		'class' => ResourceLoaderOOUIImageModule::class,
		'themeImages' => 'icons-wikimedia',
	],
];
