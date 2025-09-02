<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NewsMagazine;

class NewsMagazineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'US News',
                'url'  => 'https://www.usnews.com/',
                'is_active' => true,
            ],
            [
                'name' => 'Inquirer',
                'url'  => 'https://www.inquirer.net/',
                'is_active' => true,
            ],
            [
                'name' => 'Manila Bulletin',
                'url'  => 'https://mb.com.ph/',
                'is_active' => true,
            ],
            [
                'name' => 'Philippine Star',
                'url'  => 'https://www.philstar.com/',
                'is_active' => true,
            ],
            [
                'name' => 'UF Digital Collections (Juvenile)',
                'url'  => 'https://ufdc.ufl.edu/collections/juv',
                'is_active' => true,
            ],
            [
                'name' => 'Science Magazine News',
                'url'  => 'https://www.sciencemag.org/news',
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            NewsMagazine::create($item);
        }
    
    }
}
