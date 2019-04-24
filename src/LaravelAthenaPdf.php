<?php

namespace Olekjs\LaravelAthenaPdf;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class LaravelAthenaPdf
{
    protected $view;
    protected $html;
    protected $url;
    protected $output;
    protected $aggressive;
    protected $timeout;
    protected $delay;
    protected $paper;
    protected $landscape;
    protected $path;
    protected $cache;
    protected $zoom;

    /**
     * LaravelAthenaPdf constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param $zoom
     * @return $this
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;
        return $this;
    }

    /**
     * @param $delay
     * @return $this
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * @param $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @param $cache
     * @return $this
     */
    public function disableCache($cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * @param $paper
     * @return $this
     */
    public function setPaperSize($paper)
    {
        $this->paper = $paper;
        return $this;
    }

    /**
     * @param $landscape
     * @return $this
     */
    public function setLandscape($landscape)
    {
        $this->landscape = $landscape;
        return $this;
    }

    /**
     * @param $output
     * @return $this
     */
    public function setOutputFilename($output)
    {
        $this->output = $output;
        return $this;
    }

    /**
     * @param $aggressive
     * @return $this
     */
    public function setAggressive($aggressive)
    {
        $this->aggressive = $aggressive;
        return $this;
    }

    /**
     * @param $view
     * @param  array  $data
     * @return $this
     */
    public function loadView($view, $data = [])
    {
        $tmp = $this->createTemporaryFile(View::make($view)->with($data)->render(), 'html');
        $this->html = basename($tmp);
        $this->path = dirname($tmp);
        return $this;
    }
    /**
     * @return string
     */
    private function buildArgs()
    {
        $args = ' ';
        if ($this->landscape == true) {
            $args .= '--no-portrait ';
        }
        if ($this->paper != '') {
            $args .= '-P '.$this->paper.' ';
        }
        if ($this->timeout != '') {
            $args .= '-T '.$this->timeout.' ';
        }
        if ($this->delay != '') {
            $args .= '-D '.$this->delay.' ';
        }
        if ($this->cache == true) {
            $args .= '--no-cache ';
        }
        if ($this->zoom != '') {
            $args .= '--no-cache ';
        }
        return $args;
    }
    /**
     * @param bool $stream
     *
     * @return string
     */
    private function prepareAthena($stream = false)
    {
        $args = $this->buildArgs();
        $athena_command = 'cd '.storage_path('app/').' && docker run --rm -v $(pwd):/converted/ arachnysdocker/athenapdf athenapdf '.$args;
        if (! $stream) {
            return shell_exec($athena_command.$this->html.' '.$this->output);
        } else {
            return shell_exec($athena_command.' -S '.$this->html);
        }
    }

    protected function clearTmp()
    {
        unlink(storage_path('app/').$this->html);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function save()
    {
        $athena = $this->prepareAthena();
        if (! file_exists(storage_path('app/').$this->output)) {
            throw new \Exception('could not create screenshot');
        }
        $this->clearTmp();
        return true;
    }
    /**
     * @return Response
     */
    public function stream()
    {
        $athena = $this->prepareAthena(true);
        $this->clearTmp();
        return new Response($athena, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$this->output.'"',
        ]);
    }
    /**
     * @param null $content
     * @param null $extension
     *
     * @return string
     */
    protected function createTemporaryFile($content = null, $extension = null)
    {
        $filename = storage_path('app/').uniqid('lathenapdf_', true);
        if (null !== $extension) {
            $filename .= '.'.$extension;
        }
        if (null !== $content) {
            file_put_contents($filename, $content);
        }
        $this->temporaryFiles[] = $filename;
        return $filename;
    }

}
