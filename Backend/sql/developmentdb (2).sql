-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Gegenereerd op: 06 apr 2025 om 11:13
-- Serverversie: 11.7.2-MariaDB-ubu2404
-- PHP-versie: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(5, 'Console'),
(3, 'Laptop'),
(1, 'PC'),
(2, 'PC parts');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `created_at`) VALUES
(1, 2, 3738.00, '2025-04-06 11:07:36'),
(2, 2, 8133.00, '2025-04-06 11:11:28');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_each` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price_each`) VALUES
(1, 1, 1, 5, 469.00),
(2, 1, 2, 1, 789.00),
(3, 1, 3, 1, 604.00),
(4, 2, 1, 2, 469.00),
(5, 2, 2, 1, 789.00),
(6, 2, 3, 2, 604.00),
(7, 2, 11, 1, 1599.00),
(8, 2, 12, 1, 3599.00);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `description`, `image`, `category_id`, `stock`) VALUES
(1, 'NINTENDO Switch 2', 469.00, 'With the Nintendo Switch 2, you can play your favorite Nintendo games at home on your TV or on the go. The Nintendo Switch 2 is an improved Nintendo Switch with new functions. For example, it\'s larger than its predecessor and has improved Joy-Cons. The Joy-Con design is more ergonomic, so it fits better in your hand. You can also play your old Nintendo Switch games on the new Nintendo Switch, so you can still enjoy Mario Kart 8 Deluxe and Pokémon Scarlet or Violet. With the included dock, you can connect the Switch 2 to your TV and play the best games together with your friends and family. Suddenly have to go somewhere? Take it out of the dock and play on the train or in the car in handheld mode. Simply place your Nintendo Switch 2 on the table with the fold-out stand, which is sturdier than that of the regular Nintendo Switch.', 'https://assets.mmsrg.com/isr/166325/c1/-/ASSET_MMS_152255307?x=536&y=402&format=jpg&quality=80&sp=yes&strip=yes&trim&ex=536&ey=402&align=center&resizesource&unsharp=1.5x1+0.7+0.02&cox=0&coy=0&cdx=536&cdy=402', 5, 0),
(2, 'PlayStation 5 Pro Digital Edition', 789.00, 'With the PlayStation 5 Pro Digital Edition, you can get the most out of your favorite games. The PS5 Pro Digital Edition doesn\'t have a disc tray. This means you can\'t play games on disc or Blu-ray movies. Want to do this? Easily install the separately available Disc Drive on your PS5 Pro. This PlayStation 5 Pro has a 2TB storage space. This gives you enough space for your favorite games and you don\'t need to delete a lot of files or apps. Thanks to the new PlayStation Spectral Super Resolution, you can game with the sharpest images on your 4K television thanks to AI technology. You can also play with the smoothest gameplay because of the 120Hz support and advanced ray tracing. This ensures your games look even better with more detailed reflections, shadows, and the best in-game lighting. Like to play old PS4 games? With the special Game Boost function, you can play old games in a higher quality. Because of the fast SSD card of this PS5 Pro, you can enjoy the fastest loading times. This makes your game experience even better. Do you often play a lot of RPG or shooter games? You can hear exactly where your opponents come from thanks to 3D audio. You can control your characters in-game with the DualSense controller. This has haptic feedback and trigger effects, so you see, hear, and feel what\'s happening in your game.', 'https://image.coolblue.nl/max/2048x1536/products/2094371', 5, 196),
(3, 'Xbox Series X', 604.00, 'Play together with friends at home on the couch with the new Xbox Series X and extra Microsoft Xbox Controller Black. With this bundle, you always have an extra controller on hand, or you can play a FIFA match together with a friend or family member. The Series X has a disc tray. This allows you to play all your games on CD. This also applies to most of your Xbox One games, because the Xbox Series X is backwards compatible. This Xbox has a lot of new features compared to its predecessor. The Xbox Series X uses NVMe SSD storage, so the loading times are shorter than ever. In addition, the console supports DirectX ray tracing so games look even better. The refresh rate is now 144 fps when you game in Full HD or Quad HD. If you game in 4K, it has a maximum refresh rate of 120Hz. As a result, there\'s no screen tearing and the gameplay looks smooth. This depends on the game you\'re playing. Plus, you can easily expand the storage of the console with an expansion card. This is a card that you plug into the Xbox and expand the storage with the size of the expansion card. This gives you more space to save your games and download updates.', 'https://image.coolblue.nl/max/2048x1536/products/1887598', 5, 244),
(4, 'Samsung Galaxy Book3 360 NP750QFG-KA1NL', 999.00, 'With the 15.6-inch Samsung Galaxy Book3 360 NP750QFG-KA1NL 2-in-1 laptop, you can edit photos and videos. You can use your finger or the included stylus to swipe through apps on the 360-degree foldable touchscreen, and quickly edit a note or email. On the Super AMOLED screen, you can also enjoy true-to-life colors. With the 13th generation Intel Core i7 processor and 16GB DDR4 RAM, you can edit photos and videos and multitask with demanding programs like Adobe Premiere Pro. This laptop also has the Intel Evo label, so you can be sure the laptop is fast and energy-efficient. You can easily log in by placing your finger on the fingerprint sensor. That\'s useful if you often forget your password. Want to edit your vlog at night? You can also see what you\'re doing in the dark thanks to the backlit keyboard. Because this Samsung laptop only weighs 1.49kg, you can also take it with you.', 'https://image.coolblue.nl/max/1400xauto/products/1880140', 3, 150),
(5, 'HP Pavilion 15-eg2959nd', 749.00, 'Edit photos and switch between light programs with the 15-inch HP Pavilion 15-eg2959nd. The 12th generation Intel Core i5 processor and 16GB DDR4 RAM are powerful enough for that. Want to enjoy relaxing music while you work? The audio that comes from the Bang & Olufsen speakers sounds clearer than that of standard laptop speakers. Feel free to work at night, as the backlit keyboard shows you where to find the keys in the dark. Is your laptop empty, but you want to get back to work quickly? With HP Fast Charge, you can charge your laptop back up to 50% in about 45 minutes. That way, you can soon continue to work wirelessly. Store your photos, documents, and other files on the 512GB M.2 SSD. You don\'t have to worry about the security of your creations. That\'s because you can log in quickly and easily with the fingerprint sensor. Find sustainability important? The material of the speaker casing is made from recycled plastic, which makes this laptop more sustainable.', 'https://image.coolblue.nl/max/2048x1536/products/1941124', 3, 300),
(6, 'Acer Aspire 3 (A315-59-55YK)', 529.00, 'Edit photos and work in a couple of light programs at the same time with the Acer Aspire 3 (A315-59-55YK) 15-inch laptop. Thanks to the 12th generation Intel Core i5 processor and the 16GB DDR4 RAM, you can smoothly work in programs like Photoshop or InDesign. Or open multiple Word and Excel side by side, without slowing down. The Intel Iris Xe video card helps with processing and rendering your images. This chip works up to 2 times faster than a normal, built-in video chip, so you can process your images faster. Thanks to the 512GB storage space, your storage doesn\'t fill up quickly and your laptop boots in a few seconds. Often work with numbers? You can easily enter these via the numeric keypad. This way, you can quickly enter formulas in your spreadsheets, and make bookkeeping easier.', 'https://image.coolblue.nl/max/2048x1536/products/1856943', 3, 300),
(8, 'ASUS TUF Gaming Radeon RX 7800 XT OC 16GB', 550.00, 'Play your demanding games at high settings with the ASUS TUF Gaming Radeon RX 7800 XT OC 16GB video card. Thanks to the maximum 2520MHz clock speed, you can smoothly game in QHD at a high fps with the latest games. Want to take an extra step, such as 120 fps in QHD? Thanks to the 2565MHz turbo speed, you can get a bit extra from this video card. Thanks to the 16GB GDDR6X RAM, you can easily perform multiple graphic design tasks at the same time with this GPU. This makes it easy to stream via Twitch while you\'re video editing. The card has 3 DisplayPort 1.4a ports and a HDMI 2.1 port, so you can connect your gaming monitor to your video card without problems. The ASUS TUF Gaming Radeon RX 7800 XT OC 16GB has a 2.96 slot design with Axial tech fans. This ensure efficient cooling of the video card and allows you to easily play games at high settings. This video card also has RGB lighting, which makes your video card stand out from your PC.', 'https://image.coolblue.nl/max/1400xauto/products/2051132', 2, 400),
(9, 'INNO3D GeForce RTX 5080 X3 16GB', 1309.00, 'Play the best games with razor-sharp graphics with the INNO3D GeForce RTX 5080 X3 16GB video card. Thanks to the heatsink with 6 heat pipes and aluminum casing, your video card doesn\'t overheat. This video card also has 3 fans, so it\'s always cooled properly. Thanks to the NVIDIA RTX 5080 chipset, you can play games with an 8K resolution and a high refresh rate. That way, you can head into every battle with realistic images. This video card is also future-proof, thanks to 16GB GDDR7 VRAM. Thanks to the 2295MHz clock speed, you can process demanding tasks quickly. That way, your games load without slowing down. Overclocking is also possible with this video card, so you can reach a maximum clock speed of 2617MHz. This allows you to game with even more fps. Thanks to the PCIe 5.0 x16 support, you can place multiple graphics cards or SSDs in the video card. That way, you can game with smooth images and your PC boots faster. Thanks to the 3 DisplayPort 2.1b connectors, you can connect multiple monitors to the GPU. You can turn on the ray tracing technology for realistic light effects in your favorite game. This video card also has DLSS 4.0. This means you enjoy sharp contrasts between light and dark colors and you see more details in reflections. DLSS also increases the fps of your game. INNO3D provides a 3-year warranty on the video card.', 'https://image.coolblue.nl/max/2048x1536/products/2143140', 2, 0),
(10, 'MSI GeForce RTX 5090 VENTUS 3X OC 32GB', 2609.00, 'You can play your favorite games with the best settings with the MSI GeForce RTX 5090 VENTUS 3X OC 32GB video card. Thanks to the NVIDIA GeForce RTX 5080 GPU, you can play the latest and largest games with a 4K or 8K image quality and a high refresh rate. This way, you see every detail razor-sharp and your opponents look smooth when you game online. The metal backplate with air vents and thermal pads quickly conducts the heat, so the video card stays cooled. It also bends less quickly in the PCIe slot thanks to this sturdy case. You can also perform multiple graphic tasks at the same time thanks to the 32GB GDDR7 VRAM. This way, you can stream and game in 4K resolution even when you turn on ray tracing, for example. This RTX 5090 video card also has a 2640MHz clock speed. Thanks to this, you can play without slowing down and your games load fast. You can also place multiple graphics cards or SSDs in the video card thanks to the PCIe 5.0 x16 slots. Thanks to the HDMI 2.1b and 3 DisplayPort 2.1a, you can connect multiple monitors to the GPU. Thanks to the 3 fans, the video card stays cool during gaming or editing photos and videos. This makes the RTX 5090 future-proof. Thanks to the DLSS 4.0 support, you can boost the number of fps of your game and the images of the virtual world look lifelike.', 'https://image.coolblue.nl/max/2048x1536/products/2136415', 2, 0),
(11, 'Lenovo Legion T5 26IRB8 90UU00SNMH', 1599.00, 'With the Lenovo Legion T5 26IRB8 90UU00SNMH gaming PC, you can play medium demanding games at high settings and chat with your friends via Discord. While you game, you can enjoy extra realistic light effects thanks to the NVIDIA GeForce RTX 4060 Ti video card. The T5 is powerful enough for photo editing and multitasking between light programs, thanks to the 14th generation Intel Core i5 processor and 16GB DDR5 RAM. You can store all your games on the 1TB M.2 SSD. You can also easily take off the transparent side panel from the PC. This way, you can expand the RAM to 64GB and your gaming experience becomes even more smooth. In addition, the Legion T5 has multiple slots for extra storage.', 'https://image.coolblue.nl/max/2048x1536/products/2046677', 1, 98),
(12, 'Acer Predator Orion 7000 655 I7K1436GLS', 3599.00, 'On the Acer Predator Orion 7000 655 I7K1436GLS gaming PC, you can play demanding games and stream your gameplay via Twitch. Thanks to the NVIDIA GeForce 4080 Super video card, you can do that at high settings. This Predator provides more than just good looks, because it has powerful components. With the 14th generation Intel Core i7 processor and the fast 32GB DDR5 RAM, you can beat all your opponents in your favorite game or easily work on graphic design projects without crashes. You also don\'t have to worry about the Predator Orion 7000 overheating. The ARGB FrostBlade fans and illuminated liquid cooling make this PC look very good and keep it cool, so you can continue to game in the heat of battle. On the 1TB M.2 SSD, you have enough space for your favorite game. Want to add extra storage or quickly switch drives later? Do this via the 2.5-inch SSD/HDD Hot Swap Bay. That way, you can easily expand your storage without opening the case. Thanks to the transparent panels, the RGB-lit parts stand out really well.', 'https://image.coolblue.nl/max/2048x1536/products/2036366', 1, 303),
(13, 'HP OMEN 35L GT16-0980nd', 4399.00, 'Play all your favorite demanding games at 60 fps in Quad HD with the HP OMEN 35L GT16-0980nd gaming PC. Thanks to the NVIDIA GeForce RTX 5080 video card, you can smoothly play all games in Quad HD quality. You can also add demanding graphics packs and mods to your games, such as NaturalVision for GTA V. Want to make 3D edits or design your own games? The Intel Core Ultra 9 Series 2 processor and 32GB DDR5 RAM are powerful enough for this. You can stream in 4K via Twitch or render designs in Blender, for example. Thanks to the glass side panel and the RGB-lit parts, this desktop truly stands out as a real gaming desktop. The PC remains cool thanks to the built-in fans. That way, you can keep gaming smoothly in the heat of battle. Connect an internet cable for fast and stable wired internet via the Ethernet port. You can store all your games on the 2TB M.2 SSD.', 'https://image.coolblue.nl/max/2048x1536/products/2148982', 1, 380);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `refreshtoken` text DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `refreshtoken`, `role`) VALUES
(1, 'admin', '$2y$12$/8AhD3QLc1dy1rn/t4Kp3u5gjDkUcr0l8RexiC.A/S.fm0OxvdkzG', 'admin@admin.admin', '$2y$12$AJCezRBVDrU6NwdXLkDG4Os8.vO7En6HTW5DXsPvTw5ZV75P8ddsa', 2),
(2, 'username', '$2y$12$KKFKsfcERW1HW/Q/7HYCuO0VrI3AGtQtNKSGoh04UYFNQwWRQpjiG', 'user@user.user', '$2y$12$TtzC96NSJtrclZD1maSiyeD.r814zikcr92v6acenOIVU526nlqX.', 1);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Indexen voor tabel `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexen voor tabel `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_user` (`user_id`);

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Indexen voor tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orderitems_order` (`order_id`),
  ADD KEY `fk_orderitems_product` (`product_id`);

--
-- Indexen voor tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category` (`category_id`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_orderitems_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orderitems_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
