-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2026 at 11:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nesere_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(180) DEFAULT NULL,
  `message` text NOT NULL,
  `is_admin` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `user_id`, `subject`, `message`, `is_admin`, `created_at`) VALUES
(1, 2, 'Contact Form - hiwot', 'wserdftyg', 0, '2026-08-24 12:04:53'),
(2, 13, 'Contact Form - hiei', 'sytkjmjnbyu', 0, '2026-09-19 20:18:23');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 21, 'ለጥያቄዎ \"widdening\" ምላሽ ተሰጥቷል፡ Completed', 0, '2026-10-03 09:09:16');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `title` varchar(180) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `client_name` varchar(150) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `status` enum('Active','Completed') DEFAULT 'Completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `quotation_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `details` text DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(150) NOT NULL,
  `service_name_am` varchar(150) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `short_desc` text DEFAULT NULL,
  `short_desc_am` text DEFAULT NULL,
  `icon_class` varchar(50) DEFAULT 'fa-video',
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT 0.00,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `service_name_am`, `slug`, `short_desc`, `short_desc_am`, `icon_class`, `description`, `image`, `price`, `status`, `created_at`) VALUES
(1, 'Film & Video Production', 'የፊልም እና ቪዲዮ ፕሮዳክሽን', 'film-video-production', 'We produce films, documentaries, and cinematic videos that bring stories to life. Every frame is crafted with emotion and creativity to make your vision unforgettable.', 'ታሪኮችን ሕያው የሚያደርጉ ፊልሞችን፣ ዘጋቢ ፊልሞችን እና ሲነማቲክ ቪዲዮዎችን እናዘጋጃለን። እያንዳንዱ ምስል የእርስዎን ራዕይ የማይረሳ ለማድረግ በስሜት እና በፈጠራ የተሰራ ነው።', 'fa-film', 'Cinematic films, documentaries and narrative video content.', NULL, 0.00, 1, '2026-08-18 16:25:39'),
(2, 'TV Commercials', 'የቴሌቪዥን ማስታወቂያዎች', 'tv-commercials', 'Our commercials blend storytelling with strategy to capture attention and connect with your audience. We help brands communicate powerfully on every screen.', 'የእኛ ማስታወቂያዎች ትኩረትን ለመሳብ እና ከተመልካቾችዎ ጋር ለመገናኘት ታሪክን ከስልት ጋር ያዋህዳሉ። ብራንዶች በሁሉም ስክሪኖች ላይ በኃይል እንዲግባቡ እናግዛለን።', 'fa-tv', 'TV advertisements, brand campaigns and promotional videos.', NULL, 0.00, 1, '2026-08-18 16:25:39'),
(3, 'YouTube Video & Podcast Production', 'የዩቲዩብ ቪዲዮ እና ፖድካስት ዝግጅት', 'youtube-podcast-production', 'We help creators and businesses produce professional content for YouTube and podcast platforms — from recording to editing and publishing.', 'የፈጠራ ባለሙያዎች እና የንግድ ድርጅቶች ለዩቲዩብ እና ፖድካስት መድረኮች ሙያዊ ይዘት እንዲያዘጋጁ እናግዛለን — ከቀረጻ ጀምሮ እስከ ኤዲቲንግ እና ህትመት ድረስ።', 'fa-podcast', 'Recording, production, editing, thumbnails and publishing-ready content.', NULL, 0.00, 1, '2026-08-18 16:25:39'),
(4, 'Social Media Management', 'የማህበራዊ ሚዲያ አስተዳደር', 'social-media-management', 'We manage your online presence across Facebook, Instagram, TikTok, and more. Our creative posts and ads help your brand grow and engage with followers daily.', 'የመስመር ላይ ተገኝነትዎን በፌስቡክ፣ ኢንስታግራም፣ ቲክቶክ እና ሌሎችም ላይ እናስተዳድራለን። የእኛ የፈጠራ ልጥፎች እና ማስታወቂያዎች ብራንድዎ እንዲያድግ እና በየቀኑ ከተከታዮች ጋር እንዲገናኝ ያግዛሉ።', 'fa-hashtag', 'Content planning, graphics, short-form video and campaign packages.', NULL, 0.00, 1, '2026-08-18 16:25:39'),
(5, 'Wedding & Event Coverage', 'የሰርግ እና ልዩ ሁነቶች ቀረጻ', 'wedding-event-coverage', 'From weddings to birthdays and concerts, we capture the magic of every moment with cinematic visuals and timeless storytelling.', 'ከሠርግ እስከ ልደት እና ኮንሰርቶች ድረስ የእያንዳንዱን ቅጽበት ድንቅ ገጽታ በሲነማቲክ ምስሎች እና ዘመን የማይሽረው የታሪክ አተረካክ እንቀርጻለን።', 'fa-camera-retro', 'Wedding, corporate, cultural and special-event photo/video coverage.', NULL, 0.00, 1, '2026-08-18 16:25:39'),
(6, 'Post Production & Editing', 'ድህረ-ምርት እና ኤዲቲንግ', 'post-production-editing', 'Our editing team refines every detail — from color grading and sound design to motion graphics — turning raw footage into professional masterpieces.', 'የኤዲቲንግ ቡድናችን ከከለር ግሬዲንግ እና የድምፅ ዲዛይን ጀምሮ እስከ ሞሽን ግራፊክስ ድረስ እያንዳንዱን ዝርዝር በማስተካከል ጥሬ ቀረጻዎችን ወደ ሙያዊ ድንቅ ስራዎች ይቀይራል።', 'fa-sliders-h', 'Video editing, color grading, sound editing and motion graphics.', NULL, 0.00, 1, '2026-08-18 16:25:39'),
(7, 'Graphics Design', 'ግራፊክስ ዲዛይን', 'graphics-design', 'We create professional visual content, including company logos, full branding, school and magazine designs, posters, flyers, and social media graphics. Our designs combine creativity and strategy to make your brand memorable, cohesive, and visually impactful.', 'የድርጅት ሎጎዎችን፣ ሙሉ ብራንዲንግን፣ የትምህርት ቤት እና የመጽሔት ዲዛይኖችን፣ ፖስተሮችን፣ ፍላየሮችን እና የማህበራዊ ሚዲያ ግራፊክስን ጨምሮ ሙያዊ የእይታ ይዘቶችን እንፈጥራለን።', 'fa-palette', 'Branding, posters, social media graphics and thumbnails.', NULL, 0.00, 1, '2026-08-18 16:25:39'),
(8, 'Web Development', 'የድረ-ገጽ ልማት', 'web-development', 'We design and develop modern, responsive websites that showcase your brand online. From simple landing pages to full websites, we make your digital presence stand out.', 'ብራንድዎን በመስመር ላይ የሚያሳዩ ዘመናዊ፣ ምላሽ ሰጪ ድረ-ገጾችን እንነድፋለን እና እናዘጋጃለን። ከቀላል የማረፊያ ገጾች እስከ ሙሉ ድረ-ገጾች ድረስ የዲጂታል ተገኝነትዎ ጎልቶ እንዲታይ እናደርጋለን።', 'fa-laptop-code', 'Responsive business websites, landing pages and web systems.', NULL, 0.00, 1, '2026-08-18 16:25:39'),
(9, 'Advertisement & Boosting Service', 'የማስታወቂያ እና የማሳደግ አገልግሎት', 'advertisement-boosting-service', 'We create and boost online advertisements to reach your target audience effectively. From Facebook & Instagram ads to Google campaigns, we maximize your brand visibility and engagement.', 'የታለሙ ተመልካቾች ዘንድ በብቃት ለመድረስ የመስመር ላይ ማስታወቂያዎችን እንፈጥራለን እንዲሁም እናሳድጋለን (Boost እናደርጋለን)። ከፌስቡክ እና ኢንስታግራም ማስታወቂያዎች እስከ ጎግል ዘመቻዎች ድረስ የብራንድዎን ታይነት እና ተሳትፎ ከፍ እናደርጋለን።', 'fa-bullhorn', 'Digital advertising, campaign setup, targeting and performance support.', NULL, 0.00, 1, '2026-08-18 16:25:39'),
(10, 'Film & Video Production', 'የፊልም እና ቪዲዮ ፕሮዳክሽን', 'film-production', 'Cinematic films, narrative videos, and high-end creative documentaries.', 'ሲነማቲክ ፊልሞች፣ ዘጋቢ ፊልሞች እና ደረጃቸውን የጠበቁ የፈጠራ ስራዎች።', 'fa-film', NULL, NULL, 0.00, 1, '2026-09-30 18:49:53'),
(11, 'TV Commercials', 'የቴሌቪዥን ማስታወቂያዎች', 'tv-commercials', 'High-impact TV adverts, brand storytelling, and promotional campaigns.', 'የቴሌቪዥን እና የማህበራዊ ሚዲያ የንግድ ማስተዋወቂያዎች እና ብራንዲንግ።', 'fa-tv', NULL, NULL, 0.00, 1, '2026-09-30 18:49:53'),
(12, 'YouTube & Podcast Production', 'የዩቲዩብ እና ፖድካስት ዝግጅት', 'youtube-podcast', 'End-to-end studio setup, multi-cam recording, audio engineering, and editing.', 'ባለብዙ ካሜራ የፖድካስት እና የዩቲዩብ ስቱዲዮ ቀረጻ፣ ድምፅ ማስተካከል እና ኤዲቲንግ።', 'fa-podcast', NULL, NULL, 0.00, 1, '2026-09-30 18:49:53'),
(13, 'Social Media Management', 'የማህበራዊ ሚዲያ አስተዳደር', 'social-media', 'Engaging short-form reels/TikToks, content calendar, and digital branding.', 'ሪልስ፣ ቲክቶክ፣ የይዘት ስትራቴጂ እና የማህበራዊ ገጾች ቀጣይነት ያለው አስተዳደር።', 'fa-hashtag', NULL, NULL, 0.00, 1, '2026-09-30 18:49:53'),
(14, 'Wedding & Event Coverage', 'የሰርግ እና ልዩ ሁነቶች ቀረጻ', 'wedding-events', 'Memorable cinematic capture of corporate events, grand weddings, and galas.', 'የሰርግ፣ የድርጅት ስብሰባዎች እና ታላላቅ ሁነቶች በከፍተኛ ሲነማቲክ ጥራት።', 'fa-camera-retro', NULL, NULL, 0.00, 1, '2026-09-30 18:49:53'),
(15, 'Post Production & Editing', 'ኤዲቲንግ እና ድህረ-ምርት', 'post-production', '4K video cutting, Hollywood-grade color grading, VFX, and audio mastering.', 'የከለር ግሬዲንግ፣ የድምፅ ማስተካከል፣ ሞሽን ግራፊክስ እና ፕሮፌሽናል ኤዲቲንግ።', 'fa-sliders-h', NULL, NULL, 0.00, 1, '2026-09-30 18:49:53'),
(16, 'Graphics Design', 'ግራፊክስ ዲዛይን', 'graphics-design', 'Visual identities, movie posters, billboards, and marketing collaterals.', 'የፊልም ፖስተሮች፣ ሎጎዎች፣ ብራንዲንግ እና የግብይት ማስተዋወቂያ ዲዛይኖች።', 'fa-palette', NULL, NULL, 0.00, 1, '2026-09-30 18:49:53'),
(17, 'Web Development', 'የድረ-ገጽ ልማት', 'web-development', 'High-performance interactive websites, landing pages, and web apps.', 'ዘመናዊ፣ ፈጣን እና ለተጠቃሚ ምቹ የሆኑ የንግድ ድረ-ገጾች እና ሲስተሞች።', 'fa-laptop-code', NULL, NULL, 0.00, 1, '2026-09-30 18:49:53'),
(18, 'Advertisement & Boosting', 'የዲጂታል ማስታወቂያ እና ቡስቲንግ', 'ad-boosting', 'Targeted performance marketing across Meta, Google, TikTok, and Telegram.', 'ውጤታማ የማስታወቂያ ዘመቻዎች፣ የታለሙ ደንበኞች ተደራሽነት እና የገበያ ማሳደግ።', 'fa-bullhorn', NULL, NULL, 0.00, 1, '2026-09-30 18:49:53');

