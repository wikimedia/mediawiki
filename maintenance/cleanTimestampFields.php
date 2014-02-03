<?php

require_once 'Maintenance.php';

class CleanTimestampFields extends Maintenance {

    public function __construct() {
        parent::__construct();
    }

    public function execute() {
        $this->output("Cleaning the TimeStampFields in Mysql Database\n");
        $dbw = wfGetDB( DB_MASTER );

        if($dbw->getType() != 'mysql')
        {
            $this->output("The database used is not Mysql. This script cannot(and should not) run.\n");
            exit(1);
        }

        $unCleanFields = array
        (
        'user' => array(
                        'user_newpass_time'=>'',
                        'user_touched'=>'NOT NULL',
                        'user_email_authenticated'=>'',
                        'user_email_token_expires'=>'',
                        'user_registration'=>'',
                        'user_editcount'=>'',
                      /*  'user_last_timestamp'=>'' Reason: not found in db*/
                        ),
        'page' => array(
                        'page_touched'=>'NOT NULL',
                        ),
        'revision' => array(
                            'rev_timestamp'=>'NOT NULL',
                            ),
        'archive' => array(
                           'ar_timestamp'=>'NOT NULL',
                           ),
        'categorylinks' => array(
                                 'cl_timestamp'=>'NOT NULL'
                                 ),
         'ipblocks' => array(
                             'ipb_timestamp'=>'NOT NULL',
                             'ipb_expiry'=>'NOT NULL',
                            ),
         'image' => array(
                          'img_timestamp'=>'NOT NULL',
                         ),
         'oldimage' => array(
                             'oi_timestamp'=>'NOT NULL',
                             ),
         'filearchive'=> array(
                                'fa_deleted_timestamp' => '',
                                'fa_timestamp' => '',
                               ),
         'uploadstash'=>array(
                              'us_timestamp'=>'NOT NULL',
                             ),
         'recentchanges' => array(
                                  'rc_timestamp'=>'NOT NULL',
                                  'rc_cur_time'=>'NOT NULL'
                                  ),
         'watchlist' => array(
                              'wl_notificationtimestamp'=>'',
                              ),
         'transcache' => array(
                               'tc_time'=>'NOT NULL',
                               ),
         'logging' => array(
                            'log_timestamp'=>'NOT NULL',
                            ),
         'job' => array(
                        'job_timestamp'=>'',
                        ),
         'querycache_info' => array(
                                    'qci_timestamp'=> 'NOT NULL',
                                    ),
         'page_restrictions' => array(
                                      'pr_expiry'=>'',
                                      ),
         'protected_titles' => array(
                                     'pt_timestamp'=>'NOT NULL',
                                     'pt_expiry'=>'NOT NULL',
                                     ),
         'msg_resource' => array(
                                 'mr_timestamp'=>'NOT NULL',
                                 ),
        );

        foreach($unCleanFields as $table => $columnArray)
        {
            foreach($columnArray as $column => $constraints)
            {
                $tableName = $dbw->tableName($table);
                $query = "ALTER TABLE $tableName MODIFY COLUMN $column timestamp $constraints ;";
                $result = $dbw->query( $query, __METHOD__, true);
                if($result)
                {
                    $this->output("Successfully modified the $table.$column \n");
                }
                else
                {
                    $this->output("ERROR : Could not modify the $table.$column\n");
                    $errorText = $this->mysqlError($dbw);
                    $this->output($errorText.'\n');
                 }
            }
        }
    }
}

$maintClass = "CleanTimestampFields";
require_once REQUIRE_MAINTENANCE_IF_MAIN;

