<?php

namespace App\Http\Requests;

use App\Models\NotificationSystemTemplate;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreNotificationSystemTemplateRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('notification_system_template_create');
    }

    public function rules()
    {
        return [
            'subject' => [
                'string',
                'required',
            ],
            'message' => [
                'required',
            ],
        ];
    }
}
