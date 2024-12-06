<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransferTour;
use App\Http\Requests\StoreTransferFormRequest;
use App\Models\TransferForm;
use Illuminate\Support\Facades\Notification;
use App\Notifications\transferTourContact;

class TransferToursController extends Controller
{
    public function index()
    {
        $transfer_tours = TransferTour::all();

        return view('website.transfer_tours.index', compact('transfer_tours'));
    }

    public function tour($transfer_tour_id)
    {
        $transfer_tour = TransferTour::find($transfer_tour_id);

        return view('website.transfer_tours.transfers_tour', compact('transfer_tour'));

    }

    public function sendRequest(StoreTransferFormRequest $request)
    {
        $transferForm = TransferForm::create($request->all());

        Notification::route('mail', 'info@expertcom.pt')
            ->notify(new transferTourContact($transferForm));

        return redirect()->back()->with('message', 'Envaido com sucesso');
    }
}
