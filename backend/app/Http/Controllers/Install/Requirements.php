<?php
namespace App\Http\Controllers\Install;
use App\Utilities\Installer;
use File;
use Illuminate\Routing\Controller;
class Requirements extends Controller
{
    public function show()
    {
        $requirements = Installer::checkServerRequirements();
        if (empty($requirements)) {
            if (!File::exists(base_path('.env'))) {
                Installer::createDefaultEnvFile();
            }
            redirect('install/language')->send();
        } else {
            foreach ($requirements as $requirement) {
                flash($requirement)->error()->important();
            }
            return view('install.requirements.show', compact('requirements'));
        }
    }
}
