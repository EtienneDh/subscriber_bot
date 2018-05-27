<?php

namespace Utils;

class Tools
{
    const CONFIG_DIRECTORY = __DIR__  . '/../../config/';

    public static function extractJsonFromFile(string $fileName, bool $asArray = true)
    {
        $filePath = self::CONFIG_DIRECTORY . $fileName;

        if(file_exists($filePath . '.json')) {
            $content = file_get_contents($filePath . '.json');
        } else {
            exit("Unvalid path: \n $filePath ,\n in Tools::extractJsonFromFile \n");
        }

        return json_decode($content, $asArray);
    }
}
