<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('category');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        $services = $query->latest()->paginate(10);
        $categories = Category::serviceType()->active()->get();

        return view('services.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = Category::serviceType()->active()->get();
        return view('services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['code'] = 'SRV-' . strtoupper(Str::random(8));
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Jasa berhasil ditambahkan!');
    }

    public function show(Service $service)
    {
        $service->load('category');
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $categories = Category::serviceType()->active()->get();
        return view('services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->route('services.index')
            ->with('success', 'Jasa berhasil diupdate!');
    }

    public function destroy(Service $service)
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Jasa berhasil dihapus!');
    }

    public function toggleStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);

        return back()->with('success', 'Status jasa berhasil diubah!');
    }
}
