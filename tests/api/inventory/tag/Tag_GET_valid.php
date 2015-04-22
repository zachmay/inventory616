<?php

use Illuminate\Database\Eloquent\Model;

use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;


class Tag_GET_valid extends TestCase {

/* /inventory/tag
Description: test get method when there exists some items.
Assumption: There exists some items.
URL: http://192.168.33.99/api//inventory/tag
Calling Method: GET
Anticipated Result Code: 200
Anticipated Result Datatype: JSON or HTML.
Anticipated Result Data: Any
Test Code Source: tests/api/ /inventory/tag/tag_GET.php;  */
	 
	 
	 
	private $typetest;
	private $invtarr_existing;


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
			$this->invtarr_existing = [
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
			$sel = Item::create($this->invtarr_existing);
		}

	}

	public function tearDown()
	{
		// Cleanup assumptions.
		try
		{
			if(isset($this->invtarr_existing))
			{
				$this->invtarr_existing->delete();
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
		$response = $this->call('GET', 'http://192.168.33.99/api//inventory/tag');
		// confirm some of the response headers.
		$this->assertResponseStatus(200, $response->getStatusCode());
		$this->assertEquals('application/json', $response->headers->get('content-type'));
		// convert json to an array rather than an object.
		$body = json_decode($response->getContent(), true);
		// confirm the content is a correct answer.
		echo($body);
		$this->assertEquals($body, $this->invtarr_existing());
	}


}

