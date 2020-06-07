#!/usr/bin/env php
<?php

/**
 ******************************************* НАСТРОЙКИ *******************************************
 **/

/**
 * Настройки базы данных
 **/
$defaultMySQLSettings = [
    'main' => [
        'host'    => '127.0.0.1',
        'user'    => 'root',
        'pass'    => 'root',
        'db'      => 'dorway_keyword',
        'charset' => 'utf8mb4',
    ]
];

/**
 *********************************** ДАЛЬШЕ НИЧЕГО НЕ ТРОГАТЬ! ***********************************
 **/

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
set_time_limit(0);

require_once __DIR__ . '/vendor/autoload.php';

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;


$defaultDatabase = new SafeMySQL($defaultMySQLSettings['main']);


echo 'Start ' . implode(' ', $argv) . PHP_EOL;


$fileNames = glob(__DIR__ . '/data/*.xlsx');

echo 'Loaded ' . count($fileNames) . ' files' . PHP_EOL;

if (!$fileNames) {
    exit;
}


$countWithoutGooglePlaceId = 0;
$countWithoutName = 0;
$namesLinks = [];
$fileNumber = 0;
foreach ($fileNames as $fileName) {
    $fileNumber++;
    echo 'Start loading file (' . $fileNumber . ') ' . $fileName . PHP_EOL;

    $reader = ReaderEntityFactory::createReaderFromFile($fileName);
    $reader->open($fileName);

    foreach ($reader->getSheetIterator() as $sheet) {
        $lineNumber = 0;
        foreach ($sheet->getRowIterator() as $row) {
            $lineNumber++;
            $cells = $row->toArray();

            foreach ($cells as &$item) {
                $item = trim($item, chr(239) . chr(187) . chr(191));
                $item = trim($item);
                $item = str_replace('_x000D_', "\n", $item);
                $item = str_replace("\n", " ", $item);
                $item = str_replace("\n", " ", $item);
                $item = str_replace("\n", " ", $item);
                $item = str_replace('  ', ' ', $item);
                $item = str_replace('  ', ' ', $item);
                $item = str_replace('  ', ' ', $item);
                $item = str_replace('  ', ' ', $item);
                $item = str_replace('  ', ' ', $item);
                $item = str_replace('  ', ' ', $item);
                $item = str_replace('  ', ' ', $item);
                $item = str_replace('  ', ' ', $item);
                $item = str_replace('  ', ' ', $item);
                $item = trim($item);
            }

            if ($lineNumber == 1) {
                $namesLinks = $cells;
                continue;
            }

            $newLine = [];
            foreach ($namesLinks as $item) {
                $newLine[$item] = '';
            }

            foreach ($cells as $key => $item) {
                if (isset($namesLinks[$key])) {
                    $newLine[$namesLinks[$key]] = $item;
                }
            }

            foreach ($newLine as &$item) {
                $item = trim($item);
            }

            foreach ($newLine as &$item) {
                if (mb_strtolower($item) == 'null') {
                    $item = '';
                }
            }

            foreach ($newLine as &$item) {
                if (mb_strtolower($item) == 'none') {
                    $item = '';
                }
            }

            if (empty($newLine['google_id'])) {
                $countWithoutGooglePlaceId++;
                continue;
            }

            if (empty($newLine['name'])) {
                $countWithoutName++;
                continue;
            }

            $defaultDatabase->query('INSERT INTO temp SET id = null');

            $tempId = $defaultDatabase->insertId();

            $tempQueries = [
                'temp_id'  => $tempId,
                'query'    => mb_substr($newLine['query'], 0, 255),
                'query_p1' => mb_substr($newLine['query_p1'], 0, 255),
                'query_p2' => mb_substr($newLine['query_p2'], 0, 255),
                'query_p3' => mb_substr($newLine['query_p3'], 0, 255),
                'query_p4' => mb_substr($newLine['query_p4'], 0, 255),
            ];

            try {
                $defaultDatabase->query('INSERT INTO temp_query SET ?u', $tempQueries);
            } catch (Exception $ex) {
                echo $ex->getMessage() . PHP_EOL;
                $defaultDatabase->query('DELETE FROM temp WHERE id = ?i', $tempId);
            }

            unset($newLine['query']);
            unset($newLine['query_p1']);

            $tempQueries = [
                'temp_id'         => $tempId,
                'address'         => mb_substr($newLine['address'], 0, 512),
                'address_city'    => mb_substr($newLine['address_city'], 0, 255),
                'address_borough' => mb_substr($newLine['address_borough'], 0, 255),
                'address_street'  => mb_substr($newLine['address_street'], 0, 255),
                'postal_code'     => mb_substr($newLine['postal_code'], 0, 20),
                'latitude'        => mb_substr($newLine['latitude'], 0, 20),
                'longitude'       => mb_substr($newLine['longitude'], 0, 20),
                'time_zone'       => mb_substr($newLine['time_zone'], 0, 100),
            ];

            try {
                $defaultDatabase->query('INSERT INTO temp_address SET ?u', $tempQueries);
            } catch (Exception $ex) {
                echo $ex->getMessage() . PHP_EOL;
                $defaultDatabase->query('DELETE FROM temp WHERE id = ?i', $tempId);
            }

            unset($newLine['query_p2']);
            unset($newLine['query_p3']);
            unset($newLine['query_p4']);

            unset($newLine['address']);
            unset($newLine['address_city']);
            unset($newLine['address_borough']);
            unset($newLine['address_street']);
            unset($newLine['postal_code']);
            unset($newLine['latitude']);
            unset($newLine['longitude']);
            unset($newLine['time_zone']);

            $tempQueries = [
                'temp_id'           => $tempId,
                'rating'            => ($newLine['rating']) ? $newLine['rating'] : 0,
                'reviews'           => ($newLine['reviews']) ? $newLine['reviews'] : 0,
                'reviews_link'      => mb_substr($newLine['reviews_link'], 0, 512),
                'reviews_per_score' => mb_substr($newLine['reviews_per_score'], 0, 100),
                'reviews_id'        => mb_substr($newLine['reviews_id'], 0, 50),
                'photos_count'      => ($newLine['photos_count']) ? $newLine['photos_count'] : 0,
                'photo'             => mb_substr($newLine['photo'], 0, 512),
                'working_hours'     => mb_substr($newLine['working_hours'], 0, 512),
            ];

            try {
                $defaultDatabase->query('INSERT INTO temp_reviews_photos_hours SET ?u', $tempQueries);
            } catch (Exception $ex) {
                echo $ex->getMessage() . PHP_EOL;
                $defaultDatabase->query('DELETE FROM temp WHERE id = ?i', $tempId);
            }

            unset($newLine['rating']);
            unset($newLine['reviews']);
            unset($newLine['reviews_link']);
            unset($newLine['reviews_per_score']);
            unset($newLine['reviews_id']);
            unset($newLine['photos_count']);
            unset($newLine['photo']);
            unset($newLine['working_hours']);

            $tempQueries = [
                'temp_id'          => $tempId,
                'site'             => mb_substr($newLine['site'], 0, 256),
                'email'            => mb_substr($newLine['email'], 0, 256),
                'email2'           => mb_substr($newLine['email2'], 0, 256),
                'twitter'          => mb_substr($newLine['twitter'], 0, 256),
                'linkedin'         => mb_substr($newLine['linkedin'], 0, 256),
                'facebook'         => mb_substr($newLine['facebook'], 0, 256),
                'instagram'        => mb_substr($newLine['instagram'], 0, 256),
                'google_plus'      => mb_substr($newLine['google_plus'], 0, 256),
                'skype'            => mb_substr($newLine['skype'], 0, 256),
                'telegram'         => mb_substr($newLine['telegram'], 0, 256),
                'site_generator'   => mb_substr($newLine['site_generator'], 0, 256),
                'site_title'       => mb_substr($newLine['site_title'], 0, 256),
                'site_description' => mb_substr($newLine['site_description'], 0, 512),
                'site_keywords'    => mb_substr($newLine['site_keywords'], 0, 512),
            ];

            try {
                $defaultDatabase->query('INSERT INTO temp_internet SET ?u', $tempQueries);
            } catch (Exception $ex) {
                echo $ex->getMessage() . PHP_EOL;
                $defaultDatabase->query('DELETE FROM temp WHERE id = ?i', $tempId);
            }

            unset($newLine['site']);
            unset($newLine['email']);
            unset($newLine['email2']);
            unset($newLine['twitter']);
            unset($newLine['linkedin']);
            unset($newLine['facebook']);
            unset($newLine['instagram']);
            unset($newLine['google_plus']);
            unset($newLine['skype']);
            unset($newLine['telegram']);
            unset($newLine['site_generator']);
            unset($newLine['site_title']);
            unset($newLine['site_description']);
            unset($newLine['site_keywords']);

            $newLine['google_id'] = trim($newLine['google_id'], '/');
            $newLine['google_id'] = str_replace('https://www.google.com/maps/contrib/', '', $newLine['google_id']);

            $tempQueries = [
                'temp_id'    => $tempId,
                'name'       => $newLine['name'],
                'type'       => $newLine['type'],
                'types'      => $newLine['types'],
                'phone'      => $newLine['phone'],
                'verified'   => ($newLine['verified']) ? $newLine['verified'] : 0,
                'owner_id'   => $newLine['owner_id'],
                'owner_link' => $newLine['owner_link'],
                'google_id'  => $newLine['google_id'],
            ];

            try {
                $defaultDatabase->query('INSERT INTO temp_main SET ?u', $tempQueries);
            } catch (Exception $ex) {
                echo $ex->getMessage() . PHP_EOL;
                $defaultDatabase->query('DELETE FROM temp WHERE id = ?i', $tempId);
            }

            unset($newLine['name']);
            unset($newLine['type']);
            unset($newLine['types']);
            unset($newLine['phone']);
            unset($newLine['verified']);
            unset($newLine['owner_id']);
            unset($newLine['owner_link']);
            unset($newLine['google_id']);

            if ($lineNumber % 10000 == 0) {
                echo 'Loaded ' . $lineNumber . ' lines, sleep 10 seconds' . PHP_EOL;
                sleep(10);
            }

        }
    }

    $reader->close();
}

echo 'Count without place_id ' . $countWithoutGooglePlaceId . PHP_EOL;
echo 'Count without name ' . $countWithoutName . PHP_EOL;

echo 'DONE' . PHP_EOL;

echo PHP_EOL;




