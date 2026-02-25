<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorProfileController extends Controller
{
    public function setPhone(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required','string','min:7','max:30'],
        ]);

        $visitorId = (int) $request->attributes->get('visitor_id');

        $visitor = Visitor::findOrFail($visitorId);
        $visitor->update(['phone' => $data['phone']]);

        return response()->json([
            'ok' => true,
            'visitor_id' => $visitor->id,
            'phone' => $visitor->phone,
        ]);
    }
}