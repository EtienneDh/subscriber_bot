<?php

namespace Utils;

class Tools
{
    public static function extractJsonFromFile(string $filePath, bool $asArray = true)
    {
        if(file_exists($filePath . '.json')) {
            $content = file_get_contents($filePath . '.json');
        } else {
            exit("Unvalid path: \n $filePath ,\n in Tools::extractJsonFromFile \n");
        }

        return json_decode($content, $asArray);
    }
}
