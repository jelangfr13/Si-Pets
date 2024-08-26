-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Agu 2024 pada 13.26
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adopsi_binatang`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `animals`
--

CREATE TABLE `animals` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `image` varchar(250) NOT NULL DEFAULT 'placeholder.jpg',
  `size` enum('small','large') NOT NULL,
  `type` varchar(50) NOT NULL,
  `location` varchar(250) NOT NULL,
  `gender` enum('female','male') NOT NULL,
  `description` varchar(6000) DEFAULT NULL,
  `hobbies` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `animals`
--

INSERT INTO `animals` (`id`, `name`, `date_of_birth`, `image`, `size`, `type`, `location`, `gender`, `description`, `hobbies`) VALUES
(2, 'Micky', '2010-05-10', 'micky.jpg', 'small', 'cat', 'Jalan Sumatra V No.51, Jember, Jawa Timur', 'male', 'Mickie arrived after his owner passed away and he was found injured in the garden. He is fine now and is ready to find a new home to call his own. He is a friendly soul who has an outstanding moustache and likes his fuss and grub. He would be best suited to an all adult home as he is used to an older home and likes to go with the flow. He would enjoy a quiet home as he knows what he wants as he can nip if you touch him in the wrong spot. Any people in the home will need to have had some previous cat experience and he will appreciate a home that lets him be a cat and express normal behaviour. He will also enjoy a garden but must have a cat flap fitted if you work a full day so he can be free to explore his own patch on his own terms. Mickie has had a cat bite abscess treated as well as having a tooth extracted and is now fine.', 'sleeping'),
(11, 'Toyah', '2012-04-05', 'toyah.jpg', 'small', 'dog', 'Jalan Mastrip VIII No.19, Jember, Jawa Timur', 'female', 'Toyah is a sensitive girl who is looking for understanding and very experienced owners to help give her further guidance and manage some of her behaviours. She needs a calm and quiet adult only household away from towns in a rural setting. She is nervous of strangers so adopters will need to be prepared to work closely with the animal behaviour and welfare advisor, and be prepared to put a few simple measures in place in the home if expecting visitors. Toyah is a strong willed dog who will need ongoing training and management around other dogs, so adopters will have realistic expectations with regard to taking her out and about with them. She loves toys and games and will make a fun and interactive companion. Toyah is affectionate, comical, playful and intelligent.', 'relaxing, sleeping, getting pets'),
(12, 'Poppy', '2014-03-07', 'poppet.jpg', 'small', 'dog', 'Jalan Kalimantan IV No.25, Jember, Jawa Timur', 'female', 'Poppet would like a home where she can potter around with calm like-minded dogs, or have a nice fuss with her people. She is a lovely girl with a brilliant character that is sure to brighten up your day! She is currently in a foster home where she is getting five star treatment and enjoying the luxuries they have to offer. Any new owner will need to discuss her medical issues before they can take her home.', 'playing fetch');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `animal_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `animal_id`, `quantity`) VALUES
(24, 2, 1, 1),
(25, 2, 5, 1),
(26, 2, 7, 1),
(27, 2, 3, 1),
(28, 2, 8, 1),
(29, 2, 6, 1),
(30, 2, 9, 1),
(32, 2, 10, 1),
(35, 2, 16, 1),
(38, 2, 4, 1),
(39, 2, 2, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `useremail` varchar(60) NOT NULL,
  `userpass` varchar(225) NOT NULL,
  `status` enum('user','admin','superadmin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `useremail`, `userpass`, `status`) VALUES
(1, 'admin', 'admin@admin.com', 'd82494f05d6917ba02f7aaa29689ccb444bb73f20380876cb05d1f37537b7892', 'admin'),
(2, 'user', 'user@user.com', 'e172c5654dbc12d78ce1850a4f7956ba6e5a3d2ac40f0925fc6d691ebb54f6bf', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart` (`user_id`,`animal_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usermail` (`useremail`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
