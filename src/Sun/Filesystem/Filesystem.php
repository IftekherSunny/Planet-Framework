<?php

namespace Sun\Filesystem;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Sun\Contracts\Filesystem\Filesystem as FilesystemContract;

class Filesystem implements FilesystemContract
{
    /**
     * To create file
     *
     * @param $filename
     * @param $content
     *
     * @return int
     */
    public function create($filename, $content)
    {
        return file_put_contents($filename, $content);
    }

    /**
     * To delete file
     *
     * @param $filename
     *
     * @return bool
     * @throws FileNotFoundException
     */
    public function delete($filename)
    {
        if ($this->exists($filename) && $this->isFile($filename)) {
            return @unlink($filename);
        }

        throw new FileNotFoundException('File not found in the path [ ' . $filename . ' ].');
    }

    /**
     * To check file exists
     *
     * @param $filename
     *
     * @return bool
     */
    public function exists($filename)
    {
        if (file_exists($filename)) {
            return true;
        }

        return false;
    }

    /**
     * To update file
     *
     * @param $filename
     * @param $content
     *
     * @return int
     * @throws FileNotFoundException
     */
    public function update($filename, $content)
    {
        if ($this->exists($filename) && $this->isFile($filename)) {
            return $this->create($filename, $content);
        }

        throw new FileNotFoundException('File not found in the path [ ' . $filename . ' ].');
    }

    /**
     * To get file content
     *
     * @param $filename
     *
     * @return string
     * @throws FileNotFoundException
     */
    public function get($filename)
    {
        if ($this->exists($filename) && $this->isFile($filename)) {
            return file_get_contents($filename);
        }

        throw new FileNotFoundException('File not found in the path [ ' . $filename . ' ].');
    }

    /**
     * To append file
     *
     * @param $filename
     * @param $content
     *
     * @return int
     * @throws FileNotFoundException
     */
    public function append($filename, $content)
    {
        if ($this->exists($filename) && $this->isFile($filename)) {
            return file_put_contents($filename, $content, FILE_APPEND);
        }

        throw new FileNotFoundException('File not found in the path [ ' . $filename . ' ].');
    }

    /**
     * To copy a file
     *
     * @param $source
     * @param $destination
     *
     * @return bool
     */
    public function copy($source, $destination)
    {
        return copy($source, $destination);
    }

    /**
     * To move a file
     *
     * @param $source
     * @param $destination
     *
     * @return bool
     */
    public function move($source, $destination)
    {
        return rename($source, $destination);
    }

    /**
     * To get a filesize in byte
     *
     * @param $filename
     *
     * @return int
     * @throws FileNotFoundException
     */
    public function size($filename)
    {
        if ($this->exists($filename) && $this->isFile($filename)) {
            return filesize($filename);
        }
        throw new FileNotFoundException('File not found in the path [ ' . $filename . ' ].');
    }

    /**
     * To get all files in a directory
     *
     * @param $directoryName
     *
     * @return array
     * @throws FileNotFoundException
     */
    public function files($directoryName)
    {
        if (!$this->isDir($directoryName)) {
            throw new FileNotFoundException('Directory not found in the path [ ' . $directoryName . ' ].');
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directoryName,
                RecursiveDirectoryIterator::SKIP_DOTS
            )
        );

        return array_filter(iterator_to_array($files), 'is_file');
    }

    /**
     * To get all directories in a directory
     *
     * @param $directoryName
     *
     * @return array
     * @throws FileNotFoundException
     */
    public function directories($directoryName)
    {
        if (!$this->isDir($directoryName)) {
            throw new FileNotFoundException('Directory not found in the path [ ' . $directoryName . ' ].');
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directoryName,
                RecursiveDirectoryIterator::CURRENT_AS_FILEINFO)
        );

        $directories = [];

        foreach($iterator as $file) {
            if($file->isDir()) {
                if( !(substr($file,-2) === '..')) {
                    $directories[] = trim($file,'.');
                }
            }
        }

        array_shift($directories);

        return $directories;
    }

    /**
     * To create a directory
     *
     * @param $path
     *
     * @return bool
     */
    public function createDirectory($path)
    {
        return mkdir($path, 777, true);
    }

    /**
     * To delete a directory
     *
     * @param $directoryName
     *
     * @return bool
     * @throws FileNotFoundException
     */
    public function deleteDirectory($directoryName)
    {
        return ! $this->cleanDirectory($directoryName, true);
    }

    /**
     * To clean a directory
     *
     * @param      $directoryName
     *
     * @param bool $deleteRootDirectory
     *
     * @return bool
     */
    public function cleanDirectory($directoryName, $deleteRootDirectory = false)
    {
        if(is_dir($directoryName)){
            $files = glob( $directoryName . '/*', GLOB_NOSORT );

            foreach( $files as $file )
            {
                $this->cleanDirectory( $file, true );
            }

            if(file_exists($directoryName) && ($deleteRootDirectory == true)) {
                @rmdir( $directoryName );
            }
        } elseif(is_file($directoryName)) {
            @unlink( $directoryName );
        }

        if(file_exists($directoryName)) {
            return true;
        }

        return false;
    }

    /**
     * To check is file
     *
     * @param $filename
     *
     * @return bool
     */
    public function isFile($filename)
    {
        return is_file($filename);
    }

    /**
     * To check is directory
     *
     * @param $directoryName
     *
     * @return bool
     */
    public function isDir($directoryName)
    {
        return is_dir($directoryName);
    }
}
