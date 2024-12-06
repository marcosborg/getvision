<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Origin;
use Illuminate\Http\Request;
use App\Models\StandCar;

class StandController extends Controller
{
    public function index()
    {
        $stand_cars = StandCar::all()->load([
            'brand',
            'car_model',
            'fuel',
            'month',
            'origin',
            'status'
        ]);

        $brands = Brand::all();
        $models = CarModel::all();
        $fuels = Fuel::all();
        $origins = Origin::all();

        return view('website.stand.index', compact('stand_cars', 'brands', 'models', 'fuels', 'origins'));
    }

    public function car($stand_car_id)
    {

        $car = StandCar::find($stand_car_id)->load(
            'brand',
            'car_model',
            'fuel',
            'month',
            'origin',
            'status'
        );

        return view('website.stand.car', compact('car'));
    }
}
