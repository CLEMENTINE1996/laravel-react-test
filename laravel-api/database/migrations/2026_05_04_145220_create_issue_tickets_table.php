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
        // this is just a simple ticket table with basic fields, we can always add more fields later as needed
        // this is just intended to be a simple example for the purpose of this project, so we will keep it simple and not overcomplicate it with too many fields or relationships  

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');

            $table->enum('category', ['bug', 'feature_request', 'documentation', 'complain', 'suggestion'])->default('bug');
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');

            $table->boolean('is_escalated')->default(false);

            $table->foreignId('requestor_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['requestor_id']);
        });
        Schema::dropIfExists('tickets');
    }
};
