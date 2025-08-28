# Facebook Pixel Purchase Tracking Setup for Shopify

This guide provides multiple methods to implement Facebook Pixel Purchase tracking on your Shopify checkout page.

## Prerequisites

1. **Facebook Pixel ID**: Make sure you have your Facebook Pixel installed on your Shopify store
2. **Access Level**: Different methods require different access levels to your Shopify store

## Implementation Methods

### Method 1: Additional Scripts (Recommended for Shopify Plus)

**Best for**: Shopify Plus stores with access to checkout.liquid

1. Go to your Shopify Admin → Settings → Checkout
2. Scroll down to "Additional Scripts" section
3. Paste the content from `checkout-additional-scripts.liquid`
4. Save the changes

**Pros**: 
- Most accurate tracking
- Uses actual checkout data
- Fires only on successful purchases

### Method 2: Theme Integration

**Best for**: All Shopify plans

1. **Add to theme files**:
   - Go to Online Store → Themes → Actions → Edit Code
   - Upload `facebook-pixel-checkout.js` to the assets folder
   - Include it in your `theme.liquid` file:

```liquid
{{ 'facebook-pixel-checkout.js' | asset_url | script_tag }}
```

2. **Or add directly to checkout.liquid** (Shopify Plus only):
   - Find `checkout.liquid` in your theme files
   - Add the script before the closing `</body>` tag

### Method 3: Shopify Scripts (Alternative)

**Best for**: Stores that cannot modify checkout.liquid

1. Go to Settings → Checkout in your Shopify admin
2. In the "Additional Scripts" section, add:

```html
<script>
  // Wait for checkout completion
  if (window.Shopify && window.Shopify.checkout) {
    var checkout = window.Shopify.checkout;
    
    if (checkout.line_items && checkout.line_items.length > 0) {
      // Extract product IDs
      var contentIds = checkout.line_items.map(function(item) {
        return item.variant_id ? item.variant_id.toString() : item.product_id.toString();
      });
      
      // Track purchase
      fbq('track', 'Purchase', {
        content_ids: contentIds,
        value: parseFloat(checkout.total_price) / 100,
        currency: checkout.currency,
        content_type: 'product'
      });
    }
  }
</script>
```

## Testing Your Implementation

### 1. Use Facebook Pixel Helper

1. Install the Facebook Pixel Helper Chrome extension
2. Complete a test purchase on your store
3. Check if the Purchase event appears in the extension

### 2. Facebook Events Manager

1. Go to Facebook Events Manager
2. Select your pixel
3. Check the "Test Events" tab for real-time events
4. Complete a test purchase to verify tracking

### 3. Browser Console Testing

1. Open browser developer tools (F12)
2. Complete a test purchase
3. Check console for Facebook Pixel logs

## Dynamic Data Mapping

The implementation automatically maps:

- **content_ids**: Product variant IDs (or product IDs if variant unavailable)
- **value**: Total order value in correct currency format
- **currency**: Order currency from Shopify
- **content_type**: Set to 'product'
- **num_items**: Total quantity of items

## Troubleshooting

### Common Issues:

1. **Events not firing**: Check if Facebook Pixel base code is installed
2. **Wrong values**: Verify currency conversion (Shopify uses cents)
3. **Multiple events**: Ensure code runs only once per purchase
4. **Missing data**: Check if checkout object is available

### Debug Mode:

Add this to test if data is available:

```javascript
console.log('Checkout data:', window.Shopify.checkout);
```

## Advanced Features

### Custom Events

The implementation also tracks individual product purchases:

```javascript
fbq('trackCustom', 'ProductPurchased', {
  content_id: 'product_id',
  content_name: 'Product Name',
  value: 29.99,
  currency: 'USD'
});
```

### Enhanced Ecommerce

For better attribution, the code includes:
- Individual product tracking
- Product categories
- Quantities per item

## Notes

- The tracking code only fires on successful purchases
- Values are automatically converted from Shopify's cent-based pricing
- Product IDs use variant IDs when available, falling back to product IDs
- The implementation is designed to work with both Shopify and Shopify Plus

## Support

If you encounter issues:
1. Check browser console for errors
2. Verify Facebook Pixel is installed correctly
3. Test with Facebook Pixel Helper extension
4. Contact Shopify support for checkout.liquid access issues