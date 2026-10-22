<?php
require '/app/vendor/autoload.php';
use App\Database\LegacyMySqlConnection;
use App\Database\LegacyMySqlServerVersion;
use Illuminate\Database\Schema\Grammars\MySqlGrammar;
$cases=[
 '5.5.5-10.1.16-MariaDB'=>false,'10.1.48-MariaDB-0ubuntu0.18.04.1'=>false,
 '10.2.0-MariaDB'=>true,'5.5.5-10.11.8-MariaDB'=>true,
 '5.5.62'=>false,'5.6.51-log'=>false,'5.7.0'=>true,'8.0.43'=>true,'unknown'=>true,
];
foreach($cases as $version=>$supported){
 if(LegacyMySqlServerVersion::supportsGenerationExpression($version)!==$supported)throw new RuntimeException('Incorrect version boundary: '.$version);
 $pdo=new class($version) extends PDO {
  private string $serverVersion;
  public function __construct(string $version){$this->serverVersion=$version;}
  public function getAttribute(int $attribute): mixed {if($attribute!==PDO::ATTR_SERVER_VERSION)throw new RuntimeException('Unexpected PDO attribute');return $this->serverVersion;}
  public function quote(string $string,int $type=PDO::PARAM_STR): string|false {return "'".str_replace("'","''",$string)."'";}
 };
 $connection=new LegacyMySqlConnection($pdo,'dummy');
 $connection->useDefaultSchemaGrammar();
 $sql=$connection->getSchemaGrammar()->compileColumns('dummy','records');
 $standard=(new MySqlGrammar($connection))->compileColumns('dummy','records');
 $expected=$supported?$standard:str_replace('generation_expression as `expression`','NULL as `expression`',$standard);
 if($sql!==$expected)throw new RuntimeException('Unexpected schema SQL: '.$version);
 echo 'PASS '.$version.' '.($supported?'standard SQL preserved':'legacy metadata fallback').PHP_EOL;
}
$calls=0;
$app=new Illuminate\Foundation\Application('/app');
$provider=new App\Providers\AppServiceProvider($app);$provider->register();
$resolver=Illuminate\Database\Connection::getResolver('mysql');
$connection=$resolver(function() use (&$calls){$calls++;throw new RuntimeException('Unexpected eager connection');},'dummy','',[]);
$connection->useDefaultSchemaGrammar();
if($calls!==0 || !$connection instanceof LegacyMySqlConnection)throw new RuntimeException('Resolver not lazy');
echo "PASS registration and schema grammar creation do not connect\n";
