-- MySQL test file for DatabaseTest::testStoredFunctions()

DELIMITER //

CREATE FUNCTION mw_test_function()
RETURNS int DETERMINISTIC
BEGIN
	SET @foo = 21;
	RETURN @foo * 2;
END//

DELIMITER //
