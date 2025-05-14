<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Models\LoginQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    public function check() {
        $user = Auth::guard('web')->user();
        $isFirstPosition = LoginQueue::where('user_id', $user->id)->where('position', 1)->exists();
        return response()->json(['canEnter' => $isFirstPosition]);
    }
}
