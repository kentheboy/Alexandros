<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageService {

    public function loadTest() {
        Log::info('Log from ImageService: test');
    }

    /**
     * Save multiple dataUrl images in array, and return array of file name
     *
     * @param array  $dataUrls
     * @return array
     */
    public function saveImages(Array $dataUrls) {
        foreach ($dataUrls as $key => &$dataUrl) {

            if (!isset($dataUrl) || empty($dataUrl)) {
                continue;
            }

            $dataUrl = $this->saveImageAndReturnFileName($dataUrl);
        }

        return $dataUrls;
    }

    /**
     * Save dataUrl file in the `uploads` folder and return saved image file name
     * 
     * @param string $dataUrl
     * @return string
     */
    private function saveImageAndReturnFileName($dataUrl){
        // Split the data URL into its parts
        $parts = explode(',', $dataUrl);
    
        // Extract the mime type and the base64 encoded data
        $mimeType = explode(';', $parts[0])[0];
        $base64Data = $parts[1];
    
        // Decode the base64 encoded data
        $data = base64_decode($base64Data);
    
        // Save the file
        $filename = uniqid() . '.' . explode('/', $mimeType)[1];
        Storage::disk('public')->put('/uploads/' . $filename, $data);
    
        // return the filename back into the $dataUrls array
        return substr($filename, 0);
    }

}