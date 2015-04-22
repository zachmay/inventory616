<?php
use Illuminate\Database\Eloquent\Model;
use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;
class InValidBuildingIDTest_DELETE extends TestCase {
	/*
	Description: Test this api with an existed building id to delete non-exiting building resource. 
	Assumption: Building with id <3320>  does exit in the database. 
	URL: http://192.168.33.99/api/buildings/3320
	Calling Method: DELETE
	Anticipated Result Code: 404.
	Anticipated Result Datatype: JSON or HTML.
	Anticipated Result Data:  Any.
	Test Code Source: tests/api/buildings_id/InValidBuildingIDTest_DELETE
	*/

	private $building;
	private $typetest;
	public function setUp()
	{
		parent::SetUp();
		// Setup assumptions.
		Model::unguard();
		$sel = Building::where('id', '=', '3320');
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
	public function testDelete()
	{
		$response = $this->call('DELETE', 'http://192.168.33.99/api/buildings/3320');
		// confirm some of the response headers.
		$this->assertResponseStatus(404, $response->getStatusCode());
		// confirm the content is a correct answer.
		$body = json_decode($response->getContent(), true);
		echo($body);
	}
}
