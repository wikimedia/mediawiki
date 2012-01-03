test('jquery.delayedBind with data option', function() {
	var $fixture = $('<div>').appendTo('body'),
		data = { magic: "beeswax" },
		delay = 50;

	$fixture.delayedBind(delay, 'testevent', data, function(event) {
		start(); // continue!
		ok(true, 'testevent fired');
		ok(event.data === data, 'data is passed through delayedBind');
	});

	expect(2);
	stop(); // async!

	// We'll trigger it thrice, but it should only happen once.
	$fixture.trigger('testevent', {});
	$fixture.trigger('testevent', {});
	$fixture.trigger('testevent', {});
	$fixture.trigger('testevent', {});
});

test('jquery.delayedBind without data option', function() {
	var $fixture = $('<div>').appendTo('body'),
		data = { magic: "beeswax" },
		delay = 50;

	$fixture.delayedBind(delay, 'testevent', function(event) {
		start(); // continue!
		ok(true, 'testevent fired');
	});

	expect(1);
	stop(); // async!

	// We'll trigger it thrice, but it should only happen once.
	$fixture.trigger('testevent', {});
	$fixture.trigger('testevent', {});
	$fixture.trigger('testevent', {});
	$fixture.trigger('testevent', {});
});

