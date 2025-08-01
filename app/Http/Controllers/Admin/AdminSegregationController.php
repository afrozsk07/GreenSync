<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Segregation;
use App\Models\WasteCategory;
use App\Models\User;

class AdminSegregationController extends Controller
{
    public function index()
    {
        $segregations = Segregation::with(['user', 'category'])->orderBy('created_at', 'desc')->get();
        $categories = WasteCategory::all();
        $users = User::where('is_admin', 0)->get();
        
        $stats = [
            'total_segregations' => Segregation::count(),
            'segregations_today' => Segregation::whereDate('created_at', today())->count(),
            'total_waste_segregated' => Segregation::sum('quantity'),
            'categories_count' => WasteCategory::count(),
            'active_users' => User::where('is_admin', 0)->count()
        ];

        // Segregation by category
        $segregationsByCategory = Segregation::with('category')
            ->selectRaw('waste_category_id, SUM(quantity) as total_quantity, COUNT(*) as count')
            ->groupBy('waste_category_id')
            ->get();

        return view('admin.segregation', compact('segregations', 'categories', 'users', 'stats', 'segregationsByCategory'));
    }

    public function viewSegregationDetails($id)
    {
        $segregation = Segregation::with(['user', 'category'])->findOrFail($id);
        
        return view('admin.segregation-details', compact('segregation'));
    }

    public function generateSegregationReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30));
        $endDate = $request->get('end_date', now());

        $segregations = Segregation::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'category'])
            ->get();

        $report = [
            'total_segregations' => $segregations->count(),
            'total_waste_segregated' => $segregations->sum('quantity'),
            'segregations_by_category' => $segregations->groupBy('waste_category_id'),
            'segregations_by_user' => $segregations->groupBy('user_id'),
            'average_quantity' => $segregations->avg('quantity'),
            'top_segregators' => $segregations->groupBy('user_id')
                ->map(function($group) {
                    return [
                        'user' => $group->first()->user,
                        'total_quantity' => $group->sum('quantity'),
                        'count' => $group->count()
                    ];
                })
                ->sortByDesc('total_quantity')
                ->take(5)
        ];

        return response()->json($report);
    }

    public function manageCategories()
    {
        $categories = WasteCategory::all();
        
        return view('admin.categories', compact('categories'));
    }

    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:waste_categories',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50'
        ]);

        WasteCategory::create($request->all());

        return redirect()->back()->with('success', 'Category created successfully!');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:waste_categories,name,' . $id,
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50'
        ]);

        $category = WasteCategory::findOrFail($id);
        $category->update($request->all());

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    public function deleteCategory($id)
    {
        $category = WasteCategory::findOrFail($id);
        
        // Check if category is being used
        if (Segregation::where('waste_category_id', $id)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete category that has associated segregations!');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }

    public function userSegregationProgress($userId)
    {
        $user = User::findOrFail($userId);
        $segregations = Segregation::where('user_id', $userId)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        $progress = [
            'total_segregations' => $segregations->count(),
            'total_quantity' => $segregations->sum('quantity'),
            'categories_used' => $segregations->groupBy('waste_category_id')->count(),
            'recent_activity' => $segregations->take(10),
            'monthly_progress' => $segregations->groupBy(function($item) {
                return $item->created_at->format('Y-m');
            })
        ];

        return response()->json($progress);
    }

    public function exportSegregationData(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30));
        $endDate = $request->get('end_date', now());

        $segregations = Segregation::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'category'])
            ->get();

        $filename = 'segregation_report_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($segregations) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['User', 'Category', 'Quantity', 'Date', 'Notes']);
            
            // Add data
            foreach ($segregations as $segregation) {
                fputcsv($file, [
                    $segregation->user->name,
                    $segregation->category->name,
                    $segregation->quantity,
                    $segregation->created_at->format('Y-m-d H:i:s'),
                    $segregation->notes ?? ''
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 