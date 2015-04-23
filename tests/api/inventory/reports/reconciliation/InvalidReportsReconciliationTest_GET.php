<?php
use Illuminate\Database\Eloquent\Model;
use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;
class InvalidReportsReconciliationTest_GET extends TestCase {
/* 
/inventory/types
Description: Test this api a query parameter "last-checkin" when there is a fraud report in JSON or CSV format.
Assumption: There is a fraud report in format CSV.
URL: http://192.168.33.99/api/inventory/reports/reconciliation/
Calling Method: GET
Anticipated Result Code: 404.
Anticipated Result Datatype: JSON or HTML.
Anticipated Result Data: Any.
Test Code Source: tests/api/reports_reconciliation_inventory/InvalidReportsReconciliationTest_GET.php;  */
	private $invt;
	private $typetest;
	public function setUp()
	{
		parent::SetUp();
		// Setup assumptions.
		Model::unguard();
		$this->typetest = ItemType::where('name', '=', 'Test_Type')->first();
		if(isset($sel->first()))
			$sel->delete();
	}
	public function tearDown()
	{
		// Cleanup assumptions.
		try
		{
		
		}
		catch(Exception $e)
		{
			echo($e);
			echo("\n");
		}
		finally
		{
			parent::tearDown();
			//fwrite(STDOUT, __METHOD__ . "\n");
		}
	}
	public function testGet()
	{
		$response = $this->call('GET', ' http://192.168.33.99/api/inventory/reports/reconciliation/');
		// confirm some of the response headers.
		$this->assertResponseStatus(404, $response->getStatusCode());
		// confirm the content is a correct answer.
		$body = json_decode($response->getContent(), true);
		echo($body);
	}
