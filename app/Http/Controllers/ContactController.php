<?php

namespace App\Http\Controllers;

use App\Exports\ContactsExport;
use App\Imports\ContactImport;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{



    public function getContactByTagId($id, Request $request)
    {
        $contacts = Contact::whereTagId($id)->when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('number', 'like', '%' . $request->search . '%');
        })->whereUserId($request->user()->id)->latest()->paginate(15);
        $start_page = $contacts->currentPage();
        $last_page = $contacts->lastPage();
        $html = view('theme::pages.phonebook.datacontact', compact('contacts'))->render();
        return response()->json(['html' => $html, 'last_page' => $last_page, 'start_page' => $start_page]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate(['number' => ['required', 'min:8', 'max:13']]);
            $chck = $request->user()->contacts()->where('number', $request->number)->whereTagId($request->tag_id)->first();
            if ($chck) return response()->json(['error' => true, 'msg' => __('Number already exists in this phonebook!')]);

            $request->user()->contacts()->create($request->all());
            return response()->json(['error' => false, 'msg' => __('Success add contact!')]);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'msg' => __('Something errors!')]);
        }
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(['error' => false, 'msg' => __('Success delete contact!')]);
    }

    public function destroyAll(Request $request, $id)
    {
        try {
            $request->user()->contacts()->whereTagId($id)->delete();
            return response()->json(['error' => false, 'msg' => __('Success delete all contact!')]);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'msg' => __('Something errors!')]);
        }
    }


    public function import(Request $request)
    {
        try {
            Excel::import(new ContactImport($request->phonebook_id), $request->file('fileContacts')->store('temp'));
            return response()->json(['error' => false, 'msg' => __('Success import contact!')]);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'msg' => $th->getMessage()]);
        }
    }
    public function export(Request $request, $id)
    {

        try {
            //code...
            $tag = Tag::find($id);
            $name = $tag->name . '.xlsx';
            // Clean the output buffer
            if (ob_get_length()) {
                ob_end_clean();
            }
            return Excel::download(new ContactsExport($tag->id, $request->user()->id), $name);
        } catch (\Throwable $th) {
            return __('something errors');
        }
    }
}
