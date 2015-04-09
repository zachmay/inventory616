<?php

use Illuminate\Database\Eloquent\Model;

use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;


class InventoryTest_GET extends TestCase {

	/**
	  *
	  *  /inventory/:tag
	  *  Test case 1
	  *  Description: Delete a specified resource.
	  *  Assumption: There’s no such resource existed.
	  *  URL: http://192.168.33.99/api/inventory/test_tag
	  *  Calling Method: DELETE
	  *  Anticipated Result Code: 404
	  *  Anticipated Result Datatype: JSON or HTML:.
	  *  Anticipated Result Data: Any.
	  *  Test Code Source: tests/api/inventory/tag/tag_DELETE.php
	  *
	 **/
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
		$response = $this->call('DELETE', 'http://192.168.33.99/api/inventory/test_tag');
		// confirm some of the response headers.
		$this->assertResponseStatus(404, $response->getStatusCode());
		// confirm the content is a correct answer.
		echo($body);
	}


}
