create database perguntasif;

use perguntasif;

create table usuario(
id int not null auto_increment primary key,
nome varchar(45) not null, 
email varchar(110) not null,
texto varchar(255) not null

);

