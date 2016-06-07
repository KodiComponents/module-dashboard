<?php

namespace KodiCMS\Dashboard\database\seeds;

use DB;
use Illuminate\Database\Seeder;
use KodiCMS\Dashboard\WidgetManagerDashboard;
use KodiCMS\Users\Model\User;

class DashboardTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user) {
            DB::table('user_meta')->insert([
                [
                    'user_id' => $user->id,
                    'key' => WidgetManagerDashboard::WIDGET_SETTINGS_KEY,
                    'value' => '{"5756f21098fd0":{"id":"5756f21098fd0","type":"profiler","settings":[],"parameters":[],"size":{"x":5,"y":2,"max_size":[6,2],"min_size":[3,2]},"multiple":false,"packages":[],"updateSettingsPage":false},"5756f21263692":{"id":"5756f21263692","type":"kodicms_rss","settings":[],"parameters":[],"size":{"x":5,"y":2,"max_size":[5,10],"min_size":[3,2]},"multiple":false,"packages":[],"updateSettingsPage":false},"5756f218acc7f":{"id":"5756f218acc7f","type":"mini_calendar","settings":[],"parameters":[],"size":{"x":3,"y":1,"max_size":[5,1],"min_size":[3,1]},"multiple":false,"packages":[],"updateSettingsPage":false},"5756f21be3040":{"id":"5756f21be3040","type":"cache_button","settings":[],"parameters":[],"size":{"x":2,"y":1,"max_size":[2,1],"min_size":[2,1]},"multiple":false,"packages":[],"updateSettingsPage":false}}',
                ],
                [
                    'user_id' => $user->id,
                    'key' => WidgetManagerDashboard::WIDGET_BLOCKS_KEY,
                    'value' => '[{"col":1,"row":2,"x":5,"y":2,"max-size":[6,2],"min-size":[3,2],"id":"5756f21098fd0"},{"col":1,"row":4,"x":5,"y":2,"max-size":[5,10],"min-size":[3,2],"id":"5756f21263692"},{"col":1,"row":1,"x":3,"y":1,"max-size":[5,1],"min-size":[3,1],"id":"5756f218acc7f"},{"col":4,"row":1,"x":2,"y":1,"max-size":[2,1],"min-size":[2,1],"id":"5756f21be3040"}]',
                ],
            ]);
        }
    }
}
