<?php 
namespace Pop\Cache\Commands;

use Pop\Cli\AbstractCommands;

class CacheClear extends AbstractCommands
{
    public static function getName(): string
    {
        return "cache:clear";
    }

    public function execute(): void
    {
        $directory = (string) $this->launcher->framework->get('cache_root');
        $contents = scandir($directory);

        foreach ($contents as $entry)
        {
            if (!in_array($entry, ['.', '..'])) 
            {
                $item = $this->path->join(
                    $directory,
                    $entry
                );
                unlink($item);
            }
        }

        $this->info( (count($contents)-2)." files has been cleared from the cache directory at ".date("H:i:s") );
    }
}