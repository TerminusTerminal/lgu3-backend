<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Investor;
use App\Models\Project;
use App\Models\Incentive;
use App\Models\Application;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name'=>'LGU Admin',
            'email'=>'admin@lgu.local',
            'password'=>Hash::make('password'),
            'role'=>'admin'
        ]);

        $inv1 = Investor::create([
            'name'=>'Acme Industries',
            'email'=>'contact@acme.example',
            'phone'=>'09171234567',
            'address'=>'Industrial Estate, Brgy. 1',
            'type'=>'company',
            'tax_id'=>'TAX-ACME-001'
        ]);

        $proj1 = Project::create([
            'investor_id'=>$inv1->id,
            'name'=>'Acme Manufacturing Plant',
            'sector'=>'Manufacturing',
            'investment_amount'=>15000000,
            'location'=>'Zone A',
            'description'=>'Food processing plant',
            'status'=>'submitted'
        ]);

        $inc1 = Incentive::create([
            'title'=>'Five-year Tax Holiday',
            'description'=>'Tax holiday for new manufacturing projects meeting local employment targets',
            'type'=>'tax_holiday',
            'max_amount'=>5000000,
            'duration_months'=>60,
            'conditions'=>'create at least 50 local jobs'
        ]);

        Application::create([
            'investor_id'=>$inv1->id,
            'project_id'=>$proj1->id,
            'incentive_id'=>$inc1->id,
            'requested_amount'=>3000000,
            'status'=>'pending',
            'remarks'=>'Application submitted online',
            'submitted_at'=>now()
        ]);
    }
}
