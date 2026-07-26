<?php
/**
 * Shop Module - Order Model
 * Handles shop order data operations
 */

class ShopOrder extends Model {
    protected $table = 'shop_orders';
    protected $fillable = [
        'order_number', 'user_id', 'status', 'subtotal', 'tax', 
        'shipping_cost', 'discount', 'total', 'currency', 
        'payment_method', 'payment_status', 'shipping_method', 
        'shipping_address', 'billing_address', 'notes'
    ];
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
    
    public function items() {
        return $this->hasMany('ShopOrderItem', 'order_id');
    }
    
    public function payments() {
        return $this->hasMany('ShopPayment', 'order_id');
    }
    
    public function shipments() {
        return $this->hasMany('ShopShipment', 'order_id');
    }
    
    public function scopeStatus($query, $status) {
        return $query->where('status', $status);
    }
    
    public function scopePending($query) {
        return $query->whereIn('status', ['pending', 'processing']);
    }
    
    public function scopeCompleted($query) {
        return $query->where('status', 'completed');
    }
    
    public function scopeCancelled($query) {
        return $query->where('status', 'cancelled');
    }
    
    public function getFormattedTotalAttribute() {
        return number_format($this->total, 2);
    }
    
    public function addItem($productId, $quantity, $price) {
        return ShopOrderItem::create([
            'order_id' => $this->id,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price
        ]);
    }
    
    public function calculateTotal() {
        $subtotal = $this->items()->sum(DB::raw('price * quantity'));
        $tax = $subtotal * config('shop.tax_rate', 0.09);
        $total = $subtotal + $tax + $this->shipping_cost - $this->discount;
        
        $this->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total
        ]);
        
        return $total;
    }
    
    public function canCancel() {
        return in_array($this->status, ['pending', 'processing']);
    }
    
    public function canRefund() {
        return in_array($this->status, ['completed', 'shipped']);
    }
}
