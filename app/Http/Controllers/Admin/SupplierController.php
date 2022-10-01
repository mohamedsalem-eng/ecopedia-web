<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Supplier;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Translation;

class SupplierController extends Controller
{
    public function add_new()
    {
        $br = Supplier::latest()->paginate(Helpers::pagination_limit());
        return view('admin-views.supplier.add-new', compact('br'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name.0'=>'required'
        ]);
        $supplier = new Supplier;
        $supplier->name = $request->name[array_search('en', $request->lang)];
        $supplier->phones = $request->phones;
        $supplier->address = $request->address;
        $supplier->status = 1;
        $supplier->save();

        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Model\Supplier',
                        'translationable_id'    => $supplier->id,
                        'locale'                => $key,
                        'key'                   => 'name'
                    ],
                    ['value'                 => $request->name[$index]]
                );
            }
        }
        Toastr::success('Supplier added successfully!');
        return back();
    }

    function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $br = Supplier::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $br = new Supplier();
        }
        $br = $br->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.supplier.list', compact('br', 'search'));
    }

    public function edit($id)
    {
        $b = Supplier::where(['id' => $id])->withoutGlobalScopes()->first();
        return view('admin-views.supplier.edit', compact('b'));
    }

    public function update(Request $request, $id)
    {

        $supplier = Supplier::find($id);
        $supplier->name = $request->name[array_search('en', $request->lang)];
        $supplier->phones = $request->phones;
        $supplier->address = $request->address;
        $supplier->save();
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    [
                        'translationable_type' => 'App\Model\Supplier',
                        'translationable_id' => $supplier->id,
                        'locale' => $key,
                        'key' => 'name'
                    ],
                    ['value' => $request->name[$index]]
                );
            }
        }

        Toastr::success('Supplier updated successfully!');
        return back();
    }

    public function delete(Request $request)
    {
        $translation = Translation::where('translationable_type', 'App\Model\Supplier')
            ->where('translationable_id', $request->id);
        $translation->delete();
        $supplier = Supplier::find($request->id);

        $supplier->delete();
        return response()->json();
    }
}
