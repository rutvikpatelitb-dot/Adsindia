# CRUD MVC Application - .NET 8

A complete **Product Management System** demonstrating full CRUD (Create, Read, Update, Delete) operations using ASP.NET Core MVC with Entity Framework Core.

## 🚀 Features

### Complete CRUD Operations
- ✅ **Create** - Add new products with form validation
- ✅ **Read** - View all products in a responsive table
- ✅ **Update** - Edit existing product information
- ✅ **Delete** - Remove products with confirmation

### Technical Features
- 🎨 **Bootstrap 5** - Modern, responsive UI
- 📱 **Font Awesome Icons** - Beautiful iconography
- ✔️ **Form Validation** - Client and server-side validation
- 💾 **Entity Framework Core** - Object-relational mapping
- 🗄️ **In-Memory Database** - For development and testing
- 🔄 **Success Messages** - User feedback with TempData
- 📊 **Data Annotations** - Model validation attributes

## 🏗️ Project Structure

```
CrudMvcApp/
├── Controllers/
│   ├── HomeController.cs          # Home page controller
│   └── ProductsController.cs      # CRUD operations controller
├── Data/
│   └── ApplicationDbContext.cs    # EF Core DbContext
├── Models/
│   └── Product.cs                 # Product entity model
├── Views/
│   ├── Home/
│   │   └── Index.cshtml           # Welcome page
│   ├── Products/
│   │   ├── Index.cshtml           # List all products
│   │   ├── Create.cshtml          # Add new product
│   │   ├── Edit.cshtml            # Edit product
│   │   ├── Details.cshtml         # View product details
│   │   └── Delete.cshtml          # Delete confirmation
│   └── Shared/
│       └── _Layout.cshtml         # Main layout template
└── Program.cs                     # Application configuration
```

## 📋 Product Model

The `Product` entity includes the following properties:

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| Id | int | Primary key | Auto-generated |
| Name | string | Product name | Required, Max 100 chars |
| Description | string? | Product description | Optional, Max 500 chars |
| Price | decimal | Product price | Required, > 0 |
| Category | string | Product category | Required, Max 50 chars |
| StockQuantity | int | Available stock | ≥ 0 |
| CreatedDate | DateTime | Creation timestamp | Auto-set |
| UpdatedDate | DateTime? | Last update timestamp | Auto-set on update |

## 🎯 CRUD Operations

### 1. CREATE - Add New Product
- **Route**: `/Products/Create`
- **Method**: GET (form), POST (submit)
- **Features**:
  - Form validation with error messages
  - Category dropdown selection
  - Currency input formatting
  - Success message on creation

### 2. READ - View Products
- **Route**: `/Products` or `/Products/Index`
- **Method**: GET
- **Features**:
  - Responsive data table
  - Stock level indicators (badges)
  - Truncated descriptions
  - Action buttons for each product
  - Empty state handling

### 3. UPDATE - Edit Product
- **Route**: `/Products/Edit/{id}`
- **Method**: GET (form), POST (submit)
- **Features**:
  - Pre-populated form with existing data
  - Displays creation and last update dates
  - Concurrency handling
  - Success message on update

### 4. DELETE - Remove Product
- **Route**: `/Products/Delete/{id}`
- **Method**: GET (confirmation), POST (execute)
- **Features**:
  - Confirmation page with product details
  - Warning alerts
  - Safe deletion with confirmation
  - Success message on deletion

### 5. DETAILS - View Product Details
- **Route**: `/Products/Details/{id}`
- **Method**: GET
- **Features**:
  - Complete product information display
  - Formatted dates and prices
  - Stock status indicators
  - Navigation to edit/delete actions

## 🛠️ Setup and Installation

### Prerequisites
- .NET 8.0 SDK
- Any modern web browser

### Installation Steps

1. **Clone or download the project**
   ```bash
   # If you have the project files
   cd CrudMvcApp
   ```

2. **Restore NuGet packages**
   ```bash
   dotnet restore
   ```

3. **Build the application**
   ```bash
   dotnet build
   ```

4. **Run the application**
   ```bash
   dotnet run
   ```

5. **Access the application**
   - Open your browser and navigate to: `https://localhost:5001` or `http://localhost:5000`
   - The application will automatically redirect to the Products page

## 📦 Dependencies

### NuGet Packages
- `Microsoft.EntityFrameworkCore.SqlServer` (9.0.8) - SQL Server provider
- `Microsoft.EntityFrameworkCore.Tools` (9.0.8) - EF Core tools
- `Microsoft.EntityFrameworkCore.InMemory` (9.0.8) - In-memory database

### Frontend Libraries
- Bootstrap 5 - CSS framework
- Font Awesome 6 - Icons
- jQuery - JavaScript library

## 🗃️ Database

