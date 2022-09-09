<?php

namespace App\Http\Controllers\Admin;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class NewsletterController extends BaseSectionController
{
    protected $CM = Newsletter::class;
    /**
     * NewsletterController constructor.
     */
    public function __construct()
    {
        $this->name = 'newsletters';
        $this->title = 'Newsletters';
        $this->model = Newsletter::class;

        parent::__construct();
        view()->share([
            'name' => $this->name,
            'title' => $this->title,
        ]);
    }

    public function csv(Request $request)
    {
        $filename = sprintf('%s_%s_%s.csv', config('app.name'), $this->name, date_create('now')->format('d-m-Y'));
        if ($request->has('key')) {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $forSite = $this->CM::where('active', 1)->get()->toArray();
            $callback = function () use ($forSite, $request) {
                $file = fopen('php://output', 'w');

                fputcsv($file, ['Suscripciones al Sitio:']);

                foreach ($forSite as $value) {
                    fputcsv($file, [' Email: ' . $value['email']]);
                }

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        abort(403);
    }

    //Redefine Methods here
    public function index(Request $request, $col = 'pos')
    {
        $get = $request->except('page');

        $data = $this->model::query();

//        if ($this->isTranslable())
//            $data = $data->with('defaultTranslation');

        try {
            $order = explode("|", array_shift($get));
            $path = implode('.', $get);
            $data = $data->orderBy($path ? "$path.$order[0]" : $order[0], $order[1]);
        } catch (\Exception $e) {
            $data = $data->orderBy($col);
        }

        if ($request->ajax())
            return $this->send($data->get());

        return view("admin.{$this->name}.home", [
            'data' => $data->paginate($this->limit), 'nuevo' => true
        ]);
    }
}
