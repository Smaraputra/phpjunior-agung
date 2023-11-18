<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agung - OSKY PHP Junior</title>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <style>
            .news-container {
                /* Display none is essential for the fade in effect to work smoothly. */
                display: none;
                border: 1px solid #ccc; 
                padding: 10px; 
                margin-bottom: 20px;
                border-radius: 10px;
            }
            .news-title {
                /* Additional styling */
                color: blue;
            }
            .main-title {
                /* Additional styling */
                text-align: center;
                padding: 10px; 
                border: 3px solid blue;
            }
            .news-date {
                /* It is essential according to the requirement */
                font-style: italic;
            }
        </style>
    </head>
    <body>
        <?php
            // URL of the JSON data and Fetch the JSON data
            $jsonNewsUrl = "https://test.osky.dev/101/data.json";
            $jsonNews = file_get_contents($jsonNewsUrl);
            // Decode the JSON data into a PHP variable, using built-in php function
            $arrayNews = json_decode($jsonNews, true);
            
            // Sort the data by title, A->Z
            usort($arrayNews, function($a, $b) {
                return strcmp($a['title'], $b['title']);
            });

            // Format date function for better readibilty
            function formatDate($date) {
                // Use unix timestamp for easier formatting
                $timestamp = strtotime($date);
                // I implemented it based on the given format: Tuesday, 22nd of June 2021 10:07 pm. Ref: https://www.php.net/manual/en/datetime.format.php
                return date('l, jS \of F Y g:i a', $timestamp);
            }

            echo '<div>';
            echo '<p class="main-title">List of News</p>';
            // Loop the data according the given condition 
            foreach ($arrayNews as $item) {
                // This class help in styling and fade in animation
                echo '<div class="news-container">';
                echo '<h2 class="news-title">' . $item['title'] . '</h2>';
                // This class set the font style to italic
                echo '<p class="news-date">' . formatDate($item['pubDate']) . '</p>';
                echo '<p>' . $item['description'] . '</p>';
                // If the 'link' is an array, filter and display "Read More" link if URL is present
                if (is_array($item['link'])) {
                    $urlLinks = array_filter($item['link'], function($link) {
                        return filter_var($link, FILTER_VALIDATE_URL);
                    });

                    if (!empty($urlLinks)) {
                        echo '<p><strong>Read More:</strong> <a href="' . $urlLinks[0] . '" target="_blank">Read More</a></p>';
                    }
                } else if (filter_var($item['link'], FILTER_VALIDATE_URL)) {
                    // If the 'link' is a string and is a valid URL, display "Read More" link
                    echo '<p><strong>Read More:</strong> <a href="' . $item['link'] . '" target="_blank">Read More</a></p>';
                }
                echo '</div>';
            }
            
            echo '</div>';
        ?>

        <script>
            // jQuery script for fade-in effect
            $(document).ready(function() {
                $(".news-container").each(function(index) {
                    $(this).delay(500 * index).fadeIn(1000);
                });
            });
        </script>
    </body>
</html>