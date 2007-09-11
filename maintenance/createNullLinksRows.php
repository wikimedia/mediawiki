<?

require( 'commandLine.inc' );

$db_master = wfGetDB( DB_MASTER );
$db_slave = wfGetDB( DB_SLAVE );

## Do pagelinks update

echo "Updating pagelinks with null rows.\n";

$count = 0;

list( $page, $pagelinks ) = $db_slave->tableNamesN( 'page', 'pagelinks' );

$pl_query = "SELECT page_id
	FROM $page
	LEFT JOIN $pagelinks ON page_id=pl_from
	WHERE pl_from IS NULL";

$res = $db_slave->query( $pl_query, 'createNullLinksRows' );

$buffer = array();

while ($row = $db_slave->fetchObject( $res ))
{
	$buffer[] = array( 'pl_from' => $row->page_id, 'pl_namespace' => 0, 'pl_title' => '' );

	$count++;

	if (count($buffer) > 100)
	{
		#Batch-insert

		echo "$count pages..\n";

		$db_master->insert( 'pagelinks', $buffer, 'createNullLinksRows', array('IGNORE') );

		wfWaitForSlaves(10);

		$buffer = array();
	}
}

# Insert the rest

echo "$count pages..\n";

$db_master->insert( 'pagelinks', $buffer, 'createNullLinksRows', array('IGNORE') );

wfWaitForSlaves(10);

## Do categorylinks update

$buffer = array();

echo "Updating categorylinks with null rows.\n";

list( $page, $categorylinks ) = $db_slave->tableNamesN( 'page', 'categorylinks' );

$pl_query = "SELECT page_id
	FROM $page
	LEFT JOIN $categorylinks ON page_id=cl_from
	WHERE cl_from IS NULL";

$res = $db_slave->query( $pl_query, 'createNullLinksRows' );

$buffer = array();

while ($row = $db_slave->fetchObject( $res ))
{
	$buffer[] = array( 'cl_from' => $row->page_id, 'cl_to' => 0, 'cl_sortkey' => '' );

	if (count($buffer) > 100)
	{
		#Batch-insert

		echo "$count pages..\n";

		$db_master->insert( 'categorylinks', $buffer, 'createNullLinksRows', array('IGNORE') );

		wfWaitForSlaves(10);

		$buffer = array();
	}
}

echo "$count pages..\n";

$db_master->insert( 'categorylinks', $buffer, 'createNullLinksRows', array('IGNORE') );

$buffer = array();

echo "Done!\n";
