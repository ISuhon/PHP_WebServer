<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client();

$response = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts');

$data = json_decode($response->getBody(), true);

$filteredData = array_filter($data, function($post) {
    return $post['id'] % 2 == 0 || $post['userId'] < 8;
});

$isDescending = isset($_GET['sort']) && $_GET['sort'] === 'desc';

if ($isDescending) {
    usort($filteredData, function($a, $b) {
        return strcmp($b['title'], $a['title']); 
    });
} else {
    usort($filteredData, function($a, $b) {
        return strcmp($a['title'], $b['title']); 
    });
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts from API</title>
    <link href="https://fonts.googleapis.com/css?family=Courgette|Open+Sans&display=swap" rel="stylesheet">
</head>
<style>
    #sort-btn {
        font-size: 20px;
        font-family: open Verdana, Geneva, Tahoma, sans-serif;

        cursor: pointer;

        margin: 10px;
        padding: 15px;

        border-radius: 5px;
        
        box-shadow: 2px 3px 5px black;
        background: rgb(108,9,121);
        background: linear-gradient(90deg, rgba(108,9,121,0.7343312324929971) 21%, rgba(89,27,145,1) 78%);
        
        transition: all 400ms ease-in;
    }

    #sort-btn:hover {
        animation: gradient 1s infinite linear;
    }

    @keyframes gradient {
        0% {
            background: rgb(108,9,121);
        }

        50% {
            scale: 1.01;
            background: rgba(89,27,145,1);
        }

        100% {
            background: rgb(108,9,121);
        }
    }

    #content {
        padding-left: 25px;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-right: 10px;

        margin-top: 10px;

        box-shadow: 1px 1px 5px black;

        border: 1.5px solid black;
        border-radius: 150px;
        background-color: green;
    }
</style>
<body>
    <h1>Posts from API</h1>

    <!-- Форма для кнопки фільтрації -->
    <form method="get">
        <button id="sort-btn" type="submit" name="sort" value="<?= $isDescending ? 'asc' : 'desc' ?>">
            Sort in <?= $isDescending ? 'Ascending' : 'Descending' ?> Order
        </button>
    </form>

    <ul>
        <?php foreach ($filteredData as $post): ?>
            <li>
                <div id="content">
                    <strong><?= htmlspecialchars($post['title']) ?></strong><br>
                    <?= htmlspecialchars($post['body']) ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
