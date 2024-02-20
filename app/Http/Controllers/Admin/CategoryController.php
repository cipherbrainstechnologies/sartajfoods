<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct(
        private Category $category
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    function index(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $categories = $this->category->where(['position' => 1])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $categories = $this->category->where(['position' => 1]);
        }
        $categories = $categories->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.category.index', compact('categories', 'search'));
    }

    function sub_sub_index(Request $request): View|Factory|Application
    {
        $query_param = [];
        $Query = $this->category->with(['parent'])->where('position' , 3);
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            // $categories = $this->category->with(['parent'])->where(['position' => 1])
                $Query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
        } 
        $categories = $Query->orderBy('id','desc')->paginate(Helpers::getPagination());
        return view('admin-views.category.middle-sub-index', compact('categories', 'search'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */


    function sub_index(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];
        $Query = $this->category->with(['parent'])->where('position' , 2);
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            // $categories = $this->category->with(['parent'])->where(['position' => 1])
            $categories = $Query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
        } 
        
        $categories = $Query->orderBy('id','desc')->paginate(Helpers::getPagination());
        return view('admin-views.category.sub-index', compact('categories', 'search'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $key = explode(' ', $request['search']);
        $categories = $this->category->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('admin-views.category.partials._table', compact('categories'))->render()
        ]);
    }

    /**
     * @return Factory|View|Application
     */
    // function sub_sub_index(): View|Factory|Application
    // {
    //     return view('admin-views.category.sub-sub-index');
    // }

    /**
     * @return Factory|View|Application
     */
    function sub_category_index(): View|Factory|Application
    {
        return view('admin-views.category.index');
    }

    /**
     * @return Factory|View|Application
     */
    function sub_sub_category_index(): View|Factory|Application
    {
        return view('admin-views.category.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    function store(Request $request): RedirectResponse
    {
        
        // $request->validate([
        //     'name' => 'required',
        //     'meta_title' => 'required',
        // ]);
        $commonRules = [
            'name' => 'required',
        ];
        $previousUrl = url()->previous();
        $previousRouteName = app('router')->getRoutes()->match(app('request')->create($previousUrl))->getName();
        
        // Check if the current route is 'category/add-sub-category'
        if ($previousRouteName == 'admin.category.add-sub-category') {
            // Exclude 'meta_title' rule for this route
            $validationRules = $commonRules + [
                'meta_title' => 'nullable',
            ];
        } else {
            // Use the common rules for other routes
            $validationRules = $commonRules + [
                'meta_title' => 'required',
            ];
        }

        $request->validate($validationRules);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }

        //uniqueness check
        $parent_id = $request->parent_id ?? 0;
        $all_category = $this->category->where(['parent_id' => $parent_id])->pluck('name')->toArray();

        if (in_array($request->name[0], $all_category)) {
            Toastr::error(translate(($request->parent_id == null ? 'Category' : 'Sub_category') . ' already exists!'));
            return back();
        }

        //image upload
        if (!empty($request->file('image'))) {
            $image_name = Helpers::upload('product/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }

        //into db
        $category = $this->category;
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->description = $request->description[array_search('en', $request->lang)];
        // if ($previousRouteName != 'admin.category.add-sub-category') {
            $category->meta_title = $request->meta_title[array_search('en', $request->lang)];
            $category->meta_description = $request->meta_description[array_search('en', $request->lang)];
            $category->meta_keywords = $request->meta_keywords[array_search('en', $request->lang)];
        // }
        $category->image = $image_name;
        $category->seo_en  = $request->en_seo ?? '';
        $category->seo_ja = $request->ja_seo?? '';
        $category->parent_id = $request->parent_id == null ? 0 : $request->parent_id;
        $category->position = $request->position;
        $category->save();

        //translation
        $data = [];
        foreach ($request->lang as $index => $key) {
            // if ($request->name[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                );
            // }
            // if ($request->description[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id,
                    'locale' => $key,
                    'key' => 'description',
                    'value' => $request->description[$index],
                );
            // }
           
                // if ($request->meta_title[$index] && $key != 'en') {
                    $data[] = array(
                        'translationable_type' => 'App\Model\Category',
                        'translationable_id' => $category->id,
                        'locale' => $key,
                        'key' => 'meta_title',
                        'value' => $request->meta_title[$index],
                    );
                // }
                // if ($request->meta_description[$index] && $key != 'en') {
                    $data[] = array(
                        'translationable_type' => 'App\Model\Category',
                        'translationable_id' => $category->id,
                        'locale' => $key,
                        'key' => 'meta_description',
                        'value' => $request->meta_description[$index],
                    );
                // }
                // if ($request->meta_keywords[$index] && $key != 'en') {
                    $data[] = array(
                        'translationable_type' => 'App\Model\Category',
                        'translationable_id' => $category->id,
                        'locale' => $key,
                        'key' => 'meta_keywords',
                        'value' => $request->meta_keywords[$index],
                    );
                // }
            
            
        }
        if (count($data)) {
            Translation::insert($data);
        }

        Toastr::success($request->parent_id == 0 ? translate('Category Added Successfully!') : translate('Sub Category Added Successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($id): View|Factory|Application
    {
        $category = $this->category->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.category.edit', compact('category'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $category = $this->category->find($request->id);
        $category->status = $request->status;
        $category->save();
        Toastr::success($category->parent_id == 0 ? translate('Category status updated!') : translate('Sub Category status updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' =>'required|unique:categories,name,'.$request->id
        ]);
       
        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }

        $category = $this->category->find($id);
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->description = $request->description[array_search('en', $request->lang)];
        $category->seo_en  = $request->en_seo;
        $category->seo_ja = $request->ja_seo;
        if(!empty($request->parent_id)){
            $category->parent_id = $request->parent_id;
        }
        // if (empty($category->parent_id )){
            $category->meta_title = $request->meta_title[array_search('en', $request->lang)];
            $category->meta_description = $request->meta_description[array_search('en', $request->lang)];
            $category->meta_keywords = $request->meta_keywords[array_search('en', $request->lang)];
        // }

        $category->image = $request->has('image') ? Helpers::update('product/', $category->image, 'png', $request->file('image')) : $category->image;
        $category->save();
        foreach ($request->lang as $index => $key) {
            // if ($key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\Category',
                        'translationable_id' => $category->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            // }
            // if ($key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\Category',
                        'translationable_id' => $category->id,
                        'locale' => $key,
                        'key' => 'description'],
                    ['value' => $request->description[$index]]
                );
            // }
            // if (empty($category->parent_id )){
                // if ( $key != 'en') {
                    
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Model\Category',
                            'translationable_id' => $category->id,
                            'locale' => $key,
                            'key' => 'meta_title'],
                        ['value' => $request->meta_title[$index]]
                    );
                // }
                // if ( $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Model\Category',
                            'translationable_id' => $category->id,
                            'locale' => $key,
                            'key' => 'meta_description'],
                        ['value' => $request->meta_description[$index]]
                    );
                // }
                // if ( $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Model\Category',
                            'translationable_id' => $category->id,
                            'locale' => $key,
                            'key' => 'meta_keywords'],
                        ['value' => $request->meta_keywords[$index]]
                    );
                // }
            // }
        }
        
        
        Toastr::success($category->parent_id == 0 ? translate('Category updated successfully!') : translate('Sub Category updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $category = $this->category->find($request->id);
        if (Storage::disk('public')->exists('category/' . $category['image'])) {
            Storage::disk('public')->delete('category/' . $category['image']);
        }
        if ($category->childes->count() == 0) {
            $category->delete();
            Toastr::success($category->parent_id == 0 ? translate('Category removed!') : translate('Sub Category removed!'));
        } else {
            Toastr::warning($category->parent_id == 0 ? translate('Remove subcategories first!') : translate('Sub Remove subcategories first!'));
        }
        return back();
    }
}
