<?php
use Illuminate\Database\Eloquent\Model;
use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;
class InvalidHistoryInvalIditemTest_GET extends TestCase {
/* 
/inventory/types
Description: Test this api with an fraud history id for the inventory item with the fraud ID.
Assumption: Invalid history id number with historyid <historyid> belongs to invalid inventory item with id <itemid>.
URL:  http://192.168.33.99/api/inventory/itemid/history/historyid/
Calling Method: GET
Anticipated Result Code: 404.
Anticipated Result Datatype: JSON or HTML.
Anticipated Result Data: Any.
Test Code Source:tests/api/history_inventory/InvalidHistoryInvalIditemTest_GET.php
;  */
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
		$response = $this->call('GET', ' http://192.168.33.99/api/inventory/itemid/history/historyid/');
		// confirm some of the response headers.
		$this->assertResponseStatus(404, $response->getStatusCode());
		// confirm the content is a correct answer.
		$body = json_decode($response->getContent(), true);
		echo($body);
	}
