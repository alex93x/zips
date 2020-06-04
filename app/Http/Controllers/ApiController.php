<?php

namespace App\Http\Controllers;

use App\Zip;

class ApiController extends Controller
{
    public function getByZip()
    {
        $zip = request('zip');
        return Zip::where('zip', $zip)->get();
    }

    public function getByCityName()
    {
        $city = request('city');
        return Zip::where('city', 'like', "%$city%")->get();
    }

    public function update()
    {
        $file = request()->file('csv');
        $uploadedZips = new \ParseCsv\Csv($file);
        $existedZips = Zip::all();

        $zipsToInsert = array();

        foreach($uploadedZips->data as $zip) {
            if(!$existedZips->contains('zip', $zip['zip'])) {
                $zipsToInsert[] = $zip;
            }
            else {
                Zip::where('zip', $zip['zip'])->update($zip);
            }
        }

        if(!empty($zipsToInsert)) {
            $parts = array_chunk($zipsToInsert, 100);
            foreach($parts as $part) {
                Zip::insert($part);
            }
        }
    }
}
