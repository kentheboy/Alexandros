<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ImageService {

    public function loadTest() {
        Log::info('Log from ImageService: test');
    }


}