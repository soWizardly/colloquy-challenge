<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\LegalCase;
use App\Models\Meeting;
use App\Models\MeetingType;
use App\Models\Participant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $cases = [
            LegalCase::factory()->create([
                "name" => "John Doe vs. ACME Corporation",
                "description" => "This case is John Doe suing ACME Corporation."
            ]),
            LegalCase::factory()->create([
                "name" => "Jane Doe vs. John Doe",
                "description" => "This case is Jane Doe suing John Doe"
            ]),
            LegalCase::factory()->create([
                "name" => "ACME Corporation vs. ACME Financial",
                "description" => "This case is ACME Corporation suing ACME Financial."
            ]),
            LegalCase::factory()->create([
                "name" => "ACME Global vs. Jane Doe",
                "description" => "This case is Jane Doe is being sued by ACME Global."
            ])
        ];

        $types = [
            MeetingType::factory()->create([
                "name" => "Deposition"
            ]),
            MeetingType::factory()->create([
                "name" => "Arbitration"
            ]),
            MeetingType::factory()->create([
                "name" => "General"
            ])
        ];

        foreach (range(1,5) as $i) {
            Meeting::factory()
                ->for($cases[array_rand($cases)])
                ->for($types[array_rand($types)])
                ->has(Participant::factory()->count($i))
                ->create();
        }
    }
}
