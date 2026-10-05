<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = [
            ['name' => 'Tun Sri Lanang A', 'digit' => 1, 'gender' => 'male'],
            ['name' => 'Tun Sri Gemala A', 'digit' => 3, 'gender' => 'female'],
        ];

        foreach ($blocks as $block) {
            for ($level = 1; $level <= 5; $level++) {
                $houseCount = $level === 5 ? 10 : 20;

                for ($house = 1; $house <= $houseCount; $house++) {
                    $houseCode = $block['digit'].$level.str_pad($house, 2, '0', STR_PAD_LEFT);

                    foreach (['A', 'B', 'C'] as $letter) {
                        Room::updateOrCreate(
                            ['block' => $block['name'], 'room_number' => $houseCode.$letter],
                            ['floor' => $level, 'capacity' => 2, 'gender' => $block['gender'], 'status' => 'available']
                        );
                    }
                }
            }
        }
    }
}