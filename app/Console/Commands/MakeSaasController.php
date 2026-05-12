<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeSaasController extends Command
{
    protected $signature = 'make:saas-controller {name}';
    protected $description = 'Generate SaaS Controller (POS/QR/Inventory style)';

    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Http/Controllers/{$name}.php");

        if (File::exists($path)) {
            $this->error("Controller already exists!");
            return;
        }

        $stub = $this->getStub($name);

        File::put($path, $stub);

        $this->info("SaaS Controller created: {$name}");
    }

    private function getStub($name)
    {
        return "<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class {$name} extends Controller
{
    public function index()
    {
        return view(strtolower('" . str_replace('Controller','',$name) . "').'.index');
    }

    public function store(Request \$request)
    {
        // TODO: implement store logic
        return response()->json([
            'message' => '{$name} store success'
        ]);
    }

    public function update(Request \$request, \$id)
    {
        // TODO: implement update logic
        return response()->json([
            'message' => '{$name} update success'
        ]);
    }

    public function destroy(\$id)
    {
        // TODO: implement delete logic
        return response()->json([
            'message' => '{$name} deleted'
        ]);
    }
}
";
    }
}