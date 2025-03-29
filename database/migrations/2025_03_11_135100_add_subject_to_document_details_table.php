<?php
// filepath: c:\Users\Hp\Desktop\document-tracking-system-main\PPMU\database\migrations\2025_03_11_133936_add_subject_to_document_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusNameToDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('document_details', function (Blueprint $table) {
            $table->string('status_name')->default('pending')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('document_details', function (Blueprint $table) {
            $table->dropColumn('status_name');
        });
    }
}