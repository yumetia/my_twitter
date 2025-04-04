# My Twitter

A lightweight Twitter-like social platform built in PHP, featuring user authentication, tweet creation, comments, likes, and retweets. Developed as part of a full-stack web training project.

## Features

- User registration & login
- Create, edit and delete tweets
- Like and retweet functionality
- Comment system
- Custom PHP MVC framework (no external libraries)
- Basic responsive UI with Skeleton CSS

## Tech Stack

- PHP (vanilla)
- JavaScript (vanilla)
- Skeleton CSS
- SQL (MySQL/MariaDB)

## Setup

1. Clone the repository
2. Import `Config/common-database.sql` into your local MySQL server
3. Update `Config/config.ini` with your DB credentials
4. Run the app locally (e.g., with Apache or PHP built-in server)

```bash
php -S localhost:8000 -t my_twitter
