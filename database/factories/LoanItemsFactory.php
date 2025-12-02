<?php

namespace Database\Factories;

use App\Models\LoanItem;
use App\Models\Loan;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanItemFactory extends Factory
{
    protected $model = LoanItem::class;

    public function definition(): array
    {
        return [
            'loan_id' => Loan::factory(), 
            'item_id' => Item::factory(), 
            'quantity' => $this->faker->numberBetween(1, 5), 
        ];
    }
}