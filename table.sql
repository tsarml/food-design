CREATE DATABASE Food_stat ;

CREATE TABLE user (
    id INT AUTO-INCREMENT PRIMARY KEY,
    email varchar(200) ,
    mdp varchar(200)
    created_at date
);

CREATE TABLE Food (
    id INT AUTO-INCREMENT PRIMARY KEY,
    nom varchar(200),
    emoji varchar(20),
    image varchar(10),
    category varchar(30),
    description varchar(200)
)