-- --------------------------------------------------------

--
-- Table structure for table `service_packages`
--

CREATE TABLE `service_packages` (
  `package_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `features` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_packages`
--

INSERT INTO `service_packages` (`package_id`, `name`, `description`, `price`, `duration`, `features`, `status`) VALUES
(1, 'Basic', 'Starter creative package', 5000.00, '3-5 days', 'Basic editing|1 revision|Digital delivery', 1),
(2, 'Standard', 'Professional production package', 15000.00, '1-2 weeks', 'Professional production|3 revisions|HD delivery', 1),
(3, 'Gold', 'Premium cinematic package', 30000.00, '2-4 weeks', 'Full production|Unlimited revisions|4K delivery|Priority support', 1),
(4, 'Custom', 'Tailored to project needs', 0.00, 'Flexible', 'Custom scope|Custom quotation|Dedicated support', 1);

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `request_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `project_title` varchar(180) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(180) DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `budget` decimal(12,2) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Reviewed','Quotation Sent','Approved','In Progress','Completed','Rejected') DEFAULT 'Pending',
  `payment_status` enum('Unpaid','Pending_Verification','Paid') DEFAULT 'Unpaid',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_slip` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`request_id`, `customer_id`, `service_id`, `project_title`, `description`, `location`, `preferred_date`, `budget`, `attachment`, `status`, `payment_status`, `payment_method`, `payment_slip`, `payment_reference`, `created_at`) VALUES
