<?php
/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/**************************/

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpClient\HttpClient;
use TomCan\AcmeClient\AcmeClient;
use TomCan\AcmeClient\Objects\Account;
use TomCan\AcmeClient\Account\AccountInterface;
use TomCan\AcmeClient\Order\OrderInterface;
use TomCan\AcmeClient\Authorization\AuthorizationInterface;
use TomCan\AcmeClient\Certificate\CertificateInterface;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(
            'activate_license',
            'install',
            'test_database_connection'
        );
    }
	
    public function index()
    {
		$getHost = $this->getDomain(env('WA_URL_SERVER'));
		$host = $getHost->host;
        $port = env('PORT_NODE');
		$allEnv = $this->getAllEnv();

		$isConnected = $this->checkPort($host, $port);
		$protocolMatch = $this->checkServerProtocol(env('WA_URL_SERVER'));
		
        return view('theme::pages.admin.settings', compact('host', 'port', 'isConnected', 'protocolMatch', 'allEnv'));
    }
	
	public function getDomain($url) {
		$parsedUrl = parse_url($url);

		$host = $parsedUrl['host'] ?? '';
		$port = $parsedUrl['port'] ?? null;

		return (object)[
			'host' => $host,
			'port' => $port,
		];
	}
	
	public function setEnvAll(Request $request)
	{
		$InputAll = $request->except('_token');
		foreach ($InputAll as $key => $value) {
			setEnv($key, $value);
		}

		return back()->with('alert', ['type' => 'success', 'msg' => __('Environment variables updated successfully.')]);
	}
    
    public function generateSslCertificate(Request $request)
    {
		$domain = $request->input('domain');
		$email = $request->input('email');
		
        $httpClient = HttpClient::create();
        $acmeClient = new AcmeClient($httpClient, 'https://acme-staging-v02.api.letsencrypt.org/directory');
        $account = new Account($email, null, null);

        $acmeClient->getAccount($account);

        Storage::put('account.json', json_encode([
            'email' => $account->getEmail(),
            'url' => $account->getUrl(),
            'key' => $account->getKey()
        ]));

        $accountData = json_decode(Storage::get('account.json'), true);
        $account = new Account($accountData['email'], $accountData['url'], $accountData['key']);
        $acmeClient->getAccount($account);

        $order = $acmeClient->createOrder([$domain]);
        $authorizations = $acmeClient->authorize($order);

        $challenges = [];
        foreach ($authorizations as $authorization) {
            foreach ($authorization->getChallenges() as $challenge) {
                if ($challenge->getType() === 'http-01') {
                    $challenges[] = $challenge;
                     $path = base_path('.well-known/acme-challenge/' . $challenge->getToken());
                    if (!File::exists(dirname($path))) {
                        File::makeDirectory(dirname($path), 0755, true);
                    }
                    File::put($path, $challenge->getValue());
                }
            }
        }

        $result = $acmeClient->validate($authorizations, $challenges);

        if ($result) {
            $cert = $acmeClient->finalize($order);
            File::put(base_path('cert.pem'), $cert->getCertificate());
            File::put(base_path('key.pem'), $cert->getKey());
			File::put(base_path('csr.pem'), $cert->getCsr());
			
			if (File::exists(base_path('cert.pem'))) {
				$webPhpPath = base_path('routes/web.php');
				$webPhpContent = File::get($webPhpPath);
				$serverJsPath = base_path('server.js');
				$serverJsContent = File::get($serverJsPath);
				
				if (strpos($webPhpContent, "URL::forceScheme") === false) {
					$webPhpContent = str_replace("?>", "URL::forceScheme('https');\n?>", $webPhpContent);
					File::put($webPhpPath, $webPhpContent);
				}
				
				if ($this->getServerProtocol() === "https"){
					$pattern = '/const serverOptions = \{[\s\S]*?\}[\s\S]*?const server = https\.createServer\(serverOptions, app\);/m';
					preg_match($pattern, $serverJsContent, $matches);
					$serverOptionsContent = isset($matches[0]) ? trim($matches[0]) : '';
					File::put(base_path('server.js.bak'), $serverOptionsContent);
					$serverOptionsContent = str_replace($serverOptionsContent, "const serverOptions = {\n  key: fs.readFileSync('key.pem'),\n  cert: fs.readFileSync('cert.pem')\n}\n\nconst express = require(\"express\");\nconst app = express();\nconst https = require(\"https\");\nconst server = https.createServer(serverOptions, app);", $serverJsContent);
					File::put($serverJsPath, $serverOptionsContent);
				} else {
					$pattern = '/const express = [\s\S]*?const server = http\.createServer\(app\);/m';
					preg_match($pattern, $serverJsContent, $matches);
					$serverOptionsContent = isset($matches[0]) ? trim($matches[0]) : '';
					File::put(base_path('routes/web.php.bak'), $serverOptionsContent);
					$serverOptionsContent = str_replace($serverOptionsContent, "const serverOptions = {\n  key: fs.readFileSync('key.pem'),\n  cert: fs.readFileSync('cert.pem')\n}\n\nconst express = require(\"express\");\nconst app = express();\nconst https = require(\"https\");\nconst server = https.createServer(serverOptions, app);", $serverJsContent);
					File::put($serverJsPath, $serverOptionsContent);
				}
				
				$replaceAPP_URL = str_replace(array('https', 'http'), "https", env('APP_URL'));
				$replaceWA_URL_SERVER = str_replace(array('https', 'http'), "https", env('WA_URL_SERVER'));
				setEnv('APP_URL', $replaceAPP_URL);
				setEnv('WA_URL_SERVER', $replaceWA_URL_SERVER);
				
			} else {
				return back()->with('alert', ['type' => 'danger', 'msg' => __('Failed to generate SSL certificate.')]);
			}

            return back()->with('alert', ['type' => 'success', 'msg' => __('SSL certificate generated successfully, please restart NodeJS')]);
        }

        return back()->with('alert', ['type' => 'danger', 'msg' => __('Failed to generate SSL certificate.')]);
    }
	
	public function checkPort($host, $port, $timeout = 3)
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if (is_resource($connection)) {
            fclose($connection);
            return true;
        } else {
            return false;
        }
    }
	
	public function checkServerProtocol($url)
	{
		$serverProtocol = $this->getServerProtocol();
		$urlProtocol = $this->checkhttpsorhttp($url);

		if ($serverProtocol == $urlProtocol) {
			return '<span class="text-success">'.__("Your site is working properly").'</span>';
		} else if ($serverProtocol == "https" && $urlProtocol == "http") {
			return '<span class="text-danger">'.__("You must use the (https) link").'</span>';
		} else if ($serverProtocol == "http" && $urlProtocol == "https") {
			return '<span class="text-danger">'.__("You must use the (http) link").'</span>';
		} else {
			return '<span class="text-danger">'.__("Mismatch").'</span>';
		}
	}
	
	public function getServerProtocol()
	{
		$serverJsContent = File::get(base_path('server.js'));
			if (strpos($serverJsContent, 'require("https")') !== false) {
					return 'https';
			}
			if (strpos($serverJsContent, 'require("http")') !== false) {
				return 'http';
			}

		return 'unknown';
	}

	
	public function checkhttpsorhttp($url)
	{
		$headers = @get_headers($url);

		if ($headers && isset($headers[0])) {
			if (strpos($headers[0], 'HTTP/1.1 200') !== false) {
				return 'http';
			} elseif (strpos($headers[0], 'HTTP/2 200') !== false) {
				return 'http';
			} elseif (strpos($headers[0], 'HTTP/1.1 301') !== false || strpos($headers[0], 'HTTP/2 301') !== false) {
				return 'https';
			} elseif (strpos($headers[0], 'HTTP/1.1 302') !== false || strpos($headers[0], 'HTTP/2 302') !== false) {
				return 'https';
			}
		}

		return 'Unknown';
	}

    public function setServer(Request $request)
    {
        $request->validate([
            'typeServer' => ['required'],
            'portnode' => ['required'],
            'urlnode' => ['required_if:typeServer,other', 'nullable', 'url'],
        ]);
        $urlnode =
            $request->typeServer === 'other'
            ? $request->urlnode . ':' . $request->portnode
            : ($request->typeServer === 'hosting'
                ? url('/')
                : 'http://localhost:' . $request->portnode);
        setEnv('TYPE_SERVER', $request->typeServer);
        setEnv('PORT_NODE', $request->portnode);
        setEnv('WA_URL_SERVER', $urlnode);
        return back()->with('alert', [
            'type' => 'success',
            'msg' => __('Success Update configuration!'),
        ]);
    }

    public function activate_license(Request $request)
    {
        $push = "Nulled By Magd Almuntaser (TTMTT)";
		return json_decode($push);
    }

    public function test_database_connection(Request $request)
    {
        $data = json_decode(json_encode($request->database));
        $error_message = null;
        try {
            $db = new \mysqli(
                $data->host,
                $data->username,
                $data->password,
                $data->database
            );
            $error_message = $db->connect_errno
                ? 'Connection Failed .' . $db->connect_error
                : $error_message;
        } catch (\Throwable $th) {
            $error_message = __('Connection failed');
        }
        return response()->json([
            'status' => $error_message ?? 'Success',
            'error' => $error_message === null ? false : true,
        ]);
    }
	
	public function getAllEnv()
    {
		$allEnv = collect($_ENV)->all();
		return $allEnv;
	}

    public function install(Request $request)
    {
        if (env('APP_INSTALLED') === true) {
            return redirect('/');
        }
        if ($request->method() === 'POST') {

            $request->validate([
                'database.*' => 'string|required',
                //'licensekey      => 'required',
                //'buyeremail      =>'required|email',
                'admin.username' => 'required',
                'admin.email' => 'required|email',
                'admin.password' => 'required|max:255',
            ]);

            /** CREATE DATABASE CONNECTION STARTS **/
            $db_params = $request->input('database');
            Config::set(
                'database.connections.mysql',
                array_merge(config('database.connections.mysql'), $db_params)
            );
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                $validator = Validator::make($request->all(), [])
                    ->errors()
                    ->add('Database', $e->getMessage());
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
            /** CREATE DATABASE CONNECTION ENDS **/
            try {

                // delete old tables
                DB::transaction(function () {
                    DB::unprepared(
                        File::get(base_path('database/db_tables.sql'))
                    );
                });
                // cache clear artisan
                Artisan::call('cache:clear');
            } catch (\Throwable $th) {
                Artisan::call('migrate:fresh', [
                    '--force' => true,
                ]);
            }
            /** SETTING .ENV VARS STARTS **/
            if (isset($_SERVER['REQUEST_SCHEME'])) {
                $urll = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}";
            } else {
                $urll = $_SERVER['HTTP_HOST'];
            }
            $env['DB_HOST'] = $db_params['host'];
            $env['DB_DATABASE'] = $db_params['database'];
            $env['DB_USERNAME'] = $db_params['username'];
            $env['DB_PASSWORD'] = $db_params['password'];
            $env['APP_URL'] = $urll;
            $env['APP_INSTALLED'] = 'true';
            if ($request->input('licensekey') != null) {
                $env['LICENSE_KEY'] = $request->input('licensekey');
            }
            if ($request->input('buyeremail') != null) {
                $env['BUYER_EMAIL'] = $request->input('buyeremail');
            }


            foreach ($env as $k => &$v) {
                setEnv($k, $v);
            }

            /** SETTING .ENV VARS ENDS **/

            /** CREATE ADMIN USER STARTS **/
            if (
                !($user = User::where(
                    'email',
                    $request->input('admin.email')
                )->first())
            ) {
                $user = new User();
                $user->username = $request->input('admin.username');
                $user->email = $request->input('admin.email');
                $user->password = Hash::make($request->input('admin.password'));
                $user->email_verified_at = date('Y-m-d');
                $user->level = 'admin';
                $user->active_subscription = 'lifetime';
                $user->limit_device = 10;
                $user->chunk_blast = 0;
                $user->save();
				
            }
			
			if (function_exists('symlink')) {
				Artisan::call('storage:link');
			}
			
            /** CREATE ADMIN USER END **/
            Auth::loginUsingId($user->id, true);
            return redirect()->route('home');
        }

        // get method
        $mysql_user_version = [
            'distrib' => '',
            'version' => null,
            'compatible' => false,
        ];

        if (function_exists('exec') || function_exists('shell_exec')) {
            $mysqldump_v = function_exists('exec')
                ? exec('mysqldump --version')
                : shell_exec('mysqldump --version');

            if (
                $mysqld = str_extract(
                    $mysqldump_v,
                    '/Distrib (?P<destrib>.+),/i'
                )
            ) {
                $destrib = $mysqld['destrib'] ?? null;

                $mysqld = explode('-', mb_strtolower($destrib), 2);

                $mysql_user_version['distrib'] = $mysqld[1] ?? 'mysql';
                $mysql_user_version['version'] = $mysqld[0];

                if (
                    $mysql_user_version['distrib'] == 'mysql' &&
                    $mysql_user_version['version'] >= 5.6
                ) {
                    $mysql_user_version['compatible'] = true;
                } elseif (
                    $mysql_user_version['distrib'] == 'mariadb' &&
                    $mysql_user_version['version'] >= 10
                ) {
                    $mysql_user_version['compatible'] = true;
                }
            }
        }

        $requirements = [
            'php' => ['version' => "8.0", 'current' => phpversion()],
            'mysql' => ['version' => 5.6, 'current' => $mysql_user_version],
            'php_extensions' => [
                'curl' => false,
                'fileinfo' => false,
                'intl' => false,
                'json' => false,
                'mbstring' => false,
                'openssl' => false,
                'mysqli' => false,
                'zip' => false,
                'ctype' => false,
                'dom' => false,
            ],
        ];

        $php_loaded_extensions = get_loaded_extensions();


        foreach ($requirements['php_extensions'] as $name => &$enabled) {
            $enabled = in_array($name, $php_loaded_extensions);
        }

        return view('theme::install', [
            'requirements' => $requirements,
        ]);
    }
}
?>