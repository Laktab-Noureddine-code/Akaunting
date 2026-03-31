<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $connection = Schema::getConnection();
            $d_table = $connection->getDoctrineSchemaManager()->listTableDetails($connection->getTablePrefix() . 'settings');
            if ($d_table->hasIndex('settings_company_id_key_unique')) {
                $table->dropUnique('settings_company_id_key_unique');
            } else {
                $table->dropUnique(['company_id', 'key']);
            }
            $table->unique(['company_id', 'key', 'deleted_at']);
        });
        Schema::table('user_invitations', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('token');
            $table->string('created_from', 100)->nullable()->after('token');
        });
    }
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $connection = Schema::getConnection();
            $d_table = $connection->getDoctrineSchemaManager()->listTableDetails($connection->getTablePrefix() . 'settings');
            if ($d_table->hasIndex('settings_company_id_key_deleted_at_unique')) {
                $table->dropUnique('settings_company_id_key_deleted_at_unique');
            } else {
                $table->dropUnique(['company_id', 'key', 'deleted_at']);
            }
            $table->unique(['company_id', 'key']);
        });
        Schema::table('user_invitations', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('created_from');
        });
    }
};
