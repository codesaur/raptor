<?php namespace Velociraptor\File;

use codesaur as single;
use codesaur\Base\File;
use codesaur\Globals\Post;

class PluploadHelper
{
    public function upload($dir, $pragma_no_cache = true, $time_limit = 5 * 60) : array
    {
        if ($time_limit) {
            \set_time_limit($time_limit);
        }
        
        if ($pragma_no_cache) {
            single::header()->response('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            single::header()->response('Last-Modified: ' . \gmdate('D, d M Y H:i:s') . ' GMT');
            single::header()->response('Cache-Control: no-store, no-cache, must-revalidate');
            single::header()->response('Cache-Control: post-check=0, pre-check=0', false);
            single::header()->response('Pragma: no-cache');
        }
      
        try {
            $file = new File();
            if ( ! $file->exists($dir)
                    || ! $file->isDir($dir)) {
                $file->makeDir($dir);
            }

            $post = new Post();        
            if ($post->has('name')) {
                $name = $post->value('name');
            } elseif ( ! empty($_FILES)) {
                $name = $_FILES['file']['name'];
            } else {
                $name = \uniqid('file_');
            }

            $name = $file->generateName("$dir/", $name);
            if (empty($name)) {
                throw new \Exception('Failed to set file name.');
            }
            $filePath = "$dir/$name";

            $chunk = $post->has('chunk') ? $post->value('chunk', FILTER_VALIDATE_INT) : 0;
            $chunks = $post->has('chunks') ? $post->value('chunks', FILTER_VALIDATE_INT) : 0;

            if ( ! $out = \fopen("$filePath.part", $chunks ? 'ab' : 'wb')) {
                throw new \Exception('Failed to open output stream.');
            }
            
            if ( ! empty($_FILES)) {
                if ($_FILES['file']['error']
                        || ! $file->isUploaded($_FILES['file']['tmp_name'])) {
                    throw new \Exception('Failed to move uploaded file.');
                }
                if ( ! $in = \fopen($_FILES['file']['tmp_name'], 'rb')) {
                    throw new Exception('Failed to open input stream.');
                }
            } else {
                if ( ! $in = \fopen('php://input', 'rb')) {
                    throw new \Exception('Failed to open input stream.');
                }
            }
            
            while ($buff = \fread($in, 4096)) {
                \fwrite($out, $buff);
            }

            \fclose($out);
            \fclose($in);

            if ( ! $chunks || $chunk == $chunks - 1) {
                \rename("$filePath.part", $filePath);
            }

            return array('data' => array('file_name' => $name, 'file_path' => $filePath));
        } catch (\Exception $e) {
            return array('error' => array('message' => $e->getMessage()));
        }
    }
}
