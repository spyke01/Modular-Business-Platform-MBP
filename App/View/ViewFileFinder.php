<?php

namespace App\View;

use InvalidArgumentException;
use App\Support\FileFunctions;

class ViewFileFinder {
    /**
     * The file functions instance.
     *
     * @var \fileFunctions
     */
    protected $files;

    /**
     * The array of active view paths.
     *
     * @var array
     */
    protected $paths;

    /**
     * The array of views that have been located.
     *
     * @var array
     */
    protected $views = [];

    /**
     * Create a new file view loader instance.
     *
     * @param  \fileFunctions  $files
     * @param  array  $paths
     * @param  array  $extensions
     * @return void
     */
    public function __construct()
    {
	    $this->files = new FileFunctions();
	    $directories = array();
	    
	    // Add our module views first
	    $pathsToCheck = array(
		    BASEPATH . '/modules',
		    BASEPATH . '/themes',
	    );

	    foreach ( $pathsToCheck as $path ) {
		    $files = $this->files->dirlist( $path );

		    foreach ( $files as $file => $structure ) {
			    if ( $structure['type'] == 'd' ) {
				    $directories[] = $path . '/' . $file;
			    }
		    }
	    }
		
        $this->paths = $directories;
    }

    /**
     * Get the fully qualified location of the view.
     *
     * @param  string  $name
     * @return string
     */
    public function find($name)
    {
        if (isset($this->views[$name])) {
            return $this->views[$name];
        }

        return $this->views[$name] = $this->findInPaths($name, $this->paths);
    }

    /**
     * Find the given view in the list of paths.
     *
     * @param  string  $name
     * @param  array   $paths
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function findInPaths($name, $paths)
    {
	    //var_export($paths);
        foreach ( (array) $paths as $path ) {
            $file = str_replace('.', '/', $name).'.php';

            if ( $this->files->exists( $viewPath = $path . '/Views/' . $file ) ) {
	            return $viewPath;
            }
        }

        throw new InvalidArgumentException("View [$name] not found.");
    }

    /**
     * Get the filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->files;
    }

    /**
     * Get the active view paths.
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }
	
}