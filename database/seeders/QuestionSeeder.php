<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        // Clear existing questions first
        Question::truncate();

        $libraryQuestionsAndAnswers = [
            [
                'question' => 'Is the library open today?',
                'answer' => 'The library is open from 8:00 AM to 4:00 PM, Monday to Friday. We are closed on weekends and public holidays.',
                'keywords' => ['open', 'hours', 'today', 'schedule', 'time']
            ],
            [
                'question' => 'What are the library hours?',
                'answer' => 'Our library hours are: Monday to Friday: 8:00 AM - 4:00 PM. We are closed on weekends and public holidays.',
                'keywords' => ['hours', 'time', 'schedule', 'open', 'close']
            ],
            [
                'question' => 'How can I contact the library?',
                'answer' => 'You can reach us at: Address: St. Bridget College, Batangas City Phone: (012) 123 1234 Email: library@sbc.edu.ph',
                'keywords' => ['contact', 'phone', 'email', 'address', 'reach']
            ],
            [
                'question' => 'How can I borrow books?',
                'answer' => 'To borrow books: 1. Present your valid student/faculty ID 2. Books can be borrowed for 7 days (students) or 14 days (faculty) 3. Maximum of 3 books at a time for students, 5 for faculty 4. Late returns incur a fine of ₱5 per day per book',
                'keywords' => ['borrow', 'loan', 'checkout', 'books', 'lending']
            ],
            [
                'question' => 'How can I renew books?',
                'answer' => 'You can renew books in three ways: 1. Visit the library circulation desk 2. Call us at (012) 123 1234 3. Email us at library@sbc.edu.ph with your ID number and book titles. Books can be renewed once for the same period if no other user has reserved them.',
                'keywords' => ['renew', 'extend', 'renewal', 'books', 'due date']
            ],
            [
                'question' => 'What services do you offer?',
                'answer' => 'Our library services include: Book lending, Research assistance, Computer and internet access, Printing and photocopying, Study rooms and quiet areas, Reference materials, Thesis and dissertation archives, Online database access',
                'keywords' => ['services', 'offer', 'available', 'facilities', 'resources']
            ],
            [
                'question' => 'How do I search for books in the catalog?',
                'answer' => 'You can search our catalog by: 1. Using the online catalog computers in the library 2. Accessing our online catalog from our website 3. Search by title, author, subject, or ISBN 4. Ask our librarians for assistance if needed',
                'keywords' => ['catalog', 'search', 'find books', 'online', 'database']
            ],
            [
                'question' => 'Can I reserve books?',
                'answer' => 'Yes! You can reserve books that are currently checked out. Visit the circulation desk or call us to place a hold. You\'ll be notified when the book becomes available. Reservations are held for 3 days.',
                'keywords' => ['reserve', 'hold', 'book reservation', 'wait list']
            ],
            [
                'question' => 'What are the library rules?',
                'answer' => 'Library rules: Keep noise to a minimum, No food or drinks (except water in covered containers), Turn mobile phones to silent mode, Handle books and materials carefully, Return books on time, Present ID when borrowing, Follow study room guidelines',
                'keywords' => ['rules', 'regulations', 'policy', 'guidelines', 'behavior']
            ],
            [
                'question' => 'Do you have computers for student use?',
                'answer' => 'Yes, we have 20 computers available for student use. Time limit is 2 hours per session. Printing services available at ₱2 per page (B&W) and ₱5 per page (color). Please bring your student ID to use the computers.',
                'keywords' => ['computers', 'internet', 'printing', 'technology', 'access']
            ],
            [
                'question' => 'Can I bring my laptop to the library?',
                'answer' => 'Absolutely! We welcome laptops and personal devices. Free Wi-Fi is available throughout the library. We have power outlets at most tables and study areas. Please keep noise levels low when using devices.',
                'keywords' => ['laptop', 'wifi', 'internet', 'personal device', 'power outlet']
            ],
            [
                'question' => 'How much are the fines for overdue books?',
                'answer' => 'Overdue fines are ₱5 per day per book. After 30 days overdue, the borrower will be charged the replacement cost of the book plus a processing fee of ₱50. Please return books on time to avoid fines.',
                'keywords' => ['fines', 'overdue', 'late', 'penalty', 'charges']
            ],
            [
                'question' => 'Do you have group study rooms?',
                'answer' => 'Yes, we have 4 group study rooms that can accommodate 4-6 people each. Reservations can be made at the circulation desk up to 1 week in advance. Maximum booking is 3 hours per day. Rooms are free for students and faculty.',
                'keywords' => ['study rooms', 'group study', 'reservation', 'private room', 'meeting room']
            ],
            [
                'question' => 'What types of books do you have?',
                'answer' => 'Our collection includes: Textbooks for all academic programs, Reference books and encyclopedias, Fiction and literature, Periodicals and journals, Thesis and research papers, Digital resources and e-books, Rare books and special collections',
                'keywords' => ['collection', 'books types', 'resources', 'materials', 'textbooks']
            ],
            [
                'question' => 'Can visitors use the library?',
                'answer' => 'Visitors are welcome to use library facilities for research purposes. However, borrowing privileges require a valid student or faculty ID. Visitors must register at the front desk and may be asked to leave an ID as security.',
                'keywords' => ['visitors', 'guests', 'access', 'non-students', 'public']
            ]
        ];

        foreach ($libraryQuestionsAndAnswers as $index => $qa) {
            Question::create([
                'text' => $qa['question'],
                'answer' => $qa['answer'],
                'keywords' => json_encode($qa['keywords']), // Store keywords for future search functionality
                'order' => $index + 1,
                'is_active' => true
            ]);
        }
    }
}