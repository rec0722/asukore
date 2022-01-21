<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ReportImage extends Model
{
  use HasFactory;

  /**
   * モデルに関連付けるテーブル
   *
   * @var string
   */
  protected $table = 'report_images';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'report_id',
    'title',
    'file',
    'sort'
  ];

  /*
  * [File uploads]------------------------------------------
  */
  /**
   * upload Picture
   */
  public static function uploadFile($file, $id)
  {
    $attachId = uniqid(rand() . '_');
    $extension = $file->extension();
    $attachName = $attachId . '.' . $extension;
    $attach = InterventionImage::make($file)
      ->resize(1280, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      })->encode();
    Storage::put('public/report/' . $id . '/' . $attachName, $attach);
    return $attachName;
  }

  /*
  * [File URL]------------------------------------------
  */
  /**
   * Get the URL of the picture
   */
  public static function getFileUrl($data, $id)
  {
    if (!empty($data)) {
      $url = asset('storage/report/' . $id . '/' . $data->file);
    } else {
      $url = null;
    }
    return $url;
  }
}
