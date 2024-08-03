<?php
header('Content-Type: application/json');

$user_agents = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36",
    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36",
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.43",
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 OPR/86.0.4240.198",
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:95.0) Gecko/20100101 Firefox/95.0"
];

function extract_download_link($html_content) {
    if (preg_match('/href="((http|https):\/\/download[^"]+)/', $html_content, $matches)) {
        return $matches[1];
    }
    return null;
}

function get_mediafire_download_info($url, $user_agents) {
    $user_agent = $user_agents[array_rand($user_agents)];

    $options = [
        'http' => [
            'header' => [
                "User-Agent: $user_agent",
                "Referer: $url"
            ]
        ]
    ];
    
    $context = stream_context_create($options);
    $page = file_get_contents($url, false, $context);
    if ($page === FALSE) {
        return null;
    }

    $direct_download_url = extract_download_link($page);
    
    return $direct_download_url;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['url'])) {
    $url = $_GET['url'];
    $direct_download_url = get_mediafire_download_info($url, $user_agents);
    
    if ($direct_download_url) {
        $response_data = [
            'download_url' => $direct_download_url,
        ];
        echo json_encode($response_data, JSON_UNESCAPED_SLASHES);
    } else {
        http_response_code(404);
        echo json_encode([
            'error' => 'Direct download URL not found.',
        ], JSON_UNESCAPED_SLASHES);
    }
} else {
    http_response_code(400);
    echo json_encode([
        'error' => 'URL parameter is required.',
    ], JSON_UNESCAPED_SLASHES);
}
?>
