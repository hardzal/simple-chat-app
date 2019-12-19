<?php
date_default_timezone_set("Asia/Jakarta");
session_start();

$connect = new PDO("mysql:host=localhost;dbname=latihan_chat;port=3307", "root", "");
