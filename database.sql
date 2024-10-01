-- Active: 1727772233990@@127.0.0.1@3306@socialpost
CREATE  DATABASE IF NOT EXISTS socialpost;

USE socialpost;
DROP TABLE IF EXISTS favourite;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS users;


CREATE TABLE IF NOT EXISTS users (
userId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
username VARCHAR(255) NOT NULL UNIQUE,
email VARCHAR (255) NOT NULL UNIQUE,
password VARCHAR (255) NOT NULL,
role VARCHAR(255) NOT NULL,
banDate DATETIME,
createAt DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS posts (
    postId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    content TEXT NOT NULL,
    postedAt DATETIME,
    author_id INT NOT NULL,
    respondTo INT,
    FOREIGN KEY (author_id) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (respondTo) REFERENCES posts(postId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS favourite (
    favourite_users INT NOT NULL,
    favourite_posts INT NOT NULL,
    PRIMARY KEY (favourite_users, favourite_posts),
    FOREIGN KEY (favourite_users) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (favourite_posts) REFERENCES posts(postId) ON DELETE CASCADE
);
