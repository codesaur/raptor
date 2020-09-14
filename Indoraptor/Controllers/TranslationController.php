<?php namespace Indoraptor\Controllers;

use Indoraptor\Models\Translation;

class TranslationController extends IndoController
{
    public $text = array();
    
    function __construct(bool $single = true)
    {
        parent::__construct($single);
        
        $this->connect();
        
        $this->model = new Translation($this->conn);
    }
    
    public function index()
    {
        if ($this->accept()) {
            return $this->success(array('names' => $this->model->getTranslationNames()));
        } else {
            return $this->error('Not Allowed!');
        }
    }

    public function retrieve()
    {
        $payload = $this->payload(true);
        if (isset($payload['table'])) {
            if (\is_array($payload['table'])) {
                $tables = \array_values($payload['table']);
            } else {
                $tables = array($payload['table']);
            }

            $flag = $payload['flag'] ?? null;
            
            $table_names = $this->model->getTranslationNames();
            
            $translations = array();
            foreach (\array_unique($tables) as $table) {
                $clean = \preg_replace('/[^A-Za-z0-9_-]/', '', $table);
                
                if ( ! \in_array($clean, $table_names)) {
                    continue;
                }
                
                $text = $this->getTranslations($table, $flag);
                
                if ( ! empty($text)) {
                    $translations[$table] = $text;
                }
            }
            
            if ( ! empty($translations)) {
                return $this->success($translations);
            }
        }
        return $this->error('Not Found');
    }
    
    public function getLocally($tables, $flag)
    {
        if ( ! \is_array($tables)) {
            $tables = array($tables);
        }
        
        foreach ($tables as $table) {
            $this->text += $this->getTranslations($table, $flag);
        }
    }
    
    public function text($key) : string
    {
        return $this->text[$key] ?? '{' . $key . '}';
    }
    
    public function getTranslations($table, $flag) : array
    {
        $this->model->setTables($table);
        
        return $this->model->retrieve($flag);
    }
    
    public function getBy()
    {
        $payload = $this->payload(true);
        if (isset($payload['_keyword_'])) {
            $found = $this->model->getByKeyword($payload['_keyword_']);
        }

        if (isset($found) && $found) {
            return $this->success($found);
        } else {
            return $this->error('Not Found');
        }
    }
    
    public function datatable($flag)
    {
        $this->getLocally(array('default', 'datatable'), $flag);

        $language = array(
            'sEmptyTable'     => $this->text('no data available in table'),
            'sInfo'           => $this->text('showing _START_ to _END_ of _TOTAL_ records'),
            'sInfoEmpty'      => $this->text('no records found to show'),
            'sInfoFiltered'   => $this->text('(filtered from _MAX_ total records)'),
            'sInfoPostFix'    => '',
            'sInfoThousands'  => '.',
            'sLengthMenu'     => $this->text('view _MENU_ records'),
            'sLoadingRecords' => $this->text('loading') . '...',
            'sProcessing'     => $this->text('processing') . '...',
            'sSearch'         => $this->text('search'),
            'sZeroRecords'    => $this->text('no matching records found'),
            'oPaginate'       => array(
                'sFirst'    => $this->text('first'),
                'sPrevious' => $this->text('prev'),
                'sNext'     => $this->text('next'),
                'sLast'     => $this->text('last'),
                'sPage'     => $this->text('page')
            ),
            'oAria' => array(
                'sSortAscending'  => $this->text(': activate to sort column ascending'),
                'sSortDescending' => $this->text(': activate to sort column descending')
            )
        );
        
        $this->respond($language);
    }
}