The application uses an **In-Memory Database** for simplicity and testing purposes. The database is automatically created and seeded with sample data when the application starts.

### Sample Data
The application includes 3 sample products:
1. **Laptop** - Electronics category
2. **Smartphone** - Electronics category  
3. **Coffee Mug** - Home & Garden category

### For Production
To use a real SQL Server database:

1. Update `Program.cs`:
   ```csharp
   builder.Services.AddDbContext<ApplicationDbContext>(options =>
       options.UseSqlServer(connectionString));
   ```

2. Add connection string to `appsettings.json`:
   ```json
   {
     "ConnectionStrings": {
       "DefaultConnection": "Server=(localdb)\\mssqllocaldb;Database=CrudMvcApp;Trusted_Connection=true;"
     }
   }
   ```

3. Run migrations:
   ```bash
   dotnet ef migrations add InitialCreate
   dotnet ef database update
   ```

## 🎨 User Interface

### Design Features
- **Dark Navigation Bar** with branded logo
- **Responsive Cards** for better mobile experience
- **Color-coded Badges** for stock levels:
  - 🟢 Green: Good stock (>10 items)
  - 🟡 Yellow: Low stock (1-10 items)
  - 🔴 Red: Out of stock (0 items)
- **Icon Integration** throughout the application
- **Success/Error Messages** with dismissible alerts

### Pages Overview
- **Home Page**: Welcome page with feature overview
- **Products List**: Main CRUD interface with all products
- **Create Product**: Form to add new products
- **Edit Product**: Form to modify existing products
- **Product Details**: Read-only view of product information
- **Delete Confirmation**: Safety confirmation before deletion

## 🧪 Testing the Application

### Manual Testing Checklist

#### Create Operation
- [ ] Navigate to "Add Product"
- [ ] Fill in required fields
- [ ] Test form validation (leave required fields empty)
- [ ] Submit valid product
- [ ] Verify success message
- [ ] Confirm product appears in list

#### Read Operation
- [ ] View products list
- [ ] Check responsive design on different screen sizes
- [ ] Verify all product information displays correctly
- [ ] Test pagination (if implemented)

#### Update Operation
- [ ] Click "Edit" on a product
- [ ] Modify product information
- [ ] Test form validation
- [ ] Save changes
- [ ] Verify success message
- [ ] Confirm changes in product list

#### Delete Operation
- [ ] Click "Delete" on a product
- [ ] Review confirmation page
- [ ] Confirm deletion
- [ ] Verify success message
- [ ] Confirm product removed from list

#### Details Operation
- [ ] Click "View Details" on a product
- [ ] Verify all information displays correctly
- [ ] Test navigation links

## 📝 Code Highlights

### Controller Pattern
```csharp
[HttpPost]
[ValidateAntiForgeryToken]
public async Task<IActionResult> Create([Bind("Name,Description,Price,Category,StockQuantity")] Product product)
{
    if (ModelState.IsValid)
    {
        product.CreatedDate = DateTime.Now;
        _context.Add(product);
        await _context.SaveChangesAsync();
        TempData["SuccessMessage"] = "Product created successfully!";
        return RedirectToAction(nameof(Index));
    }
    return View(product);
}
```

### Model Validation
```csharp
[Required(ErrorMessage = "Product name is required")]
[StringLength(100, ErrorMessage = "Product name cannot exceed 100 characters")]
public string Name { get; set; } = string.Empty;

[Required(ErrorMessage = "Price is required")]
[Range(0.01, double.MaxValue, ErrorMessage = "Price must be greater than 0")]
public decimal Price { get; set; }
```

### Entity Framework Configuration
```csharp
protected override void OnModelCreating(ModelBuilder modelBuilder)
{
    modelBuilder.Entity<Product>(entity =>
    {
        entity.HasKey(p => p.Id);
        entity.Property(p => p.Name).IsRequired().HasMaxLength(100);
        entity.Property(p => p.Price).HasColumnType("decimal(18,2)");
        entity.Property(p => p.CreatedDate).HasDefaultValueSql("GETDATE()");
    });
}
```

## 🔧 Troubleshooting

### Common Issues

1. **Port already in use**
   ```bash
   dotnet run --urls="http://localhost:5001"
   ```

2. **Database not created**
   - Check `Program.cs` for `context.Database.EnsureCreated()`

3. **CSS/JS not loading**
   - Verify `UseStaticFiles()` is called in `Program.cs`

4. **Validation not working**
   - Ensure `_ValidationScriptsPartial` is included in forms

## 🤝 Contributing

This is a demonstration project for learning CRUD operations. Feel free to:
- Add new features (search, filtering, pagination)
- Improve the UI/UX
- Add unit tests
- Implement authentication
- Add more validation rules

## 📄 License

This project is created for educational purposes. Feel free to use and modify as needed.

---

**Happy Coding! 🚀**