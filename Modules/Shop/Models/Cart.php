<?php
/**
 * Shop Module - Cart Model
 * Handles shopping cart operations
 */

class ShopCart extends Model {
    protected $table = 'shop_cart';
    protected $fillable = ['user_id', 'session_id', 'expires_at'];
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
    
    public function items() {
        return $this->hasMany('ShopCartItem', 'cart_id');
    }
    
    public static function getOrCreate($userId = null, $sessionId = null) {
        if ($userId) {
            $cart = self::where('user_id', $userId)
                ->where(function($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', date('Y-m-d H:i:s'));
                })
                ->first();
            
            if ($cart) {
                return $cart;
            }
            
            return self::create([
                'user_id' => $userId,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days'))
            ]);
        }
        
        if ($sessionId) {
            $cart = self::where('session_id', $sessionId)
                ->where(function($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', date('Y-m-d H:i:s'));
                })
                ->first();
            
            if ($cart) {
                return $cart;
            }
            
            return self::create([
                'session_id' => $sessionId,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days'))
            ]);
        }
        
        return null;
    }
    
    public function addItem($productId, $quantity = 1, $variantId = null) {
        $item = ShopCartItem::where('cart_id', $this->id)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();
        
        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $product = ShopProduct::findOrFail($productId);
            ShopCartItem::create([
                'cart_id' => $this->id,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'price' => $product->final_price
            ]);
        }
        
        Event::dispatch('shop.cart.updated', ['cart' => $this]);
        
        return $this;
    }
    
    public function removeItem($itemId) {
        $item = ShopCartItem::findOrFail($itemId);
        if ($item->cart_id !== $this->id) {
            throw new Exception('Item does not belong to this cart');
        }
        
        $item->delete();
        
        Event::dispatch('shop.cart.updated', ['cart' => $this]);
        
        return $this;
    }
    
    public function updateQuantity($itemId, $quantity) {
        $item = ShopCartItem::findOrFail($itemId);
        if ($item->cart_id !== $this->id) {
            throw new Exception('Item does not belong to this cart');
        }
        
        if ($quantity <= 0) {
            return $this->removeItem($itemId);
        }
        
        $item->quantity = $quantity;
        $item->save();
        
        Event::dispatch('shop.cart.updated', ['cart' => $this]);
        
        return $this;
    }
    
    public function clear() {
        $this->items()->delete();
        Event::dispatch('shop.cart.cleared', ['cart' => $this]);
        return $this;
    }
    
    public function getTotalAttribute() {
        return $this->items()->sum(DB::raw('price * quantity'));
    }
    
    public function getItemCountAttribute() {
        return $this->items()->sum('quantity');
    }
    
    public function isEmpty() {
        return $this->items()->count() === 0;
    }
}
