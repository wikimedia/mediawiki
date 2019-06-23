Wikimedia API Parameter Validator
=================================

This library implements a system for processing and validating parameters to an
API from data like that in PHP's `$_GET`, `$_POST`, and `$_FILES` arrays, based
on a declarative definition of available parameters.

Usage
-----

<pre lang="php">
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\ParamValidator\SimpleCallbacks as ParamValidatorCallbacks;
use Wikimedia\ParamValidator\ValidationException;

$validator = new ParamValidator(
	new ParamValidatorCallbacks( $_POST + $_GET, $_FILES ),
	$serviceContainer->getObjectFactory()
);

try {
	$intValue = $validator->getValue( 'intParam', [
			ParamValidator::PARAM_TYPE => 'integer',
			ParamValidator::PARAM_DEFAULT => 0,
			IntegerDef::PARAM_MIN => 0,
			IntegerDef::PARAM_MAX => 5,
	] );
} catch ( ValidationException $ex ) {
	$error = lookupI18nMessage( 'param-validator-error-' . $ex->getFailureCode() );
	echo "Validation error: $error\n";
}
</pre>

I18n
----

This library is designed to generate output in a manner suited to use with an
i18n system. To that end, errors and such are indicated by means of "codes"
consisting of ASCII lowercase letters, digits, and hyphen (and always beginning
with a letter).

Additional details about each error, such as the allowed range for an integer
value, are similarly returned by means of associative arrays with keys being
similar "code" strings and values being strings, integers, or arrays of strings
that are intended to be formatted as a list (e.g. joined with commas). The
details for any particular "message" will also always have the same keys in the
same order to facilitate use with i18n systems using positional rather than
named parameters.

For possible codes and their parameters, see the documentation of the relevant
`PARAM_*` constants and TypeDef classes.

Running tests
-------------

    composer install --prefer-dist
    composer test
