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
        // this is just a simple ticket analysis table with basic fields, we can always add more fields later as needed
        // this is just intended to be a simple example for the purpose of this project, so we will keep it simple and not overcomplicate it with too many fields or relationships  

        Schema::create('ticket_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            
            $table->text('summary');
            $table->json('suggested_next_actions');
            
            $table->string('source');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_analyses', function (Blueprint $table) {
            $table->dropForeign(['ticket_id']);
        });

        Schema::dropIfExists('ticket_analyses');
    }
};
