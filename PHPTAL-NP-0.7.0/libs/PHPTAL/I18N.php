<?php

class PHPTAL_I18N 
{
    function set($varName, $value)
    {
        return GetText::setVar($varName, $value);
    }

    function translate($key)
    {
        return GetText::gettext($key);
    }
}

?>
