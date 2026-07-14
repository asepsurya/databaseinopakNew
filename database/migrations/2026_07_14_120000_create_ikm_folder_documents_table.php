<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ikm_folder_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained('ikm_folders')->cascadeOnDelete();
            $table->foreignId('id_Project')->constrained('projects')->cascadeOnDelete();
            $table->string('nama_file');
            $table->string('url');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ikm_folder_documents');
    }
};
