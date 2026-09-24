<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mail_driver', 50)->default('smtp');
            $table->string('mail_host', 255)->nullable();
            $table->integer('mail_port')->nullable();
            $table->string('mail_username', 255)->nullable();
            $table->text('mail_password')->nullable();
            $table->enum('mail_encryption', ['tls', 'ssl'])->nullable();
            $table->string('mail_from_address', 255)->nullable();
            $table->string('mail_from_name', 255)->nullable();
            $table->string('mailgun_domain', 255)->nullable();
            $table->text('mailgun_secret')->nullable();
            $table->text('sendgrid_api_key')->nullable();
            $table->text('ses_key')->nullable();
            $table->text('ses_secret')->nullable();
            $table->string('ses_region', 50)->nullable();
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};

