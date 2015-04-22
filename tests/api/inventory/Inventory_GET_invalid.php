<?php

use Illuminate\Database\Eloquent\Model;

use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;


class Inventory_GET_invalid extends TestCase {

/* 
/inventory
Description: Retrieve a listing of all existing inventory items. 
Assumption: There are no items.
URL: http://192.168.33.99/api/inventory
Calling Method: GET
Anticipated Result Code: 404
Anticipated Result Datatype: JSON or HTML:.
Anticipated Result Data: Any.
Test Code Source: tests/api/inventory/inventory_GET.php */




	private $invt;
	private $typetest;

	public function setUp()
	{
		parent::SetUp();
		// Setup assumptions.
		Model::unguard();
		$this->typetest = ItemType::where('name', '=', 'Test_Type')->first();
		if(is_null($this->typetest))
     		$this->typetest = ItemType::create(['name' => 'Computer']);

		$sel = Item::where('name', '=', 'test_comp');
		if(isset($sel->first()))
			$sel->delete();
	}

	public function tearDown()
	{
		// Cleanup assumptions.
		try
		{
			if(isset($this->invt))
			{
				$this->invt->delete();
			}
			if(isset($this->typetest))
			{
				$this->typetest->delete();
			}
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
		$response = $this->call('GET','http://192.168.33.99/api/inventory');
		// confirm some of the response headers.
		$this->assertResponseStatus(404, $response->getStatusCode());
		$body = json_decode($response->getContent(), true);
		echo($body);
	}


}
