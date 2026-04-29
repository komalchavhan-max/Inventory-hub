<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Services\DataTableService;

class CategoryController extends Controller
{
    public function index(){
        return view('admin.categories.index');
    }

    public function getCategoriesData(Request $request){
        $categories = Category::select(
                'categories.id',
                'categories.name',
                'categories.slug',
                'categories.icon',
                'categories.description',
                'categories.created_at',
                'categories.updated_at',
                \DB::raw('COUNT(DISTINCT equipment.id) as equipment_count')
            )
            ->leftJoin('equipment', 'equipment.category_id', '=', 'categories.id')
            ->groupBy(
                'categories.id',
                'categories.name',
                'categories.slug',
                'categories.icon',
                'categories.description',
                'categories.created_at',
                'categories.updated_at'
            );
        
        if ($request->has('order')) {
            $columnIndex = $request->input('order')[0]['column'];
            $sortDirection = $request->input('order')[0]['dir'];
            
            $columns = [
                0 => 'categories.id',
                1 => 'categories.name', 
                3 => 'categories.slug',
                4 => 'categories.description',
                5 => 'equipment_count'
            ];
            
            if (isset($columns[$columnIndex])) {
                $categories->orderBy($columns[$columnIndex], $sortDirection);
            }
        } else {
            $categories->orderBy('categories.id', 'asc');
        }
        
        return DataTableService::categoriesData($categories);
    }
    
    public function create(){
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }
    
   public function store(CategoryStoreRequest $request){
        Category::create($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Category created!');
    }

    public function edit($id){
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }
    
    public function update(CategoryUpdateRequest $request, $id){
        $category = Category::findOrFail($id);
         $category->update($request->validated());
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }
    
    public function destroy($id){
        $category = Category::findOrFail($id);
        
        if ($category->equipment()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category. It has ' . $category->equipment()->count() . ' equipment items assigned.');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
    
    public function showEquipment($slug){
        $category = Category::where('slug', $slug)->firstOrFail();
        $equipment = Equipment::where('category_id', $category->id)->with('assignedUser')->get();
        $categories = Category::withCount('equipment')->get();
        
        return view('admin.categories.equipment', compact('category', 'equipment', 'categories'));
    }

    public function show($id){
        $category = Category::withCount('equipment')->findOrFail($id);
        $equipment = Equipment::where('category_id', $category->id)->get();
        return view('admin.categories.show', compact('category', 'equipment'));
    }
}