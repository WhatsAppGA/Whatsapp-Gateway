<?php

namespace App\Http\Controllers;


use App\Models\Device;
use App\Models\Tag;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TagController extends Controller
{

    protected $wa;
    public function __construct(WhatsappService $whatsappService )
    {
        
        $this->wa = $whatsappService; 
    }
  
    public function index(){
        return view('theme::pages.phonebook.index');
    }

    public function getPhonebook(Request $request){
        if($request->ajax()){
            $phonebooks = $request->user()->phonebooks()->when($request->search, function($query) use ($request){
                $query->where('name','like','%'.$request->search.'%');
            })->latest()->paginate(15);

            $html = view('theme::pages.phonebook.dataphonebook',compact('phonebooks'))->render();
            $last_page = $phonebooks->lastPage();
            $current_page = $phonebooks->currentPage();

            return response()->json([ 'html' => $html,'last_page' => $last_page,'current_page' => $current_page]);
            
        }
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required','min:3','unique:tags']
        ]);

        Tag::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name
        ]);

        return back()->with('alert',[
            'type' => 'success',
            'msg' => __('Success add tag!')
        ]);
    }


    public function destroy(Request $request){
        try {
            //code...
            $t = Tag::with('contacts')->find($request->id);
            $t->delete();
            return back()->with('alert',[
                'type' => 'success',
                'msg' => __('Success delete tag!')
            ]);
        } catch (\Throwable $th) {
          return back()->with('alert',['type' => 'danger','msg' => __('Something went wrong! (dt)')]);
        }
    }

    public function fetchGroups(Request $request){
       try {
        if(!$request->device){
            return back()->with('alert',[ 'type' => 'danger','msg' => __('Please select device first!')]);
        }
        $device = Device::find($request->device);
        if($device->status != 'Connected'){
                return back()->with('alert', ['type' => 'danger', 'msg' => __('Your sender is not connected!') ]);
        }
        // add cache for 60 minutes
        $respon = Cache::remember('groups'.$device->body, 60, function () use ($device) {
           return $this->wa->fetchGroups($device);
        });

       if($respon->status === false){
        return back()->with('alert',['type' => 'danger','msg' => $respon->message ]);
       }

       if (count($respon->data) < 1) {
            Cache::forget('groups'.$device->body);
            return back()->with('alert',['type' => 'danger','msg' => __('No groups found!,try in a few minutes')]);
         }
                
                foreach ($respon->data as $group) {
                    // insert to tags
                    $namePhoneBook = $group->subject . " ( ID : " . $group->id . " )";
                    $tag = $request->user()->phonebooks()->firstOrCreate(['name' => $namePhoneBook]);
                    
                    foreach ($group->participants as $member) {
					    $number = str_replace('@s.whatsapp.net','',$member->id);
                        $cek = $request->user()->contacts()->where('tag_id',$tag->id)->where('number',$number)->count();
                        if($cek < 1){
						    $tag->contacts()->create(['user_id' =>$request->user()->id,'name' => $number,'number' => $number]);
                        }
                    }
                }
                return back()->with('alert',[
                    'type' => 'success',
                    'msg' => 'Generate success'
                ]);
         
       } catch (\Throwable $th) {
        throw $th;
             return back()->with('alert',['type' => 'danger','msg' => __('Something went wrong! (fg)')]);
       }
    }

    public function clearPhonebook(Request $request){
        try {
            $request->user()->phonebooks()->delete();
            return response()->json(['error' => false,'msg' => __('Success clear phonebook!')]);
        } catch (\Throwable $th) {
            return response()->json(['error' => true,'msg' => __('Something went wrong! (cp)')]);
        }
    }

}
