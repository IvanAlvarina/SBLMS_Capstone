<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function start()
    {
        try {
            // Start with a welcome message and show available options
            $welcomeMessage = "Hello! I'm Bridget, your library assistant. I can help you with:\n\n";
            $welcomeMessage .= "Library hours and contact info\n\n";
            $welcomeMessage .= "How to borrow and renew books?\n\n";
            $welcomeMessage .= "Computer and study room availability?\n\n";
            $welcomeMessage .= "Library rules and services\n\n";
            $welcomeMessage .= "You can ask me specific questions or type 'help' to see all available topics.\n\n";
            $welcomeMessage .= "What would you like to know?";

            return response()->json([
                'step' => 0,
                'question' => $welcomeMessage,
                'mode' => 'interactive'
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot start error: ' . $e->getMessage());
            return response()->json([
                'step' => 0,
                'question' => 'Hello! I\'m having some technical difficulties. Please try again in a moment.',
                'mode' => 'interactive'
            ], 500);
        }
    }

    public function next(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'step' => 'required|integer',
                'answer' => 'required|string|max:500'
            ]);

            $step = $request->input('step');
            $answer = trim($request->input('answer'));

            // Handle special commands
            if (strtolower($answer) === 'help' || strtolower($answer) === 'menu') {
                return $this->showHelp();
            }

            // Try to find matching question/answer
            $matchedQuestion = $this->findBestMatch($answer);

            if ($matchedQuestion) {
                $response = $matchedQuestion->answer . "\n\n";
                $response .= "Is there anything else I can help you with?\n";
                $response .= "Type 'help' to see all available topics.";
                
                return response()->json([
                    'step' => $step + 1,
                    'question' => $response,
                    'mode' => 'interactive',
                    'matched_question' => $matchedQuestion->text
                ]);
            }

            // If no match found, provide helpful response
            $suggestions = $this->getSuggestions($answer);
            
            $response = "I'm sorry, I didn't understand that question. ";
            
            if ($suggestions && $suggestions->count() > 0) {
                $response .= "Did you mean one of these?\n\n";
                foreach ($suggestions as $suggestion) {
                    $response .= "• " . $suggestion->text . "\n";
                }
                $response .= "\nOr type 'help' to see all available topics.";
            } else {
                $response .= "Here are some things you can ask me about:\n\n";
                $response .= "• Library hours\n";
                $response .= "• How to borrow books\n";
                $response .= "• Contact information\n";
                $response .= "• Study rooms\n";
                $response .= "• Computer access\n\n";
                $response .= "Type 'help' for a complete list of topics.";
            }

            return response()->json([
                'step' => $step + 1,
                'question' => $response,
                'mode' => 'interactive'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'step' => $request->input('step', 0),
                'question' => 'Please enter a valid message.',
                'mode' => 'error'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Chatbot next error: ' . $e->getMessage());
            return response()->json([
                'step' => $request->input('step', 0),
                'question' => 'Sorry, I encountered an error. Please try again.',
                'mode' => 'error'
            ], 500);
        }
    }

    private function findBestMatch($userInput)
    {
        try {
            $userInput = strtolower(trim($userInput));
            
            // Direct text search in questions and answers
            $directMatch = Question::active()
                ->where(function ($query) use ($userInput) {
                    $query->whereRaw('LOWER(text) LIKE ?', ['%' . $userInput . '%'])
                          ->orWhereRaw('LOWER(answer) LIKE ?', ['%' . $userInput . '%']);
                })
                ->first();

            if ($directMatch) {
                return $directMatch;
            }

            // Keyword-based matching
            $questions = Question::active()->get();
            $bestMatch = null;
            $highestScore = 0;

            foreach ($questions as $question) {
                $score = 0;
                
                // Check keywords
                if ($question->keywords && is_array($question->keywords)) {
                    foreach ($question->keywords as $keyword) {
                        if (stripos($userInput, strtolower($keyword)) !== false) {
                            $score += 2; // Higher weight for keyword matches
                        }
                    }
                }

                // Check question text
                $questionWords = explode(' ', strtolower($question->text));
                $userWords = explode(' ', $userInput);
                
                foreach ($userWords as $userWord) {
                    if (strlen($userWord) > 3) { // Ignore short words
                        foreach ($questionWords as $qWord) {
                            if (stripos($qWord, $userWord) !== false || stripos($userWord, $qWord) !== false) {
                                $score += 1;
                            }
                        }
                    }
                }

                if ($score > $highestScore && $score > 2) { // Minimum threshold
                    $highestScore = $score;
                    $bestMatch = $question;
                }
            }

            return $bestMatch;
        } catch (\Exception $e) {
            Log::error('Error in findBestMatch: ' . $e->getMessage());
            return null;
        }
    }

    private function getSuggestions($userInput)
    {
        try {
            // Get top 3 most likely matches
            return Question::active()
                ->where(function ($query) use ($userInput) {
                    $query->where('text', 'like', '%' . $userInput . '%')
                          ->orWhere('answer', 'like', '%' . $userInput . '%');
                })
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error in getSuggestions: ' . $e->getMessage());
            return collect(); // Return empty collection
        }
    }

    private function showHelp()
    {
        try {
            $questions = Question::active()->ordered()->get();
            
            $helpMessage = "Here are all the topics I can help you with:\n\n";
            
            foreach ($questions as $index => $question) {
                $helpMessage .= ($index + 1) . ". " . $question->text . "\n";
            }
            
            $helpMessage .= "\nJust type your question or mention any topic above!";

            return response()->json([
                'step' => 0,
                'question' => $helpMessage,
                'mode' => 'help'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in showHelp: ' . $e->getMessage());
            return response()->json([
                'step' => 0,
                'question' => 'Sorry, I\'m having trouble loading the help menu.\n\nPlease try asking me a specific question.',
                'mode' => 'error'
            ]);
        }
    }

    // Optional: Admin method to manage questions
    public function manageQuestions()
    {
        try {
            $questions = Question::ordered()->get();
            return view('admin.chatbot-questions', compact('questions'));
        } catch (\Exception $e) {
            Log::error('Error in manageQuestions: ' . $e->getMessage());
            return back()->with('error', 'Unable to load questions.');
        }
    }
}