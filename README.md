# Installation Guide Application of coffee

## 1. Create a database
Create a new database. Import the .sql file in "db" directory.


## 2. Configure the database
Create users of the application. Table "cafe_usuario"

"Nombre" -> User Name.<br>
"Password" -> Password for this user in text mode<br>
"foto" -> name of the image file. Hosted in the "img/users/" folder<br>
"Activo" -> User Active Yes / No.<br>


## 3. Set up the database in PHP
We go to the file: controlador/modelo.php<br>
We set the variables:<br>
$db_host = 'localhost';<br>
$db_user = 'db-username';<br>
$dB_pass = 'db-password';<br>
$db_database = 'db-name';<br>

**We report on our server data.**


## 4. Add the images of the company logo and users
Company logo: img/logo.png<br>
Avatar: img/users/file.png <br>
This name of file is the name we have given in the database/table/"cafe_usuarios".


## 5. User Settings.
User ADMIN:  Database / table "cafe_usuarios" field type = "ADMIN"<br>
User NORMAL: Database / table "cafe_usuarios" field type = "NORMAL"


## 6. Delete sample data
Delete records of the tables "cafe_pagos" and "cafe_consumo"
