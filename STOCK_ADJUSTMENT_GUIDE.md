# Stock Adjustment - Complete User Guide
## स्टॉक एडजस्टमेंट - पूरी गाइड

---

## 🎯 Stock Adjustment Kaise Kaam Karta Hai?

### **Stock Adjustment Kya Hai?**
Stock Adjustment ka matlab hai manually stock ko increase ya decrease karna jab:
- Product damage ho gaya
- Stock kho gaya / chori ho gaya
- Extra stock mil gaya
- System mein galat entry ho gayi
- Physical count se difference mila

---

## 📋 STEP-BY-STEP PROCESS (Hindi + English)

### **STEP 1: Stock Adjustments Page Kholein**
```
Menu → Stock Management → Stock Adjustments
Ya direct URL: /stock-adjustments
```

**Aapko Dikhega:**
```
┌─────────────────────────────────────────────────────────────┐
│ Stock Adjustments                                           │
│ Manual stock adjustments history                            │
│                                        [+ New Adjustment]    │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ Table showing all previous adjustments:                      │
│ ┌────────┬──────────┬─────────┬──────────┬────────┬────────┐│
││ Adj #  │ Date     │ Type    │ Product  │ Before │ Change ││
││ ────────┼──────────┼─────────┼──────────┼────────┼────────┤│
││ ADJ10001│28/10/2025│Damaged  │Oil Filter│   30   │  -5    ││
││ ADJ10002│27/10/2025│Found    │Brake Pad │   20   │  +3    ││
│ └────────┴──────────┴─────────┴──────────┴────────┴────────┘│
└──────────────────────────────────────────────────────────────┘
```

---

### **STEP 2: New Adjustment Button Par Click Karein**
```
Button: [+ New Adjustment]
```

**Modal Khulega:**
```
┌──────────────────────────────────────────────────────────────┐
│                  New Stock Adjustment                        │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│ 1. Product* (Search karein):                                 │
│ ┌───────────────────────────────────────────────────────────┐│
││ 🔍 Search by SKU, Name, Barcode, OE#, Supplier Code...   ││
││                                                             ││
││ Type karein ya select karein:                               ││
││ • 0001 - Oil Filter Bosch (Supplier: SUP123) - Bosch      ││
││ • 0002 - Brake Pad Front (Supplier: BP456) - Brembo       ││
││ • 0003 - Spark Plug NGK (Supplier: SP789) - NGK           ││
│ └───────────────────────────────────────────────────────────┘│
│                                                               │
│ Product select karne ke baad dikhega:                        │
│ ┌───────────────────────────────────────────────────────────┐│
││ ℹ️ Current Stock Information:                              ││
││ [On Hand: 50 units] [SKU: 0001] [🏷 MMP-0001]            ││
││ [🏢 Supplier: SUP123]                                      ││
│ └───────────────────────────────────────────────────────────┘│
│                                                               │
│ 2. Type* (Reason select karein):                             │
│    ○ Manual Adjustment (सामान्य adjustment)                 │
│    ○ Damaged Stock (खराब माल)                               │
│    ○ Lost/Stolen (गुम / चोरी)                                │
│    ○ Found/Recovered (मिल गया)                               │
│    ○ Correction (गलती सुधार)                                 │
│                                                               │
│ 3. Adjustment Qty* (+ या - number):                         │
│    Example:                                                   │
│    • +10 → Stock BADHEGA (10 units add)                     │
│    • -5  → Stock GHATE GA (5 units minus)                   │
│                                                               │
│ 4. Date*: [28/10/2025]                                      │
│                                                               │
│ 5. Reason*: "Damaged during transport"                      │
│    (Kya hua ye batayein - required)                          │
│                                                               │
│ 6. Notes: Optional details...                                │
│                                                               │
│ [Cancel] [Create Adjustment]                                 │
└──────────────────────────────────────────────────────────────┘
```

---

### **STEP 3: Form Bharein (Fill the Form)**

