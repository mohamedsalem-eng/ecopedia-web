<?php

namespace App\CPU;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EnquiryManager
{
    public static function upload(string $dir,  $image = null)
    {
        if ($image != null) {
            $format = $image->extension() ?? null;


            $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!Storage::disk('private')->exists($dir)) {
                Storage::disk('private')->makeDirectory($dir);
            }
            Storage::disk('private')->put($dir . $imageName, file_get_contents($image));
        } else {
            $imageName = null;
        }

        return $imageName;
    }

    public static function update(string $dir, $old_image,  $image = null)
    {
        if (Storage::disk('private')->exists($dir . $old_image)) {
            Storage::disk('private')->delete($dir . $old_image);
        }
        $format = $image->extension() ?? null;
        $imageName = EnquiryManager::upload($dir, $format, $image);
        return $imageName;
    }

    public static function delete($full_path)
    {
        if (Storage::disk('private')->exists($full_path)) {
            Storage::disk('private')->delete($full_path);
        }

        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];
    }
}
