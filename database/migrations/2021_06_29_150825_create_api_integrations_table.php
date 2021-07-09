<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_integrations', function (Blueprint $table) {
            $table->id();
            $table->string('api_vendor', 50)->index();
            $table->string('api_vendor_key', 100)->index();
            $table->morphs('syncable');
            $table->string('syncable_external_id', 100)->nullable()->index();
            $table->dateTime('syncable_synced_at')->nullable();
            $table->timestamps();

            $table->unique([
                'api_vendor',
                'api_vendor_key',
                'syncable_type',
                'syncable_id',
                'syncable_external_id',
            ], 'four_factors_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_integrations');
    }
}
