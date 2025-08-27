<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Oer;

class OerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $oers = [
            ['name' => 'RSTA', 'url' => 'https://www.rsta.org/'],
            ['name' => 'RefSeek', 'url' => 'https://www.refseek.com/sites/about'],
            ['name' => 'Answers.com', 'url' => 'https://www.answers.com/'],
            ['name' => 'The Free Dictionary', 'url' => 'http://www.thefreedictionary.com/'],
            ['name' => 'Scholarpedia', 'url' => 'http://www.scholarpedia.org/article/Main_Page'],
            ['name' => 'Wall Street Journal', 'url' => 'https://www.wsj.com/'],
            ['name' => 'DOABooks', 'url' => 'https://www.doabooks.org/doab?func=search&uiLanguage=en&template'],
            ['name' => 'ABC Chemistry (Full Text)', 'url' => 'http://www.abc.chemistry.bsu.by/current/fulltext.htm'],
            ['name' => 'ABC Chemistry', 'url' => 'http://abc-chemistry.org/'],
            ['name' => 'DOG.org', 'url' => 'https://www.dog.org/'],
            ['name' => 'ERIC', 'url' => 'https://eric.ed.gov/'],
            ['name' => 'Europeana', 'url' => 'https://www.europeana.eu/en'],
            ['name' => 'Online Books Library', 'url' => 'https://onlinebooks.library.upenn.edu/'],
            ['name' => 'Online Books Library (Alt)', 'url' => 'http://onlinebooks.library.upenn.edu/'],
            ['name' => 'Knowledge Stream', 'url' => 'https://www.knowledgestream.org/index.asp'],
            ['name' => 'MedlinePlus Dictionary', 'url' => 'https://medlineplus.gov/mplusdictionary.html'],
            ['name' => 'MedlinePlus Encyclopedia', 'url' => 'https://medlineplus.gov/encyclopedia.html'],
            ['name' => 'New York Times', 'url' => 'https://www.nytimes.com/'],
            ['name' => 'APS Journals Archive', 'url' => 'https://journals.aps.org/archive/'],
            ['name' => 'Bibliomania', 'url' => 'http://www.bibliomania.com/0/0/frameset.html'],
            ['name' => 'FindArticles', 'url' => 'http://www.findarticles.com/'],
            ['name' => 'Google Books', 'url' => 'http://books.google.com/googlebooks/about/index.html'],
        ];

        foreach ($oers as $oer) {
            Oer::create($oer);
        }
    }
}
