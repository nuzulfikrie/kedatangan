<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    use HasFactory;

    protected $table = 'site_settings';

    protected $primaryKey = 'id';

    protected $attributes = [];

    public function getAllRecords()
    {
        return Sitesettings::all();
    }

    public function getSetting($setting)
    {
        return Sitesettings::where('setting', $setting)->first();
    }

    public function setSetting($setting, $value)
    {
        return Sitesettings::where('setting', $setting)->update(['value' => $value]);
    }

    public function createRecord($setting, $value)
    {
        return Sitesettings::create(['setting' => $setting, 'value' => $value]);
    }

    public function deleteRecord($id)
    {
        return Sitesettings::where('id', $id)->delete();
    }

    public function wipeAll()
    {
        return Sitesettings::truncate();
    }
}
