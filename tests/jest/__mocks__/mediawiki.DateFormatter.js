module.exports = {
	formatTimeAndDate( date ) {
		return new Date( date ).toISOString();
	},

	forUser() {
		return {
			getShortZoneName: () => 'GMT',
			getZoneOffsetMinutes: () => 0,
			formatForDateTimeInput: () => '2100-01-01T12:59',
			formatIso: () => '2999-01-23T12:34:00Z'
		};
	},

	forSiteZone: () => module.exports.forUser(),
	forUtc: () => module.exports.forUser()
};
