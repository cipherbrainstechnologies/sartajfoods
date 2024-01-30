<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Manufacturer;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class manufacturerController extends Controller
{
    public function __construct(
        private Manufacturer   $manufacturer,
        private Translation $translation
    ){}

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    function index(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $manufacturers = $this->manufacturer->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })->orderBy('name');
            $query_param = ['search' => $request['search']];
        } else {
            $manufacturers = $this->manufacturer->orderBy('name');
        }
        $manufacturers = $manufacturers->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.manufacturer.index', compact('manufacturers', 'search'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:manufacturers',
            'image' => 'required',
            'meta_title'  => 'required',
        ], [
            'name.required' => translate('Name is required'),
            'name.unique' => translate('Name is already taken'),
            'image.required' => translate('image is required'),
            'meta_title.required' => translate('Meta Title is required'),
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }

        $manufacturer = $this->manufacturer;
        $manufacturer->name = $request->name[array_search('en', $request->lang)];
        $manufacturer->meta_title = $request->meta_title[array_search('en', $request->lang)];
        $manufacturer->meta_description = $request->meta_description[array_search('en', $request->lang)];
        $manufacturer->meta_keywords = $request->meta_keywords[array_search('en', $request->lang)];
        $manufacturer->seo_en = $request->en_seo;
        $manufacturer->seo_ja = $request->ja_seo;

        $image_data = '';
        if (!empty($request->file('image'))) {
            $image_data = Helpers::upload('product/image/', 'png', $request->file('image'));
        }
        $manufacturer->image = $image_data;
        $manufacturer->sort_order = $request->sort_order;
        $manufacturer->save();

        $data = [];
        foreach ($request->lang as $index => $key) {
            // if ($request->name[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\manufacturer',
                    'translationable_id' => $manufacturer->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                );
            // }
            // if ($request->meta_title[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\manufacturer',
                        'translationable_id' => $manufacturer->id,
                        'locale' => $key,
                        'key' => 'meta_title'],
                    ['value' => $request->meta_title[$index]]
                );
            // }
            // if ($request->meta_description[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\manufacturer',
                        'translationable_id' => $manufacturer->id,
                        'locale' => $key,
                        'key' => 'meta_description'],
                    ['value' => $request->meta_description[$index]]
                );
            // }
            // if ($request->meta_keywords[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\manufacturer',
                        'translationable_id' => $manufacturer->id,
                        'locale' => $key,
                        'key' => 'meta_keywords'],
                    ['value' => $request->meta_keywords[$index]]
                );
            // }
        }
        if (count($data)) {
            $this->translation->insert($data);
        }

        Toastr::success(translate('manufacturer added successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): View|Factory|Application
    {
        $manufacturer = $this->manufacturer->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.manufacturer.edit', compact('manufacturer'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:manufacturers,name,' . $request->id,
        ], [
            'name.required' => translate('Name is required'),
        ]);


        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }

        $manufacturer = $this->manufacturer->find($id);
        $manufacturer->name = $request->name[array_search('en', $request->lang)];
        $manufacturer->meta_title = $request->meta_title[array_search('en', $request->lang)];
        $manufacturer->meta_description = $request->meta_description[array_search('en', $request->lang)];
        $manufacturer->meta_keywords = $request->meta_keywords[array_search('en', $request->lang)];
        $image_data = $manufacturer->image;
        $manufacturer->seo_en = $request->en_seo;
        $manufacturer->seo_ja = $request->ja_seo;
        if (!empty($request->file('image'))) {
            $image_data = Helpers::upload('product/image/', 'png', $request->file('image'));
        }
        $manufacturer->image = $image_data;
        $manufacturer->sort_order = $request->sort_order;
        $manufacturer->save();

        foreach ($request->lang as $index => $key) {
            
                $this->translation->updateOrInsert(
                    ['translationable_type' => 'App\Model\manufacturer',
                        'translationable_id' => $manufacturer->id,
                        'locale' => $key,
                        'key' => 'name'
                    ],
                    ['value' => $request->name[$index]]
                );
            
            
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\manufacturer',
                        'translationable_id' => $manufacturer->id,
                        'locale' => $key,
                        'key' => 'meta_title'
                    ],
                    ['value' => $request->meta_title[$index]]
                );
            
            
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\manufacturer',
                        'translationable_id' => $manufacturer->id,
                        'locale' => $key,
                        'key' => 'meta_description'
                    ],
                    ['value' => $request->meta_description[$index]]
                );
            
            
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\manufacturer',
                        'translationable_id' => $manufacturer->id,
                        'locale' => $key,
                        'key' => 'meta_keywords'
                    ],
                    ['value' => $request->meta_keywords[$index]]
                );
            
        }

        Toastr::success(translate('manufacturer updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $manufacturer = $this->manufacturer->find($request->id);
        $manufacturer->delete();
        Toastr::success(translate('manufacturer removed!'));
        return back();
    }

}
