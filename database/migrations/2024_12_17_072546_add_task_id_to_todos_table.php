<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaskIdToTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('todos', function (Blueprint $table) {
            // Menambahkan kolom task_id setelah kolom status
            $table->unsignedBigInteger('task_id')->nullable()->after('status'); // Set nullable if needed

            // Menambahkan foreign key constraint
            $table->foreign('task_id')
                  ->references('id')  // Kolom yang menjadi referensi pada tabel lain
                  ->on('todos')       // Tabel yang menjadi referensi
                  ->onDelete('cascade');  // Menghapus data terkait ketika data pada referensi dihapus
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('todos', function (Blueprint $table) {
            // Menghapus kolom task_id dan foreign key constraint
            $table->dropForeign(['task_id']);
            $table->dropColumn('task_id');
        });
    }
}
