<?php

$data = [
    'id' => '10',
    'name' => 'テスト',
    'price' => '4600'
];

$opts = [
    'http' => [
        'method' => 'POST',
        'header' => implode("\r\n", [
            "User-Agent: Mozilla/5.0 (windows nt 6.3; wow64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36",
            "Accept-Language: ja",
            "Cookie: test=hoge",
        ]),
    ],
    'data' => http_build_query($data)
];

$ctx = stream_context_create($opts);

$response = file_get_contents('http://localhost/api_test/items/apiindex', false, $ctx);
