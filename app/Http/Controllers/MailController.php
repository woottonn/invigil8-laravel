<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\DefaultEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function exam_add_view(Request $request)
    {
        return view('mails.default');
    }
}
