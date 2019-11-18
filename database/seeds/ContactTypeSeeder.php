<?php

use Illuminate\Database\Seeder;

class ContactTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contact_type')->insert([
            'contact' => 'email',
        ]);

        DB::table('contact_type')->insert([
            'contact' => 'phone',
        ]); 
        
        DB::table('contact_type')->insert([
            'contact' => 'alternative_email',
        ]);

        DB::table('contact_type')->insert([
            'contact' => 'alternative_phone',
        ]);  
    }
}
