<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ThemesController extends Controller
{
    public function index()
    {
        $themesPath = public_path('themes');
        $themes = [];
		
        if (File::exists($themesPath)) {
            $folders = File::directories($themesPath);
            
            foreach ($folders as $folder) {
                $infoFile = $folder . '/info.json';
                $screenshotFile = $folder . '/screenshot.jpg';

                if (File::exists($infoFile)) {
                    $info = json_decode(File::get($infoFile), true);
                    $themes[] = [
                        'name' => $info['name'] ?? 'Unknown',
                        'version' => $info['version'] ?? 'Unknown',
                        'author' => $info['author'] ?? 'Unknown',
                        'website' => $info['website'] ?? '',
						'folder' => basename($folder) ?? '',
                        'screenshot' => File::exists($screenshotFile) ? '/themes/'.basename($folder) . '/screenshot.jpg' : null
                    ];
                }
            }
        }
		
		///////// Online Themes //////////
		$randomNumber = rand(10000, 99999);
		$currentVersion = config('app.version');
		$checkUrl = "https://www.onexgen.com/mpwa/themes.php?v=$currentVersion&lang=".config('app.locale')."&rand=".$randomNumber;
		$response = Http::timeout(10)->get($checkUrl);
		$onlines = $response->json();
		
		$clearLocalStorage = session('clearLocalStorage', null);
		session()->forget('clearLocalStorage');

        return view('theme::pages.admin.themes', compact('themes', 'onlines', 'currentVersion', 'clearLocalStorage'));
    }
	
	public function activeTheme(Request $request)
    {
		if($request->user()->level != 'admin'){
			return redirect()->route('home');
		}
		
		if(!$request->name){
			return redirect()->route('admin.manage-themes')->with('error', __('No theme selected.'));
		} else {
			$themeName = $request->name;
		}
		
		try {
			setEnv('THEME_NAME', $themeName);
			
			Artisan::call('route:clear');
			Artisan::call('cache:clear');
			Artisan::call('config:clear');
			Artisan::call('view:clear');
			
			$clearLocalStorage = '<script>localStorage.clear();</script>';
			session(['clearLocalStorage' => $clearLocalStorage]);

			return redirect()->route('admin.manage-themes')->with('status', __('Theme activated successfully'));
			
		} catch (\Exception $e) {
			return redirect()->route('admin.manage-themes')->with('error', __('There is an error activating the theme.'));
		}
	}
	
	public function deleteTheme(Request $request)
    {
		try {
			File::deleteDirectory(public_path('themes/'.$request->folder));
			File::deleteDirectory(resource_path('themes/'.$request->folder));
			
			return redirect()->route('admin.manage-themes')->with('status', __('Theme deleted successfully'));
			
		} catch (\Exception $e) {
			return redirect()->route('admin.manage-themes')->with('error', __('There is an error activating the theme.'));
		}
	}
	
	public function downloadTheme(Request $request)
    {
		if($request->user()->level != 'admin'){
			return redirect()->route('home');
		}
		
		$downloadURL = $request->download;
		$downloadFolder = $request->folder;
		$pathFolder = 'themes/'.$downloadFolder;
		
		$newFolder = Storage::path($pathFolder);
		
		$zipFile = Storage::path($pathFolder.'.zip');
		$response = Http::get($downloadURL);
		Storage::put($pathFolder.'.zip', $response);
		
		if (class_exists('ZipArchive')) {
			$zip = new ZipArchive;
			if ($zip->open($zipFile) === TRUE) {
				$zip->extractTo($newFolder);
				$zip->close();
			}
		}elseif (class_exists('PharData')) {
			$phar = new PharData($zipFile);
			$phar->extractTo($newFolder);
		}else{
			return redirect()->route('admin.manage-themes')->with('error', __('Failed to download theme file. (zip-100)'));
		}
		
		if (File::exists($newFolder.'/info.json')) {

			File::copyDirectory($newFolder.'/views', resource_path('themes/eres/views/'));
			Storage::deleteDirectory($pathFolder.'/views');
			File::copyDirectory($newFolder, public_path('themes/eres'));
			
			Storage::deleteDirectory('themes');
		}else{
			return redirect()->route('admin.manage-themes')->with('error', __('Failed to download theme file. (zip-100)'));
		}
		
		setEnv('THEME_NAME', $downloadFolder);
		
		Artisan::call('route:clear');
		Artisan::call('cache:clear');
		Artisan::call('config:clear');
		Artisan::call('view:clear');
		
		return redirect()->route('admin.manage-themes')->with('status', __('Theme activated successfully'));
	}
}
