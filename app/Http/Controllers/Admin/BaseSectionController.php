<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Facades\{App\Facades\Media as MediaManager};
use Hashids\Hashids;
use Illuminate\Http\Request;
use stdClass;

class BaseSectionController extends Controller
{
    //    TODO Faltan las funcionalidades del manejo de medias por personalizar

    private $hasher;
    protected $name;
    protected $title;
    protected $model;
    protected $limit;
    protected $instance = null;
    protected $images;

    public function __construct()
    {
        $this->isValid();
        $this->images = new stdClass();
        $this->hasher = new Hashids();
        $this->middleware('auth:admin');
        $this->middleware("permission:listar-{$this->name}|asignar-{$this->name}")->only(['index','search']);
        $this->middleware("permission:nuevo-{$this->name}")->only('create');
        $this->middleware("permission:editar-{$this->name}")->only('edit');
        $this->middleware("permission:crear-{$this->name}")->only('store');
        $this->middleware("permission:actualizar-{$this->name}")->only('update');
        $this->middleware("permission:borrar-{$this->name}")->only('destroy');
        $this->middleware("permission:cambiar-{$this->name}")->only('toggle');
        $this->limit = 50;
        $this->images->sizes = array_keys(config('wo2.images.sizes'));
        $this->images->ignore = config('wo2.images.ignore');
    }

