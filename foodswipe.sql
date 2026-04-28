
-- Create database with UTF-8 encoding
CREATE DATABASE IF NOT EXISTS foodswipe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE foodswipe;

-- Table: users

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Table: foods

CREATE TABLE IF NOT EXISTS foods (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  emoji VARCHAR(10),
  image VARCHAR(255),
  category VARCHAR(100) NOT NULL,
  time_min INT NOT NULL,
  calories INT NOT NULL,
  rating DECIMAL(3,1) NOT NULL DEFAULT 4.0,
  description TEXT,
  user_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_category (category),
  INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Table: swipes

CREATE TABLE IF NOT EXISTS swipes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  food_id INT NOT NULL,
  action ENUM('like', 'super', 'skip') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (food_id) REFERENCES foods(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_food_id (food_id),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Initial Data: 12 Foods from home.html

INSERT INTO foods (name, emoji, image, category, time_min, calories, rating, description) VALUES
('Ramen Tonkotsu', '🍜', 'images/ramen.jpg', 'Japonais', 45, 620, 4.8, 'Bouillon de porc riche, nouilles fraîches, œuf mollet et chashu.'),
('Pizza Margherita', '🍕', 'images/pizza.jpg', 'Italien', 30, 540, 4.7, 'Tomate San Marzano, mozzarella di bufala, basilic frais.'),
('Tacos al Pastor', '🌮', 'images/tacos.jpg', 'Mexicain', 20, 480, 4.6, 'Porc mariné aux épices, ananas, coriandre et salsa verde.'),
('Pad Thaï', '🍝', 'images/padthai.jpg', 'Thaïlandais', 25, 550, 4.5, 'Nouilles de riz sautées, crevettes, cacahuètes et citron vert.'),
('Burger Smash', '🍔', 'images/burger.jpg', 'Américain', 15, 750, 4.9, 'Double galette beurrée, cheddar fondu, pickles maison.'),
('Sushi Omakase', '🍣', 'images/sushi.jpg', 'Japonais', 60, 420, 5.0, 'Sélection du chef : thon, saumon, oursin et bar de ligne.'),
('Shakshuka', '🍳', 'images/shakshuka.jpg', 'Oriental', 20, 390, 4.4, 'Œufs pochés dans une sauce tomate épicée aux poivrons.'),
('Crêpe Suzette', '🥞', 'images/crepes.jpg', 'Français', 15, 310, 4.6, 'Crêpes au beurre d''agrumes flambées au Grand Marnier.'),
('Biryani d''agneau', '🍚', 'images/biryani.jpg', 'Indien', 90, 680, 4.8, 'Riz basmati parfumé, agneau tendre, safran et raïta.'),
('Poke Bowl Saumon', '🥗', 'images/pokebowl.jpg', 'Hawaïen', 10, 490, 4.7, 'Riz sushi, saumon frais, avocat, edamame et sauce ponzu.'),
('Couscous Royal', '🍲', 'images/couscous.jpg', 'Maghrébin', 75, 720, 4.9, 'Semoule fine, merguez, poulet, légumes et bouillon parfumé.'),
('Tiramisu', '🍮', 'images/tiramisu.jpg', 'Dessert', 20, 380, 4.8, 'Mascarpone aérien, biscuits imbibés d''espresso et cacao.');
