-- Creates table math used for caching TeX blocks.  Needs to be run
-- on old installations when adding TeX support (2002-12-26)
-- Or, TeX can be disabled via $wgUseTeX=false in LocalSettings.php

-- Note: math table has changed, and this script needs to be run again
-- to create it. (2003-02-02)

DROP TABLE IF EXISTS math;
CREATE TABLE math (
    math_inputhash varchar(16) NOT NULL,
    math_outputhash varchar(16) NOT NULL,
    math_html_conservativeness tinyint(1) NOT NULL,
    math_html text,
    math_mathml text,
    UNIQUE KEY math_inputhash (math_inputhash)
);
