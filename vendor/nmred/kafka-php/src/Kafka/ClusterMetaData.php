<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// +---------------------------------------------------------------------------
// | SWAN [ $_SWANBR_SLOGAN_$ ]
// +---------------------------------------------------------------------------
// | Copyright $_SWANBR_COPYRIGHT_$
// +---------------------------------------------------------------------------
// | Version  $_SWANBR_VERSION_$
// +---------------------------------------------------------------------------
// | Licensed ( $_SWANBR_LICENSED_URL_$ )
// +---------------------------------------------------------------------------
// | $_SWANBR_WEB_DOMAIN_$
// +---------------------------------------------------------------------------

namespace Kafka;

/**
+------------------------------------------------------------------------------
* Metadata about the kafka cluster
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author ebernhardson@wikimedia.org
+------------------------------------------------------------------------------
*/

interface ClusterMetaData
{
    /**
     * get broker list from kafka metadata
     *
     * @access public
     * @return array
     */
    public function listBrokers();

    /**
     * @param string $topicName
     * @param integer $partitionId
     * @access public
     * @return array
     */
    public function getPartitionState($topicName, $partitionId = 0);

    /**
     * @param string $topicName
     * @access public
     * @return array
     */
    public function getTopicDetail($topicName);
}
