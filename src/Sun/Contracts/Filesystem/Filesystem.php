<?php

namespace Sun\Contracts\Filesystem;

interface Filesystem
{
    /**
     * To create file
     *
     * @param $filename
     * @param $content
     *
     * @return int
     */
    public function create($filename, $content);

    /**
     * To delete file
     *
     * @param $filename
     *
     * @return bool
     */
    public function delete($filename);

    /**
     * To check file exists
     *
     * @param $filename
     *
     * @return bool
     */
    public function exists($filename);

    /**
     * To update file
     *
     * @param $filename
     * @param $content
     *
     * @return init
     */
    public function update($filename, $content);

    /**
     * To get file content
     *
     * @param $filename
     *
     * @return string
     */
    public function get($filename);

    /**
     * To append file
     *
     * @param $filename
     * @param $content
     *
     * @return int
     */
    public function append($filename, $content);

    /**
     * To copy a file
     *
     * @param $source
     * @param $destination
     *
     * @return bool
     */
    public function copy($source, $destination);

    /**
     * To move a file
     *
     * @param $source
     * @param $destination
     *
     * @return bool
     */
    public function move($source, $destination);

    /**
     * To get a filesize in byte
     *
     * @param $filename
     *
     * @return int
     */
    public function size($filename);

    /**
     * To get all files in a directory
     *
     * @param $directoryName
     *
     * @return array
     */
    public function files($directoryName);


    /**
     * To get all directories in a directory
     *
     * @param $directoryName
     *
     * @return array
     */
    public function directories($directoryName);

    /**
     * To create a directory
     *
     * @param $path
     *
     * @return bool
     */
    public function createDirectory($path);

    /**
     * To delete a directory
     *
     * @param $directoryName
     *
     * @return bool
     * @throws FileNotFoundException
     */
    public function deleteDirectory($directoryName);

    /**
     * To clean a directory
     *
     * @param $directoryName
     *
     * @throws FileNotFoundException
     */
    public function cleanDirectory($directoryName);

    /**
     * To check is file
     *
     * @param $filename
     *
     * @return bool
     */
    public function isFile($filename);

    /**
     * To check is directory
     *
     * @param $directoryName
     *
     * @return bool
     */
    public function isDir($directoryName);
}
