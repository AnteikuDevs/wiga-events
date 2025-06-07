<?php

namespace Database\Seeders;

use App\Models\ApiCredential;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApiCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApiCredential::create([
            'name' => 'WigaEvents',
            'client_id' => 'c0008c1e-718c-41d4-86b8-4c383a7b1e03',
            'client_secret' => 'GG649mX5v2mZJAM8HFcBiCnhSwbBRBrZ6Fc6uMpY',
            'access_key' => 'cO4pv4W7LASruheVHTDBH17QSzt9G1WHSs6rdRgBJWrYO62JYpk8bx6mT6AxwFKlmPvhQzkgbv2diz2zp5zHcPkBtHJKbdNpm4GJ'
        ]);
    }
}
