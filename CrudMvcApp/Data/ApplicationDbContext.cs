using Microsoft.EntityFrameworkCore;
using CrudMvcApp.Models;

namespace CrudMvcApp.Data
{
    public class ApplicationDbContext : DbContext
    {
        public ApplicationDbContext(DbContextOptions<ApplicationDbContext> options)
            : base(options)
        {
        }

        public DbSet<Product> Products { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);

            // Configure Product entity
            modelBuilder.Entity<Product>(entity =>
            {
                entity.HasKey(p => p.Id);
                entity.Property(p => p.Name).IsRequired().HasMaxLength(100);
                entity.Property(p => p.Description).HasMaxLength(500);
                entity.Property(p => p.Category).IsRequired().HasMaxLength(50);
                entity.Property(p => p.Price).HasColumnType("decimal(18,2)");
                entity.Property(p => p.CreatedDate).HasDefaultValueSql("GETDATE()");
            });

            // Seed some sample data
            modelBuilder.Entity<Product>().HasData(
                new Product
                {
                    Id = 1,
                    Name = "Laptop",
                    Description = "High-performance laptop for gaming and productivity",
                    Price = 1299.99m,
                    Category = "Electronics",
                    StockQuantity = 50,
                    CreatedDate = DateTime.Now
                },
                new Product
                {
                    Id = 2,
                    Name = "Smartphone",
                    Description = "Latest model smartphone with advanced features",
                    Price = 899.99m,
                    Category = "Electronics",
                    StockQuantity = 100,
                    CreatedDate = DateTime.Now
                },
                new Product
                {
                    Id = 3,
                    Name = "Coffee Mug",
                    Description = "Ceramic coffee mug with custom design",
                    Price = 15.99m,
                    Category = "Home & Garden",
                    StockQuantity = 200,
                    CreatedDate = DateTime.Now
                }
            );
        }
    }
}