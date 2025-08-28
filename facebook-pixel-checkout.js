/**
 * Facebook Pixel Purchase Tracking for Shopify
 * Alternative JavaScript implementation for modern Shopify themes
 */

// Method 1: Using Shopify's Checkout Events (Shopify Plus)
if (window.Shopify && window.Shopify.Checkout) {
  // Listen for checkout completion
  document.addEventListener('DOMContentLoaded', function() {
    if (window.Shopify.Checkout.isComplete) {
      trackPurchase();
    }
  });
  
  // Also listen for checkout events
  if (window.Shopify.analytics && window.Shopify.analytics.replayQueue) {
    window.Shopify.analytics.replayQueue.push(['trackPurchase', trackPurchase]);
  }
}

// Method 2: Using order status page detection
function detectOrderStatusPage() {
  // Check if we're on the order status page (thank you page)
  const isOrderStatusPage = window.location.pathname.includes('/orders/') || 
                           window.location.pathname.includes('/checkouts/') ||
                           document.querySelector('.os-step__title') !== null ||
                           document.querySelector('[data-step="thank_you"]') !== null;
  
  if (isOrderStatusPage && window.Shopify && window.Shopify.checkout) {
    trackPurchase();
  }
}

function trackPurchase() {
  try {
    // Get checkout data from Shopify
    const checkout = window.Shopify.checkout;
    
    if (!checkout || !checkout.line_items || checkout.line_items.length === 0) {
      console.warn('Facebook Pixel: No checkout data found');
      return;
    }
    
    // Prepare content_ids array
    const contentIds = checkout.line_items.map(item => {
      return item.variant_id ? item.variant_id.toString() : item.product_id.toString();
    });
    
    // Calculate total value (Shopify amounts are in cents)
    const totalValue = parseFloat(checkout.total_price) / 100;
    
    // Track the main purchase event
    if (typeof fbq !== 'undefined') {
      fbq('track', 'Purchase', {
        content_ids: contentIds,
        value: totalValue,
        currency: checkout.currency,
        content_type: 'product',
        num_items: checkout.line_items.reduce((sum, item) => sum + item.quantity, 0),
        content_name: checkout.line_items.map(item => item.title).join(', '),
        content_category: 'ecommerce'
      });
      
      console.log('Facebook Pixel Purchase event tracked:', {
        content_ids: contentIds,
        value: totalValue,
        currency: checkout.currency
      });
      
      // Track individual products (optional)
      checkout.line_items.forEach(item => {
        fbq('trackCustom', 'ProductPurchased', {
          content_id: item.variant_id ? item.variant_id.toString() : item.product_id.toString(),
          content_name: item.title,
          content_category: item.product_type || 'product',
          value: parseFloat(item.final_price) / 100,
          currency: checkout.currency,
          quantity: item.quantity
        });
      });
      
    } else {
      console.error('Facebook Pixel (fbq) not found. Make sure Facebook Pixel is properly installed.');
    }
    
  } catch (error) {
    console.error('Error tracking Facebook Pixel Purchase event:', error);
  }
}

// Initialize tracking when page loads
document.addEventListener('DOMContentLoaded', detectOrderStatusPage);

// Also try immediately in case DOMContentLoaded already fired
if (document.readyState === 'loading') {
  detectOrderStatusPage();
}

// Export for manual triggering if needed
window.trackFacebookPixelPurchase = trackPurchase;