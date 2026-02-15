<?php

namespace App\Domains\Planning\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BOQRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'version' => 'required|string',
            'items' => 'required|array',
            'items.*.code' => 'required|string',
            'items.*.description' => 'required|string',
            'items.*.unit' => 'required|string',
            'items.*.quantity' => 'required|numeric',
            'items.*.unit_rate' => 'required|numeric',
            'items.*.material_cost' => 'required|numeric',
            'items.*.labor_cost' => 'required|numeric',
            'items.*.total' => 'required|numeric',
        ];
    }
}
