<?php

use Illuminate\Database\Eloquent\Model;

use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;


class ValidRoomTest_GET extends TestCase {

	/**
	  *
	  *  /buildings/:id/rooms/:roomid/inventory
	  *  Test Case 1
	  *  Description: Test this api with an existed room id which belong
      *               to an existed buildings id.
	  *  Assumption: Room with roomid <101> belongs to building with id <3320>.
	  *  URL: http://192.168.33.99/api/buildings/101/rooms/3320/inventory
	  *  Calling Method: GET
	  *  Anticipated Result Code: 200.
	  *  Anticipated Result Datatype: JSON.
	  *  Anticipated Result Data: a list of inventory belong to the room.
	  *  Test Code Source: tests/api/building_room_inventory/ValidRoomTest_GET.php
	  *
	 **/
	private $room;
	private $building;

	public function setUp()
	{
		parent::SetUp();
		// Setup assumptions.
		Model::unguard();
		$building = Building::where('id', '=', 3320)->first();
		if(is_null($building))
		{
			$building = Building::create(['id' => '101', 'name' => 'test_bld', 'description' => 'test_bld']);
		}
		//echo($building->toJson());

		$room = Room::where('id', '=', 101,
						'and', 'building_id','=', $building->id)->first();
		if(is_null($room))
		{
			$room = Room::create(['id' => '101', 'name' => 'test_room', 'description' => 'test_room', 'building_id' => $building->id]);
		}
		//echo($room->toJson());
		$this->room = $room;
		$this->building = $building;
		//fwrite(STDOUT, __METHOD__ . "\n");
	}

	public function tearDown()
	{
		// Cleanup assumptions.
		try
		{
			if(isset($this->room))
			{
				$this->room->Delete();
			}
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
		$response = $this->call('GET', 'http://192.168.33.99/api/buildings/3320/rooms/101/inventory');
		// confirm some of the response headers.
		$this->assertResponseStatus(200, $response->getStatusCode());
		$this->assertEquals('application/json', $response->headers->get('content-type'));
		// convert json to an array rather than an object.
		$body = json_decode($response->getContent(), true);
		// confirm the content is a correct answer.
		echo($body);
		$this->assertEquals($body, $this->room->toArray());
	}


}
