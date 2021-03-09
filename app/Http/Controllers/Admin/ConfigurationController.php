<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfigUploadRequest;
use App\Services\ConfigurationService;
use App\Services\LanguageService;
use Carbon\Carbon;
use Spatie\Analytics\Period;
use Analytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;

class ConfigurationController extends Controller
{
    private $service, $serviceLang;

    public function __construct(
        ConfigurationService $service,
        LanguageService $serviceLang
    )
    {
        $this->service = $service;
        $this->serviceLang = $serviceLang;
    }

    /**
     * extras
     */
    public function filemanager()
    {
        return view('backend.config.filemanager', [
            'title' => 'File Manager',
            'breadcrumbs' => [
                'File Manager' => '',
            ],
        ]);
    }

    public function visitor(Request $request)
    {
        if ($request->f == 'today') {
            $start = Carbon::now()->today();
            $end = Carbon::now()->today();
            $periode = Period::create($start, $end);
            $data['total'] = Analytics::fetchTotalVisitorsAndPageViews($periode);
            $data['n_visitor'] = Analytics::fetchUserTypes($periode);
            $data['browser'] = Analytics::fetchTopBrowsers($periode);
            $data['refe'] = Analytics::fetchTopReferrers($periode);
            $data['top'] = Analytics::fetchMostVisitedPages($periode);
            $data['vp'] = Analytics::fetchVisitorsAndPageViews($periode);

        } elseif ($request->f == 'current-week') {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
            $periode = Period::create($start, $end);
            $data['total'] = Analytics::fetchTotalVisitorsAndPageViews($periode);
            $data['n_visitor'] = Analytics::fetchUserTypes($periode);
            $data['browser'] = Analytics::fetchTopBrowsers($periode);
            $data['refe'] = Analytics::fetchTopReferrers($periode);
            $data['top'] = Analytics::fetchMostVisitedPages($periode);
            $data['vp'] = Analytics::fetchVisitorsAndPageViews($periode);

        } elseif ($request->f == 'latest-week') {
            $start = Carbon::now()->subWeek()->startOfWeek();
            $end = Carbon::now()->subWeek()->endOfWeek();
            $periode = Period::create($start, $end);
            $data['total'] = Analytics::fetchTotalVisitorsAndPageViews($periode);
            $data['n_visitor'] = Analytics::fetchUserTypes($periode);
            $data['browser'] = Analytics::fetchTopBrowsers($periode);
            $data['refe'] = Analytics::fetchTopReferrers($periode);
            $data['top'] = Analytics::fetchMostVisitedPages($periode);
            $data['vp'] = Analytics::fetchVisitorsAndPageViews($periode);

        } elseif ($request->f == 'current-month') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $periode = Period::create($start, $end);
            $data['total'] = Analytics::fetchTotalVisitorsAndPageViews($periode);
            $data['n_visitor'] = Analytics::fetchUserTypes($periode);
            $data['browser'] = Analytics::fetchTopBrowsers($periode);
            $data['refe'] = Analytics::fetchTopReferrers($periode);
            $data['top'] = Analytics::fetchMostVisitedPages($periode);
            $data['vp'] = Analytics::fetchVisitorsAndPageViews($periode);

        } elseif ($request->f == 'latest-month') {
            $start = Carbon::parse('-1 months')->startOfMonth();
            $end = Carbon::parse('-1 months')->endOfMonth();
            $periode = Period::create($start, $end);
            $data['total'] = Analytics::fetchTotalVisitorsAndPageViews($periode);
            $data['n_visitor'] = Analytics::fetchUserTypes($periode);
            $data['browser'] = Analytics::fetchTopBrowsers($periode);
            $data['refe'] = Analytics::fetchTopReferrers($periode);
            $data['top'] = Analytics::fetchMostVisitedPages($periode);
            $data['vp'] = Analytics::fetchVisitorsAndPageViews($periode);

        } elseif ($request->f == 'current-year') {
            $start = Carbon::now()->startOfYear();
            $end = Carbon::now()->endOfYear();
            $periode = Period::create($start, $end);
            $data['total'] = Analytics::fetchTotalVisitorsAndPageViews($periode);
            $data['n_visitor'] = Analytics::fetchUserTypes($periode);
            $data['browser'] = Analytics::fetchTopBrowsers($periode);
            $data['refe'] = Analytics::fetchTopReferrers($periode);
            $data['top'] = Analytics::fetchMostVisitedPages($periode);
            $data['vp'] = Analytics::fetchVisitorsAndPageViews($periode);

        } elseif ($request->f == 'latest-year') {
            $start = Carbon::parse('-1 years')->startOfYear();
            $end = Carbon::parse('-1 years')->endOfYear();
            $periode = Period::create($start, $end);
            $data['total'] = Analytics::fetchTotalVisitorsAndPageViews($periode);
            $data['n_visitor'] = Analytics::fetchUserTypes($periode);
            $data['browser'] = Analytics::fetchTopBrowsers($periode);
            $data['refe'] = Analytics::fetchTopReferrers($periode);
            $data['top'] = Analytics::fetchMostVisitedPages($periode);
            $data['vp'] = Analytics::fetchVisitorsAndPageViews($periode);

        } else {
            $data['total'] = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7));
            $data['n_visitor'] = Analytics::fetchUserTypes(Period::days(7));
            $data['browser'] = Analytics::fetchTopBrowsers(Period::days(7));
            $data['refe'] = Analytics::fetchTopReferrers(Period::days(7));
            $data['top'] = Analytics::fetchMostVisitedPages(Period::days(7));
            $data['vp'] = Analytics::fetchVisitorsAndPageViews(Period::days(7));
            $data['aa'] = Analytics::performQuery(Period::years(1),
            'ga:sessions', [
                'metrics' => 'ga:sessions, ga:pageviews',
                'dimensions' => 'ga:yearMonth'
            ]);
        }

        return view('backend.config.visitor', compact('data'), [
            'title' => 'Visitor',
            'breadcrumbs' => [
                'Visitor' => ''
            ],
        ]);
    }

    /**
     * configuration
     */
    public function web(Request $request)
    {
        $data['upload'] = $this->service->getConfigIsUpload();
        $data['general'] = $this->service->getConfig(2);
        $data['meta_data'] = $this->service->getConfig(3);
        $data['social_media'] = $this->service->getConfig(4);

        return view('backend.config.web', compact('data'), [
            'title' => 'Configurations',
            'breadcrumbs' => [
                'Configurations' => '',
                'Web Config' => ''
            ],
        ]);
    }

    public function update(Request $request)
    {
        foreach ($request->name as $key => $value) {
            $this->service->updateConfig($key, $value);
        }

        return back()->with('success', 'Update config successfully');
    }

    public function upload(ConfigUploadRequest $request, $name)
    {
        $this->service->uploadFile($request, $name);

        return back()->with('success', 'Upload successfully');
    }

    public function common(Request $request, $lang)
    {
        if ($request->has('lang')) {
            $data = "<?php \n\nreturn [\n";
            foreach ($request->lang as $key => $value) {
                $data .= "\t'$key' => '$value',\n";
            }
            $data .= "];";
            File::put(base_path('resources/lang/'.$lang.'/common.php'), $data);
            return back()->with('success', 'update common successfully');
        }

        $data['title'] = 'Common';
        $data['files'] = Lang::get('common', [], $lang);
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['country'] = $this->serviceLang->finLangByIsoCodes($lang);

        return view('backend.config.common', compact('data'), [
            'title' => 'Common',
            'breadcrumbs' => [
                'Common' => ''
            ],
        ]);
    }

    public function maintenance(Request $request)
    {
        if ($request->input()) {
            $data = "<?php \n\nreturn [\n";
            if ($request->mode == 1) {
                $data .= "\t 'mode' => TRUE,\n";
            } else {
                $data .= "\t 'mode' => FALSE,\n";
            }
            $data .= "];";
            File::put(base_path('config/custom/maintenance.php'), $data);
            return back()->with('success', 'Update maintenance successfully');

        }

        $data['maintenance'] = config('custom.maintenance');

        return view('backend.config.maintenance', compact('data'), [
            'title' => 'Maintenance Mode',
            'breadcrumbs' => [
                'Maintenance Mode' => ''
            ],
        ]);
    }
}
