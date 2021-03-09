<?php

namespace App\Services;

use App\Models\Language;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class LanguageService
{
    private $model;

    public function __construct(Language $model)
    {
        $this->model = $model;
    }

    public function getLanguageList($request)
    {
        $query = $this->model->query();

        if ($request->get('is_trash') == 'yes') {
            $query->onlyTrashed();
        }

        $query->when($request->s, function ($query, $s) {
            $query->where('status', $s);
        })->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('iso_codes', 'like', '%'.$q.'%')
                ->orWhere('country', 'like', '%'.$q.'%')
                ->orWhere('time_zone', 'like', '%'.$q.'%');
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('id', 'ASC')->paginate($limit);

        return $result;
    }

    public function getAllLang()
    {
        $query = $this->model->query();

        $query->active();
        if (config('custom.language.multiple') == false) {
            $query->where('iso_codes', App::getLocale());
        }

        $result = $query->get();

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function finLangByIsoCodes($isoCodes)
    {
        $query = $this->model->query();

        $query->where('iso_codes', $isoCodes);

        $result = $query->first();

        return $result;
    }

    public function store($request)
    {
        $language = new Language($request->only(['country']));
        $language->iso_codes = strtolower($request->iso_codes);
        $language->description = $request->description ?? null;
        $language->faker_locale = $request->faker_locale ?? null;
        $language->time_zone = $request->time_zone ?? null;
        $language->gmt = $request->gmt ?? null;
        $language->icon = $request->icon ?? null;
        $language->status = (bool)$request->status;
        $language->save();

        $path = resource_path('lang/'.strtolower($request->iso_codes));
        File::makeDirectory($path, $mode = 0777, true, true);
        File::put($path.'/common.php', '<?php');

        return $language;
    }

    public function update($request, int $id)
    {
        $language = $this->find($id);
        $language->fill($request->only(['country']));
        $language->iso_codes = strtolower($request->iso_codes);
        $language->description = $request->description ?? null;
        $language->faker_locale = $request->faker_locale ?? null;
        $language->time_zone = $request->time_zone ?? null;
        $language->gmt = $request->gmt ?? null;
        $language->icon = $request->icon ?? null;
        $language->status = (bool)$request->status;
        $language->save();

        return $language;
    }

    public function status(int $id)
    {
        $language = $this->find($id);
        $language->status = !$language->status;
        $language->save();

        return $language;
    }

    public function softDelete(int $id)
    {
        $language = $this->find($id);

        if ($language->iso_codes == 'id' || $language->iso_codes == 'en') {
            return false;
        } else {

            $language->delete();

            return true;
        }
    }

    public function permanentDelete($request, int $id)
    {
        if ($request->get('is_trash') == 'yes') {
            $language = $this->model->onlyTrashed()->where('id', $id)->first();
        } else {
            $language = $this->find($id);
        }

        if ($language->iso_codes == 'id' || $language->iso_codes == 'en') {
            return false;
        } else {

            File::delete('common.php');
            File::deleteDirectory(resource_path('lang/'.$language->iso_codes));

            $language->forceDelete();

            return true;
        }
    }

    public function restore(int $id)
    {
        $language = $this->model->onlyTrashed()->where('id', $id);
        $language->restore();

        return $language;
    }
}
