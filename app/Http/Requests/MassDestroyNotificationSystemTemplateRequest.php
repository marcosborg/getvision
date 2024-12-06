<?php

namespace App\Http\Requests;

use App\Models\NotificationSystemTemplate;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyNotificationSystemTemplateRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('notification_system_template_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:notification_system_templates,id',
        ];
    }
}
