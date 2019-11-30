<?php
date_default_timezone_set("Asia/Jakarta");

$connect = new PDO("mysql:host=localhost;dbname=latihan_chat", "root", "");

function fetch_user_last_activity($user_id, $connect)
{
    $query = "SELECT * FROM login_details WHERE user_id = '" . $user_id . "' ORDER BY last_activity DESC LIMIT 1";

    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        return $row['last_activity'];
    }
}

function fetch_user_chat_history($from_user_id, $to_user_id, $connect)
{
    $query = "SELECT * FROM chat_message WHERE (from_user_id = '" . $from_user_id . "' AND to_user_id = '" . $to_user_id . "') OR (from_user_id = '" . $to_user_id . "' AND to_user_id ='" . $from_user_id . "') ORDER BY created_at DESC";

    $statement = $connect->prepare($query);
    $statement->execute();

    $result = $statement->fetchAll();
    $output = '<ul class="list-unstyled">';
    $user_name = '';

    foreach ($result as $row) {
        if ($row['from_user_id'] == $from_user_id) {
            $user_name = '<strong class="text-success">You</strong>';
        } else {
            $user_name = '<strong class="text-danger">' . get_user_name($row['from_user_id'], $connect) . '</strong>';
        }

        $output .= '
            <li style="border-bottom: 1px solid #ccc;">
                <p>' . $user_name . ' - ' . $row['chat_message'] . '
                    <div align="right">
                        - <small><em>' . $row['created_at'] . '</em></small>
                    </div>
                </p>
            </li>
        ';
    }

    $output .= '</ul>';

    $query = "UPDATE chat_message SET status = '0' WHERE from_user_id ='" . $from_user_id . "' AND to_user_id ='" . $to_user_id . "' AND status = '1'";

    $statement = $connect->prepare($query);
    $statement->execute();
    return $output;
}

function get_user_name($user_id, $connect)
{
    $query = "SELECT username FROM login WHERE user_id = '" . $user_id . "'";

    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();

    foreach ($result as $row) {
        return $row['username'];
    }
}


function count_unseen_message($from_user_id, $to_user_id, $connect)
{
    $query = "SELECT * FROM chat_message WHERE from_user_id = '" . $from_user_id . "' AND to_user_id = '" . $to_user_id . "' AND status = '1'";

    $statement = $connect->prepare($query);
    $statement->execute();
    $count = $statement->rowCount();

    $output = '';

    if ($count > 0) {
        $output = '<span class="label label-success">' . $count . '</span>';
        echo "hell!";
    }

    return $output;
}