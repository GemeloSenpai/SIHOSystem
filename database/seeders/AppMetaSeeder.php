<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppMetaSeeder extends Seeder {
    public function run(): void {
        if (!DB::table('app_meta')->where('clave','trial_started_at')->exists()) {
            DB::table('app_meta')->insert([
                'clave' => 'trial_started_at',
                'valor' => Carbon::now()->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
