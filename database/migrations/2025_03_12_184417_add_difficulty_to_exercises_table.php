<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('exercises', function (Blueprint $table) {
        $table->string('difficulty')->after('description'); // Agrega la columna
    });
}

public function down()
{
    Schema::table('exercises', function (Blueprint $table) {
        $table->dropColumn('difficulty'); // Elimina la columna si se revierte la migración
    });
}
};
