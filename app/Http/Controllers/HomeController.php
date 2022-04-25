<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $messages = Message::get()->sortBy('sort_order');; 

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
        $message->sort_order = strip_tags(0);

        $err = 'There was an error';

        if($request->user()->messages()->save($message))
        {
            $err = 'Post successfully created.';
            //colocar o valor do id no sort_order
            $message->sort_order = strip_tags($message->id);
            $message->save();
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

    public function moveMessageUp($message_id)
    {
        // guardo o valor do sort_order da mensagem atual, procuro a mensagem com anterior sort_order e verifico se existe mensagem anterior
        // depois troco os valores dos sort_orders     

        $message = Message::findOrFail($message_id);
        $currentMsgPos = $message->sort_order;

        $previous = Message::where('sort_order', '<', $message->sort_order)->max('sort_order');        

        if(!isset($previous))
        {
            $err = 'There is no post before';
        }
        else
        {            
            $prevMsg = DB::table('messages')->where('sort_order', $previous)->first();
            $prevMessage = Message::findOrFail($prevMsg->id);
            
            $prevMessage->sort_order = $currentMsgPos;
            $prevMessage->save(); 

            $message->sort_order = $previous;
            $message->save();             

            $err = 'Success changing order';
        }        

        return redirect()->route('home')->with(['err'=> $err]); 
    }

    public function moveMessageDown($message_id)
    {

        $message = Message::findOrFail($message_id);
        $currentMsgPos = $message->sort_order;

        $next = Message::where('sort_order', '>', $message->sort_order)->min('sort_order');        

        if(!isset($next))
        {
            $err = 'There is no post after';
        }
        else
        {
            $nextMsg = DB::table('messages')->where('sort_order', $next)->first();
            $nextMessage = Message::findOrFail($nextMsg->id);   

            $nextMessage->sort_order = $currentMsgPos;
            $nextMessage->save(); 

            $message->sort_order = $next;
            $message->save();

            $err = 'Success changing order';
        }        

        return redirect()->route('home')->with(['err'=> $err]); 
    }
}
