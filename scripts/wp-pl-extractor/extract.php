<?php
include_once(__DIR__ . '/guzzle.phar');

$wpClient = new Guzzle\Service\Client(
    "http://en.wikipedia.org/"
);

echo "Retrieving: http://en.wikipedia.org/wiki/List_of_programming_languages\n";
$response = $wpClient->get("wiki/List_of_programming_languages")->send();

$overviewDom = new DOMDocument();
@$overviewDom->loadHTML($response->getBody(true));

$overviewXpath = new DOMXPath($overviewDom);

$languageLinks = $overviewXpath->query(
   "//table[preceding-sibling::h2[1]]/tr/td/ul/li/a"
);

$languages = array();
$urls = array();

foreach($languageLinks as $languageLink){
    $hrefAttribute = $languageLink->attributes->getNamedItem("href")->textContent;

    $language = array(
        "name" => $languageLink->textContent,
        "aliases" => array(),
        "uid" => preg_replace(
            array('(_?\\((?<!/)programming_language\\)?)', '(/)', '#'),
            array('', '_', '_sharp'),
            urldecode(
                strtolower(
                    substr(
                        $hrefAttribute,
                        strpos(
                            "/wiki/",
                            $hrefAttribute
                        ) + 6
                    )
                )
            )
        )
    );

    $urls[$language['uid']] = $hrefAttribute;
    $languages[] = $language;
}

printf(
    "%d languages collected, starting to retrieve detailed data.\n",
    count($languages)
);

$detailedLanguages = array();
foreach($languages as $language) {
    $url = $urls[$language['uid']];
    printf(
        "Detail retrieval for %s (%s)\n",
        $language['name'],
        $url
    );

    $detailedLanguage = array(
        'name' => $language['name'],
        'aliases' => $language['aliases'],
        'wiki' => "http://en.wikipedia.org" . $url,
        'description' => array()
    );

    try{
        $response = $wpClient->get($url)->send();
    }
    catch(Guzzle\Http\Exception\ClientErrorResponseException $e) {
        printf("ERROR retrieving %s\n", $url);
        $detailedLanguages[$language['uid']] = $detailedLanguage;
        continue;
    }

    $languageDom = new DOMDocument();
    @$languageDom->loadHTML($response->getBody(true));
    $languageXPath = new DOMXPath($languageDom);

    $paragraphs = $languageXPath->query(
        '//div[@id="mw-content-text"]/p[not(following-sibling::p[0])]'
    );

    $descriptions = array();
    foreach($paragraphs as $paragraph) {
        $description = strip_tags($paragraph->textContent);
        $description = preg_replace('(\\s*\\[[0-9]+\\])', '', $description);

        $descriptions[] = $description;
    }

    $detailedLanguage['description'] = $descriptions;

    $detailedLanguages[$language['uid']] = $detailedLanguage;

    // Sleep 600ms between each retrieval. We don't want to DDOS wikipedia ;)
    usleep( 600000 );
}

echo "Storing information to disk.\n";

file_put_contents(
    __DIR__ . '/languages/languages.json',
    json_encode($languages)
);

foreach($detailedLanguages as $uid => $detailedLanguage){
    file_put_contents(
        __DIR__ . '/languages/' . $uid . '.json',
        json_encode($detailedLanguage)
    );
}

echo "Everyting done. Have fun.\n";
