The `telemetry` library implements a minimal OpenTelemetry tracing client
that is compatible with the OTEL data model but not compliant with the OTEL client specification.

This was developed to avoid taking a dependency on the official OpenTelemetry PHP client,
which was deemed too complex to integrate with MediaWiki ([T340552](https://phabricator.wikimedia.org/T340552)).

`telemetry` requires a PSR-3 logger, a PSR-18 HTTP client and a PSR-17 HTTP factory.
