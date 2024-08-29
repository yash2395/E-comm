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
        // Backup the data in the coupon_code column
        $backupData = DB::table('orders')->pluck('coupon_code', 'id');

        Schema::table('orders', function (Blueprint $table) {
            // Truncate the coupon_code column
            $table->string('coupon_code')->nullable()->change();

            // Add a new column
            $table->double('coupon_code_id')->before('coupon_code')->nullable();
        });
        // Re-insert the backed-up data into the coupon_code column
        foreach ($backupData as $id => $couponCode) {
            DB::table('orders')->where('id', $id)->update(['coupon_code' => $couponCode]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->double('coupon_code')->change();
            $table->dropColumn('coupon_code_id');
        });
    }
};
