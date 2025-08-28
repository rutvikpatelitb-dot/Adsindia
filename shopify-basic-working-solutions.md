# Facebook Pixel for Shopify Basic Plan - Working Solutions

## ❌ What You DON'T Have Access To:
- `checkout.liquid` file (Shopify Plus only)
- Additional Scripts in checkout settings (Shopify Plus only)
- Checkout script editor

## ✅ What You CAN Do on Shopify Basic:

### Method 1: Theme Integration (Recommended)

**Step 1: Access Your Theme Files**
1. Go to **Online Store** → **Themes**
2. Click **Actions** → **Edit Code**
3. Find `theme.liquid` in the Layout folder

**Step 2: Add Facebook Pixel Base Code** (if not already added)
Add this in the `<head>` section of `theme.liquid`:

```html
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', 'YOUR_PIXEL_ID'); // Replace with your actual Pixel ID
  fbq('track', 'PageView');
</script>
```

**Step 3: Add Purchase Tracking**
Add this script before the closing `</body>` tag in `theme.liquid`:

```html
<script>
// Facebook Pixel Purchase Tracking for Shopify Basic
document.addEventListener('DOMContentLoaded', function() {
  // Check if we're on a thank you page (order confirmation)
  if (window.location.search.includes('checkout') || 
      window.location.pathname.includes('thank') ||
      window.location.pathname.includes('orders') ||
      document.querySelector('.os-step__title')) {
    
    // Try to get order data from URL parameters or page elements
    var urlParams = new URLSearchParams(window.location.search);
    
    // Method A: From URL parameters (if available)
    var orderId = urlParams.get('order_id') || urlParams.get('id');
    
    // Method B: From page content
    var orderElements = document.querySelectorAll('[data-order-number], .order-number, .os-order-number');
    
    if (orderId || orderElements.length > 0) {
      // Basic tracking with minimal data
      fbq('track', 'Purchase', {
        content_type: 'product',
        currency: 'USD' // Change to your store's currency
      });
      
      console.log('✅ Facebook Pixel Purchase event fired');
    }
  }
});
</script>
```

### Method 2: Google Tag Manager (Easiest & Most Powerful)

**This is often the BEST solution for Basic plans!**

**Step 1: Set Up Google Tag Manager**
1. Create a GTM account at tagmanager.google.com
2. Add GTM container code to your `theme.liquid`
3. Install GTM app from Shopify App Store (optional but easier)

**Step 2: Create Facebook Pixel Tag in GTM**
1. In GTM, create a new **Custom HTML** tag
2. Add this code:

```html
<script>
// Enhanced Facebook Pixel Purchase Tracking via GTM
if (typeof fbq !== 'undefined') {
  // Get order data from GTM dataLayer
  var purchaseData = {
    content_type: 'product',
    currency: '{{Currency}}', // GTM will fill this
    value: {{Total Price}}, // GTM will fill this
  };
  
  // Add content_ids if available
  if ({{Product IDs}}) {
    purchaseData.content_ids = {{Product IDs}};
  }
  
  fbq('track', 'Purchase', purchaseData);
}
</script>
```

**Step 3: Set Trigger**
- Trigger: Purchase/Transaction
- Fire on: Thank you page or purchase completion

### Method 3: Shopify Scripts Alternative

**Some Basic plans have limited script access:**

1. Go to **Settings** → **Checkout**
2. Look for **"Order processing"** section
3. If you see any script fields, add:

```html
<script>
setTimeout(function() {
  if (typeof fbq !== 'undefined') {
    fbq('track', 'Purchase', {
      content_type: 'product'
    });
  }
}, 2000);
</script>
```

### Method 4: Order Confirmation Email (Creative Solution)

Add a tracking pixel to your order confirmation email:

1. **Settings** → **Notifications**
2. **Order confirmation** email template
3. Add this at the bottom:

```html
<img src="https://www.facebook.com/tr?id=YOUR_PIXEL_ID&ev=Purchase&noscript=1" 
     style="display:none" width="1" height="1" />
```

## 🎯 Recommended Path for You:

**For Shopify Basic, I recommend Method 2 (Google Tag Manager) because:**
- ✅ No theme file editing required
- ✅ Better data capture
- ✅ Easier to manage
- ✅ Works with all Shopify plans
- ✅ More reliable tracking

## 📋 Quick Setup Checklist:

1. **Install Facebook Pixel base code** in theme.liquid
2. **Choose your method** (GTM recommended)
3. **Test with a real purchase**
4. **Verify in Facebook Events Manager**

Would you like me to help you with any of these specific methods?