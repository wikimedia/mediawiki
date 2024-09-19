const { defineComponent, h, ref } = require( 'vue' );
const { CdxTextInput } = require( '@wikimedia/codex' );

/**
 * A wrapper for a TextInput that uses HTMLInputElement's `checkValidity()` to validate
 * the input against any constraints given via HTML attributes (i.e. `required`, or `min=1`).
 *
 * @todo replace with Codex useNativeValidation composable once available (T373872#10175988)
 */
module.exports = exports = defineComponent( {
	name: 'ValidatingTextInput',
	components: {
		CdxTextInput
	},
	emits: [ 'update:status' ],
	setup( props, { emit } ) {
		const status = ref( 'default' );
		const messages = ref( {} );

		/**
		 * Clear error status when input is valid and emit the `update:messages` event.
		 *
		 * @param {Event} e
		 */
		const onInput = ( e ) => {
			if ( e.target.checkValidity() ) {
				status.value = 'default';
				messages.value = {};
				emit( 'update:status', status.value, messages.value );
			}
		};

		/**
		 * Set error status when input is invalid and emit the `update:messages` event.
		 *
		 * @param {Event} e
		 */
		const onInvalid = ( e ) => {
			status.value = 'error';
			messages.value = {
				error: e.target.validationMessage
			};
			emit( 'update:status', status.value, messages.value );
		};

		return {
			status,
			messages,
			onInput,
			onInvalid
		};
	},
	render() {
		return h( CdxTextInput, Object.assign( {}, this.$props, {
			status: this.status,
			onInput: this.onInput,
			onInvalid: this.onInvalid
		} ) );
	}
} );
