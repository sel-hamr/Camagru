# Camagru <img src="https://img.shields.io/static/v1?label=&message=php&logo=php&color=gray"/> <img src="https://img.shields.io/static/v1?label=&message=html&logo=html5&color=gray"/> <img src="https://img.shields.io/static/v1?label=&message=css&logo=css3&color=gray"/> <img src="https://img.shields.io/static/v1?label=&message=javascript&logo=javascript&color=gray"/>

Welcome to Camagru, a small Instagram-like website allowing users to create and share photo-montages. Create a profile, take new photo-montages, share it on the public gallery.

## Intro

Objective of this project is to create a complete website like instagram,sharing pictures with other users

### Stack

-   [PHP](http://www.php.net/) - Backend
-   HTML/CSS/JS - Frontend
-   [BOOTSTRAP](https://getbootstrap.com/)
-   [MySQL](https://www.mysql.com/fr/) - Database
-   Ajax - XMLHttpRequest
-   DOCKER

### Features

My Camagru project handles:

-   DB creation script
-   User creation and authentication using session
-   Pictures upload and default profile picture
-   Complete user profile page with gender, bio, location, interests details...
-   User profile edition (password, details)
-   Capture Images using camera
-   Manipulate image adding to it emoji with possibility to move emoji around image
-   Email notifications for authentication and password reset (with auth key)
-   Profile, pictures deletion and user DB cleanup
-   Responsive design from mobile to desktop
-   Password hashing
-   HTML/Javascript/SQL injections prevention

## Installation

#### Docker installation
   [Linux installation](https://docs.docker.com/engine/install/ubuntu/)</br>
   [Mac installation](https://docs.docker.com/docker-for-mac/install/)</br>
   [Windows installation](https://docs.docker.com/docker-for-windows/install/)</br>
#### Configuration file

-   config/msmtprc file contain mail sender info(you could change this)

```bash
defaults
auth    on
tls     on
logfile /var/log/msmtp.log

dsn_notify off
dsn_return off

# GMAIL
account     gmail
host        smtp.gmail.com
port        587
from        <email>
user        <email>
password    <password>
protocol smtp

account default : gmail

```

#### Docker images installation

```bash
#inside folder of camagru
docker-compose up -d #-d to run it on background
```

after images installed you're good to start website using your ip address,
because we're using http instead of https camera won't work to fix this issue
browse chrome://flags/#unsafely-treat-insecure-origin-as-secure
enable it and add your ip address to the field and relaunch the browser

## Screenshots

Home</br>
![](screenShot/home.png)</br>
SignIn</br>
![](screenShot/singIn.png)</br>
SignUp</br>
![](screenShot/singUp.png)</br>
Snap</br>
![](screenShot/camera.png)</br>
Profile</br>
![](screenShot/profile1.png)</br>
![](screenShot/profile2.png)</br>
