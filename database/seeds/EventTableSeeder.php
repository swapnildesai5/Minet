<?php

use Illuminate\Database\Seeder;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('events')->delete();
        \DB::table('event_attendees')->delete();

        \DB::statement('ALTER TABLE events AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE event_attendees AUTO_INCREMENT = 1');

        $count = env('SEED_RECORD_COUNT', 30);
        $faker = \Faker\Factory::create();

        factory(\App\Event::class, (int) $count)->create()->each(function ($event) use($faker, $count) {
            $employess = \App\User::allEmployees()->pluck('id')->toArray();
            try{
                $randomEmployeeArray = $faker->unique()->randomElements($employess, $faker->numberBetween(1, 10));

                foreach($randomEmployeeArray as $employee) {
                    $eventAttendees = new \App\EventAttendee();
                    $eventAttendees->user_id = $employee;
                    $eventAttendees->event_id = $event->id;
                    $eventAttendees->save();
                }
            }catch(Exception $e){

            }
        });
    }
}
