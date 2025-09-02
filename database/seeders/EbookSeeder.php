<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ebook;

class EbookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ebooks = [
            [
                'name' => 'IntechOpen - Education Subject',
                'url'  => 'https://www.intechopen.com/subjects/4',
            ],
            [
                'name' => 'IntechOpen - Learning Theories',
                'url'  => 'https://www.intechopen.com/books/10662',
            ],
            [
                'name' => 'IntechOpen - Education and New Technologies',
                'url'  => 'https://www.intechopen.com/books/11481',
            ],
            [
                'name' => 'IntechOpen - Innovative Teaching',
                'url'  => 'https://www.intechopen.com/books/11281',
            ],
            [
                'name' => 'IntechOpen - E-learning & Online Education',
                'url'  => 'https://www.intechopen.com/books/10911',
            ],
            [
                'name' => 'Bookboon',
                'url'  => 'https://bookboon.com/',
            ],
            [
                'name' => 'Bookboon - IT & Programming',
                'url'  => 'https://bookboon.com/en/it-programming-ebooks',
            ],
            [
                'name' => 'Bookboon - Education Search',
                'url'  => 'https://bookboon.com/en/search?q=education',
            ],
        ];

        foreach ($ebooks as $ebook) {
            Ebook::create($ebook);
        }
    }
}