#### **Example 1: Damaged Stock (माल खराब हो गया)**
```
✏️ Filling Form:
────────────────────────────────────────────────────────
Product:        Oil Filter Bosch (Search: "oil" ya "0001")
                Current Stock Shows: 50 units

Type:           Damaged Stock

Adjustment Qty: -5
                (Negative because stock GHATE GA)
                (5 units damaged hai, so minus 5)

Date:           28/10/2025

Reason:         "5 units damaged during transport"

Notes:          "Boxes got wet, filters rusted"

[Create Adjustment] ← Click karein
```

**Result Kya Hoga:**
```
✅ Success Message: "Stock adjustment created successfully!"

Database mein kya hua:
────────────────────────────────────────────────────────
1. StockAdjustment created:
   ADJ10001
   Type: Damaged
   Product: Oil Filter (ID: 1)
   Before: 50
   Change: -5
   After: 45
   ✓ Saved

2. Product updated:
   Oil Filter
   on_hand: 50 → 45
   ✓ Updated

3. StockLedger entry:
   ADJUSTMENT | ADJ10001 | Oil Filter
   Qty: -5 | Cost: R50 | Total: -R250
   ✓ Audit trail created

✅ Stock ab 45 units hai!
```

---

#### **Example 2: Found Stock (Extra माल मिल गया)**
```
✏️ Filling Form:
────────────────────────────────────────────────────────
Product:        Brake Pad Front
                Current Stock: 20 units

Type:           Found/Recovered

Adjustment Qty: +10
                (Positive because stock BADHEGA)
                (10 extra units mile, so plus 10)

Date:           28/10/2025

Reason:         "Found 10 units in old warehouse"

[Create Adjustment]
```

**Result:**
```
✅ Brake Pad stock: 20 → 30 units
✅ Adjustment ADJ10002 created
✅ Audit trail saved
```

---

#### **Example 3: Correction (गलती सुधार)**
```
✏️ Filling Form:
────────────────────────────────────────────────────────
Product:        Spark Plug NGK
                Current Stock: 100 units

Type:           Correction

Adjustment Qty: -50
                (Pehle galat entry ho gayi thi)
                (Actually 50 hi hain, 100 nahi)

Reason:         "Data entry error correction"

[Create Adjustment]
```

**Result:**
```
✅ Spark Plug stock: 100 → 50 units
✅ Sahi stock ho gaya!
```

---

## 🔍 PRODUCT SEARCH - Kaise Karein?

### **Search Field mein Type Kar Sakte Hain:**

**1. SKU se search:**
```
Type: "0001"
Result: ✓ Oil Filter Bosch (SKU: 0001)
```

**2. Name se search:**
```
Type: "oil"
Result: ✓ Oil Filter Bosch
        ✓ Oil Filter Mann
        ✓ Engine Oil 5W30
```

**3. Supplier Code se search:**
```
Type: "SUP123"
Result: ✓ Oil Filter Bosch (Supplier: SUP123)
```

**4. Barcode se search:**
```
Type: "MMP-0001"
Result: ✓ Oil Filter Bosch (Barcode: MMP-0001)
```

**5. Brand se search:**
```
Type: "bosch"
Result: ✓ Oil Filter Bosch
        ✓ Spark Plug Bosch
        ✓ Air Filter Bosch
```

---

## ⚠️ IMPORTANT RULES (Zaroori Niyam)

### **1. Positive vs Negative Quantity:**

```
✅ BADHANA HAI (Increase):
   +10 (plus 10) likho
   Example: +5, +10, +25
   
❌ GHATANA HAI (Decrease):
   -10 (minus 10) likho
   Example: -5, -10, -25
   
⛔ GALAT:
   0 (zero) nahi likh sakte - Error aayega
```

### **2. Stock Negative Ho Sakta Hai?**

