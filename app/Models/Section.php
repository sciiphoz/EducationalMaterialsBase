<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Laravel\Facades\Image;

class Section extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'content',
        'language',
        'image_base64',
        'image_mime_type',
        'image_name',
        'image_alt',
        'order',
        'material_id'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Оптимизированное сжатие для скриншотов
    public function setCompressedImage($file, $quality = 70)
    {
        try {
            $image = Image::read($file);
            
            // Оптимизация для скриншотов - максимальная ширина 1200px
            if ($image->width() > 1200) {
                $image->scale(width: 1200);
            }

            // Конвертируем в JPEG для лучшего сжатия скриншотов
            $encodedImage = $image->toJpeg($quality);
            
            $this->image_base64 = base64_encode($encodedImage);
            $this->image_mime_type = 'image/jpeg';
            $this->image_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg';
            
        } catch (\Exception $e) {
            // Если произошла ошибка, используем обычное сохранение
            $this->setImageFromFile($file);
        }
    }

    // Простой метод без сжатия
    public function setImageFromFile($file)
    {
        $imageData = file_get_contents($file);
        $this->image_base64 = base64_encode($imageData);
        $this->image_mime_type = $file->getMimeType();
        $this->image_name = $file->getClientOriginalName();
    }

    // Умный выбор метода
    public function setImageSmart($file)
    {
        $fileSize = $file->getSize();
        
        // Для файлов больше 500KB используем сжатие
        if ($fileSize > 500 * 1024) {
            $this->setCompressedImage($file);
        } else {
            $this->setImageFromFile($file);
        }
    }

    // Получение URL для изображения
    public function getImageUrlAttribute()
    {
        if ($this->isImage() && $this->image_base64) {
            return "data:{$this->image_mime_type};base64,{$this->image_base64}";
        }
        return null;
    }

    // Получение размера изображения в KB
    public function getImageSizeAttribute()
    {
        if ($this->isImage() && $this->image_base64) {
            return round((strlen($this->image_base64) * 3 / 4) / 1024, 2);
        }
        return 0;
    }

    // Проверка типа секции
    public function isText()
    {
        return $this->type === 'text';
    }

    public function isCode()
    {
        return $this->type === 'code';
    }

    public function isImage()
    {
        return $this->type === 'image';
    }

    // Получение содержимого в зависимости от типа
    public function getDisplayContentAttribute()
    {
        if ($this->isText()) {
            return nl2br(e($this->content));
        } elseif ($this->isCode()) {
            return $this->content;
        }
        return null;
    }

}