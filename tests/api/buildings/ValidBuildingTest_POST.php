<?php
use Illuminate\Database\Eloquent\Model;
use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;
class ValidBuildingTest_POST extends TestCase {
	/*
	Description: Test this api when create a valid building resource
	Assumption: The building name in data in the request body does not exist in the database.  PCHS is not in the database yet.  
	URL: http://192.168.33.99/api/buildings/
	Calling Method: POST
	Data to Post: ['name' => 'PCHS', 'description' => 'Powell Co. High School']
	Anticipated Result Code: 201.
	Anticipated Result Datatype: JSON.
	Anticipated Result Data:  a building resource corresponding to the posted data. 
	Test Code Source: tests/api/building/ValidBuildingTest_POST.php
	*/
	private $building;

	public function setUp()
	{
		parent::SetUp();
		// Setup assumptions.
		Model::unguard();
        $_POST = json_encode(['name' => 'PCHS', 'description' => 'Powell Co. High School']);
	}
	public function tearDown()
	{
		// Cleanup assumptions.
		try
		{
			if(isset($this->building))
			{
				$this->building->delete();
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
	public function testPost()
	{
		$response = $this->call('POST', 'http://192.168.33.99/api/buildings');
		// confirm some of the response headers.
		$this->assertResponseStatus(201, $response->getStatusCode());
		// confirm the content is a correct answer.
		$body = json_decode($response->getContent(), true);
		echo($body);
		$this->building = Item::where('name', '=', 'PCHS');
		$this->assertTrue(isset($this->building->get()));
		$this->assertEquals(json_decode($_POST), [$this->buildings->toArray()]);
	}
}
