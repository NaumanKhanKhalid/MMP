# 📋 Stock Adjustment - Quick Reference Card
## त्वरित संदर्भ कार्ड

---

## ⚡ QUICK START (2 Minutes)

```
1. Menu → Stock Management → Stock Adjustments
2. Click [+ New Adjustment]
3. Search product (Type: Name/SKU/Barcode)
4. Select type (Damaged/Lost/Found/Correction)
5. Enter quantity (+10 badhana, -5 ghatana)
6. Fill reason
7. Create!
```

---

## 🎯 WHEN TO USE (Kab Use Karein)

| Situation | Type | Quantity | Example |
|-----------|------|----------|---------|
| माल टूट गया | Damaged Stock | **-5** | 5 units damaged |
| माल गुम हुआ | Lost/Stolen | **-10** | 10 units missing |
| माल मिल गया | Found/Recovered | **+10** | Found in old warehouse |
| गलत entry | Correction | **+15** or **-15** | Fix wrong data |
| General | Manual Adjustment | **+** or **-** | Any other reason |

---

## ➕➖ QUANTITY RULES

```
✅ CORRECT:
   +10   → Add 10 units (stock बढ़ेगा)
   -5    → Remove 5 units (stock घटेगा)
   +2.5  → Add 2.5 units (decimals OK)
   -0.75 → Remove 0.75 units

❌ WRONG:
   10    → Missing + sign (confusing!)
   0     → Zero not allowed
   5     → Is it +5 or -5? Not clear!
```

**Pro Tip:** Always use + or - sign!

---

## 🔍 PRODUCT SEARCH TRICKS

```
Search By:         Type:          Finds:
───────────────────────────────────────────────────
SKU                "0001"         Exact product
Name               "oil"          All oil products
Barcode            "MMP-0001"     By barcode
Supplier Code      "SUP123"       By supplier#
Brand              "bosch"        All Bosch items
OE Number          "06A115561"    By OE#
```

---

## 📊 LIVE PREVIEW FEATURE

When you enter quantity, instant preview shows:

```
Product: Oil Filter (Current: 50 units)
Enter: -5

Preview Shows:
┌────────────────────────────────────────┐
│ ⚠️ Preview:                            │
│ Current: 50 units ➖ 5 → New: 45 units│
└────────────────────────────────────────┘

Product: Brake Pad (Current: 20 units)
Enter: +10

Preview Shows:
┌────────────────────────────────────────┐
│ ✅ Preview:                            │
│ Current: 20 units ➕ 10 → New: 30 units│
└────────────────────────────────────────┘

Product: Spark Plug (Current: 5 units)
Enter: -10

Preview Shows:
┌────────────────────────────────────────┐
│ ⚠️ Preview:                            │
│ Current: 5 units ➖ 10 → New: -5 units │
│ ⚠️ Warning: Stock will be NEGATIVE!   │
└────────────────────────────────────────┘
```

---

## 🎨 VISUAL INDICATORS

### **Type Options with Emojis:**
```
📝 Manual Adjustment (सामान्य adjustment)
💔 Damaged Stock (खराब माल)
🔍 Lost/Stolen (गुम / चोरी)
✨ Found/Recovered (मिल गया)
✏️ Correction (गलती सुधार)
```

### **Preview Colors:**
```
🟢 Green  → Adding stock (+)
🟡 Yellow → Removing stock (-)
🔴 Red    → Will go negative!
```

### **Stock Info Badges:**
```
[On Hand: 50 units]     → Blue (current stock)
[SKU: 0001]             → Light gray
[🏷 MMP-0001]          → Light gray (barcode)
[🏢 SUP123]            → Yellow (supplier)
```

---

## ⌨️ KEYBOARD SHORTCUTS

```
Action                  Shortcut
────────────────────────────────
Open search field       Click + Type
Next field              Tab
Previous field          Shift + Tab
Submit form             Enter
Close modal             Esc
Clear search            Backspace
```

---

## ✅ CHECKLIST BEFORE CREATING

```
Before clicking "Create Adjustment":
□ Product selected? (naam/SKU/barcode check karo)
□ Type selected? (kyun kar rahe ho)
□ Quantity correct? (+ ya - sahi hai?)
□ Preview dekha? (Current → New correct hai?)
□ Date correct hai?
□ Reason clearly likha?
□ Double-check kiya?

All ✓ → Create Adjustment!
```

---

## 🔄 WHAT HAPPENS AFTER SUBMIT

