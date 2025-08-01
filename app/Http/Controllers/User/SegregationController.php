<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WasteCategory;
use App\Models\Segregation;

class SegregationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $categories = WasteCategory::all();
        $userSegregations = Segregation::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        
        // Calculate user statistics
        $stats = $this->getUserSegregationStats($user->id);
        
        // Get recent segregations
        $recentSegregations = $userSegregations->take(5);
        
        // Get educational content
        $educationalContent = $this->getEducationalContent();
        
        return view('user.segregation', compact(
            'categories', 
            'userSegregations', 
            'user', 
            'stats', 
            'recentSegregations', 
            'educationalContent'
        ));
    }

    public function learnSegregation()
    {
        $categories = WasteCategory::all();
        $educationalContent = $this->getEducationalContent();
        
        return view('user.learn-segregation', compact('categories', 'educationalContent'));
    }

    public function getSegregationGuidelines()
    {
        $categories = WasteCategory::all();
        
        return response()->json($categories);
    }

    public function trackSegregationProgress()
    {
        $user = Auth::user();
        $segregations = Segregation::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        
        $progress = [
            'total_segregations' => $segregations->count(),
            'correct_segregations' => $segregations->where('accuracy', '>=', 80)->count(),
            'accuracy_percentage' => $segregations->count() > 0 ? 
                round(($segregations->where('accuracy', '>=', 80)->count() / $segregations->count()) * 100, 2) : 0,
            'recent_activity' => $segregations->take(5),
            'monthly_progress' => $this->getMonthlyProgress($user->id),
            'category_breakdown' => $this->getCategoryBreakdown($user->id)
        ];

        return response()->json($progress);
    }

    public function submitSegregation(Request $request)
    {
        $request->validate([
            'waste_type' => 'required|string|max:255',
            'category_id' => 'required|exists:waste_categories,id',
            'quantity' => 'required|numeric|min:0.1|max:1000',
            'description' => 'nullable|string|max:1000'
        ]);

        $category = WasteCategory::find($request->category_id);
        $accuracy = $this->calculateAccuracy($request->waste_type, $category);

        $segregation = Segregation::create([
            'user_id' => Auth::id(),
            'waste_type' => $request->waste_type,
            'category_id' => $request->category_id,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'accuracy' => $accuracy,
            'status' => $accuracy >= 80 ? 'correct' : 'needs_review'
        ]);

        return response()->json([
            'message' => 'Segregation submitted successfully!',
            'accuracy' => $accuracy,
            'status' => $accuracy >= 80 ? 'correct' : 'needs_review',
            'feedback' => $this->getFeedback($accuracy, $category),
            'segregation_id' => $segregation->id
        ]);
    }

    private function calculateAccuracy($wasteType, $category)
    {
        // Enhanced accuracy calculation
        $wasteTypeLower = strtolower($wasteType);
        $categoryNameLower = strtolower($category->name);
        $categoryDescriptionLower = strtolower($category->description ?? '');
        
        // Check for exact matches first
        if (strpos($wasteTypeLower, $categoryNameLower) !== false) {
            return 95; // High accuracy for exact matches
        }
        
        // Check for keyword matches
        $keywords = explode(' ', $wasteTypeLower);
        $categoryKeywords = array_merge(
            explode(' ', $categoryNameLower),
            explode(' ', $categoryDescriptionLower)
        );
        
        $matches = 0;
        foreach ($keywords as $keyword) {
            if (strlen($keyword) > 2 && in_array($keyword, $categoryKeywords)) {
                $matches++;
            }
        }
        
        $accuracy = $matches > 0 ? min(90, ($matches / count($keywords)) * 100) : 30;
        
        // Bonus for common waste types
        $commonWasteTypes = [
            'plastic' => ['recyclable', 'plastic'],
            'paper' => ['recyclable', 'paper'],
            'glass' => ['recyclable', 'glass'],
            'metal' => ['recyclable', 'metal'],
            'organic' => ['organic', 'food', 'garden'],
            'hazardous' => ['hazardous', 'chemical', 'battery']
        ];
        
        foreach ($commonWasteTypes as $waste => $categories) {
            if (strpos($wasteTypeLower, $waste) !== false) {
                if (in_array(strtolower($category->name), $categories)) {
                    $accuracy = min(100, $accuracy + 20);
                }
            }
        }
        
        return round($accuracy, 1);
    }

    private function getFeedback($accuracy, $category)
    {
        if ($accuracy >= 90) {
            return "Excellent! You correctly identified this as {$category->name}.";
        } elseif ($accuracy >= 80) {
            return "Good job! This is indeed {$category->name}.";
        } elseif ($accuracy >= 60) {
            return "Almost correct! This belongs to {$category->name} category.";
        } else {
            return "This item belongs to {$category->name} category. Keep learning!";
        }
    }

    public function viewSegregationHistory()
    {
        $user = Auth::user();
        $segregations = Segregation::where('user_id', $user->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $stats = $this->getUserSegregationStats($user->id);
        
        return view('user.segregation-history', compact('segregations', 'stats'));
    }

    public function getSegregationTips()
    {
        $tips = [
            'Always separate recyclables from non-recyclables',
            'Clean containers before recycling',
            'Check local guidelines for specific items',
            'Use separate bins for different waste types',
            'Educate family members about proper segregation',
            'Rinse food containers before recycling',
            'Keep hazardous waste separate from regular waste',
            'Compost organic waste when possible',
            'Check packaging symbols for recycling information',
            'Reduce waste by choosing reusable items'
        ];

        return response()->json($tips);
    }

    public function getEducationalContent()
    {
        return [
            'categories' => [
                [
                    'name' => 'Recyclable Waste',
                    'description' => 'Materials that can be processed and reused',
                    'examples' => ['Plastic bottles', 'Paper', 'Glass', 'Metal cans', 'Cardboard'],
                    'tips' => ['Clean before recycling', 'Check local guidelines', 'Separate by material type']
                ],
                [
                    'name' => 'Organic Waste',
                    'description' => 'Biodegradable waste that can be composted',
                    'examples' => ['Food scraps', 'Garden waste', 'Coffee grounds', 'Tea bags'],
                    'tips' => ['Compost when possible', 'Avoid meat and dairy in compost', 'Keep it separate']
                ],
                [
                    'name' => 'Hazardous Waste',
                    'description' => 'Dangerous materials requiring special handling',
                    'examples' => ['Batteries', 'Paints', 'Chemicals', 'Electronics'],
                    'tips' => ['Never mix with regular waste', 'Use designated collection points', 'Follow safety guidelines']
                ],
                [
                    'name' => 'General Waste',
                    'description' => 'Non-recyclable, non-hazardous waste',
                    'examples' => ['Dirty paper', 'Broken ceramics', 'Used tissues', 'Styrofoam'],
                    'tips' => ['Minimize this category', 'Consider alternatives', 'Reduce consumption']
                ]
            ],
            'best_practices' => [
                'Start with the 3 Rs: Reduce, Reuse, Recycle',
                'Set up separate bins for different waste types',
                'Educate everyone in your household',
                'Regularly review and improve your segregation',
                'Stay updated with local waste management guidelines'
            ]
        ];
    }

    private function getUserSegregationStats($userId)
    {
        $segregations = Segregation::where('user_id', $userId)->get();
        
        return [
            'total_segregations' => $segregations->count(),
            'correct_segregations' => $segregations->where('accuracy', '>=', 80)->count(),
            'accuracy_percentage' => $segregations->count() > 0 ? 
                round(($segregations->where('accuracy', '>=', 80)->count() / $segregations->count()) * 100, 2) : 0,
            'total_quantity' => $segregations->sum('quantity'),
            'monthly_progress' => $this->getMonthlyProgress($userId),
            'category_breakdown' => $this->getCategoryBreakdown($userId)
        ];
    }

    private function getMonthlyProgress($userId)
    {
        $currentMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');
        
        $currentMonthSegregations = Segregation::where('user_id', $userId)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
            
        $lastMonthSegregations = Segregation::where('user_id', $userId)
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();
            
        return [
            'current_month' => $currentMonthSegregations,
            'last_month' => $lastMonthSegregations,
            'improvement' => $lastMonthSegregations > 0 ? 
                round((($currentMonthSegregations - $lastMonthSegregations) / $lastMonthSegregations) * 100, 1) : 0
        ];
    }

    private function getCategoryBreakdown($userId)
    {
        return Segregation::where('user_id', $userId)
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_quantity' => $group->sum('quantity'),
                    'average_accuracy' => round($group->avg('accuracy'), 1)
                ];
            });
    }

    public function getSegregationQuiz()
    {
        $quiz = [
            [
                'question' => 'Where should you dispose of a plastic water bottle?',
                'options' => ['Recyclable Waste', 'Organic Waste', 'Hazardous Waste', 'General Waste'],
                'correct' => 0,
                'explanation' => 'Clean plastic bottles should go in recyclable waste.'
            ],
            [
                'question' => 'What should you do with food scraps?',
                'options' => ['Recyclable Waste', 'Organic Waste', 'Hazardous Waste', 'General Waste'],
                'correct' => 1,
                'explanation' => 'Food scraps are organic waste and can be composted.'
            ],
            [
                'question' => 'How should you dispose of used batteries?',
                'options' => ['Recyclable Waste', 'Organic Waste', 'Hazardous Waste', 'General Waste'],
                'correct' => 2,
                'explanation' => 'Batteries contain hazardous materials and need special handling.'
            ]
        ];
        
        return response()->json($quiz);
    }
}
?>