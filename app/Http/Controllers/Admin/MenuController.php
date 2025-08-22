<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || !Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    // List all categories and items
    public function index()
    {
        $categories = MenuCategory::with(['menuItems' => function($q) {
            $q->ordered();
        }])->ordered()->get();
        return view('admin.menu.index', compact('categories'));
    }

    // Show form to create a new category
    public function createCategory()
    {
        return view('admin.menu.create_category');
    }

    // Store a new category
    public function storeCategory(Request $request)
    {
        $validated = $request->validate(MenuCategory::rules());
        MenuCategory::create($validated);
        return redirect()->route('admin.menu.index')->with('success', 'Category created.');
    }

    // Show form to edit a category
    public function editCategory(MenuCategory $category)
    {
        return view('admin.menu.edit_category', compact('category'));
    }

    // Update a category
    public function updateCategory(Request $request, MenuCategory $category)
    {
        $validated = $request->validate(MenuCategory::rules($category->id));
        $category->update($validated);
        return redirect()->route('admin.menu.index')->with('success', 'Category updated.');
    }

    // Delete a category
    public function destroyCategory(MenuCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Category deleted.');
    }

    // Show form to create a new item
    public function createItem(MenuCategory $category)
    {
        return view('admin.menu.create_item', compact('category'));
    }

    // Store a new item
    public function storeItem(Request $request, MenuCategory $category)
    {
        $validated = $request->validate(MenuItem::rules());
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu_items', 'public');
        }
        $validated['category_id'] = $category->id;
        MenuItem::create($validated);
        return redirect()->route('admin.menu.index')->with('success', 'Menu item created.');
    }

    // Show form to edit an item
    public function editItem(MenuCategory $category, MenuItem $item)
    {
        return view('admin.menu.edit_item', compact('category', 'item'));
    }

    // Update an item
    public function updateItem(Request $request, MenuCategory $category, MenuItem $item)
    {
        $validated = $request->validate(MenuItem::rules($item->id));
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('menu_items', 'public');
        }
        $item->update($validated);
        return redirect()->route('admin.menu.index')->with('success', 'Menu item updated.');
    }

    // Delete an item
    public function destroyItem(MenuCategory $category, MenuItem $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu item deleted.');
    }
}
