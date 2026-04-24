-- Run once to add demo products (MySQL / MariaDB)
-- mysql -u root ecommerce_web < sql/sample_products.sql

INSERT INTO `product` (`name`, `description`, `price`, `stock`, `image_url`) VALUES
('Wireless Headphones', 'Noise-cancelling over-ear headphones with 30-hour battery and plush ear cushions.', 79.99, 40, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80'),
('Minimal Desk Lamp', 'Adjustable LED desk lamp with warm-to-cool color temperature control.', 45.50, 60, 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=600&q=80'),
('Ceramic Pour-Over Set', 'Hand-glazed pour-over coffee dripper with matching server.', 32.00, 35, 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=600&q=80'),
('Leather Weekender', 'Full-grain leather travel bag with brass hardware and cotton lining.', 189.00, 15, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80'),
('Smart Fitness Watch', 'Heart rate, GPS, and sleep tracking with a week of battery life.', 129.99, 50, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80'),
('Organic Cotton Tee', 'Relaxed fit crewneck tee in garment-dyed organic cotton.', 28.00, 100, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80'),
('Stainless Water Bottle', 'Double-wall insulated bottle keeps drinks cold for 24 hours.', 24.99, 80, 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=600&q=80'),
('Bluetooth Speaker', 'Compact waterproof speaker with 360-degree sound and USB-C charging.', 59.00, 45, 'assets/img/products/bluetooth-speaker.jpg');
