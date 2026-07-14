<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IkmFolder extends Model
{
    use HasFactory;

    protected $table = 'ikm_folders';

    protected $fillable = [
        'name',
        'id_Project',
        'parent_id',
    ];

    /**
     * Get the project this folder belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'id_Project');
    }

    /**
     * Get the parent folder.
     */
    public function parent()
    {
        return $this->belongsTo(IkmFolder::class, 'parent_id');
    }

    /**
     * Get the child folders.
     */
    public function children()
    {
        return $this->hasMany(IkmFolder::class, 'parent_id');
    }

    /**
     * Get the IKMs in this folder.
     */
    public function ikms()
    {
        return $this->hasMany(Ikm::class, 'folder_id');
    }

    /**
     * Get the documents in this folder.
     */
    public function documents()
    {
        return $this->hasMany(IkmFolderDocument::class, 'folder_id');
    }
}