```
Product Settings:
├─ allow_negative = ON
│  → Stock -5, -10 bhi ho sakta hai
│  → Warning dikhega par adjustment hoga
│
└─ allow_negative = OFF
   → Stock 0 se neeche nahi ja sakta
   → Error aayega agar negative ho jayega
```

### **3. Date Selection:**
```
✓ Aaj ka date
✓ Past date (pichle ka)
❌ Future date (aage ka) - Best practice avoid karein
```

---

## 📊 COMPLETE WORKFLOW DIAGRAM

```
               STOCK ADJUSTMENT WORKFLOW
               ═══════════════════════════

1. OPEN PAGE
   ↓
   /stock-adjustments
   ↓
   List of all previous adjustments dikhta hai
   
2. CLICK NEW ADJUSTMENT
   ↓
   Modal opens
   
3. SELECT PRODUCT
   ↓
   Search box mein type karein:
   • SKU (0001)
   • Name (Oil Filter)
   • Barcode (MMP-0001)
   • Supplier Code (SUP123)
   • Brand (Bosch)
   ↓
   Product select hone par:
   → Current Stock dikhe ga
   → SKU, Barcode, Supplier Code badges me
   
4. SELECT TYPE
   ↓
   Kyun adjust kar rahe ho?
   • Damaged
   • Lost
   • Found
   • Correction
   • Manual
   
5. ENTER QUANTITY
   ↓
   + (plus) = BADHEGA
   - (minus) = GHATEGA
   
   Example:
   Current: 50
   Enter: -5
   Result: 45 ho jayega
   
6. FILL DETAILS
   ↓
   • Date: Kab hua
   • Reason: Kya hua (required)
   • Notes: Extra details
   
7. CREATE ADJUSTMENT
   ↓
   Button click karein
   ↓
   Database me 3 entries:
   ┌─────────────────────────────┐
   │ 1. StockAdjustment (ADJ#)  │
   │ 2. Product.on_hand update  │
   │ 3. StockLedger (audit)     │
   └─────────────────────────────┘
   ↓
   ✅ SUCCESS!
   Stock updated
   History saved
   Page reload
   ↓
   New adjustment list mein dikhe ga
```

---

## 💡 REAL SCENARIOS (Asli Examples)

### **Scenario 1: माल गिर के टूट गया**
```
Situation:
5 Oil Filters गिर के टूट गए warehouse mein

Steps:
1. Stock Adjustments page kholo
2. "New Adjustment" click karo
3. Search: "oil filter" type karo
4. Select: Oil Filter Bosch
5. Type: Damaged Stock
6. Qty: -5 (minus 5 kyunki stock kam ho raha hai)
7. Reason: "Dropped and damaged in warehouse"
8. Date: Today
9. Create Adjustment

Result:
✅ Oil Filter stock: 50 → 45
✅ ADJ10001 created
✅ Value: -R250 (5 × R50)
✅ Audit trail saved
```

---

### **Scenario 2: पुराने गोदाम में माल मिला**
```
Situation:
Old warehouse mein 10 Brake Pads mile jo system mein nahi the

Steps:
1. New Adjustment
2. Search: "brake pad"
3. Select: Brake Pad Front
4. Type: Found/Recovered
5. Qty: +10 (plus 10 kyunki stock badh raha hai)
6. Reason: "Found in old warehouse section B"
7. Create

Result:
✅ Brake Pad stock: 20 → 30
✅ ADJ10002 created
✅ Value: +R5,000 (10 × R500)
```

---

### **Scenario 3: चोरी हो गया**
```
Situation:
Security check mein pata chala 15 Spark Plugs गायब हain

Steps:
1. New Adjustment
2. Search: "spark plug"
3. Select: Spark Plug NGK
4. Type: Lost/Stolen
5. Qty: -15 (minus kyunki stock kam hua)
6. Reason: "Missing after security check"
7. Notes: "Police report filed - Ref#12345"
8. Create

Result:
✅ Spark Plug stock: 100 → 85
✅ ADJ10003 created
✅ Police report number saved in notes
```

