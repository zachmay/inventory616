<?php

use Illuminate\Database\Eloquent\Model;

use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;


class Tag_GET_invalid extends TestCase {

/* 
/inventory/tag
Description: test get method when the specified items are not existing.
Assumption: DELETE method works..
URL: http://192.168.33.99/api//inventory/tag
Calling Method: GET
Anticipated Result Code: 404
Anticipated Result Datatype: JSON or HTML.
Anticipated Result Data: Any
Test Code Source: tests/api/ /inventory/tag/tag_GET.php;  */




private $invt;
	private $typetest;

	public function setUp()
	{
		parent::SetUp();
		// Setup assumptions.
		Model::unguard();

		$sel = Item::where('asset_tag', '=', 'test_tag');
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
		$response = $this->call('GET', 'http://192.168.33.99/api/inventory/test_tag');
		// confirm some of the response headers.
		$this->assertResponseStatus(404, $response->getStatusCode());
		// confirm the content is a correct answer.
		$body = json_decode($response->getContent(), true);
		echo($body);
	}
