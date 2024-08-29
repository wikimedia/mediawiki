/**
 * Mock calls to mw.config.get().
 * The default implementation correlates to the SpecialBlock::codexFormData property in PHP.
 *
 * @param {Object} [config]
 */
function mockMwConfigGet( config = {} ) {
	const mockConfig = Object.assign( {
		blockAlreadyBlocked: false,
		blockTargetUser: null,
		blockAllowsEmailBan: true,
		blockAllowsUTEdit: true,
		blockAutoblockExpiry: '1 day',
		blockDefaultExpiry: 'infinite',
		blockHideUser: true,
		blockExpiryOptions: {
			infinite: 'infinite',
			'Other time:': 'other'
		},
		blockReasonOptions: [
			{ label: 'block-reason-1', value: 'block-reason-1' },
			{ label: 'block-reason-2', value: 'block-reason-2' }
		]
	}, config );
	mw.config.get.mockImplementation( ( key ) => mockConfig[ key ] );
}

module.exports = {
	mockMwConfigGet
};
