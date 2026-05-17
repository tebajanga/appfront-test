<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadProductImage
{
    public function handle(UploadedFile $file): string
    {
        return $file->storeAs(
            'uploads/products',
            Str::uuid()->toString().'.'.$file->getClientOriginalExtension(),
            'public'
        );
    }
}