<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationManagerSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('padosoft-settings.enabled',false)) {
            DB::table('settings')->insert([
                //ROLES
                ['key'=>'notification_manager.audit.send', 'value'=>'0','descr'=>'En/Disable audits on notification send','config_override'=>'padosoft-notification.audit.send','load_on_startup'=>0],
                ['key'=>'notification_manager.audit.failed', 'value'=>'0','descr'=>'En/Disable audits on notification send','config_override'=>'padosoft-notification.audit.failed','load_on_startup'=>0],
                ['key'=>'notification_manager.sms_sender', 'value'=>substr(config('app.name'), 0, 11),'descr'=>'notification sender for sms','config_override'=>'padosoft-notification.sms_sender','load_on_startup'=>0],
                ['key'=>'notification_manager.slack.sender', 'value'=>config('app.name'),'descr'=>'notification sender for slack','config_override'=>'padosoft-notification.slack.sender','load_on_startup'=>0],
                ['key'=>'notification_manager.slack.sender_icon', 'value'=>'','descr'=>'notification sender icon for slack','config_override'=>'padosoft-notification.slack.sender_icon','load_on_startup'=>0],
                

            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
