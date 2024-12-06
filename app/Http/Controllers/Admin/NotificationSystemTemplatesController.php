<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNotificationSystemTemplateRequest;
use App\Http\Requests\StoreNotificationSystemTemplateRequest;
use App\Http\Requests\UpdateNotificationSystemTemplateRequest;
use App\Models\NotificationSystemTemplate;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NotificationSystemTemplatesController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('notification_system_template_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = NotificationSystemTemplate::query()->select(sprintf('%s.*', (new NotificationSystemTemplate)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'notification_system_template_show';
                $editGate      = 'notification_system_template_edit';
                $deleteGate    = 'notification_system_template_delete';
                $crudRoutePart = 'notification-system-templates';

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
            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.notificationSystemTemplates.index');
    }

    public function create()
    {
        abort_if(Gate::denies('notification_system_template_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.notificationSystemTemplates.create');
    }

    public function store(StoreNotificationSystemTemplateRequest $request)
    {
        $notificationSystemTemplate = NotificationSystemTemplate::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $notificationSystemTemplate->id]);
        }

        return redirect()->route('admin.notification-system-templates.index');
    }

    public function edit(NotificationSystemTemplate $notificationSystemTemplate)
    {
        abort_if(Gate::denies('notification_system_template_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.notificationSystemTemplates.edit', compact('notificationSystemTemplate'));
    }

    public function update(UpdateNotificationSystemTemplateRequest $request, NotificationSystemTemplate $notificationSystemTemplate)
    {
        $notificationSystemTemplate->update($request->all());

        return redirect()->route('admin.notification-system-templates.index');
    }

    public function show(NotificationSystemTemplate $notificationSystemTemplate)
    {
        abort_if(Gate::denies('notification_system_template_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.notificationSystemTemplates.show', compact('notificationSystemTemplate'));
    }

    public function destroy(NotificationSystemTemplate $notificationSystemTemplate)
    {
        abort_if(Gate::denies('notification_system_template_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $notificationSystemTemplate->delete();

        return back();
    }

    public function massDestroy(MassDestroyNotificationSystemTemplateRequest $request)
    {
        $notificationSystemTemplates = NotificationSystemTemplate::find(request('ids'));

        foreach ($notificationSystemTemplates as $notificationSystemTemplate) {
            $notificationSystemTemplate->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('notification_system_template_create') && Gate::denies('notification_system_template_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new NotificationSystemTemplate();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
