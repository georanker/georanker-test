<?php
// disable php error report
// error_reporting(0);
set_time_limit(0);

if (!empty($_GET)) {
    $base_url = urldecode($_GET["url"]);
    $urls = array();
    $robots = file_get_contents(sprintf("%s/robots.txt", $base_url));
    $sitemaps = explode("Sitemap:", $robots);

    for ($i = 1; $i < sizeof($sitemaps); $i++) {
        $url = trim($sitemaps[$i]);

        if (filter_var($url, FILTER_VALIDATE_URL))
            $urls[] = $url;
        else
            $urls[] = $base_url . $url;
    }

    global $page_uris;
    $page_uris = array();

    foreach ($urls as $url) {
        page_counter($url, $page_uris);
    }

    $page_uris = array_unique($page_uris);
    printf(sizeof($page_uris));

    // $sitemaps = simplexml_load_file($url);

    // // if it has a sitemap.xml file
    // $page_uris = array();

    // // iterate through sitemap locations and urls, and fill the URI's array
    // foreach ($sitemaps as $sitemap) {
    //     $urlset = simplexml_load_file($sitemap->loc);

    //     foreach ($urlset as $url) {
    //         $page_uris[] = $url->loc;
    //     }
    // }

    // // remove duplicates
    // $page_uris = array_unique($page_uris);
    // generate json response
    // print(json_encode(array("error" => 0, "message" => sprintf("This site have %d pages!", sizeof($page_uris)))));
} else {
    // print(json_encode(array("error" => 1, "message" => "Please, set the url parameter!")));
}

function page_counter($url) {
    $xml = simplexml_load_file($url);

    if (isset($xml->sitemap)) {
        foreach ($xml as $sitemap) {
            page_counter($sitemap->loc);
        }
    } else {
        foreach ($xml as $urlset_url) {
            $page_uris[] = $urlset_url->loc;
        }
    }
}
?>