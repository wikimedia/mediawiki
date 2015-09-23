<?php

namespace Liuggio\StatsdClient\Sender;


Class SysLogSender implements SenderInterface
{
    private $priority;

    public function __construct($priority = LOG_INFO)
    {
        $this->priority = $priority;
    }

    /**
     * {@inheritDoc}
     */
    public function open()
    {
        syslog($this->priority, "statsd-client-open");

        return true;
    }

    /**
     * {@inheritDoc}
     */
    function write($handle, $message, $length = null)
    {
        syslog($this->priority, sprintf("statsd-client-write \"%s\" %d Bytes", $message, strlen($message)));

        return strlen($message);
    }

    /**
     * {@inheritDoc}
     */
    function close($handle)
    {
        syslog($this->priority, "statsd-client-close");
    }
}
