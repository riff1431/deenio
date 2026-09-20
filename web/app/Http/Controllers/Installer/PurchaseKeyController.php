<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\PurchaseCodeValidatorService;
use Illuminate\Http\Request;

class PurchaseKeyController extends Controller
{
    public function __construct(PurchaseCodeValidatorService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('installer.purchase_key');
    }

    public function check(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|min:3|regex:/^[^#]+$/U',
        ]);

        $userInput = $request->all();
        array_walk_recursive($userInput, function (&$userInput) {
            $userInput = strip_tags($userInput);
        });
        $request->merge($userInput);

        return [
            'status' => true,
            'message' => 'success',
            'url' => url('/verified.json'),
        ];
    }
}