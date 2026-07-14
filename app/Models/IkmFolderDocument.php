<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IkmFolderDocument extends Model
{
    use HasFactory;

    protected $table = 'ikm_folder_documents';

    protected $fillable = [
        'folder_id',
        'id_Project',
        'nama_file',
        'url',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    public function folder()
    {
        return $this->belongsTo(IkmFolder::class, 'folder_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
