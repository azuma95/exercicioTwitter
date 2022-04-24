<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //$following = Auth::user()->following->pluck('id');
        
        //$messages = Message::whereIn('user_id', $following)->orWhere('user_id', Auth::user()->id)->get();  
        
        $messages = Message::get();  

        return view('home', [
            'messages' => $messages
        ]);
    }

    public function changePassword()
    {
        return view('change-password');
    }

    public function updatePassword(Request $request)
    {     
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if(!Hash::check($request->old_password, auth()->user()->password )){
            return back()->with("error", "Old password doesn't match!");
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make(strip_tags($request->new_password))
        ]);

        return back()->with("status", "Password changed sucessfully");
    }

    public function postCreateMessage(Request $request)
    {
        $request->validate([
            'body' => 'required|max:1000'
        ]);

        $message = new Message();
        $message->body = strip_tags($request['body']);

        $err = 'There was an error';

        if($request->user()->messages()->save($message))
        {
            $err = 'Post successfully created.';
        }       

        return redirect()->route('home')->with(['err'=> $err]);        
    }

    public function postEditMessage(Message $message)
    {
        return view('edit-message', [
            'message' => $message
        ]);
    }

    public function postupdateMessage(Request $request, $message_id)
    {
        $request->validate([
            'body' => 'required|max:1000'
        ]); 
        
        $message = Message::findOrFail($message_id);

        $message->body = strip_tags($request['body']);
        $message->save();

        return redirect()->route('edit-message', ['message' => $message])->with(['err' => 'Successfully edited']);
    } 

    public function getDeletePost($message_id)
    {
        $message = Message::where('id', $message_id)->first();

        if(Auth::user() != $message->user)
        {
            return redirect()->back();
        }

        $message->delete();

        return redirect()->route('home')->with(['err' => 'Successfully deleted']);
    }
}
