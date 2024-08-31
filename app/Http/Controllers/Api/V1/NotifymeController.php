<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\NotifyMe;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redirect;

class NotifymeController extends Controller
{   public function __construct(
     private NotifyMe $notifyMe
     ){}

      
    public function subscribe(Request $request): JsonResponse
    {   $user = auth()->user();
        if (!$user) {
          return redirect()->route('login')->with('message', 'login  Notify to for products.');
        }
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $notifyMe = $this->notifyMe->create([
            'product_id' => $request->input('product_id'),
            'user_id' => $request->input('user_id'),
        ], [
            'notified' => false,
        ]);

        return response()->json(['message' => 'Notify successfully'], 200);
    }
}
