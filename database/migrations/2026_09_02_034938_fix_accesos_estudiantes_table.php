<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('accesos_estudiantes', function (Blueprint $table) {

            if (! Schema::hasColumn('accesos_estudiantes', 'email_hash')) {
                $table->string('email_hash', 64)->after('id');
            }

            if (! Schema::hasColumn('accesos_estudiantes', 'accedido_en')) {
                $table->timestamp('accedido_en')->useCurrent()->after('email_hash');
            }

        });
    }

    public function down()
    {
        Schema::table('accesos_estudiantes', function (Blueprint $table) {
            $table->dropColumn(['email_hash', 'accedido_en']);
        });
    }
};