<?php
/**
 * Shop Module - Cart Controller
 * Handles shopping cart operations
 */

class ShopCartController extends Controller {
    
    public function index() {
        $cart = $this->getCurrentCart();
        
        if (!$cart || $cart->isEmpty()) {
            return view('shop.cart.empty');
        }
        
        $items = $cart->items()->with('product.images')->get();
        $subtotal = $cart->total;
        $tax = $subtotal * config('shop.tax_rate', 0.09);
        $shipping = $this->calculateShipping($cart);
        $total = $subtotal + $tax + $shipping;
        
        return view('shop.cart.index', compact('cart', 'items', 'subtotal', 'tax', 'shipping', 'total'));
    }
    
    public function add(Request $request) {
        $validated = Validator::make($request->all(), [
            'product_id' => 'required|exists:shop_products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:shop_product_variants,id'
        ]);
        
        if ($validated->fails()) {
            return response()->json(['success' => false, 'errors' => $validated->errors()], 422);
        }
        
        $cart = $this->getCurrentCart(true);
        $product = ShopProduct::findOrFail($request->product_id);
        
        // Check stock availability
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false, 
                'message' => __('Not enough stock available')
            ], 400);
        }
        
        $cart->addItem(
            $request->product_id, 
            $request->quantity, 
            $request->variant_id
        );
        
        $cartCount = $cart->item_count;
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => __('Product added to cart'),
                'cart_count' => $cartCount
            ]);
        }
        
        return redirect()->back()
            ->with('success', __('Product added to cart'));
    }
    
    public function update(Request $request, $itemId) {
        $validated = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:0'
        ]);
        
        if ($validated->fails()) {
            return response()->json(['success' => false, 'errors' => $validated->errors()], 422);
        }
        
        $cart = $this->getCurrentCart();
        
        try {
            $cart->updateQuantity($itemId, $request->quantity);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'cart_total' => $cart->total,
                    'cart_count' => $cart->item_count
                ]);
            }
            
            return redirect()->back()
                ->with('success', __('Cart updated successfully'));
        } catch (Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function remove($itemId) {
        $cart = $this->getCurrentCart();
        
        try {
            $cart->removeItem($itemId);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'cart_total' => $cart->total,
                    'cart_count' => $cart->item_count
                ]);
            }
            
            return redirect()->back()
                ->with('success', __('Item removed from cart'));
        } catch (Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function clear() {
        $cart = $this->getCurrentCart();
        $cart->clear();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('Cart cleared successfully')
            ]);
        }
        
        return redirect()->back()
            ->with('success', __('Cart cleared successfully'));
    }
    
    public function checkout() {
        $cart = $this->getCurrentCart();
        
        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('shop.cart.index')
                ->with('error', __('Your cart is empty'));
        }
        
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('info', __('Please login to continue checkout'));
        }
        
        $items = $cart->items()->with('product.images')->get();
        $subtotal = $cart->total;
        $tax = $subtotal * config('shop.tax_rate', 0.09);
        $shipping = $this->calculateShipping($cart);
        $total = $subtotal + $tax + $shipping;
        
        $shippingMethods = $this->getAvailableShippingMethods();
        $paymentMethods = $this->getAvailablePaymentMethods();
        
        return view('shop.cart.checkout', compact('cart', 'items', 'subtotal', 'tax', 'shipping', 'total', 'shippingMethods', 'paymentMethods'));
    }
    
    private function getCurrentCart($createIfNotExists = false) {
        $userId = Auth::id();
        $sessionId = session()->getId();
        
        $cart = ShopCart::getOrCreate($userId, $sessionId);
        
        if (!$cart && $createIfNotExists) {
            $cart = ShopCart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days'))
            ]);
        }
        
        return $cart;
    }
    
    private function calculateShipping($cart) {
        // Simple shipping calculation - can be extended with real shipping providers
        $weight = $cart->items()->sum(DB::raw('quantity * 1')); // Assume 1kg per item
        $baseRate = config('shop.shipping.base_rate', 5.00);
        $perKgRate = config('shop.shipping.per_kg_rate', 2.00);
        
        return $baseRate + ($weight * $perKgRate);
    }
    
    private function getAvailableShippingMethods() {
        return [
            ['id' => 'standard', 'name' => __('Standard Shipping'), 'cost' => 5.00, 'days' => '5-7'],
            ['id' => 'express', 'name' => __('Express Shipping'), 'cost' => 15.00, 'days' => '2-3'],
            ['id' => 'overnight', 'name' => __('Overnight Shipping'), 'cost' => 25.00, 'days' => '1']
        ];
    }
    
    private function getAvailablePaymentMethods() {
        return [
            ['id' => 'cash', 'name' => __('Cash on Delivery'), 'icon' => 'fa-money-bill'],
            ['id' => 'card', 'name' => __('Credit/Debit Card'), 'icon' => 'fa-credit-card'],
            ['id' => 'paypal', 'name' => 'PayPal', 'icon' => 'fa-paypal']
        ];
    }
}
