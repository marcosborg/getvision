<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyStandCarRequest;
use App\Http\Requests\StoreStandCarRequest;
use App\Http\Requests\UpdateStandCarRequest;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Month;
use App\Models\Origin;
use App\Models\StandCar;
use App\Models\Status;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class StandCarController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('stand_car_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $standCars = StandCar::with(['brand', 'car_model', 'fuel', 'month', 'origin', 'status', 'media'])->get();

        return view('admin.standCars.index', compact('standCars'));
    }

    public function create()
    {
        abort_if(Gate::denies('stand_car_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $brands = Brand::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $car_models = CarModel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fuels = Fuel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $months = Month::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $origins = Origin::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $statuses = Status::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.standCars.create', compact('brands', 'car_models', 'fuels', 'months', 'origins', 'statuses'));
    }

    public function store(StoreStandCarRequest $request)
    {
        $standCar = StandCar::create($request->all());

        foreach ($request->input('images', []) as $file) {
            $standCar->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $standCar->id]);
        }

        return redirect()->route('admin.stand-cars.index');
    }

    public function edit(StandCar $standCar)
    {
        abort_if(Gate::denies('stand_car_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $brands = Brand::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $car_models = CarModel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fuels = Fuel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $months = Month::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $origins = Origin::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $statuses = Status::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $standCar->load('brand', 'car_model', 'fuel', 'month', 'origin', 'status');

        return view('admin.standCars.edit', compact('brands', 'car_models', 'fuels', 'months', 'origins', 'standCar', 'statuses'));
    }

    public function update(UpdateStandCarRequest $request, StandCar $standCar)
    {
        $standCar->update($request->all());

        if (count($standCar->images) > 0) {
            foreach ($standCar->images as $media) {
                if (! in_array($media->file_name, $request->input('images', []))) {
                    $media->delete();
                }
            }
        }
        $media = $standCar->images->pluck('file_name')->toArray();
        foreach ($request->input('images', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $standCar->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
            }
        }

        return redirect()->route('admin.stand-cars.index');
    }

    public function show(StandCar $standCar)
    {
        abort_if(Gate::denies('stand_car_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $standCar->load('brand', 'car_model', 'fuel', 'month', 'origin', 'status');

        return view('admin.standCars.show', compact('standCar'));
    }

    public function destroy(StandCar $standCar)
    {
        abort_if(Gate::denies('stand_car_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $standCar->delete();

        return back();
    }

    public function massDestroy(MassDestroyStandCarRequest $request)
    {
        $standCars = StandCar::find(request('ids'));

        foreach ($standCars as $standCar) {
            $standCar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('stand_car_create') && Gate::denies('stand_car_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new StandCar();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}