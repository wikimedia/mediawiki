<?php

namespace Liuggio\StatsdClient\Sender;

Interface SenderInterface
{
    /**
     * @abstract
     * @return mixed
     */
    function open();

    /**
     * @abstract
     *
     * @param        $handle
     * @param string $string
     * @param null   $length
     *
     * @return mixed
     */
    function write($handle, $string, $length = null);

    /**
     * @abstract
     *
     * @param $handle
     *
     * @return mixed
     */
    function close($handle);
}
