<?php

use Illuminate\Database\Eloquent\Model;

use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;


class InventoryTest_POST extends TestCase {

	/**
	  *  /inventory
	  *  Test case 3
	  *  Description: Add a new item. 
	  *  Assumption: There are no item conflict.
	  *  URL: http://192.168.33.99/api/inventory
	  *  Calling Method: POST
	  *  Data to post:  [
	  *  					'asset_tag'          => 'test_tag',
	  *  					'name'               => 'test_comp',
	  *  					'funding_source'     => 'test_src',
	  *  					'item_type_id'       => $this->typetest->id,
	  *  					'model'              => 'test model',
	  *  					'cpu'                => '2.2 GHz Quad-core',
	  *  					'ram'                => '16GB',
	  *  					'hard_disk'          => '512GB',
	  *  					'os'                 => 'TEST OS',
	  *  					'administrator_flag' => true,
	  *  					'teacher_flag'       => false,
	  *  					'student_flag'       => false,
	  *  					'institution_flag'   => false
	  *  				]
	  *  Anticipated Result Code: 201
	  *  Anticipated Result Datatype: JSON.
	  *  Anticipated Result Data: a list of all posted inventory.
	  *  Test Code Source:tests/api/inventory/inventory_POST.php
	  *
	 **/
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
        $_POST = json_encode([
            'asset_tag'          => 'test_tag',
            'name'               => 'test_comp',
            'funding_source'     => 'test_src',
            'item_type_id'       => $this->typetest->id,
            'model'              => 'test model',
            'cpu'                => '2.2 GHz Quad-core',
            'ram'                => '16GB',
            'hard_disk'          => '512GB',
            'os'                 => 'TEST OS',
            'administrator_flag' => true,
            'teacher_flag'       => false,
            'student_flag'       => false,
            'institution_flag'   => false
        ]);


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
		$response = $this->call('POST', 'http://192.168.33.99/api/inventory');
		// confirm some of the response headers.
		$this->assertResponseStatus(201, $response->getStatusCode());
		// confirm the content is a correct answer.
		echo($body);
		$this->invt = Item::where('name', '=', 'test_comp');
		$this->assertTrue(isset($this->invt->get()));
		$this->assertEquals(json_decode($_POST), [$this->invt->toArray()]);
	}


}
