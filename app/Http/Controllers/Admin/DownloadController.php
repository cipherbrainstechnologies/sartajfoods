<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Downloads;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;

class DownloadController extends Controller
{
    public function __construct(
        private Downloads   $download,
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
            $downloads = $this->download->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })->orderBy('name');
            $query_param = ['search' => $request['search']];
        } else {
            $downloads = $this->download->orderBy('name');
        }
        $downloads = $downloads->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.download.index', compact('downloads', 'search'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'file' => 'required',
            'mask' => 'required',
        ], [
            'name.required' => translate('Name is required'),
            'file.required' => translate('File is required'),
            'mask.required' => translate('Mask is required'),
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }

        $download = $this->download;
        $download->name = $request->name[array_search('en', $request->lang)];
        $download->mask = $request->mask;
        if (!empty($request->file('file'))) {
            $file_name = Helpers::upload('downloads/', $request->file('file')->extension(), $request->file('file'));
        }
        $download->file = $file_name;
        $download->save();

        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\downloads',
                    'translationable_id' => $download->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                );
            }
        }
        if (count($data)) {
            $this->translation->insert($data);
        }

        Toastr::success(translate('download link added successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): View|Factory|Application
    {
        $download = $this->download->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.download.edit', compact('download'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:downloads,name,' . $request->id,
            'mask' => 'required',
        ], [
            'name.required' => translate('Name is required'),
            'mask.required' => translate('Mask is required'),
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }

        $download = $this->download->find($id);
        $download->name = $request->name[array_search('en', $request->lang)];
        $download->mask = $request->mask;
        $file_name = $download->file;
        if (!empty($request->file('file'))) {
            $file_name = Helpers::upload('downloads/', $request->file('file')->extension(), $request->file('file'));
        }
        $download->file = $file_name;
        $download->save();

        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                $this->translation->updateOrInsert(
                    ['translationable_type' => 'App\Model\download',
                        'translationable_id' => $download->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            }
        }

        Toastr::success(translate('download updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $download = $this->download->find($request->id);
        $download->delete();
        Toastr::success(translate('download removed!'));
        return back();
    }
}
