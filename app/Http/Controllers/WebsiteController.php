<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\FormName;
use App\Models\FormData;
use Illuminate\Support\Facades\Blade;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('website.home');
    }

    public function cms($page_id, $slug)
    {
        $page = Page::find($page_id);

        if ($page) {
            $page->text = $this->replaceFormPlaceholders($page->text);
        }

        return view('website.cms', compact('page'));
    }

    private function replaceFormPlaceholders($text)
    {
        // Utiliza uma expressão regular para encontrar e substituir todos os placeholders {{ form_X }}
        return preg_replace_callback('/\{\{\s*form_(\d+)\s*\}\}/', function ($matches) {
            $formId = $matches[1];
            // Gera a string do componente Blade
            $componentString = view('components.form-component', ['form_name_id' => $formId])->render();
            return $componentString;
        }, $text);
    }

    public function formData(Request $request)
    {
        $form_name = FormName::find($request->form_name_id);
        $processedData = [];

        foreach ($request->all() as $key => $value) {
            $processedData[$key] = $value;
        }

        $data = [];

        foreach ($processedData as $label => $value) {
            if ($label != '_token' && $label != 'form_name_id' && $label != 'driver_id' && $label != 'vehicle_item_id') {
                $data[$label] = $value;
            }
        }

        $data = json_encode($data);

        $form_data = new FormData;
        $form_data->form_name_id = $request->form_name_id;
        $form_data->driver_id = $request->driver_id ?? null;
        $form_data->vehicle_item_id = $request->vehicle_item_id ?? null;
        $form_data->user_id = $request->user_id ?? null;
        $form_data->data = $data;
        $form_data->save();

        return redirect()->back()->with('message', 'Enviado com sucesso');
    }
}
