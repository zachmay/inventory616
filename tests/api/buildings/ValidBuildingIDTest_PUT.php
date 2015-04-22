<?php
use Illuminate\Database\Eloquent\Model;
use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;
class ValidBuildingIDTest_PUT extends TestCase {
	/*
	Description: Test this api with an existed building id to update building resource. 
	Assumption: Building with id <3320>  exits in the database. The updated school name is unique.
	URL: http://192.168.33.99/api/buildings/PCHS
	Data to Post: ['name' => 'NewSchool', 'description' => 'Adding new schools for test']
	Calling Method: PUT
	Anticipated Result Code: 200.
	Anticipated Result Datatype: JSON.
	Anticipated Result Data: building resource description with id <PCHS>.
	Test Code Source: tests/api/buildings_id/ValidBuildingIDTest_PUT.php
	*/
	
	private $building_beforeModified;
	private $building_Modification;
	private $building_afterModified;
	public function setUp()
	{
		parent::SetUp();
		// Setup assumptions.
		Model::unguard();
		$sel = Building::where('name', '=', 'PCHS')->first();
		if(is_null($sel))
		{
			$this->Building_beforeModified = ['name' => 'PCHS', 'description' => 'Powell Co. High School'];
			$sel = Building::create($this->Building_beforeModified);
		}
		$this->Building_Modification = ['name' => 'PCHS', 'description' => 'Adding new schools for test'];
		$_REQUEST = $this->Building_Modification;
	}
	public function tearDown()
	{
		// Cleanup assumptions.
		try
		{
			if(isset($this->Building_afterModified))
			{
				$this->Building_afterModified->delete();
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
	public function testPut()
	{
		$response = $this->call('PUT', 'http://192.168.33.99/api/buildings/PCHS');
		// confirm some of the response headers.
		$this->assertResponseStatus(201, $response->getStatusCode());
		// confirm the content is a correct answer.
		$body = json_decode($response->getContent(), true);
		echo($body);
		$this->Building_afterModified = Building::where('name', '=', 'PCHS')->first();
		$this->assertTrue(isset($this->Building_afterModified));
		$this->assertTrue(!($this->Building_beforeModified === $this->Building_afterModified->toArray()));
		$this->assertEquals($this->Building_Modification,
					$this->Building_afterModified->toArray());
	}
}
