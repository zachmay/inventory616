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
	  *  Description: Modify the information of a existed inventory.
	  *  Assumption: Inventory with tag <test_tag> exists..
	  *  URL: http://192.168.33.99/api/inventory/test_tag
	  *  Data to put:  [
	  *              'asset_tag'          => 'test_tag',
	  *              'name'               => 'test_compMoid',
	  *              'funding_source'     => 'test_srcMoid',
	  *              'item_type_id'       => $this->typetest->id,
	  *              'model'              => 'test model Moid',
	  *              'cpu'                => '2.2 GHz Quad-core Moid',
	  *              'ram'                => '16GB Moid',
	  *              'hard_disk'          => '512GB Moid',
	  *              'os'                 => 'TEST OS Moid',
	  *              'administrator_flag' => false,
	  *              'teacher_flag'       => true,
	  *              'student_flag'       => true,
	  *              'institution_flag'   => true
	  *          ]
	  *  Calling Method: PUT
	  *  Anticipated Result Code: 200
	  *  Anticipated Result Datatype: JSON:.
	  *  Anticipated Result Data: a list of all existing resources.
	  *  Test Code Source: tests/api/ /inventory/tag/tag_PUT.php; 
	  *
	 **/
	private $typetest;
	private $invtarr_beforeModified;
	private $invtarr_Modification;
	private $invt_afterModified;

	public function setUp()
	{
		parent::SetUp();
		// Setup assumptions.
		Model::unguard();
		$this->typetest = ItemType::where('name', '=', 'Test_Type')->first();
		if(is_null($this->typetest))
     		$this->typetest = ItemType::create(['name' => 'Computer']);

		$sel = Item::where('asset_tag', '=', 'test_tag')->first();
		if(is_null($sel))
		{
			$this->invtarr_beforeModified = [
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
		    ];
			$sel = Item::create($this->invtarr_beforeModified);
		}

		$this->invtarr_Modification = [
			'asset_tag'          => 'test_tag',
			'name'               => 'test_compMoid',
			'funding_source'     => 'test_srcMoid',
			'item_type_id'       => $this->typetest->id,
			'model'              => 'test model Moid',
			'cpu'                => '2.2 GHz Quad-core Moid',
			'ram'                => '16GB Moid',
			'hard_disk'          => '512GB Moid',
			'os'                 => 'TEST OS Moid',
			'administrator_flag' => false,
			'teacher_flag'       => true,
			'student_flag'       => true,
			'institution_flag'   => true
		];
		$_REQUEST = $this->invtarr_Modification;
	}

	public function tearDown()
	{
		// Cleanup assumptions.
		try
		{
			if(isset($this->invt_afterModified))
			{
				$this->invt_afterModified->delete();
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
		//$client = static::createClient();

        //$req = $client->request('PUT',
		//			'/post/hello-world',
		//			json_encode($this->invtarr_Modification));
		//$response = $req->getResponse();
		$response = $this->call('PUT', 'http://192.168.33.99/api/inventory/test_tag');
		// confirm some of the response headers.
		$this->assertResponseStatus(201, $response->getStatusCode());
		// confirm the content is a correct answer.
		$body = json_decode($response->getContent(), true);
		echo($body);
		$this->invt_afterModified = Item::where('asset_tag', '=', 'test_tag')->first();
		$this->assertTrue(isset($this->invt_afterModified));
		$this->assertTrue(!($this->invtarr_beforeModified === $this->invt_afterModified->toArray()));
		$this->assertEquals($this->invtarr_Modification,
					$this->invt_afterModified->toArray());
	}


}
