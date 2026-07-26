<?php
/**
 * Shop Module - Product Controller
 * Handles shop product operations
 */

class ShopProductController extends Controller {
    
    public function index() {
        $products = ShopProduct::with(['category', 'brand', 'images'])
            ->published()
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $categories = ShopCategory::all();
        $brands = ShopBrand::all();
        
        return view('shop.products.index', compact('products', 'categories', 'brands'));
    }
    
    public function show($slug) {
        $product = ShopProduct::with(['category', 'brand', 'images', 'variants', 'reviews.user'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();
        
        $relatedProducts = ShopProduct::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->published()
            ->limit(4)
            ->get();
        
        Event::dispatch('shop.product.viewed', ['product' => $product]);
        
        return view('shop.products.show', compact('product', 'relatedProducts'));
    }
    
    public function search(Request $request) {
        $query = ShopProduct::published();
        
        if ($request->has('q')) {
            $searchTerm = $request->input('q');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }
        
        if ($request->has('category')) {
            $query->where('category_id', $request->input('category'));
        }
        
        if ($request->has('brand')) {
            $query->where('brand_id', $request->input('brand'));
        }
        
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }
        
        if ($request->has('on_sale')) {
            $query->onSale();
        }
        
        if ($request->has('in_stock')) {
            $query->inStock();
        }
        
        $sortBy = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $query->orderBy($sortBy, $order);
        
        $products = $query->paginate(12);
        
        return view('shop.products.index', compact('products'));
    }
    
    public function create() {
        $this->authorize('create', ShopProduct::class);
        
        $categories = ShopCategory::all();
        $brands = ShopBrand::all();
        $tags = ShopTag::all();
        
        return view('shop.products.create', compact('categories', 'brands', 'tags'));
    }
    
    public function store(Request $request) {
        $this->authorize('create', ShopProduct::class);
        
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'required|unique:shop_products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:shop_categories,id',
            'brand_id' => 'nullable|exists:shop_brands,id',
            'status' => 'in:draft,published'
        ]);
        
        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        
        $data = $validated->validated();
        $data['slug'] = $this->generateSlug($data['name']);
        $data['seller_id'] = Auth::id();
        
        $product = ShopProduct::create($data);
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ShopProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => false
                ]);
            }
        }
        
        Event::dispatch('shop.product.created', ['product' => $product]);
        
        return redirect()->route('shop.products.show', ['slug' => $product->slug])
            ->with('success', __('Product created successfully'));
    }
    
    public function edit($id) {
        $product = ShopProduct::findOrFail($id);
        $this->authorize('update', $product);
        
        $categories = ShopCategory::all();
        $brands = ShopBrand::all();
        $tags = ShopTag::all();
        
        return view('shop.products.edit', compact('product', 'categories', 'brands', 'tags'));
    }
    
    public function update(Request $request, $id) {
        $product = ShopProduct::findOrFail($id);
        $this->authorize('update', $product);
        
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'required|unique:shop_products,sku,' . $id,
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:shop_categories,id',
            'brand_id' => 'nullable|exists:shop_brands,id',
            'status' => 'in:draft,published'
        ]);
        
        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        
        $data = $validated->validated();
        $data['slug'] = $this->generateSlug($data['name'], $product->id);
        
        $product->update($data);
        
        Event::dispatch('shop.product.updated', ['product' => $product]);
        
        return redirect()->route('shop.products.show', ['slug' => $product->slug])
            ->with('success', __('Product updated successfully'));
    }
    
    public function destroy($id) {
        $product = ShopProduct::findOrFail($id);
        $this->authorize('delete', $product);
        
        $product->delete();
        
        Event::dispatch('shop.product.deleted', ['product' => $product]);
        
        return redirect()->route('shop.products.index')
            ->with('success', __('Product deleted successfully'));
    }
    
    private function generateSlug($title, $excludeId = null) {
        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
        $originalSlug = $slug;
        $count = 1;
        
        while (true) {
            $query = ShopProduct::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $count++;
        }
        
        return $slug;
    }
}
