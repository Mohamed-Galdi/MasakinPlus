<?php

namespace Database\Seeders;

use App\Enums\InvestmentRequestStatus;
use App\Enums\PropertyStatus;
use App\Models\InvestmentRequest;
use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestmentRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all properties that are still in 'draft' status
        $draftProperties = Property::where('status', PropertyStatus::Draft)->get();

        foreach ($draftProperties as $property) {
            InvestmentRequest::create([
                'property_id' => $property->id,
                'suggested_investment_amount' => fake()->numberBetween(200000, 800000),
                'suggested_daily_rent_price' => fake()->numberBetween(150, 500),
                'owner_note' => fake()->sentence(),
                'status' => InvestmentRequestStatus::Pending,
                'admin_note' => null,
            ]);
        }
    }
}
