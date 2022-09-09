<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CreateSectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:section {name}';

    protected $fields = [];

    protected $valid_columns = [];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for app section generation, ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->valid_columns = [
            "bigIncrements", "bigInteger", "binary", "boolean", "char", "date",
            "double", "float", "increments", "integer", "longText", "mediumInteger", "dateTime", "decimal",
            "mediumText", "smallInteger", "tinyInteger", "string", "text", "time"
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = $this->ask(".:::Liste los atributos de la seccion a generar, con el formato \"nombre=tipo_de_dato\", separados por coma:::. \n" . 'Tipos: ' . implode(',', $this->valid_columns));
        $section = $this->argument('name');
        $cap_section = $classname = ucfirst($section);

        if ($this->validate($params)) {
            $has_medias = $this->confirm('¿La sección contiene medias?');

            $has_translations = $this->confirm('¿La sección contiene traducciones?');

            //Creando la migracion
            $attributes = [];
            array_walk($this->fields, function ($type, $name) use (&$attributes) {
                $attributes[] = "\$table->$type('$name');";
            });
            $content = sprintf(file_get_contents(base_path('wargo/templates/migration.template')), ucfirst($section), $section, implode("\n\t\t\t", $attributes));

            file_put_contents(sprintf('database/migrations/%s_create_%s_table.php', date_format(new \Datetime(), 'Y_m_d_Hms'), $section), $content);
            $this->info('Creada migración para ' . ucfirst($section));

            //Creando el model
            $traits = [];
            $attributes = $booleans = $appends_and_events = '';
            if ($has_medias) {
                $traits [] = "Mediable";
                $appends_and_events='
                protected $appends = [\'first_image\'];

                //Arreglo de atributos para el manejo de eventos en el Subscriber de Eloquent. que se lanza cuando se elimine el modelo
                protected $events = [
                    //Relacion, Metodo QueryBuilder
                    \'medias\' => [
                        \'constraint\' => false,
                        \'operation\' => \'delete\'
                    ]
                ];';
            }

            if ($has_translations) 
                $traits [] = "Translable";

            array_walk($this->fields, function ($type, $attr) use (&$attributes, &$booleans) {
                $attributes .= "'$attr',";
                if ($type == 'boolean') {
                    $cap = ucfirst($attr);
                    $booleans .= "public function set{$cap}Attribute(\$value)
                    {
                        return \$this->attributes['$attr'] = w2_set_checkbox(\$value);
                    }
                \n\t";
                }
            });

            $traits = empty($traits)?'':sprintf('use %s;',implode(',',$traits));

            $content = sprintf(file_get_contents(base_path('wargo/templates/model.template')), $classname, $section, $attributes, $traits, $appends_and_events,$booleans);
            file_put_contents(sprintf('app/Models/%s.php', $cap_section), $content);
            $this->info('Creado Model para ' . $cap_section);

            //Creando el controller
            $content = sprintf(file_get_contents(base_path('wargo/templates/controller.template')), $cap_section, $section);
            file_put_contents(sprintf('app/Http/Controllers/Admin/%sController.php', $cap_section), $content);
            $this->info('Creado Controller para ' . $cap_section);

            //Creando los blades

            //Creo el directorio
            mkdir("resources/views/admin/$section");
            //new.blade
            $attributes = [];
            array_walk($this->fields, function ($type, $name) use (&$attributes) {
                $attributes[] = $this->getTemplate($type, $name);
            });

            $media_tag = $has_medias ? '<div class="wo2-fileupload" data-video="0" data-type="image" data-limit="1"></div>' : '';
            $content = sprintf(file_get_contents(base_path('wargo/templates/blades/new.template')), implode("\n", $attributes), $media_tag);
            file_put_contents(sprintf('resources/views/admin/%s/new.blade.php', $section), $content);
            $this->info('Adicionada vista "new.blade"');

            //home.blade

            $attributes = ['thead', 'tbody'];

            array_walk($this->fields, function ($type, $name) use (&$attributes) {
                $attributes['thead'][] = sprintf('<th data-col="%s">%s</th>', $name, ucfirst($name));
                $attributes['tbody'][] = sprintf('<td>{{ $d->%s}}</td>', $name);
            });

            $content = sprintf(file_get_contents(base_path('wargo/templates/blades/home.template')), implode("\n", $attributes['thead']), implode("\n", $attributes['tbody']));
            file_put_contents(sprintf('resources/views/admin/%s/home.blade.php', $section), $content);
            $this->info('Adicionada vista "home.blade"');

            //Modifico el fichero del menu

            $original = file_get_contents(base_path('resources/views/admin/components/menu.blade.php'));
            $content = Str::before($original, '</ul>') . "
             @can('listar-$section')
                <li><a href=\"{{ url('admin/$section') }}\" class=\"{{ w2_isactive(\$name, '$section') }} wo-section-click\">
                        $cap_section
                </a></li>
            @endcan
            \n</ul>";
            $content .= Str::after($original, '</ul>');
            file_put_contents('resources/views/admin/components/menu.blade.php', $content);
            $this->info('Modificado menú principal');

            Artisan::call("create:permissions", ['name' => $section]);
            $this->info(Artisan::output());

            $migrate = $this->confirm('¿Desea ejecutar la migración creada?');
            if ($migrate)
                Artisan::call('migrate');

            $this->info('Generación terminada ;-)');
        }
    }

    private function getTemplate($type, $name)
    {
        $input = '';
        switch ($type) {
            case 'text':
                $input = sprintf('<textarea name="%1$s" class="input" type="text" placeholder="" value="">{{ $data->%1$s??\'\' }}</textarea>', $name);
                break;
            case 'tinyInteger':
                $input = sprintf('<div class="select">
                            <select class="wo2-form-select" name="%s">
                               <!-- Llamada al helper constructor de arreglos -->
                            </select>
                        </div>', $name);
                break;
            case 'boolean':
                $input = sprintf('<input type="checkbox" name="%1$s[]"
                           class="switch" {{ isset($data->%1$s) ? ($data->%1$s ? \'checked\' : \'\') : \'checked\' }} />', $name);
                break;
            default:
                $input = sprintf('<input name="%1$s" class="input" type="text" placeholder=""
                                       value="{{ $data->%1$s??\'\'}}">', $name);
        }
        return sprintf(" 
                <div class=\"field\" data-validation=\"['OPT']\" data-alias=\"%s\">
                    <div class=\"control has-icons-right\">
                       %s
                    </div>
                </div>", ucfirst($name), $input);
    }

    private function validate($params)
    {
        if (preg_match('/(\w+=\w+\,?)+?/', $params)) {
            preg_match_all('/((?P<fieldnames>\w+)=(?P<fieldtypes>\w+)\,?)+?/', $params, $matches);
            if (collect($matches['fieldtypes'])->every(function ($type) {
                return in_array($type, $this->valid_columns);
            })) {
                $this->fields = array_combine($matches['fieldnames'], $matches['fieldtypes']);
                return true;
            }
            $this->info('Existen columnas inválidas en el listado de atributos entrado.');
            return false;
        }
        $this->info('La sintaxis de los elementos entrados no es válida.');
        return false;
    }
}
