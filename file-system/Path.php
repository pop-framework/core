<?php 
namespace Pop\FileSystem;

class Path 
{
    const SEPARATOR = "/";
    const CURRENT   = ".";
    const PARENT    = "..";

    public function join(): string 
    {
        $args = func_get_args();

        $base = $args[0];
        unset($args[0]);
        
        $sections = explode(self::SEPARATOR, $base);

        foreach ($args as $path)
        {
            $parts = explode(self::SEPARATOR, $path);

            foreach ($parts as $part)
            {
                if ($part === self::PARENT)
                {
                    array_pop($sections);
                }
                else if ($part !== self::CURRENT && !empty($part))
                {
                    array_push($sections, $part);
                }
            }
        }

        return implode(self::SEPARATOR, $sections);
    }
}