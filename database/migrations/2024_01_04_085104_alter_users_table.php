<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('users',function(Blueprint $table){
            $table->integer('status')->default(1)->after('role');
        });
    }

    
    public function down(): void
    {
        Schema::table('users',function(Blueprint $table){
            $table->dropColoumn('status');
        });
    }
};
