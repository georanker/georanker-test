<?php
// disable php error report
error_reporting(0);

if (!empty($_GET)) {
    $url = urldecode($_GET["url"]);
    $sitemaps = simplexml_load_file(sprintf("%s/sitemap.xml", $url));

    if ($sitemaps !== false) {
        $page_uris = array();
    
        // iterate through sitemap locations and urls, and fill the URI's array
        foreach ($sitemaps as $sitemap) {
            $urlset = simplexml_load_file($sitemap->loc);

            foreach ($urlset as $url) {
                $page_uris[] = $url->loc;
            }
        }

        // remove duplicates
        $page_uris = array_unique($page_uris);
        // generate json response
        print(json_encode(array("error" => 0, "message" => sprintf("This site have %d pages!", sizeof($page_uris)))));
    } else {
        print(json_encode(array("error" => 1, "message" => "This site doesn't have a sitemap.xml! Try again!")));
    }
} else {
    print(json_encode(array("error" => 1, "message" => "Please, set the url parameter!")));
}
?>