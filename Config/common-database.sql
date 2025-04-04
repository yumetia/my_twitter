-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 19, 2025 at 12:48 PM
-- Server version: 11.6.2-MariaDB
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tweet_academy`
--

-- --------------------------------------------------------

--
-- Table structure for table `Comments`
--

CREATE TABLE `Comments` (
  `id` int(11) NOT NULL,
  `tweet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `like_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `content` varchar(140) NOT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Follows`
--

CREATE TABLE `Follows` (
  `follow_id` int(11) NOT NULL,
  `follower_id` int(11) DEFAULT NULL,
  `followed_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Hashtags`
--

CREATE TABLE `Hashtags` (
  `hashtag_id` int(11) NOT NULL,
  `tweet_id` int(11) DEFAULT NULL,
  `retweet_id` int(11) DEFAULT NULL,
  `tag` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Likes`
--

CREATE TABLE `Likes` (
  `like_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tweet_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Mentions`
--

CREATE TABLE `Mentions` (
  `mention_id` int(11) NOT NULL,
  `tweet_id` int(11) DEFAULT NULL,
  `mentioned_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Messages`
--

CREATE TABLE `Messages` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` date DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Retweets`
--

CREATE TABLE `Retweets` (
  `retweet_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tweet_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Tweets`
--

CREATE TABLE `Tweets` (
  `tweet_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` varchar(140) NOT NULL,
  `created_at` date DEFAULT NULL,
  `is_archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `bio` varchar(200) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `posts` int(11) DEFAULT 0,
  `reactions` int(11) DEFAULT 0,
  `retweets` int(11) DEFAULT 0,
  `theme` tinyint(1) DEFAULT 1,
  `created_at` date DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Comments`
--
ALTER TABLE `Comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tweet_id` (`tweet_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `like_id` (`like_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `Follows`
--
ALTER TABLE `Follows`
  ADD PRIMARY KEY (`follow_id`),
  ADD KEY `follower_id` (`follower_id`),
  ADD KEY `followed_id` (`followed_id`);

--
-- Indexes for table `Hashtags`
--
ALTER TABLE `Hashtags`
  ADD PRIMARY KEY (`hashtag_id`),
  ADD UNIQUE KEY `tag` (`tag`),
  ADD KEY `tweet_id` (`tweet_id`),
  ADD KEY `retweet_id` (`retweet_id`);

--
-- Indexes for table `Likes`
--
ALTER TABLE `Likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tweet_id` (`tweet_id`);

--
-- Indexes for table `Mentions`
--
ALTER TABLE `Mentions`
  ADD PRIMARY KEY (`mention_id`),
  ADD KEY `tweet_id` (`tweet_id`),
  ADD KEY `mentioned_user_id` (`mentioned_user_id`);

--
-- Indexes for table `Messages`
--
ALTER TABLE `Messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Retweets`
--
ALTER TABLE `Retweets`
  ADD PRIMARY KEY (`retweet_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tweet_id` (`tweet_id`);

--
-- Indexes for table `Tweets`
--
ALTER TABLE `Tweets`
  ADD PRIMARY KEY (`tweet_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Comments`
--
ALTER TABLE `Comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Follows`
--
ALTER TABLE `Follows`
  MODIFY `follow_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Hashtags`
--
ALTER TABLE `Hashtags`
  MODIFY `hashtag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Likes`
--
ALTER TABLE `Likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Mentions`
--
ALTER TABLE `Mentions`
  MODIFY `mention_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Messages`
--
ALTER TABLE `Messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Retweets`
--
ALTER TABLE `Retweets`
  MODIFY `retweet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Tweets`
--
ALTER TABLE `Tweets`
  MODIFY `tweet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Comments`
--
ALTER TABLE `Comments`
  ADD CONSTRAINT `Comments_ibfk_1` FOREIGN KEY (`tweet_id`) REFERENCES `Tweets` (`tweet_id`),
  ADD CONSTRAINT `Comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `Comments_ibfk_3` FOREIGN KEY (`like_id`) REFERENCES `Likes` (`like_id`),
  ADD CONSTRAINT `Comments_ibfk_4` FOREIGN KEY (`parent_id`) REFERENCES `Comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Follows`
--
ALTER TABLE `Follows`
  ADD CONSTRAINT `Follows_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `Follows_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `Hashtags`
--
ALTER TABLE `Hashtags`
  ADD CONSTRAINT `Hashtags_ibfk_1` FOREIGN KEY (`tweet_id`) REFERENCES `Tweets` (`tweet_id`),
  ADD CONSTRAINT `Hashtags_ibfk_2` FOREIGN KEY (`retweet_id`) REFERENCES `Retweets` (`retweet_id`);

--
-- Constraints for table `Likes`
--
ALTER TABLE `Likes`
  ADD CONSTRAINT `Likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `Likes_ibfk_2` FOREIGN KEY (`tweet_id`) REFERENCES `Tweets` (`tweet_id`);

--
-- Constraints for table `Mentions`
--
ALTER TABLE `Mentions`
  ADD CONSTRAINT `Mentions_ibfk_1` FOREIGN KEY (`tweet_id`) REFERENCES `Tweets` (`tweet_id`),
  ADD CONSTRAINT `Mentions_ibfk_2` FOREIGN KEY (`mentioned_user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `Messages`
--
ALTER TABLE `Messages`
  ADD CONSTRAINT `Messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `Retweets`
--
ALTER TABLE `Retweets`
  ADD CONSTRAINT `Retweets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `Retweets_ibfk_2` FOREIGN KEY (`tweet_id`) REFERENCES `Tweets` (`tweet_id`);

--
-- Constraints for table `Tweets`
--
ALTER TABLE `Tweets`
  ADD CONSTRAINT `Tweets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;