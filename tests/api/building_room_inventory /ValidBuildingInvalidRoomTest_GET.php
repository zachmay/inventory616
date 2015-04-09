<?php

use Illuminate\Database\Eloquent\Model;

use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;


class ValidBuildingInvalidRoomTest_GET extends TestCase {

	/**
	  *  
	  *  /buildings/:id/rooms/:roomid/inventory
	  *  Test Case 3: Pending
	  *  Description: Test this api with an fraud room id and an existed buildings id.
	  *  Assumption: invalid roomid <79234> and valid id <3320>.
	  *  URL: http://192.168.33.99/api/buildings/79234/rooms/3320/inventory
	  *  Calling Method: GET
	  *  Anticipated Result Code: 404.
	  *  Anticipated Result Datatype: JSON or HTML.
	  *  Anticipated Result Data: Any.
	  *  Test Code Source: tests/api/building_room_inventory/ValidBuildingInvalidRoomTest_GET.php
	  *
	 **/

	private $building;

	public function setUp()
	{
		parent::SetUp();
		// Setup assumptions.
		Model::unguard();
		$this->building = Building::where('id', '=', 3320)->first();
		if(is_null($this->building))
		{
			$this->building = Building::create(['id' => '3320', 'name' => 'test_bld', 'description' => 'test_bld']);
		}
		$room = Room::where('id', '=', 79234,
						'and', 'building_id','=', $this->building->id);
		if(isset($room->first()))
		{
			$room->delete();
		}
		//fwrite(STDOUT, __METHOD__ . "\n");
	}

	public function tearDown()
	{
		// Cleanup assumptions.
		try
		{
			if(isset($this->building))
			{
				$this->building->Delete();
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
		$response = $this->call('GET', 'http://192.168.33.99/api/buildings/3320/rooms/79234/inventory');
		// confirm some of the response headers.
		$this->assertResponseStatus(404, $response->getStatusCode());
		// convert json to an array rather than an object.
		$body = json_decode($response->getContent(), true);
		// confirm the content is a correct answer.
		echo($body);
	}


}
