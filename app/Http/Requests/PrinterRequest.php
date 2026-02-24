<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrinterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'connection_type' => ['required', 'in:network,file,windows'],
            'ip' => ['required_if:connection_type,network', 'nullable', 'ip'],
            'port' => ['required_if:connection_type,network', 'nullable', 'integer', 'min:1', 'max:65535'],
            'path' => ['required_if:connection_type,file,windows', 'nullable', 'string', 'max:255'],
            'timeout' => ['nullable', 'integer', 'min:1', 'max:120'],
            'header' => ['nullable', 'string', 'max:255'],
            'footer' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048', 'dimensions:max_width=300,max_height=200'],
            'show_qr_code' => ['boolean'],
            'width' => ['nullable', 'integer', 'min:24', 'max:48'],
            'is_default' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Printer name is required.',
            'connection_type.required' => 'Connection type is required.',
            'ip.required_if' => 'IP address is required for network connection.',
            'path.required_if' => 'Path is required for file/windows connection.',
            'logo.image' => 'Logo must be an image file.',
            'logo.max' => 'Logo file size must not exceed 2MB.',
            'logo.dimensions' => 'Logo dimensions should not exceed 300x200 pixels.',
        ];
    }
}
