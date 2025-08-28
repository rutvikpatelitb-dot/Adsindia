# Facebook Pixel Setup for Shopify Basic Plan

## ✅ Perfect Solution for Your Basic Plan

Since you're on Shopify Basic, you **cannot** access `checkout.liquid`, but you **CAN** use the "Additional Scripts" feature!

## 🎯 Step-by-Step Instructions:

### Step 1: Access Additional Scripts
1. Go to your **Shopify Admin**
2. Navigate to **Settings** → **Checkout**
3. Scroll down to find **"Additional Scripts"** section
4. This is where you'll paste the tracking code

### Step 2: Use This Code
Copy and paste this code into the Additional Scripts section:

```html
<script>
// Facebook Pixel Purchase Tracking for Shopify Basic Plan
(function() {
  // Wait a moment for Shopify data to load
  setTimeout(function() {
    if (window.Shopify && window.Shopify.checkout) {
      var checkout = window.Shopify.checkout;
      
      // Only track if we have line items (successful purchase)
      if (checkout.line_items && checkout.line_items.length > 0) {
        
        // Extract content_ids from line items
        var contentIds = [];
        for (var i = 0; i < checkout.line_items.length; i++) {
          var item = checkout.line_items[i];
          var productId = item.variant_id ? item.variant_id.toString() : item.product_id.toString();
          contentIds.push(productId);
        }
        
        // Calculate total value (Shopify stores prices in cents)
        var totalValue = parseFloat(checkout.total_price) / 100;
        
        // Track the purchase
        if (typeof fbq !== 'undefined') {
          fbq('track', 'Purchase', {
            content_ids: contentIds,
            value: totalValue,
            currency: checkout.currency,
            content_type: 'product',
            num_items: checkout.line_items.length
          });
          
          console.log('✅ Facebook Pixel Purchase tracked:', {
            content_ids: contentIds,
            value: totalValue,
            currency: checkout.currency
          });
        } else {
          console.log('❌ Facebook Pixel not found. Make sure fbq is loaded.');
        }
      }
    }
  }, 1000); // Wait 1 second for data to load
})();
</script>
```

### Step 3: Save and Test
1. **Save** the Additional Scripts
2. **Test** with a real purchase (or test order)
3. **Check** browser console for confirmation logs

## 🔍 How to Find Additional Scripts:

If you can't find "Additional Scripts":

1. **Shopify Admin** → **Settings**
2. **Checkout** (in the Settings menu)
3. **Scroll down** past all the checkout settings
4. Look for **"Additional Scripts"** near the bottom
5. It might be in a section called **"Order processing"**

## 📱 Alternative Method (If Additional Scripts Not Available):

Some Basic plans might not have Additional Scripts. In that case:

### Option A: Theme Integration
1. **Online Store** → **Themes** → **Actions** → **Edit Code**
2. **Find** `theme.liquid` file
3. **Add** the script before `</head>` tag
4. **Wrap** it to only run on thank you page:

```liquid
{% if template contains 'customers/order' or checkout.id %}
  <script>
    // Same script as above goes here
  </script>
{% endif %}
```

### Option B: Google Tag Manager (Recommended Alternative)
1. Set up **Google Tag Manager** on your store
2. Create a **Custom HTML tag** with the Facebook Pixel code
3. **Trigger** it on purchase completion
4. This method works on ALL Shopify plans

## ✅ What This Code Does:

- **Waits** for Shopify checkout data to load
- **Extracts** real product IDs from your order
- **Uses** actual order total and currency
- **Only fires** on successful purchases
- **Includes** error checking and logging

## 🧪 Testing:

1. **Install** Facebook Pixel Helper (Chrome extension)
2. **Make** a test purchase
3. **Check** if Purchase event shows up
4. **Look** at browser console for confirmation messages

Your Facebook Pixel will now track real purchase data instead of the hardcoded values!