(1, 2, 1, 'erty', 'zsxdcfgvhbnjlmngxdcjghbm', '567y', '2026-08-21', 12345.00, NULL, 'Pending', 'Unpaid', NULL, NULL, NULL, '2026-08-20 16:10:15'),
(2, 13, 1, 'wwesrdtfyg', 'ertdfyu', '', NULL, NULL, NULL, 'Pending', 'Unpaid', NULL, NULL, NULL, '2026-09-19 20:17:36'),
(3, 14, 5, 'widdening', 'tryghjrhngv', '', '2026-11-03', 2300.00, NULL, 'Completed', 'Unpaid', NULL, NULL, NULL, '2026-09-28 16:51:08'),
(4, 21, 5, 'widdening', 'erdft', 'koye', '2026-11-03', 2300.00, NULL, 'Completed', 'Pending_Verification', 'ቴሌብር (Telebirr)', 'slip_30101308a0bf2bf1.png', '', '2026-10-03 08:46:26'),
(5, 22, 6, 'Film Editing', 'Full Movie', 'Saris', NULL, NULL, NULL, 'Pending', 'Pending_Verification', 'የኢትዮጵያ ንግድ ባንክ (CBE)', 'slip_2d3ae90afaefae22.jpeg', '', '2026-10-05 08:33:26'),
(6, 22, 5, 'wedding', '', '', NULL, NULL, NULL, 'Pending', 'Unpaid', NULL, NULL, NULL, '2026-10-05 14:55:15'),
(7, 22, 7, 'Graphics Design - Full Brand Suite (Standard)', NULL, NULL, NULL, 35000.00, NULL, 'Pending', 'Unpaid', NULL, NULL, NULL, '2026-10-06 08:06:16'),
(8, 22, 7, 'Graphics Design - Essential Identity (Basic)', NULL, NULL, NULL, 12000.00, NULL, 'Pending', 'Unpaid', NULL, NULL, NULL, '2026-10-06 08:06:21'),
(9, 22, 7, 'Graphics Design - Essential Identity (Basic)', NULL, NULL, NULL, 12000.00, NULL, 'Pending', 'Unpaid', NULL, NULL, NULL, '2026-10-06 08:08:24'),
(10, 22, 8, 'Web Development - Portfolio Landing (Basic)', NULL, NULL, NULL, 25000.00, NULL, 'Pending', 'Pending_Verification', 'የኢትዮጵያ ንግድ ባንክ (CBE)', 'slip_513d74229e74bfc3.webp', '', '2026-10-06 13:04:44');

