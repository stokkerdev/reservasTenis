<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BlockedSlot;
class BlockedSlotSeeder extends Seeder
{
    public function run(): void
    {
        BlockedSlot::factory()->count(6)->create();
    }
}