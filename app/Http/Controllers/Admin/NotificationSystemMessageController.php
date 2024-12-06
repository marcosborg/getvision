<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNotificationSystemMessageRequest;
use App\Http\Requests\StoreNotificationSystemMessageRequest;
use App\Http\Requests\UpdateNotificationSystemMessageRequest;
use App\Models\Company;
use App\Models\Driver;
use App\Models\NotificationSystemMessage;
use App\Models\NotificationSystemTemplate;
use App\Models\Role;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\NotificationSystemMessageNotification;
use Illuminate\Support\Facades\Notification;

class NotificationSystemMessageController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('notification_system_message_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = NotificationSystemMessage::with(['roles', 'drivers', 'companies', 'notification_system_template'])->select(sprintf('%s.*', (new NotificationSystemMessage)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'notification_system_message_show';
                $editGate      = 'notification_system_message_edit';
                $deleteGate    = 'notification_system_message_delete';
                $crudRoutePart = 'notification-system-messages';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('roles', function ($row) {
                $labels = [];
                foreach ($row->roles as $role) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $role->title);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('drivers', function ($row) {
                $labels = [];
                foreach ($row->drivers as $driver) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $driver->name);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('companies', function ($row) {
                $labels = [];
                foreach ($row->companies as $company) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $company->name);
                }

                return implode(' ', $labels);
            });
            $table->addColumn('notification_system_template_subject', function ($row) {
                return $row->notification_system_template ? $row->notification_system_template->subject : '';
            });

            $table->editColumn('custom_subject', function ($row) {
                return $row->custom_subject ? $row->custom_subject : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'roles', 'drivers', 'companies', 'notification_system_template']);

            return $table->make(true);
        }

        return view('admin.notificationSystemMessages.index');
    }

    public function create()
    {
        abort_if(Gate::denies('notification_system_message_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $drivers = Driver::pluck('name', 'id');

        $companies = Company::pluck('name', 'id');

        $notification_system_templates = NotificationSystemTemplate::pluck('subject', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.notificationSystemMessages.create', compact('companies', 'drivers', 'notification_system_templates', 'roles'));
    }

    public function store(StoreNotificationSystemMessageRequest $request)
    {
        $notificationSystemMessage = NotificationSystemMessage::create($request->all());
        $notificationSystemMessage->roles()->sync($request->input('roles', []));
        $notificationSystemMessage->drivers()->sync($request->input('drivers', []));
        $notificationSystemMessage->companies()->sync($request->input('companies', []));
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $notificationSystemMessage->id]);
        }

        return redirect()->route('admin.notification-system-messages.index');
    }

    public function edit(NotificationSystemMessage $notificationSystemMessage)
    {
        abort_if(Gate::denies('notification_system_message_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $drivers = Driver::pluck('name', 'id');

        $companies = Company::pluck('name', 'id');

        $notification_system_templates = NotificationSystemTemplate::pluck('subject', 'id')->prepend(trans('global.pleaseSelect'), '');

        $notificationSystemMessage->load('roles', 'drivers', 'companies', 'notification_system_template');

        return view('admin.notificationSystemMessages.edit', compact('companies', 'drivers', 'notificationSystemMessage', 'notification_system_templates', 'roles'));
    }

    public function update(UpdateNotificationSystemMessageRequest $request, NotificationSystemMessage $notificationSystemMessage)
    {
        $notificationSystemMessage->update($request->all());
        $notificationSystemMessage->roles()->sync($request->input('roles', []));
        $notificationSystemMessage->drivers()->sync($request->input('drivers', []));
        $notificationSystemMessage->companies()->sync($request->input('companies', []));

        return redirect()->route('admin.notification-system-messages.index');
    }

    public function show(NotificationSystemMessage $notificationSystemMessage)
    {
        abort_if(Gate::denies('notification_system_message_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $notificationSystemMessage->load('roles', 'drivers', 'companies', 'notification_system_template');

        return view('admin.notificationSystemMessages.show', compact('notificationSystemMessage'));
    }

    public function destroy(NotificationSystemMessage $notificationSystemMessage)
    {
        abort_if(Gate::denies('notification_system_message_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $notificationSystemMessage->delete();

        return back();
    }

    public function massDestroy(MassDestroyNotificationSystemMessageRequest $request)
    {
        $notificationSystemMessages = NotificationSystemMessage::find(request('ids'));

        foreach ($notificationSystemMessages as $notificationSystemMessage) {
            $notificationSystemMessage->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('notification_system_message_create') && Gate::denies('notification_system_message_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new NotificationSystemMessage();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function notificationSystemTemplate($notification_system_template_id)
    {
        return NotificationSystemTemplate::find($notification_system_template_id);
    }

    public function notificationSystemSendEmail($notification_system_message_id)
    {

        $notification_system_message = NotificationSystemMessage::find($notification_system_message_id)
            ->load('roles.users', 'drivers.user', 'companies.drivers.user');

        $users = collect();

        if ($notification_system_message->drivers) {
            $drivers = $notification_system_message->drivers->map(function ($driver) {
                return $driver->user;
            });
            $users = $users->merge($drivers);
        }

        if ($notification_system_message->roles) {
            foreach ($notification_system_message->roles as $role) {
                $users = $users->merge($role->users);
            }
        }

        if ($notification_system_message->companies) {
            foreach ($notification_system_message->companies as $company) {
                $companyDrivers = $company->drivers->map(function ($driver) {
                    return $driver->user;
                });
                $users = $users->merge($companyDrivers);
            }
        }

        $users = $users->unique('id');

        Notification::send($users, new NotificationSystemMessageNotification($notification_system_message));
    }
}
