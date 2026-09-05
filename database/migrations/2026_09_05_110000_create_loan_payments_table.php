<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('loan_id');
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        // Backfill: loans already flagged as fully paid before this table existed
        // get a matching payment record so their paid amount/balance stay accurate.
        $now = now();
        DB::table('loans')
            ->where('is_paid', 1)
            ->get(['id', 'amount'])
            ->each(function ($loan) use ($now) {
                DB::table('loan_payments')->insert([
                    'loan_id' => $loan->id,
                    'amount' => $loan->amount,
                    'note' => 'Backfilled from prior full-payment record.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};
