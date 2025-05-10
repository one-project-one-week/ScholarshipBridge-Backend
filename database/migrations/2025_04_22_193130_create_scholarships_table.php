<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->text('organization');
            $table->text('eligibility');
            $table->enum('category', ['merit-based', 'need-based', 'field-specific', 'athletic', 'identity-based', 'goverment-funded', 'others'])->default('merit-based');
            $table->enum('program', ['Exchange', 'Diploma', 'Bachalor', 'Master',  'Phd', 'all'])->default('all');
            $table->string('field');
            $table->string('country');
            $table->date('deadline');
            $table->string('apply_link');
            $table->string('photo_link');


            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
