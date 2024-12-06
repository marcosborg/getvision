<?php

namespace App\Http\Requests;

use App\Models\NotificationSystemMessage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateNotificationSystemMessageRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('notification_system_message_edit');
    }

    public function rules()
    {
        return [
            'roles.*' => [
                'integer',
            ],
            'roles' => [
                'array',
            ],
            'drivers.*' => [
                'integer',
            ],
            'drivers' => [
                'array',
            ],
            'companies.*' => [
                'integer',
            ],
            'companies' => [
                'array',
            ],
            'custom_subject' => [
                'string',
                'required',
            ],
            'message' => [
                'required',
            ],
        ];
    }
}
