<?php
if (getenv('APP_ENV') !== 'testing') { throw new RuntimeException('Run only with APP_ENV=testing and isolated dummy configuration.'); }
require '/app/vendor/autoload.php';
$app=require '/app/bootstrap/app.php';
$kernel=$app->make(Illuminate\Contracts\Http\Kernel::class);
foreach(explode(',',getenv('SMOKE_PATHS')?:'/,/login') as $path){
 $request=Illuminate\Http\Request::create('http://localhost'.$path,'GET');
 $response=$kernel->handle($request);
 if (!in_array($response->getStatusCode(), [200,302], true)) { throw new RuntimeException('Unexpected HTTP status for '.$path.': '.$response->getStatusCode()); }
 echo json_encode(['path'=>$path,'status'=>$response->getStatusCode(),'location'=>$response->headers->get('location'),'bytes'=>strlen($response->getContent())]).PHP_EOL;
}
