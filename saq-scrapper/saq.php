<?php
require 'vendor/autoload.php';

use Goutte\Client;
use App\Models\Bouteille;

function scrapeSAQWines($url, $client, $file)
{
    $crawler = $client->request('GET', $url);

    // Extract wine titles using the updated selector
    $crawler->filter('a.product-item-link')->each(function ($node) use ($file) {
        $title = trim($node->text());
        echo "Wine Title: $title\n";


        $bouteille = new Bouteille();
        $data = ['title'=> $title];
       
        $insert = $bouteille->insert($data); 

        // if($insert){
        //     echo 'ok';
        // }
        fputcsv($file, [$title]);
    });

    // Handle pagination
    try {
        $nextPage = $crawler->filter('a.action.next')->attr('href');
        if ($nextPage) {
            // Returns the absolute URL
            return $nextPage; 
        }
    } catch (InvalidArgumentException $e) {
        // No next page found
        return null; 
    }

    return null;
}

$client = new Client();
$file = fopen("wines.csv", "a");


$nextUrl = "https://www.saq.com/en/products/wine";

while ($nextUrl) {
    echo "Scraping: $nextUrl\n";
    $nextUrl = scrapeSAQWines($nextUrl, $client, $file);
}

fclose($file);
?>
