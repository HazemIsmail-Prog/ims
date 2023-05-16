<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            ['name' => 'Depreciation',              'type' => 'depreciation'    ],
            ['name' => 'Adjustment',                'type' => 'adjustment'      ],
            ['name' => 'Unipiles Main',             'type' => 'store'           ],
            ['name' => 'Hessa Project',             'type' => 'store'           ],
            ['name' => 'Al-Hamad Tower',            'type' => 'store'           ],
            ['name' => 'Supplier 1',                'type' => 'supplier'        ],
            ['name' => 'Supplier 2',                'type' => 'supplier'        ],
            ['name' => 'Supplier 3',                'type' => 'supplier'        ],
        ];
        Store::insert($stores);
    }
}
