<?php

namespace App\Http\Controllers;

use App\Zip;
use Illuminate\Support\Facades\DB;

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
        $zipsToUpdate = array();

        foreach($uploadedZips->data as $zip) {
            if(!$existedZips->contains('zip', $zip['zip'])) {
                $zipsToInsert[] = $zip;
            }
            else {
                $zipsToUpdate['lat'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['lat'] . "'";
                $zipsToUpdate['lng'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['lng'] . "'";
                $zipsToUpdate['city'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['city'] . "'";
                $zipsToUpdate['state_id'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['state_id'] . "'";
                $zipsToUpdate['state_name'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['state_name'] . "'";
                $zipsToUpdate['zcta'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['zcta'] . "'";
                $zipsToUpdate['parent_zcta'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['parent_zcta'] . "'";
                $zipsToUpdate['population'][] = "WHEN zip = '" . $zip['zip'] . "' THEN " . $zip['population'];
                $zipsToUpdate['density'][] = "WHEN zip = '" . $zip['zip'] . "' THEN " . $zip['density'];
                $zipsToUpdate['county_fips'][] = "WHEN zip = '" . $zip['zip'] . "' THEN " . $zip['county_fips'];
                $zipsToUpdate['county_name'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['county_name'] . "'";
                $zipsToUpdate['county_weights'][] = 'WHEN zip = "' . $zip['zip'] . '" THEN "' . $zip['county_weights'] . '"';
                $zipsToUpdate['county_names_all'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['county_names_all'] . "'";
                $zipsToUpdate['county_fips_all'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['county_fips_all'] . "'";
                $zipsToUpdate['imprecise'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['imprecise'] . "'";
                $zipsToUpdate['military'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['military'] . "'";
                $zipsToUpdate['timezone'][] = "WHEN zip = '" . $zip['zip'] . "' THEN '" . $zip['timezone'] . "'";
//                Zip::where('zip', $zip['zip'])->update($zip);
            }
        }

        if(!empty($zipsToInsert)) {
            $parts = array_chunk($zipsToInsert, 100);
            foreach($parts as $part) {
                Zip::insert($part);
            }
        }

        if(!empty($zipsToUpdate)) {
            $parts = array_chunk($zipsToUpdate, 100, true);
            foreach($parts as $part) {
                DB::update(
                    "UPDATE `zips` SET "
                    . "lat = CASE " . implode(' ', $part['lat']) . " ELSE lat END, "
                    . "lng = CASE " . implode(' ', $part['lng']) . " ELSE lng END, "
                    . "city = CASE " . implode(' ', $part['city']) . " ELSE city END, "
                    . "state_id = CASE " . implode(' ', $part['state_id']) . " ELSE state_id END, "
                    . "state_name = CASE " . implode(' ', $part['state_name']) . " ELSE state_name END, "
                    . "zcta = CASE " . implode(' ', $part['zcta']) . " ELSE zcta END, "
                    . "parent_zcta = CASE " . implode(' ', $part['parent_zcta']) . " ELSE parent_zcta END, "
                    . "population = CASE " . implode(' ', $part['population']) . " ELSE population END, "
                    . "density = CASE " . implode(' ', $part['density']) . " ELSE density END, "
                    . "county_fips = CASE " . implode(' ', $part['county_fips']) . " ELSE county_fips END, "
                    . "county_name = CASE " . implode(' ', $part['county_name']) . " ELSE county_name END, "
                    . "county_weights = CASE " . implode(' ', $part['county_weights']) . " ELSE county_weights END, "
                    . "county_names_all = CASE " . implode(' ', $part['county_names_all']) . " ELSE county_names_all END, "
                    . "county_fips_all = CASE " . implode(' ', $part['county_fips_all']) . " ELSE county_fips_all END, "
                    . "imprecise = CASE " . implode(' ', $part['imprecise']) . " ELSE imprecise END, "
                    . "military = CASE " . implode(' ', $part['military']) . " ELSE military END, "
                    . "timezone = CASE " . implode(' ', $part['timezone']) . " ELSE timezone END; "
                );
            }
        }
    }
}
