<?php

use Illuminate\Database\Eloquent\Model;

use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;


class InvalidBuildingRoomTest_GET extends TestCase {

	/**
	  *  
	  *  /buildings/:id/rooms/:roomid/inventory
	  *  Test Case 2
	  *  Description: Test this api with an fraud room id and an fraud buildings id.
	  *  Assumption: invalid roomid <79234> and invalid id <97391>.
	  *  URL: http://192.168.33.99/api/buildings/97391/rooms/79234/inventory
	  *  Calling Method: GET
	  *  Anticipated Result Code: 404.
	  *  Anticipated Result Datatype: JSON or HTML.
	  *  Anticipated Result Data: Any.
	  *  Test Code Source: tests/api/building_room_inventory/InvalidBuildingRoomTest_GET.php
	  *
	 **/

	public function setUp()
	{
		parent::SetUp();
		// Setup assumptions.
		Model::unguard();
		$building = Building::where('id', '=', 97391);
		if(isset($building))
		{
			$building->Delete();
		}
		$room = Room::where('id', '=', 79234,
						'and', 'building_id','=', 97391);
		if(isset($room))
		{
			$room->Delete();
		}
		//fwrite(STDOUT, __METHOD__ . "\n");
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
		$response = $this->call('GET', 'http://192.168.33.99/api/buildings/97391/rooms/79234/inventory');
		// confirm some of the response headers.
		$this->assertResponseStatus(404, $response->getStatusCode());
		// convert json to an array rather than an object.
		$body = json_decode($response->getContent(), true);
		// confirm the content is a correct answer.
		echo($body);
	}


}
