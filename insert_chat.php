<?php

require_once './database.php';
require_once './functions.php';

$data = array(
    ':to_user_id'       => $_POST['to_user_id'],
    ':from_user_id'     => $_SESSION['user_id'],
    ':chat_message'     => encrypt($sBox, $_SESSION['username'], $_POST['chat_message']),
    ':status_message'   => '1'
);

$query = "INSERT INTO chat_message(to_user_id, from_user_id, chat_message, status_message) VALUES(:to_user_id, :from_user_id, :chat_message, :status_message)";

$statement = $connect->prepare($query);

if ($statement->execute($data)) {
    echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);
}
