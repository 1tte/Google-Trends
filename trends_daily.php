<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $country = $_POST['country'];

    // Format date for the Google Trends API
    $formatted_date = date('Ymd', strtotime($date));

    $url = "https://trends.google.com/trends/api/dailytrends?hl=en-US&tz=480&ed={$formatted_date}&geo={$country}&hl=en-US&ns=15";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json, text/plain, */*',
        'accept-language: en-US,en;q=0.9',
        'priority: u=1, i',
        'referer: https://trends.google.com/trends/trendingsearches/daily?geo=' . $country . '&hl=en-US',
        'sec-ch-ua: "Not)A;Brand";v="99", "Google Chrome";v="127", "Chromium";v="127"',
        'sec-ch-ua-arch: "x86"',
        'sec-ch-ua-bitness: "64"',
        'sec-ch-ua-form-factors: "Desktop"',
        'sec-ch-ua-full-version: "127.0.6533.89"',
        'sec-ch-ua-full-version-list: "Not)A;Brand";v="99.0.0.0", "Google Chrome";v="127.0.6533.89", "Chromium";v="127.0.6533.89"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-model: ""',
        'sec-ch-ua-platform: "Windows"',
        'sec-ch-ua-platform-version: "15.0.0"',
        'sec-ch-ua-wow64: ?0',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36',
        'x-client-data: CIy2yQEIorbJAQipncoBCNfaygEIkqHLAQia/swBCP2YzQEIhaDNAQjb/M0BCOKnzgEIhKzOAQiFrc4BCOWvzgEIirLOARihnc4BGLyuzgEYnbHOAQ==',
    ]);

    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        // Remove the unwanted prefix
        $cleaned_response = preg_replace('/^\)\]\}\',/', '', $response);

        // Decode the JSON response to a PHP associative array
        $data = json_decode($cleaned_response, true);

        // Check if decoding was successful
        if ($data === null) {
            echo 'Failed to decode JSON: ' . json_last_error_msg();
        } else {
            $detailed_output = '';
            $simplified_output = '';

            if (isset($data['default']['trendingSearchesDays'])) {
                foreach ($data['default']['trendingSearchesDays'] as $day) {
                    if (isset($day['trendingSearches'])) {
                        foreach ($day['trendingSearches'] as $search) {
                            if (isset($search['title']['query'])) {
                                // Detailed output
                                $detailed_output .= '<div class="card mb-3">';
                                $detailed_output .= '<div class="card-body">';
                                $detailed_output .= '<h5 class="card-title">' . htmlspecialchars($search['title']['query']) . '</h5>';

                                // Check for and display related queries
                                if (isset($search['relatedQueries'])) {
                                    $detailed_output .= '<h6 class="card-subtitle mb-2 text-muted"></h6>';
                                    $detailed_output .= '<ul class="list-group">';
                                    foreach ($search['relatedQueries'] as $related) {
                                        if (isset($related['query'])) {
                                            $detailed_output .= '<li class="list-group-item">' . htmlspecialchars($related['query']) . '</li>';
                                            // Simplified output
                                            $simplified_output .= htmlspecialchars($related['query']) . "\n";
                                        }
                                    }
                                    $detailed_output .= '</ul>';
                                }

                                // Add traffic information if available
                                if (isset($search['formattedTraffic'])) {
                                    $detailed_output .= '<p class="card-text mt-2">' . htmlspecialchars($search['formattedTraffic']) . '</p>';
                                }

                                $detailed_output .= '</div>';
                                $detailed_output .= '</div>';
                            }
                        }
                    }
                }
            } else {
                $detailed_output = '<div class="alert alert-warning">No trending searches found.</div>';
            }

            // Save the simplified output to a file when the save button is clicked
            if (isset($_POST['save']) && $_POST['save'] === 'true') {
                $filename = 'trends-' . $country . '-' . $formatted_date . '.txt';
                file_put_contents($filename, trim($simplified_output));

                // Output saved filename for confirmation
                echo '<div class="alert alert-success">Data saved to ' . $filename . '</div>';
            } else {
                // Output detailed data
                echo '<div class="container mt-4">' . $detailed_output . '</div>';
            }
        }
    }

    // Close cURL session
    curl_close($ch);
} else {
    echo '<div class="alert alert-danger">Invalid request method.</div>';
}
?>
