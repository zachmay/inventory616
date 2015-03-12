<?php

use App\Item;
use App\Room;
use App\CheckIn;

class CheckInTest extends TestCase {

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->seed();

        $checkinsBefore = CheckIn::count();

        $room = Room::take(1)->get()[0];
        $item = Item::take(1)->get()[0];

        $checkin = new CheckIn();
        $checkin->room()->associate($room);
        $checkin->item()->associate($item);
        $checkin->push();

        $checkinsAfter = CheckIn::count();

        $this->assertEquals($checkinsBefore + 1, $checkinsAfter);
    }

}
