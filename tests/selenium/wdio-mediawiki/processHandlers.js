const logActiveHandles = ( signal ) => {
	// eslint-disable-next-line no-underscore-dangle
	console.log( `Received ${ signal }. Active handles:`, process._getActiveHandles() );
};

export const setupProcessHandlers = () => {
	process.on( 'uncaughtException', ( error ) => {
		console.error( 'Caught uncaughtException: ', error );
		// eslint-disable-next-line n/no-process-exit
		process.exit( 1 );
	} );

	process.on( 'unhandledRejection', ( reason, promise ) => {
		console.log( 'Unhandled Rejection at:', promise, 'reason:', reason );
	} );

	for ( const signal of [ 'SIGINT', 'SIGTERM' ] ) {
		process.on( signal, () => {
			logActiveHandles( signal );
		} );
	}
};
