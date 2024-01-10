<?php

namespace App\Observers;

use App\Models\generalrequests\GeneralRequestMaintenanceCar;
use Illuminate\Database\Eloquent\Model;

class UploadImage
{
    public function created(model $model )
    {

        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', request()->builder[GeneralRequestMaintenanceCar::find($model->general_request_maintenance_car_id)->builder()->count()-1	]['image']));
        $filePath = storage_path('app/public/image.png' );
        file_put_contents($filePath, $imageData);
        $model->addMedia($filePath)->toMediaCollection('images');
    }
}
