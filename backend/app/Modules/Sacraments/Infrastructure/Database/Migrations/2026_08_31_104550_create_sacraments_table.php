<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sacraments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('baptism_status')->default('not_baptized');
            $table->date('baptism_date')->nullable();
            $table->string('baptism_place')->nullable();

            $table->string('confirmation_status')->default('not_confirmed');
            $table->date('confirmation_date')->nullable();
            $table->string('confirmation_place')->nullable();

            $table->string('marriage_status')->default('single');
            $table->date('marriage_date')->nullable();
            $table->string('marriage_place')->nullable();
            $table->timestamps();

            $table->unique('member_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sacraments');
    }
};
