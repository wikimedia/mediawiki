QUnit.module( 'startup/clientprefs', () => {
	// See /resources/src/startup/clientprefs.js
	const clientprefs = mw.clientprefs;

	QUnit.test.each( 'clientprefs()', {
		'default behaviour': {
			cookie: '',
			initialClass: 'nojs',
			initialJsClass: 'js',
			expectedClass: 'js'
		},
		'default behaviour on skin with clientprefs': {
			cookie: '',
			initialClass: 'nojs example-foo-clientpref-disabled',
			initialJsClass: 'js example-foo-clientpref-disabled',
			expectedClass: 'js example-foo-clientpref-disabled'
		},
		'toggle feature with boolean suffix': {
			cookie: 'mwclientpreferences=example-foo-clientpref-enabled',
			initialClass: 'nojs example-foo-clientpref-disabled',
			initialJsClass: 'js example-foo-clientpref-disabled',
			expectedClass: 'js example-foo-clientpref-enabled'
		},
		'toggle feature with number suffix': {
			cookie: 'mwclientpreferences=example-foo-clientpref-4',
			initialClass: 'nojs example-foo-clientpref-0',
			initialJsClass: 'js example-foo-clientpref-0',
			expectedClass: 'js example-foo-clientpref-4'
		},
		'toggle feature with word suffix': {
			cookie: 'mwclientpreferences=example-foo-clientpref-m4rty',
			initialClass: 'nojs example-foo-clientpref-f1ux',
			initialJsClass: 'js example-foo-clientpref-f1ux',
			expectedClass: 'js example-foo-clientpref-m4rty'
		},
		'ignore key without clientpref suffix': {
			cookie: 'mwclientpreferences=example-foo-4',
			initialClass: 'nojs example-foo-clientpref-0 example-foo-0',
			initialJsClass: 'js example-foo-clientpref-0 example-foo-0',
			expectedClass: 'js example-foo-clientpref-0 example-foo-0'
		},
		'ignore value with dashes': {
			cookie: 'mwclientpreferences=example-foo-clientpref-m4-rty',
			initialClass: 'nojs example-foo-clientpref-f1ux',
			initialJsClass: 'js example-foo-clientpref-f1ux',
			expectedClass: 'js example-foo-clientpref-f1ux'
		},
		'toggle multiple features': {
			cookie: 'mwclientpreferences=example-color-clientpref-dark%2Cmw-foo-clientpref-4',
			initialClass: 'nojs example-foo mw-foo-clientpref-0 example-foo-bar example-color-clientpref-light',
			initialJsClass: 'js example-foo mw-foo-clientpref-0 example-foo-bar example-color-clientpref-light',
			expectedClass: 'js example-foo mw-foo-clientpref-4 example-foo-bar example-color-clientpref-dark'
		}
	}, ( assert, data ) => {
		const doc = {
			cookie: data.cookie,
			documentElement: {
				className: data.initialClass
			}
		};
		const vars = {
			jsClass: data.initialJsClass
		};
		clientprefs( doc, vars );

		assert.strictEqual( doc.documentElement.className, data.expectedClass, 'className' );
	} );
} );
