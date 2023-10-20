<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Filter;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class filterController extends Controller
{
    public function __construct(
        private filter   $filter,
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
            $filters = $this->filter->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })->orderBy('name');
            $query_param = ['search' => $request['search']];
        } else {
            $filters = $this->filter->orderBy('name');
        }
        $filters = $filters->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.filter.index', compact('filters', 'search'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:filters',
        ], [
            'name.required' => translate('Name is required'),
            'name.unique' => translate('Name is already taken'),
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }

        $filter = $this->filter;
        $filter->name = $request->name[array_search('en', $request->lang)];
        $filter->save();

        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\filter',
                    'translationable_id' => $filter->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                );
            }
        }
        if (count($data)) {
            $this->translation->insert($data);
        }

        Toastr::success(translate('filter added successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): View|Factory|Application
    {
        $filter = $this->filter->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.filter.edit', compact('filter'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:filters,name,' . $request->id,
        ], [
            'name.required' => translate('Name is required'),
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }

        $filter = $this->filter->find($id);
        $filter->name = $request->name[array_search('en', $request->lang)];
        $filter->save();

        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                $this->translation->updateOrInsert(
                    ['translationable_type' => 'App\Model\filter',
                        'translationable_id' => $filter->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            }
        }

        Toastr::success(translate('filter updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $filter = $this->filter->find($request->id);
        $filter->delete();
        Toastr::success(translate('filter removed!'));
        return back();
    }

}
