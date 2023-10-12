<?php
/**
 * MsProAdmin is forked from Min-eAdmin, with the aim of building the system more customizable and faster
 */

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateSystemAppApiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_app_api', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('应用和api关联表');
            $table->addColumn('bigInteger', 'app_id', ['unsigned' => true, 'comment' => '应用ID']);
            $table->addColumn('bigInteger', 'api_id', ['unsigned' => true, 'comment' => 'API—ID']);
            $table->primary(['app_id', 'api_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_app_api');
    }
}
