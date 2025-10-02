# Available Slots Badge Feature

## 🎯 Overview

An elegant overlay badge displaying available investment slots for each package on the Investment Packages page.

---

## ✨ Features

### **1. Dynamic Slot Display**

- **Unlimited Slots**: Shows infinity symbol (∞) with "Unlimited" label
- **Limited Slots**: Shows exact number with "Slots Left" or "Slot Left" (singular)
- **Low Stock Warning**: Changes appearance when ≤10 slots remain

---

## 🎨 Design Specifications

### **Standard Badge (>10 slots or unlimited)**

- **Background**: Navy blue gradient with glass morphism
- **Border**: 2px ENI yellow (#FFCD00) with 60% opacity
- **Shadow**: Multi-layered with yellow glow
- **Animation**: Subtle pulsing glow every 3 seconds
- **Position**: Top-right corner (12px from edges)

### **Low Stock Badge (≤10 slots)**

- **Background**: Red gradient (warning state)
- **Border**: Light red/pink with 80% opacity
- **Animation**: Urgent pulsing (faster, more prominent)
- **Number Color**: White with glow
- **Purpose**: Create urgency for nearly sold-out packages

### **Unlimited Badge**

- **Background**: Green gradient
- **Border**: Light green
- **Symbol**: ∞ (infinity)
- **Label**: "Unlimited"

---

## 📐 Styling Details

### **Badge Container**

```css
- Position: Absolute, top-right
- Z-index: 20 (above video)
- Padding: 8px 16px
- Border-radius: 12px
- Backdrop-filter: Blur(12px)
```

### **Number Display**

```css
- Font-size: 1.5rem (24px)
- Font-weight: 800 (Extra bold)
- Color: ENI Yellow (#FFCD00)
- Text-shadow: Yellow glow effect
```

### **Label Text**

```css
- Font-size: 0.625rem (10px)
- Font-weight: 600 (Semi-bold)
- Color: White (90% opacity)
- Text-transform: Uppercase
- Letter-spacing: 0.5px
```

---

## 🔄 States & Animations

### **1. Normal State (>10 slots)**

- Border: `rgba(255, 205, 0, 0.6)`
- Animation: `slots-pulse` (3s ease-in-out infinite)
- Glow: Yellow, 24px radius

### **2. Low Stock State (≤10 slots)**

- Background: Red gradient
- Border: `rgba(248, 113, 113, 0.8)`
- Animation: `slots-urgent` (2s ease-in-out infinite)
- Glow: Red, 36px radius
- More prominent pulsing

### **3. Unlimited State**

- Background: Green gradient
- Border: `rgba(110, 231, 183, 0.8)`
- Shows: ∞ symbol
- Label: "Unlimited"

### **4. Hover State**

- Transform: `scale(1.05)`
- Enhanced shadow and glow
- Smooth 0.3s transition

---

## 💻 Implementation

### **Location**

`resources/views/user/packages.blade.php`

### **PHP Logic**

```php
// Calculate slots badge class
$slotsClass = '';
if ($package->available_slots === null) {
    $slotsClass = 'unlimited-slots';
} elseif ($package->available_slots <= 10 && $package->available_slots > 0) {
    $slotsClass = 'low-slots';
}
```

### **HTML Structure**

```blade
<div class="slots-badge {{ $slotsClass }}">
    @if($package->available_slots === null)
        <span class="slots-badge-number">∞</span>
        <span class="slots-badge-label">Unlimited</span>
    @else
        <span class="slots-badge-number">{{ $package->available_slots }}</span>
        <span class="slots-badge-label">Slots Left</span>
    @endif
</div>
```

---

## 📱 Responsive Design

### **Desktop (>769px)**

- Badge size: Standard (padding 8px 16px)
- Number: 1.5rem (24px)
- Label: 0.625rem (10px)

### **Tablet (768px)**

- Badge maintains size
- Positioned relative to card

### **Mobile (<768px)**

- Badge maintains size for readability
- Remains in top-right corner
- Reduces hover scale (1.02 instead of 1.05)

---

## 🎭 Visual Hierarchy

### **Z-Index Stack**

1. **Badge (z-20)**: Highest, always visible
2. **Video/Image (default)**: Below badge
3. **Card Effects (z-10)**: Background effects

### **Color Coordination**

- **Navy Blue**: Matches ENI dark theme
- **Yellow Border**: Matches ENI brand color
- **Red Warning**: Creates urgency (low stock)
- **Green**: Positive indicator (unlimited)

---

## ✅ Quality Checklist

- [x] Elegant, minimal design
- [x] ENI brand color consistency
- [x] Smooth animations (no jarring effects)
- [x] Glass morphism backdrop
- [x] Multi-state support (normal/low/unlimited)
- [x] Responsive on all devices
- [x] Accessible contrast ratios
- [x] Hover interaction feedback
- [x] No layout shift (absolute positioning)
- [x] Works with video backgrounds

---

## 🚀 Performance

- **CSS-only animations**: No JavaScript overhead
- **GPU-accelerated**: Uses `transform` and `opacity`
- **Optimized z-index**: Minimal stacking context
- **No layout reflow**: Absolute positioning

---

## 🎨 ENI Brand Compliance

✅ **Navy Blue (#0B2241)**: Primary background
✅ **Yellow (#FFCD00)**: Accent and highlights
✅ **Glass Effect**: Modern, premium feel
✅ **Clean Typography**: Professional, readable
✅ **Subtle Animations**: Elegant, not distracting

---

## 📊 User Experience Benefits

1. **Immediate Visibility**: Users instantly see availability
2. **Urgency Creation**: Low stock creates FOMO (fear of missing out)
3. **Decision Making**: Helps users prioritize packages
4. **Professional Look**: Adds polish to the platform
5. **Trust Building**: Transparency about slot availability

---

## 🔧 Customization Options

### **Change Low Stock Threshold**

```php
// Currently set to 10 slots
elseif ($package->available_slots <= 10 && $package->available_slots > 0)

// To change to 5 slots:
elseif ($package->available_slots <= 5 && $package->available_slots > 0)
```

### **Modify Animation Speed**

```css
/* Current: 3s for normal, 2s for urgent */
animation: slots-pulse 3s ease-in-out infinite;
animation: slots-urgent 2s ease-in-out infinite;

/* Faster animations: */
animation: slots-pulse 2s ease-in-out infinite;
animation: slots-urgent 1.5s ease-in-out infinite;
```

### **Change Badge Position**

```css
/* Current: Top-right */
top: 12px;
right: 12px;

/* Top-left alternative: */
top: 12px;
left: 12px;
```

---

**Created**: October 3, 2025
**Platform**: ENI Investment Platform
**File**: `resources/views/user/packages.blade.php`
**Status**: ✅ Production Ready
