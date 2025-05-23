<?php

namespace Database\Seeders;

use App\Enums\InvestmentRequestStatus;
use App\Enums\PropertyStatus;
use App\Models\InvestmentRequest;
use App\Models\Property;
use Illuminate\Database\Seeder;

class InvestmentRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all properties that are still in 'draft' status
        $draftProperties = Property::where('status', PropertyStatus::Draft)->limit(5)->get();

        foreach ($draftProperties as $property) {
            InvestmentRequest::create([
                'property_id' => $property->id,
                'suggested_valuation' => fake()->numberBetween(100000, 5000000),
                'suggested_investment_amount' => fake()->numberBetween(50000, 500000),
                'suggested_monthly_operating_cost' => fake()->numberBetween(500, 5000),
                'suggested_nightly_rent' => fake()->numberBetween(100, 2000),
                'owner_note' => fake()->sentence(),
                'status' => InvestmentRequestStatus::Pending,
            ]);
            $property->status = PropertyStatus::InvestmentRequested;
            $property->save();
        }
    }
}
