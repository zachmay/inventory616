<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Building;
use App\Item;
use App\ItemType;
use App\Room;
use App\User;
use App\CheckIn;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
		$this->call('FacilitiesSeeder');
        $this->call('ItemSeeder');
        $this->call('UserSeeder');
        $this->call('CheckinSeeder');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}

}

class FacilitiesSeeder extends Seeder
{
    public function run()
    {
        DB::table('rooms')->delete();
        DB::table('buildings')->delete();


        $pchs = Building::create(['name' => 'PCHS', 'description' => 'Powell Co. High School']);
        Room::create(['name' => 'Room 101', 'description' => 'Mr. Smith', 'building_id' => $pchs->id]);
        Room::create(['name' => 'Room 102', 'description' => 'Mrs. Jones', 'building_id' => $pchs->id]);

        $pcms    = Building::create(['name' => 'PCMS',      'description' => 'Powell Co. Middle School']);
        Room::create(['name' => 'Room 11', 'description' => 'Mr. White', 'building_id' => $pcms->id]);
        Room::create(['name' => 'Room 12', 'description' => 'Mrs. Black', 'building_id' => $pcms->id]);
        Room::create(['name' => 'Gym', 'description' => 'Mrs. Green', 'building_id' => $pcms->id]);

        $bowen   = Building::create(['name' => 'Bowen',     'description' => 'Bowen Elementary School']);
        $city    = Building::create(['name' => 'Clay City', 'description' => 'Clay City Elementary']);
        $stanton = Building::create(['name' => 'Stanton',   'description' => 'Stanton Elementary']);
        $academy = Building::create(['name' => 'Academy',   'description' => 'Powell Co. Academy']);
    }
}

class ItemSeeder extends Seeder
{
    public function run()
    {
        DB::table('items')->delete();
        DB::table('item_types')->delete();

        $comp = ItemType::create(['name' => 'Computer']);
        $tab  = ItemType::create(['name' => 'Tablet']);
        $proj = ItemType::create(['name' => 'Projector']);
        $cam  = ItemType::create(['name' => 'Doc Camera']);
        $int  = ItemType::create(['name' => 'Interactive']);

        Item::create([
            'asset_tag'          => 'ABC123',
            'name'               => 'MacBook Pro',
            'funding_source'     => 'Grant 1234A',
            'item_type_id'       => $comp->id,
            'model'              => 'MacBook Pro 2015',
            'cpu'                => '2.2 GHz Quad-core',
            'ram'                => '16GB',
            'hard_disk'          => '512GB',
            'os'                 => 'Mac OS X 10.10',
            'administrator_flag' => true,
            'teacher_flag'       => false,
            'student_flag'       => false,
            'institution_flag'   => false
        ]);
		Item::create([
            'asset_tag'          => 'abc1234',
            'name'               => 'MacBook Pro',
            'funding_source'     => 'Grant 1234A',
            'item_type_id'       => $comp->id,
            'model'              => 'MacBook Pro 2015',
            'cpu'                => '2.2 GHz Quad-core',
            'ram'                => '16GB',
            'hard_disk'          => '512GB',
            'os'                 => 'Mac OS X 10.10',
            'administrator_flag' => true,
            'teacher_flag'       => false,
            'student_flag'       => false,
            'institution_flag'   => false
        ]);
        Item::create([
            'asset_tag'          => 'XYZ789',
            'name'               => 'Projector',
            'funding_source'     => 'Grant 1234B',
            'item_type_id'       => $proj->id,
            'model'              => 'ViewSonic PJD5132',
            'administrator_flag' => false,
            'teacher_flag'       => true,
            'student_flag'       => false,
            'institution_flag'   => false
        ]);
    }
}

class CheckinSeeder extends Seeder
{
    public function run()
    {
        DB::table('check_ins')->delete();

		$rooms = Room::all();
		$items = Item::all();
		srand(1);
		foreach($rooms as $room) {
			foreach($items as $item) {
				for($i = 0; $i < rand(1,4); $i++) {
					CheckIn::create([
						'room_id'	=> $room->id,
						'item_id'	=> $item->id,
						'created_at' => rand(time()/2,time())
					]);
				}
			}
		}
        
    }
}

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'name'     => 'Test User',
            'email'    => 'test@test.com',
            'password' => Hash::make('test')
        ]);
    }

}
