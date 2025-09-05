<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendPushRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // add auth later if needed
    }

    public function rules(): array
    {
        return [
            'token'         => ['required_without:tokens','string'],
            'tokens'        => ['required_without:token','array'],
            'tokens.*'      => ['string'],

            'title'         => ['required','string','max:150'],
            'body'          => ['required','string','max:500'],

            'data'          => ['nullable','array'],      // extra key/values
            'email'          => ['nullable','array'],      // extra key/values
            'click_action'  => ['nullable','url'],        // open on click
            'image'         => ['nullable','url'],        // notification image (optional)
        ];
    }

    public function payload(): array
    {
        return [
            'tokens'       => $this->filled('token') ? [$this->string('token')] : $this->input('tokens', []),
            'title'        => $this->string('title'),
            'body'         => $this->string('body'),
            'data'         => $this->input('data', []),
            'click_action' => $this->input('click_action'),
            'image'        => $this->input('image'),
            'email'         => $this->input('email', []),
        ];
    }
}
