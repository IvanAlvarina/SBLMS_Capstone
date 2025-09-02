<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Journal;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $journals = [
            // Main E-Journals
            ["name" => "JSTOR", "url" => "https://www.jstor.org/action/showLogin"],
            ["name" => "ERIC (Education Resources)", "url" => "https://eric.ed.gov/"],
            ["name" => "Asian Language and Information Journal (UPD)", "url" => "https://journals.upd.edu.ph/index.php/ali"],
            ["name" => "Education Quarterly (UPD)", "url" => "https://educ.upd.edu.ph/education-quarterly/"],
            ["name" => "Journal of Management and Development Studies (UPOU)", "url" => "https://jmds.upou.edu.ph/index.php/journal"],
            ["name" => "Asian Journal of Open and Distance Learning (OUM)", "url" => "https://ajodl.oum.edu.my/"],
            ["name" => "International Journal on Open and Distance e-Learning (UPOU)", "url" => "https://ijodel.upou.edu.ph/ijodel"],
            ["name" => "E-Journals PH", "url" => "https://ejournals.ph/"],

            // Additional Links
            ["name" => "E-Journals PH Issue", "url" => "https://ejournals.ph/issue.php?id=396"],
            ["name" => "Taylor & Francis Online", "url" => "https://www.tandfonline.com/journals/cape20"],
            ["name" => "SAGE Journals (AED)", "url" => "https://journals.sagepub.com/home/AED"],
            ["name" => "International Journal of Educational Research Open", "url" => "https://www.sciencedirect.com/journal/international-journal-of-educational-research-open"],
            ["name" => "Learning, Culture and Social Interaction", "url" => "https://www.sciencedirect.com/journal/learning-culture-and-social-interaction"],
            ["name" => "International Journal of Educational Development", "url" => "https://www.sciencedirect.com/journal/international-journal-of-educational-development"],
            ["name" => "International Journal of Educational Research", "url" => "https://www.sciencedirect.com/journal/international-journal-of-educational-research"],
        ];

        foreach ($journals as $journal) {
            Journal::create($journal);
        }
    }
}
