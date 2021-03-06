<?php

namespace Sunarc\LaravelChat\Http\Controller;

use App\Events\MessageSend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sunarc\LaravelChat\Models\Message;
use Sunarc\LaravelChat\Models\User;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public $userID;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userID = auth()->id();
            return $next($request);
        });
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user_list(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $users = User::latest()->where('id', '!=', $this->userID)->get();

        return response()->json($users, 200);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function user_message(Request $request, $id = null)
    {
        abort_if(!$request->ajax(), 404);

        return response()->json([
            'messages' => $this->message_by_user_id($id),
            'user' => User::findOrFail($id),
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send_message(Request $request){
        if(!$request->ajax()){
            return abort(404);
        }
        if($request->file('file')){
            $request->validate([
                'file' => [
                function ($attribute, $value, $fail) {
                    
                    $file_type = explode('/',request('file')->getMimeType())[0];
                    if($file_type == 'image') 
                    {
                        $validator = Validator::make(
                            ['image' => $value],
                            ['image' => config('chat.image_validation')]
                        );
                    }
                    elseif($file_type == 'video') 
                    {
                        $validator = Validator::make(
                            ['video' => $value],
                            ['video' => config('chat.video_validation')]
                        );
                    }
                    else
                    {
                        $validator = Validator::make(
                            ['file' => $value],
                            ['file' => config('chat.file_validation')]
                        );
                    }
                    if ($validator->fails()) {
                        $fail($validator->messages()->first());
                    }
                }
                ]
            ]);
            $file = request('file')->hashName();
            request('file')->store('public/files');
            $file_type = explode('/',request('file')->getMimeType())[0];
        }
        $messages = Message::create([
            'message'=>$request->message,
            'file_type'=>$file_type ?? null,
            'file'=>$file ?? null,
            'from'=>auth()->user()->id,
            'to'=>$request->user_id,
            'type'=>0
        ]);
        $messages = Message::create([
            'message'=>$request->message,
            'file_type'=>$file_type ?? null,
            'file'=>$file ?? null,
            'from'=>auth()->user()->id,
            'to'=>$request->user_id,
            'type'=>1
        ]);
        broadcast(new MessageSend($messages));
        return response()->json($messages,201);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_single_message(Request $request, $id = null)
    {
        abort_if(!$request->ajax(), 404);

        Message::findOrFail($id)->delete();
        return response()->json('deleted', 200);
    }

    /**
     * Undocumented function
     *
     * @param mixed $id
     * @return void
     */
    public function delete_all_message($id = null)
    {
        $messages = $this->message_by_user_id($id);

        foreach ($messages as $value) {
            Message::findOrFail($value->id)->delete();
        }

        return response()->json('all deleted', 200);
    }

    /**
     * Undocumented function
     *
     * @param mixed $id
     * @return Message $message
     */
    public function message_by_user_id($id)
    {
        $messages = Message::where(function($q) use ($id) {
            $q->where('from',$this->userID);
            $q->where('to',$id);
            $q->where('type',0);
        })->orWhere(function($q) use ($id) {
            $q->where('to',$this->userID);
            $q->where('from',$id);
            $q->where('type',1);
        })->with('user')->get();
        return $messages;
    }
}