    public function index(Request $request, $col = 'pos')
    {
        $get = $request->except('page');

        $data = $this->model::query();

        if ($this->isTranslable())
            $data = $data->with('defaultTranslation');

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
            'data' => $data->paginate($this->limit)
        ]);
    }

    public function create()
    {
        return view("admin.{$this->name}.new", [
            'subtitle' => 'Nuevo',
            'url' => route("admin.{$this->name}.store"),
            'data' => new \stdClass()
        ]);
    }

    public function store(Request $request)
    {
        $this->performStore($request);

        return $this->send($this->instance);
    }

    protected function performStore($request)
    {
        $this->instance = $this->model::create($request->all());

        $this->saveTranslations($request);
    }

    public function edit($id)
    {
        $this->findOrFail($id);
        
        if($this->isTranslable())
            $this->instance = $this->instance->load('translations');

        return view("admin.{$this->name}.new", [
            'subtitle' => 'Editar',
            'url' => route("admin.{$this->name}.update", $this->instance->id),
            'data' => $this->instance
        ]);
    }

    public function update($id, Request $request)
    {
        $this->performUpdate($id, $request);

        return $this->send($this->instance);
    }

    protected function performUpdate($id, $request)
    {
        $this->findOrFail($id);

        $this->instance->update($request->all());

        $this->saveTranslations($request);

        $this->instance = $this->instance->refresh();
    }

    public function destroy($id, Request $request)
    {
        $this->performDestroy($id, $request);

        if ($request->ajax())
            return $this->send($this->instance);

        return redirect()->route("admin.{$this->name}");
    }

    protected function performDestroy($id, Request $request)
    {
        $this->findOrFail($id);

        if (!$this->instance)
            return $this->errorResponse('Item not found', 404);

        if (isset($this->instance->relations)) {
            foreach ($this->instance->relations as $name => $options) {
                if (@$options['constraint']) {
                    if ($this->model->{$name}->isNotEmpty() && $request->has('confirm'))
                        return $this->send(
                            $this->instance,
                            sprintf('Este elemento tiene %1s relacionados. Está seguro de eliminar este elemento?
                                ¡Si elimina este elemento se perderán los %1s!', $name),
                            'WARNING'
                        );
                }
            }
        }

        $this->model::destroy($id);
    }

    private function isTranslable()
    {
        return in_array('App\Models\Traits\Translable', $this->model::getDeclaredTraits());
    }

    public function saveTranslations(Request $request, $model = 'Translation')
    {
        if ($this->isTranslable()) {
            $model = "App\Models\\$model";
            if ($request->has('translations'))
                foreach ($request->get('translations') as $locale => $fields) {
                    $data = [];
                    $data = array_merge($data, ['locale' => $locale]);
                    foreach ($fields as $name => $value) {
                        $data = array_merge($data, [$name => $value]);
                    }
                    $translation = $this->instance->translations()->where('locale', $locale)->first();
                    if (!$translation)
                        $this->instance->translations()->save(new $model($data));
                    else
                        $translation->update($data);
                } else
                throw new \RuntimeException('Request Data doesn\'t have translations key');
        }
    }

    public function saveMedia($id = null)
    {
        if (in_array('App\Models\Traits\Mediable', $this->model::getDeclaredTraits())) {
            if (!is_null(request()->row_id)) {
                if (MediaManager::updateMetas(request()->row_id, request()->metas, request()->pos)) {
                    return response()->json([
                        'status' => 'OK',
                        'id' => request()->row_id,
                        'data' => $this->hasher->encode(request()->row_id),
                        'uuid' => request()->uuid,
                        'errors' => []
                    ]);
                }
            }

            $this->findOrFail($id);
            $media_instance = MediaManager::new(request());
            $media_save = $this->instance->medias()->save($media_instance);
            if ($media_save) {
                $media_id = $media_save->id;
                if ($media_instance->type !== 'video') {
                    MediaManager::updateUrl($media_id, $media_instance->extension);
                }
            } else {
                return response()->json([
                    'status' => 'ERROR',
                    'id' => null,
                    'data' => null,
                    'errors' => ['No se pudo escribir en la DB']
                ]);
            }

            $hash_id = $this->hasher->encode($media_id);

            if (request()->hasFile('file') && request()->file('file')->isValid()) {
                $files_url = MediaManager::store(
                    request()->simple_type,
                    request()->file('file'),
                    $hash_id,
                    $this->images->sizes,
                    $this->images->ignore
                );

                $response = $files_url;
            } else {
                $response = "NO tiene archivo";
            }

            if (request()->ajax()) {
                return response()->json([
                    'status' => 'OK',
                    'id' => $media_id,
                    'data' => $hash_id,
                    'uuid' => request()->uuid,
                    'errors' => []
                ]);
            }
        } else
            throw new \RuntimeException(sprintf('Model %s doesn\'t have Mediable Trait on its declaration', class_basename($this->model)));
    }

    public function deleteMedia($id = null)
    {
        MediaManager::delete($id);

        return response()->json([
            'status' => 'OK',
            'uuid' => request()->uuid,
            'errors' => []
        ]);
    }


    public function search($needle, $query = null)
    {
        $data = $this->dive($needle, $query);

        if (request()->ajax()) {
            if ($data->isNotEmpty()) {
                return response()->json([
                    'from' => $this->name,
                    'info' => $data
                ]);
            }
        }
        return view("admin.{$this->name}.home", [
            'data' => $data->paginate($this->limit)
        ]);
    }

    public function dive($needle, $query, $columns = null, $relations = null)
    {
        $builder = $query ?? $this->model::query();

        $needle = "%$needle%";
        $fields = $columns ?? @$this->model::$searchable['fields'];
        $relations = $relations ?? @$this->model::$searchable['relations'];
        if ($fields)
            foreach ($fields as $f) {
                $builder = $builder->orWhere($f, 'like', $needle);
            }

        if ($relations)
            foreach ($relations as $relation => $fields) {
                $builder = $builder->orWhereHas($relation, function ($q) use ($fields, $needle) {
                    $q->whereRaw('(' . implode(" like '$needle' or ", $fields) . " like '$needle'" . ')');
                });
            }

        return $builder->get()->load(array_keys($relations ?? []));
    }

    private function sort($builder, $orientation, ...$steps)
    {
        return $builder->get()->orderBy(implode('.', array_clear($steps)), $orientation);
    }

    public function order()
    {
        if (!is_null(request()->order)) {
            $order_type = request()->order['type'];
            $order_column = request()->order['column'];
            $relation = @request()->order['relation'];
            $sort = @request()->order['sort'];
            $sort_int = 200;
            try {
                $builder = $this->model::with($relation ?? []);

                $builder = $this->sort($builder, $order_type, $relation, $sort, $order_column);

                $builder->chunk(500)->each(function ($items) use (&$sort_int, $relation) {
                    $items->map(function ($item) use ($relation, &$sort_int) {
                        $relation ? $item->{$relation}->update(['pos' => $sort_int]) : $item->update(['pos' => $sort_int]);
                        $sort_int += 200;
                    });
                });
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'ERROR',
                    'errors' => ['Ocurrió un error inesperado.']
                ]);
            }
        }

        $arrange = function ($item, &$values, $after = false) {
            $posIndex = $values->search($item['pos']);
            $pos = $values->pull($posIndex);
            $prevIndex = $values->values()->search($item['prev']);
            if ($after) {
                $per_page = $values->paginate($this->amount, $item['page'] - 1);
                if ($item['page'] < 1 || $item['page'] > $per_page->lastPage())
                    return response()->json([
                        'status' => 'ERROR',
                        'errors' => ['La página entrada es incorrecta']
                    ]);

                if ($item['page'] == 1) {
                    $values->prepend($pos);
                    return $values;
                } else
                    $prevIndex = $values->values()->search($per_page->last());
            }
            $head = $values->slice(0, $prevIndex + 1);
            $tail = $values->slice($prevIndex + 1, $values->count());
            $values = $head->push($pos)->concat($tail)->values();
        };

        //        TODO Implementar el reordenamiento de tuplas
        if (request()->has('dragged')) {
            $values = $this->model::get()->sortBy('pos')->pluck('id');
            $items = collect(request()->get('dragged'))->sortBy('prev');
            $items->each(function ($item) use (&$values, $arrange) {
                $arrange($item, $values, $item['prev'] == 0);
            });

            $pos = 200;
            $values->each(function ($id) use (&$pos) {
                $this->model::find($id)->update(['pos' => $pos += 200]);
            });
        }

        if (isset($this->repository))
            $this->repository->clear('list', true);

        return response()->json([
            'status' => 'OK',
            'errors' => []
        ]);
    }

    public function toggle($field, $id)
    {
        $ele = $this->model::find($id);
        $ele->{$field} = $ele->{$field} === 1 ? 0 : 1;
        $ele->save();

        if (request()->ajax()) {
            return response()->json([
                'status' => 'OK',
                'errors' => []
            ]);
        }

        return redirect()->route("admin.{$this->name}");
    }

    private function isValid()
    {
        //Controller requirements validations
        collect([$this->model, $this->name])->each(function ($property) {
            if (is_null($property)) {
                throw new \RuntimeException("BaseSectionController property is required");
            }
        });
    }

    private function findOrFail($id)
    {
        $this->instance = $this->model::find($id);

        if (!$this->instance)
            return $this->errorResponse('Item not found', 404);

        return $this;
    }

    public function raiseSuccessResponse(){
        return $this->send($this->instance,'Success Response');
    }
    
    public function raiseErrorResponse(){
        return $this->errorResponse('Something gets wrong!!!',500);
    }

    //JSON Responses
    public function send($data, $message = null, $status = 'OK')
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message
        ]);
    }

    public function errorResponse($message, $code = 403)
    {
        return response()->json([
            'status' => 'ERROR',
            'message' => $message,
        ], $code);
    }
}
