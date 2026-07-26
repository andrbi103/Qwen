<?php
/**
 * Shop Module - Product Model
 * Handles shop product data operations
 */

class ShopProduct extends Model {
    protected $table = 'shop_products';
    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'sku', 
        'price', 'sale_price', 'cost', 'stock_quantity', 'min_stock', 
        'category_id', 'brand_id', 'seller_id', 'status', 
        'meta_title', 'meta_description', 'meta_keywords'
    ];
    
    public function category() {
        return $this->belongsTo('ShopCategory', 'category_id');
    }
    
    public function brand() {
        return $this->belongsTo('ShopBrand', 'brand_id');
    }
    
    public function seller() {
        return $this->belongsTo('User', 'seller_id');
    }
    
    public function images() {
        return $this->hasMany('ShopProductImage', 'product_id');
    }
    
    public function variants() {
        return $this->hasMany('ShopProductVariant', 'product_id');
    }
    
    public function reviews() {
        return $this->hasMany('ShopReview', 'product_id');
    }
    
    public function orderItems() {
        return $this->hasMany('ShopOrderItem', 'product_id');
    }
    
    public function tags() {
        return $this->belongsToMany('ShopTag', 'shop_product_tags', 'product_id', 'tag_id');
    }
    
    public function scopePublished($query) {
        return $query->where('status', 'published');
    }
    
    public function scopeDraft($query) {
        return $query->where('status', 'draft');
    }
    
    public function scopeOnSale($query) {
        return $query->whereNotNull('sale_price')
                    ->where('sale_price', '<', DB::raw('price'));
    }
    
    public function scopeInStock($query) {
        return $query->where('stock_quantity', '>', 0);
    }
    
    public function scopeLowStock($query) {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock');
    }
    
    public function getFinalPriceAttribute() {
        return $this->sale_price ?? $this->price;
    }
    
    public function isOnSale() {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }
    
    public function isInStock() {
        return $this->stock_quantity > 0;
    }
    
    public function isLowStock() {
        return $this->stock_quantity <= $this->min_stock;
    }
}
