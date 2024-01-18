'use strict';
module.exports = {
	opts: {
		destination: 'docs/js',
		package: 'resources/package.json',
		pedantic: true,
		readme: 'resources/README.md',
		recurse: true,
		template: 'node_modules/jsdoc-wmf-theme'
	},
	plugins: [
		'plugins/markdown',
		'plugins/summarize'
	],
	source: {
		include: [
			'resources/src/'
		],
		exclude: [
			/* The following modules are temporarily disabled as we haven't
			 got round to reviewing them and incorporating them into the documentation page yet. */
			'resources/src/codex',
			'resources/src/codex-search',
			'resources/src/mediawiki.ForeignApi',
			'resources/src/mediawiki.ForeignStructuredUpload.BookletLayout',
			'resources/src/mediawiki.rcfilters',
			'resources/src/mediawiki.router',
			'resources/src/mediawiki.searchSuggest',
			'resources/src/mediawiki.Upload.BookletLayout',
			'resources/src/mediawiki.Upload.Dialog.js',
			'resources/src/mediawiki.tempUserBanner',
			'resources/src/mediawiki.tempUserCreated',
			'resources/src/mediawiki.htmlform.ooui',
			'resources/src/mediawiki.jqueryMsg',
			'resources/src/mediawiki.language',
			'resources/src/mediawiki.language.months',
			'resources/src/mediawiki.language.specialCharacters',
			'resources/src/mediawiki.libs.jpegmeta',
			'resources/src/mediawiki.messagePoster',
			'resources/src/mediawiki.widgets.datetime',
			'resources/src/moment',
			'resources/src/oojs-global.js',
			'resources/src/mediawiki.notification.convertmessagebox.js',
			'resources/src/ooui-local.js',
			'resources/src/mediawiki.page.gallery.slideshow.js',
			'resources/src/vue'
		]
	},
	templates: {
		cleverLinks: true,
		default: {
			useLongnameInNav: true
		},
		wmf: {
			linkMap: {
				Array: 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array',
				Blob: 'https://developer.mozilla.org/en-US/docs/Web/API/Blob',
				CSSStyleSheet: 'https://developer.mozilla.org/en-US/docs/Web/API/CSSStyleSheet',
				Event: 'https://developer.mozilla.org/en-US/docs/Web/API/Event',
				File: 'https://developer.mozilla.org/en-US/docs/Web/API/File',
				HTMLElement: 'https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement',
				HTMLInputElement: 'https://developer.mozilla.org/en-US/docs/Web/API/HTMLInputElement',
				'JSON.parse': 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/JSON/parse',
				jQuery: 'https://api.jquery.com/jQuery/',
				'jQuery.fn': 'https://api.jquery.com/jQuery/',
				'jQuery.Event': 'https://api.jquery.com/Types/#Event',
				'jQuery.Promise': 'https://api.jquery.com/Types/#Promise',
				Node: 'https://developer.mozilla.org/en-US/docs/Web/API/Node',
				'OO.ui.CopyTextLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.CopyTextLayout.html',
				'OO.ui.DropdownInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.DropdownInputWidget.html',
				'OO.ui.MenuOptionWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MenuOptionWidget.html',
				'OO.ui.MenuTagMultiselectWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MenuTagMultiselectWidget.html',
				'OO.ui.MessageDialog': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MessageDialog.html',
				'OO.ui.OptionWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.OptionWidget.html',
				'OO.ui.ProcessDialog': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ProcessDialog.html',
				'OO.ui.SearchWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.SearchWidget.html',
				'OO.ui.SearchInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.SearchInputWidget.html',
				'OO.ui.TagItemWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.TagItemWidget.html',
				'OO.ui.TagMultiselectWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.TagMultiselectWidget.html',
				'OO.ui.TextInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.TextInputWidget.html',
				'OO.ui.ToggleSwitchWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ToggleSwitchWidget.html',
				'OO.ui.Widget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.Widget.html',
				Promise: 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise',
				Set: 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set',
				URLSearchParams: 'https://developer.mozilla.org/en-US/docs/Web/API/URLSearchParams'
			}
		}
	}
};