---

### **Scenario 4: गलत एंट्री सुधार**
```
Situation:
Manager ne galti se 200 units enter kar diye the
Actually sirf 50 units hain

Steps:
1. New Adjustment
2. Select product
3. Type: Correction
4. Qty: -150 (200 - 50 = -150)
5. Reason: "Data entry error correction"
6. Create

Result:
✅ Stock: 200 → 50 (correct ho gaya)
```

---

## 🎨 UI IMPROVEMENTS ADDED

### **Enhanced Product Select (Select2):**
```
Features:
✓ Type-ahead search (jaise hi type karo, results aate hain)
✓ Multiple fields search (SKU, Name, Barcode, Supplier Code)
✓ Rich display (SKU, Name, Supplier Code, Brand sab dikhta hai)
✓ Keyboard navigation (arrow keys se select kar sakte ho)
✓ Clear button (selection clear karne ke liye)
```

### **Current Stock Display:**
```
Jab product select karo, dikhe ga:
┌────────────────────────────────────────────────────┐
│ ℹ️ Current Stock Information:                     │
│                                                    │
│ [On Hand: 50 units]     ← Abhi kitna hai         │
│ [SKU: 0001]             ← Product code           │
│ [🏷 MMP-0001]          ← Barcode                 │
│ [🏢 SUP123]            ← Supplier code           │
└────────────────────────────────────────────────────┘

Ye dekhkar aap samajh sakte ho:
• Abhi kitna stock hai
• Kitna adjust karna hai
• Product sahi hai ya nahi
```

### **Visual Feedback:**
```
✅ Success:
   → Green success message
   → Modal band ho jata hai
   → List mein naya adjustment dikhe ga
   → Page auto-reload

❌ Error:
   → Red error message
   → Form open rehta hai
   → Kya galat hai wo dikhta hai
```

---

## 📱 HOW TO USE (Step-by-Step Screenshots)

### **Screen 1: Main Page**
```
┌────────────────────────────────────────────────────────────┐
│ Stock Adjustments                  [+ New Adjustment]      │
├────────────────────────────────────────────────────────────┤
│ Manual stock adjustments history                           │
│                                                             │
│ Adj #    Date        Type      Product      Before  Change │
│ ─────────────────────────────────────────────────────────  │
│ ADJ10001 28/10/2025  Damaged   Oil Filter     30     -5    │
│ ADJ10002 27/10/2025  Found     Brake Pad      20     +3    │
│ ADJ10003 26/10/2025  Manual    Spark Plug    100     -10   │
└────────────────────────────────────────────────────────────┘

Action: [+ New Adjustment] button par click karo
```

### **Screen 2: Search Product**
```
┌──────────────────────────────────────────┐
│       New Stock Adjustment               │
├──────────────────────────────────────────┤
│ Product*                                 │
│ ┌────────────────────────────────────┐  │
││ 🔍 Search...                        │  │
││                                      │  │
││ Type: "oil" ↵                       │  │
││                                      │  │
││ Results:                             │  │
││ ▼ 0001 - Oil Filter Bosch           │  │  ← Click here
││   0015 - Oil Filter Mann             │  │
││   0023 - Engine Oil 5W30             │  │
│ └────────────────────────────────────┘  │
└──────────────────────────────────────────┘
```

### **Screen 3: Product Selected**
```
┌──────────────────────────────────────────┐
│ Product*                                 │
│ Oil Filter Bosch ✓                      │
│                                          │
│ ┌────────────────────────────────────┐  │
││ ℹ️ Current Stock Information:       │  │
││ [On Hand: 50 units]                 │  │
││ [SKU: 0001] [🏷 MMP-0001]          │  │
││ [🏢 SUP123]                         │  │
│ └────────────────────────────────────┘  │
│                                          │
│ Type*                                    │
│ [Damaged Stock ▼]                       │
│                                          │
│ Adjustment Qty*                          │
│ [-5] ← Minus 5 likhenge                 │
│                                          │
│ Date*                                    │
│ [28/10/2025]                            │
│                                          │
│ Reason*                                  │
│ [Damaged during transport]              │
│                                          │
│ Notes                                    │
│ [Boxes got wet...]                      │
│                                          │
│ [Cancel] [Create Adjustment]            │
└──────────────────────────────────────────┘
```