-- --------------------------------------------------------

--
-- Table structure for table `social_media_links`
--

CREATE TABLE `social_media_links` (
  `id` int(11) NOT NULL,
  `platform` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone`, `password_hash`, `role`, `status`, `created_at`, `reset_token`, `reset_expires`) VALUES
(1, 'NESERE Administrator', 'admin@nesere.local', '0911000000', '$2y$10$KUiBdRdR.gMfxUbRe3zik.2GLWsDPzTCQbMnLHTRwzb368TiONpj.', 'admin', 1, '2026-08-18 16:25:39', NULL, NULL),
(2, 'hiwot', 'kaldis@gmail.com', 'kaldis@gmail.com', '$2y$10$6UozfCEUbUKdP9LOsyEB8ugs4pTsRntNbSUpnDW2cN1bM2oeGiZaO', 'customer', 1, '2026-08-18 18:26:54', NULL, NULL),
(5, 'susu', 's@gmail.com', '991113720', '$2y$10$3aFLg05RMBaVFeSd.Nzy8.kTRgps/LGuqp.RDT.4PoS42clkj1cAi', 'customer', 1, '2026-09-09 20:41:12', NULL, NULL),
(7, 'susu', 'ldis@gmail.com', '991113720', '$2y$10$2FiSgjH3g9Z4rScDrr7SLe0yWH1gnBLKHH4Pe5ymeMd4g0Fh1X3wu', 'customer', 1, '2026-09-09 20:42:34', NULL, NULL),
(10, 'Hiwi Na', 'hiwina@gmail.com', '0900008712', '$2y$10$AUzPSLGkuMuK1VKUJRwUROI1CYMt/A9Hj9/upNBsXPc1CUipz5nhu', 'customer', 1, '2026-09-19 19:53:13', NULL, NULL),
(12, 'New Admin', 'admin@neser.com', '0912345678', '$2y$10$92IXUNpkjoO0rOQ5byMi.Ye4oKoEa3Ro9llC8b9sH9XO...', 'admin', 1, '2026-09-19 20:07:35', NULL, NULL),
(13, 'Admin User', 'admin@gmail.com', '0911111111', '$2y$10$fyQVCTPJAfNf5qZi9ig4O.97862AnPfoZuvc5HUoYAzTIIbFlTeIu', 'customer', 1, '2026-09-19 20:16:09', NULL, NULL),
(14, 'hiwot', 'hiwot@gmail.com', '991113720', '$2y$10$8fo6HbGXJdInzURxMxfTtuaSJ.NOEbeSPmo594FEeCqWAluV7cpt2', 'customer', 1, '2026-09-28 16:42:21', NULL, NULL),
(15, 'neser', 'neser@gmail.com', '0989879868', '$2y$10$cjt0FhcVfTtbrjdFPf/duuKS6ShBCQfmwokifgsT0OaamrNnyeApi', 'customer', 1, '2026-09-28 16:58:12', NULL, NULL),
(16, 'hiwot', 'hiwina48@gmail.com', '0991113720', '$2y$10$u70atwZUmZ7Xu3VfY8wWaeG6eb2z/4u4yNS3Jx0jboIaJJ.XXqPWy', 'customer', 1, '2026-10-02 08:37:59', '886b41a68c537b8c6228abe731da77c21a7d579901fd9013fa9f097837acc38a', '2026-10-02 11:40:55'),
(19, 'Yeabsira', 'Yeab@nesere.local', '0940695261', '$2y$10$j7nif8UHmYIx3LTKWIp0z.k85pYlL1QGYLMzIqudgJu/RVpn6.hY6', 'admin', 1, '2026-10-02 16:26:22', NULL, NULL),
(20, 'hiwot', 'hiwot04@gmail.com', '0909905312', '$2y$10$sxey04VE0WofOLGS1wuzF.xdqOc22kpIr6DCqcGDcTTUK90wQVKW2', 'customer', 1, '2026-10-02 20:49:01', NULL, NULL),
(21, 'hiwi', 'hiwi20@gmail.com', '0989879868', '$2y$10$FTQVx14RULg/LHQfKvgQo.I4gSJhohU/JjkVIIhPHTmX2AeRqMIky', 'customer', 1, '2026-10-03 07:46:26', NULL, NULL),
(22, 'Yeab', 'Yeabsirademissie20@gmail.com', '0940695261', '$2y$10$jar83lhQUVvPc/AQV9cqI.ro.glEXRfvf/hDsfmzltV97KTHl.VyW', 'customer', 1, '2026-10-05 07:33:26', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`quotation_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `service_packages`
--
ALTER TABLE `service_packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `social_media_links`
--
ALTER TABLE `social_media_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `quotation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `service_packages`
--
ALTER TABLE `service_packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `social_media_links`
--
ALTER TABLE `social_media_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `service_requests` (`request_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_requests_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
