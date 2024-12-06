<?php

namespace App\Http\Requests;

use App\Models\NotificationSystemMessage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyNotificationSystemMessageRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('notification_system_message_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:notification_system_messages,id',
        ];
    }
}