```
Click "Create Adjustment"
         ↓
Form Validation
         ↓
    All OK? ────NO───► Show error message
         │              Fix & retry
         YES
         ↓
Database Transaction START
         ↓
  ┌──────────────────────────┐
  │ 1. Create StockAdjustment│ (ADJ10001)
  │ 2. Update Product.on_hand│ (50 → 45)
  │ 3. Create StockLedger    │ (Audit)
  └──────────────────────────┘
         ↓
Database Transaction COMMIT
         ↓
   ✅ Success Message
         ↓
   Modal Closes
         ↓
   Page Reloads
         ↓
New adjustment visible in list!
```

---

## 📱 MOBILE / TABLET USE

```
✓ Responsive design
✓ Touch-friendly buttons
✓ Large input fields
✓ Easy scrolling
✓ Works on all devices
```

---

## 🆘 COMMON ERRORS & SOLUTIONS

| Error Message | Reason | Solution |
|--------------|--------|----------|
| "Product is required" | Koi product select nahi kiya | Product select karein |
| "Adjustment quantity cannot be zero" | 0 enter kiya hai | + ya - number enter karein |
| "Reason is required" | Reason khali hai | Reason zaroor bharen |
| "Adjustment would result in negative stock" | Stock negative ho jayega aur allow_negative=OFF | Kam quantity enter karein ya product settings change karein |

---

## 📊 AUDIT TRAIL

Har adjustment automatically save hota hai:

```
Adjustment History:
├─ Adjustment Number (ADJ10001, ADJ10002...)
├─ Date & Time
├─ Who created (User name)
├─ Product details
├─ Before quantity
├─ Change (+ or -)
├─ After quantity
├─ Type & Reason
└─ Complete notes

Stock Ledger:
├─ Document Type: ADJUSTMENT
├─ Quantity changed
├─ Cost calculated
├─ User tracked
└─ Timestamp saved

Everything is tracked! 🔒
```

---

## 🎯 DOS & DON'TS

### **✅ DO:**
- Always use + or - with quantity
- Write clear, specific reasons
- Double-check preview before submitting
- Take photos of damaged stock
- Keep police reports for stolen items
- Review adjustments regularly

### **❌ DON'T:**
- Enter quantity without + or -
- Leave reason blank
- Use future dates
- Forget to check current stock
- Rush without verification
- Make adjustments without authorization

---

## 📞 QUICK HELP

**Need to:**
- Add stock? → Use **+10**
- Remove stock? → Use **-5**
- Find product? → Search by SKU, Name, or Barcode
- See current stock? → Shown automatically after product selection
- Check if correct? → Look at preview (Current → New)
- Verify adjustment? → Check Stock Adjustments list

**Formula:**
```
New Stock = Current Stock + Adjustment Qty

Examples:
50 + (+10) = 60  ✅ Added 10
50 + (-5)  = 45  ✅ Removed 5
50 + (-60) = -10 ⚠️ Negative (if allowed)
```

---

## 🚀 BEST PRACTICES

1. **Same Day:**
   - Jaise hi problem ho, adjustment karo
   - Fresh memory mein reason clear hota hai

2. **Clear Reasons:**
   - "Damaged" ❌ (not specific)
   - "5 units damaged during transport, boxes got wet" ✅

3. **Documentation:**
   - Photos attach karo (notes mein reference do)
   - Police reports ka number save karo
   - Manager approval lo agar large adjustment hai

4. **Regular Review:**
   - Weekly adjustments dekho
   - Patterns identify karo
   - Prevention steps lo

---

## ✨ NEW FEATURES ADDED

| Feature | Description | Benefit |
|---------|-------------|---------|
| **Select2 Search** | Type-ahead dropdown with rich display | Find products faster |
| **Multi-field Search** | SKU, Name, Barcode, OE#, Supplier Code | Search any way you want |
| **Live Preview** | Shows Current → New stock instantly | Avoid mistakes |
| **Color Indicators** | Green (add), Yellow (remove), Red (negative) | Visual clarity |
| **Stock Info Display** | Current stock with all details | Full context |
| **Hindi Labels** | Type options in Hindi & English | Easy to understand |
| **Validation Messages** | Clear error messages | Know what's wrong |
| **Auto-calculated Cost** | FIFO/average cost auto-used | Accurate value tracking |

---

## 🎉 SUMMARY

**Stock Adjustment ab bahut easy hai!**

**3 Simple Steps:**
1. 🔍 Search product (any field)
2. ➕➖ Enter quantity with + or -
3. ✅ Create!

**Live Preview Dekho:**
- Current stock kitna hai
- New stock kitna hoga
- Warning agar negative ho raha hai

**Everything Tracked:**
- Who did it
- When
- Why
- Complete audit trail

**READY TO USE! 🚀**

