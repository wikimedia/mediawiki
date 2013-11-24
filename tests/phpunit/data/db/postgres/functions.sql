-- Postgres test file for DatabaseTest::testStoredFunctions()

CREATE FUNCTION mw_test_function()
RETURNS INTEGER
LANGUAGE plpgsql AS
$mw$
DECLARE foo INTEGER;
BEGIN
	foo := 21;
	RETURN foo * 2;
END
$mw$;