### **Screen 4: Success**
```
┌──────────────────────────────────────────┐
│ ✅ Success!                              │
│                                          │
│ Stock adjustment created successfully!   │
│                                          │
│ ADJ10001                                 │
│ Oil Filter: 50 → 45 units               │
└──────────────────────────────────────────┘

List mein naya entry:
ADJ10001  28/10/2025  Damaged  Oil Filter  50  -5  45
```

---

## 🔢 QUANTITY EXAMPLES (+ aur - samajhiye)

### **Stock BADHANA Hai (Increase):**
```
Current Stock: 50
Want: 60
Calculation: 60 - 50 = +10
Enter: +10

Current Stock: 20
Want: 35
Calculation: 35 - 20 = +15
Enter: +15
```

### **Stock GHATANA Hai (Decrease):**
```
Current Stock: 50
Want: 45
Calculation: 45 - 50 = -5
Enter: -5

Current Stock: 100
Want: 75
Calculation: 75 - 100 = -25
Enter: -25
```

### **Stock ZERO Karna Hai:**
```
Current Stock: 10
Want: 0
Calculation: 0 - 10 = -10
Enter: -10

Current Stock: 5
Want: 0
Enter: -5
```

---

## 🎯 QUICK TIPS (Jaldi Tips)

1. **Product Search:**
   - Pura naam type karne ki zaroorat nahi
   - "oil" type karo, saare oil filters dikhengen
   - Barcode scan bhi kar sakte ho
   - Supplier code bhi chalega

2. **Quantity:**
   - + ya - lagana mat bhoolna!
   - Bina sign ke number galat hoga
   - Decimals allowed: 2.5, 10.75 etc.

3. **Reason:**
   - Clear aur specific likho
   - Audit ke liye zaroori hai
   - Baad mein samajh aayega kya hua tha

4. **Date:**
   - Aaj ka ya pichle ka date select karo
   - Jab actually hua tab ka date do

---

## 📊 WHAT HAPPENS IN DATABASE (Technical)

```
Example: -5 Oil Filters damaged

BEFORE ADJUSTMENT:
─────────────────────────────────────────
products table:
  id=1, name="Oil Filter", on_hand=50

AFTER CLICKING "Create Adjustment":
─────────────────────────────────────────
1. stock_adjustments table:
   INSERT
   ├─ id: 1
   ├─ adjustment_number: ADJ10001
   ├─ adjustment_type: 'damage'
   ├─ product_id: 1
   ├─ quantity_before: 50
   ├─ adjustment_qty: -5
   ├─ quantity_after: 45
   ├─ reason: "Damaged during transport"
   └─ user_id: 1 (current user)

2. products table:
   UPDATE products
   SET on_hand = 45
   WHERE id = 1

3. stock_ledger table:
   INSERT
   ├─ product_id: 1
   ├─ document_type: 'ADJUSTMENT'
   ├─ document_id: 1 (adjustment id)
   ├─ qty: -5
   ├─ unit_cost: 50.00
   ├─ total_cost: -250.00
   └─ user_id: 1

Result: ✅ 3 tables updated, complete audit trail
```

---

## 🔍 TROUBLESHOOTING (Agar Problem Aaye)

### **Problem 1: Product Select Nahi Ho Raha**
```
Solution:
✓ Page refresh karo (F5)
✓ Check Select2 library loaded hai
✓ Console errors check karo (F12)
```

### **Problem 2: Current Stock Nahi Dikh Raha**
```
Solution:
✓ Product properly select karein
✓ Dropdown se click karke select karein
✓ Network tab check karein (/product/{id} API call ho rahi hai)
```

