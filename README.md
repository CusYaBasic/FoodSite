# Food Delivery Site

I orginally created this out of boredom as I've always used HTML/PHP for small scale websites but nothing overly big. I wanted to expand upon [Food App](https://github.com/CusYaBasic/FoodApp) as Food App was only ever a prototype made to show someone I previously worked with that I could build them an app. However with a change of job the app became abandoned due to lack of personal time. This is far from finished and only really has basic login avaliable, but it might be a good start for someone who wants to make a website.
Admittingly alot of the PHP stuff could have been done better which is why I started a whole new project from the ground up. Not saying this isn't usable in anyway but lacks a little orginisation.

---

## Features:
* MySQL databases for storing user info
* mailtrap.io for testing emails
* Email verifcation
* Forgotten password
* Register account
* Full login
* JWT for security
* Sessions and cookies
* Basic index page with header and sidebar
* News tab with news page which grabs news from the MySQL database

## Media:
### Login:
![image](https://github.com/CusYaBasic/FoodSite/assets/86253238/7e2919b3-9171-40a6-9453-56b0a3559628)  

### Register:
![image](https://github.com/CusYaBasic/FoodSite/assets/86253238/40585c0b-0b80-47f6-a378-719ef5823d98)  

### Forgot Password:
![image](https://github.com/CusYaBasic/FoodSite/assets/86253238/cd8051c3-5365-449c-afe2-187a7795c62a)  

### Email Verifcation:
![image](https://github.com/CusYaBasic/FoodSite/assets/86253238/f55a7e24-974b-4952-b616-c6cdf1be470c)  

### Dashboard:
![image](https://github.com/CusYaBasic/FoodSite/assets/86253238/35514e9c-c9ff-44c9-b056-465a4f270bab)  

### News Tab:
![image](https://github.com/CusYaBasic/FoodSite/assets/86253238/d8c6a116-47e2-424a-bb71-81573db9b8ad)  

---

Please excuse the layout and design of the pages, I'm not very strong when it comes to frontend or any type of artist design. But should be more than enough for someone to use as a base and build off from

---

### Config.php:
You'll need to change a few things in config.php like database details and mail servers etc.

---

### MySQL:

```
CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `image` blob DEFAULT NULL,
  `date` date NOT NULL,
  `time_posted` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('active','inactive','suspended','deleted') DEFAULT 'active',
  `verification_status` enum('pending','verified') DEFAULT 'pending',
  `verification_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
```

---

### Buy me a coffee:  
If you use any part of this and want to thank me you can buy me a coffee at [this link](https://www.paypal.me/CusYaBasic)
