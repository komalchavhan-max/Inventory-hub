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

    public function getCategoriesData(){
        $categories = Category::withCount('equipment')->select('categories.*');
        return DataTableService::categoriesData($categories);
    }
    
    public function create(){
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }
    
    public function store(CategoryStoreRequest $request){
        Category::create($request->validated());
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
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