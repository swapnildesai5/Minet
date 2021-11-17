<?php

namespace App\Http\Controllers\Admin;

use App\EmailNotificationSetting;
use App\Helper\Files;
use App\Helper\Reply;
use App\Notifications\TestSlack;
use App\SlackSetting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SlackSettingController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.slackSettings';
        $this->pageIcon = 'fa fa-slack';
    }

    public function index()
    {
        $this->emailSetting = email_notification_setting();

        $this->userRegistrationNotification = $this->emailSetting->first(function ($value, $key) {
            return $value->slug == 'user-registrationadded-by-admin';
        });
        $this->newExpenseByAdmin = $this->emailSetting->first(function ($value, $key) {
            return $value->slug == 'new-expenseadded-by-admin';
        });
        $this->newExpenseByMember = $this->emailSetting->first(function ($value, $key) {
            return $value->slug == 'new-expenseadded-by-member';
        });
        $this->expenseStatusChange = $this->emailSetting->first(function ($value, $key) {
            return $value->slug == 'expense-status-changed';
        });
        $this->newSupportTicket = $this->emailSetting->first(function ($value, $key) {
            return $value->slug == 'new-support-ticket-request';
        });
        $this->newLeaveApplication = $this->emailSetting->first(function ($value, $key) {
            return $value->slug == 'new-leave-application';
        });
        $this->taskCompleted = $this->emailSetting->first(function ($value, $key) {
            return $value->slug == 'task-completed';
        });
        $this->invoiceNotification = $this->emailSetting->first(function ($value, $key) {
            return $value->slug == 'invoice-createupdate-notification';
        });
        $this->newProjectMember = $this->emailSetting->first(function ($value, $key) {
            return $value->slug == 'employee-assign-to-project';
        });
        $this->newNoticePublished = $this->emailSetting->first(function ($value, $key) {
            return $value->slug == 'new-notice-published';
        });
        $this->newTaskAssigned = $this->emailSetting->first(function ($value, $key) {
            return $value->slug == 'user-assign-to-task';
        });
        
        $this->slackSettings = SlackSetting::setting();
        return view('admin.slack-settings.index', $this->data);
    }

    public function update(Request $request, $id)
    {
        $setting = SlackSetting::findOrFail($id);
        $setting->slack_webhook = $request->slack_webhook;

        if (isset($request->removeImage) && $request->removeImage == 'on') {
            if ($setting->slack_logo) {

                Files::deleteFile($setting->notification_logo, 'slack-logo');
            }

            $setting->slack_logo = null; // Remove image from database
        } elseif ($request->hasFile('slack_logo')) {

            Files::deleteFile($setting->slack_logo, 'slack-logo');
            $setting->slack_logo = Files::upload($request->slack_logo, 'slack-logo');
        }

        $setting->save();
        cache()->forget('slack-setting');

        return Reply::redirect(route('admin.slack-settings.index'), __('messages.settingsUpdated'));
    }

    public function updateSlackNotification(Request $request)
    {
        $setting = EmailNotificationSetting::findOrFail($request->id);
        $setting->send_slack = $request->send_slack;
        $setting->save();
        session(['email_notification_setting' => EmailNotificationSetting::all()]);
        return Reply::success(__('messages.settingsUpdated'));
    }

    public function sendTestNotification()
    {
        $user = User::find($this->user->id);
        // Notify User
        $user->notify(new TestSlack());

        return Reply::success('Test notification sent.');
    }
}
