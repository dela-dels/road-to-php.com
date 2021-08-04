@setup
require __DIR__.'/vendor/autoload.php';

$server = "road-to-php.com";
$userAndServer = 'forge@'. $server;
$repository = "brendt/road-to-php.com";
$baseDir = "/home/forge/road-to-php.com/current";
$currentDir = $baseDir;

function logMessage($message) {
return "echo '\033[32m" .$message. "\033[0m';\n";
}
@endsetup

@servers(['local' => '127.0.0.1', 'remote' => $userAndServer])

@macro('deploy')
startDeployment
pullChanges
finishDeploy
@endmacro

@macro('deploy-code')
startDeployment
pullChanges
finishDeploy
@endmacro

@task('startDeployment', ['on' => 'local'])
{{ logMessage("🏃  Starting deployment...") }}
git checkout master;
git pull origin master;
@endtask

@task('pullChanges', ['on' => 'remote'])
{{ logMessage("🔑  Using correct SSH key...") }}

eval `ssh-agent -s`
ssh-add -D
ssh-add ~/.ssh/id_rsa_road

{{ logMessage("📦  Pulling changes...") }}

cd {{ $currentDir }};
git checkout master;
git pull origin master;
@endtask

@task('finishDeploy', ['on' => 'local'])
{{ logMessage("🚀  Application deployed!") }}
@endtask
