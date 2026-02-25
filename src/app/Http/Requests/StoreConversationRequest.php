<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'visitor_id' => ['required', 'exists:visitors,id'],
            'session_id' => ['required', 'exists:visitor_sessions,id'],
            'subject' => ['nullable', 'string', 'max:255'],
        ];
    }
}