### **Problem 3: Negative Stock Error**
```
Error: "Adjustment would result in negative stock"

Reason:
Product ka allow_negative = OFF hai

Solutions:
Option 1: Smaller quantity enter karo
Option 2: Product settings mein allow_negative = ON karo
```

### **Problem 4: Zero Quantity Error**
```
Error: "Adjustment quantity cannot be zero"

Reason:
0 (zero) enter kiya hai

Solution:
✓ + ya - number enter karo
✓ Example: +5 ya -5
❌ 0 allowed nahi hai
```

---

## ✅ VERIFICATION (Check Kaise Karein)

### **Adjustment Sahi Hua Ki Nahi:**

**Method 1: Stock Adjustments List**
```
Menu → Stock Adjustments
Last entry check karo:
✓ Apka adjustment dikhe ga
✓ Before/After quantities check karo
```

**Method 2: Product Page**
```
Menu → Inventory → Products
Apna product search karo
✓ on_hand column mein updated quantity dikhe ga
```

**Method 3: Stock Ledger**
```
Menu → Reports → Stock Ledger
Product filter karo
✓ ADJUSTMENT entry dikhe gi
✓ Qty, cost, total sab dikhe ga
```

---

## 🚀 BEST PRACTICES

1. **Daily:**
   - Damaged items ka adjustment turant karo
   - Reason clearly likho
   - Photos lo agar possible ho

2. **Weekly:**
   - All adjustments review karo
   - Patterns dekho (baar baar kya ho raha hai)
   - Prevention ke steps lo

3. **Monthly:**
   - Stock Count karo (physical verification)
   - Adjustments vs Stock Count compare karo
   - Variance analysis karo

---

## 📈 REPORTS KAISE DEKHEN

### **1. Adjustment History:**
```
/stock-adjustments
Filter options:
• By Type (Damaged, Lost, Found, etc.)
• By Product
• By Date Range
• Search by Adj#, Reason, Product
```

### **2. Stock Ledger:**
```
/reports → Stock Ledger
Complete audit trail:
• Every stock movement
• Grouped by product
• Export to CSV/Excel
```

### **3. Adjustment Summary:**
```
Dashboard → Stock Adjustments Widget
Quick view:
• Today's adjustments
• This week
• This month
• By type breakdown
```

---

## ⚡ KEYBOARD SHORTCUTS

```
F2          → Focus search field
Enter       → Submit form
Esc         → Close modal
Tab         → Next field
Shift+Tab   → Previous field
```

---

## 🎯 SUMMARY (Saransh)

### **Stock Adjustment Ke Liye:**

1. **Kab Use Karein:**
   - माल damaged
   - माल lost/stolen  
   - Extra माल मिला
   - गलत entry सुधार

2. **Kaise Karein:**
   - Product search (SKU/Name/Barcode/OE#/Supplier Code)
   - Type select (why adjusting)
   - Quantity enter (+ badhana, - ghatana)
   - Reason likho
   - Create!

3. **Kya Hota Hai:**
   - Product.on_hand update
   - StockAdjustment record
   - StockLedger entry (audit)
   - Complete history saved

4. **Check Kaise Karein:**
   - List mein dekho
   - Product inventory mein verify karo
   - Stock Ledger mein audit trail dekho

---

## ✅ SYSTEM STATUS

**Stock Adjustment:** ✅ Fully Functional
**Product Search:** ✅ Comprehensive (7+ fields)
**Validation:** ✅ Complete
**Audit Trail:** ✅ 100% Tracked
**User Interface:** ✅ Enhanced with Select2
**Error Handling:** ✅ Clear messages

**READY TO USE! 🎉**

---

## 📞 NEED HELP?

Agar koi problem aaye:
1. Screenshot lo
2. Error message note karo
3. Kya kar rahe the wo batao
4. Console errors check karo (F12)

Happy Stock Managing! 🚀

